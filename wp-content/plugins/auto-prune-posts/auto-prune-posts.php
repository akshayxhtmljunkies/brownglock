<?php
/*
 Plugin Name: Auto Prune Posts
 Plugin URI: http://www.mijnpress.nl
 Description: Auto deletes (prune) posts after a certain amount of time. On a per category basis.
 Version: 1.6.5
 Author: Ramon Fincken
 Author URI: http://mijnpress.nl
 Created on 31-oct-2010 17:33:40
 */

if(!class_exists('mijnpress_plugin_framework'))
{
	include('mijnpress_plugin_framework.php');
}

/**
 * @author  Ramon Fincken
 */
class plugin_auto_prune_posts extends mijnpress_plugin_framework
{
	function __construct()
	{
		$this->showcredits = true;
		$this->showcredits_fordevelopers = true;
		$this->plugin_title = 'Auto prune posts';
		$this->plugin_class = 'plugin_auto_prune_posts';
		$this->plugin_filename = 'auto-prune-posts/auto-prune-posts.php';
		$this->plugin_config_url = 'plugins.php?page='.$this->plugin_filename;

		$this->default_posttype = array('post');

		// General config
		$plugin_autopruneposts_conf = get_option('plugin_autopruneposts_conf');
		$default_settings = array('force_delete' => 0,'admin_email' => '','types' => '');

		$reset = false; // If you really want to start over, only do this when you know what you are doing matie! HarrRrr
		if ($plugin_autopruneposts_conf === false) {
			add_option('plugin_autopruneposts_conf', array ('version' => '1.6','settings' => $default_settings,'config' => array()), NULL, 'yes');
		}
		else
		{
			if($reset)
			{
				update_option('plugin_autopruneposts_conf', array ('version' => '1.1','settings' => $default_settings,'config' => array()));
				$this->show_message('Auto prune posts plugin RESET');
			}
			else
			{
				if(!isset($plugin_autopruneposts_conf['version']) || $plugin_autopruneposts_conf['version'] == '1.0')
				{
					// Upgrade
					$newconfig = array();
					foreach($plugin_autopruneposts_conf as $cat_id => $values)
					{
						$newconfig[$cat_id]['post'] = $values;
					}
					
					update_option('plugin_autopruneposts_conf', array ('version' => '1.1','settings' => $default_settings,'config' => $newconfig));
					$this->show_message('Auto prune posts plugin updated to new version');
				}
			}
		}


		// Reload
		$this->conf = get_option('plugin_autopruneposts_conf');

		$temp = explode(',',$this->conf['settings']['types']);
		if(empty($this->conf['settings']['types']))
		{
			$temp = array();
		}
		$this->all_types = array_merge($this->default_posttype,$temp);

		$this->periods = array (
	         'hour',
	         'day',
	         'week',
	         'month',
	         'year'
	         );
	}

	function plugin_name()
	{
		$args= func_get_args();
		call_user_func_array
		(
		array(&$this, '__construct'),
		$args
		);
	}

	function addPluginSubMenu()
	{
		$plugin = new plugin_auto_prune_posts();
		parent::addPluginSubMenu($plugin->plugin_title,array($plugin->plugin_class, 'admin_menu'),__FILE__);
	}

	/**
	 * Additional links on the plugin page
	 */
	function addPluginContent($links, $file) {
		$plugin = new plugin_auto_prune_posts();
		$links = parent::addPluginContent($plugin->plugin_filename,$links,$file,$plugin->plugin_config_url);
		return $links;
	}

	/**
	 * Shows the admin plugin page
	 */
	public function admin_menu()
	{
		$plugin = new plugin_auto_prune_posts();
		$plugin->content_start();

		$action_taken = false;
		if (isset ($_POST['formaction'])) {
			switch ($_POST['formaction']) {
				case 'updatesettings':
					if($_POST['force_delete'] == 0 || $_POST['force_delete'] == 1)
					{
						$plugin->conf['settings']['force_delete'] = $_POST['force_delete'];
						update_option('plugin_autopruneposts_conf', $plugin->conf);
						$action_taken = true;
					}
					if(empty($_POST['admin_email']) || is_email($_POST['admin_email'],true))
					{
						$plugin->conf['settings']['admin_email'] = $_POST['admin_email'];
						update_option('plugin_autopruneposts_conf', $plugin->conf);
						$action_taken = true;
					}

					$types_new = $_POST['types'];
					if($plugin->conf['settings']['types'] != $types_new)
					{
						$plugin->conf['settings']['types'] = $types_new;
						update_option('plugin_autopruneposts_conf', $plugin->conf);
						$action_taken = true;
					}
						
					break;
				case 'add' :
					if (isset ($_POST['period_duration_add']) && !empty ($_POST['period_duration_add']) && intval($_POST['period_duration_add']) > 0) {
						if (in_array($_POST['period_add'], $plugin->periods) && intval($_POST['cat_id_add']) >= 0) {
							$period_php = intval(trim($_POST['period_duration_add'])) . ' ' . $_POST['period_add'];
							$period = intval(trim($_POST['period_duration_add']));
							$period_duration = $_POST['period_add'];
							$post_type = $_POST['type'];
								
							$plugin->conf['config'][intval($_POST['cat_id_add'])][$post_type] = array (
			                        	'period_php' => $period_php,
			                        	'period' => $period,
			                        	'period_duration' => $period_duration,
							'post_type' => $post_type
							);
							update_option('plugin_autopruneposts_conf', $plugin->conf);
							$action_taken = true;
						}
					}
					break;
				case 'update' :
					// Walk
					foreach($plugin->conf['config'] as $cat_id => $type)
					{
						foreach($type as $the_type => $values)
						{
							$action = $_POST['action'][$cat_id][$the_type];
							if ($action == 'delete') {
								unset ($plugin->conf['config'][$cat_id][$the_type]);
								$action_taken = true;
							}
							else
							{
								// Update falltrough
								if (isset ($_POST['period_duration'][$cat_id][$the_type]) && !empty ($_POST['period_duration'][$cat_id][$the_type]) && intval($_POST['period_duration'][$cat_id][$the_type]) > 0) {
									if (in_array($_POST['period'][$cat_id][$the_type], $plugin->periods)) {
										$period_php = intval(trim($_POST['period_duration'][$cat_id][$the_type])) . ' ' . $_POST['period'][$cat_id][$the_type];
										$period = intval(trim($_POST['period_duration'][$cat_id][$the_type]));
										$period_duration = $_POST['period'][$cat_id][$the_type];
										$plugin->conf['config'][$cat_id][$the_type] = array (
                              'period_php' => $period_php,
                              'period' => $period,
                              'period_duration' => $period_duration
										);
										$action_taken = true;
									}
								}
									
							}
						}
					}

					// Now perform updates
					update_option('plugin_autopruneposts_conf', $plugin->conf);
					delete_transient('auto-prune-posts-lastrun');
					
					break;
			}
		}

		if($action_taken)
		{
			// Reload
			$plugin->conf = get_option('plugin_autopruneposts_conf');
			$temp = explode(',',$plugin->conf['settings']['types']);
			if(empty($plugin->conf['settings']['types']))
			{
				$temp = array();
			}
			$plugin->all_types = array_merge($plugin->default_posttype,$temp);
			delete_transient('auto-prune-posts-lastrun');
		}
		
		if(isset($_GET['prune']))
		{
			delete_transient('auto-prune-posts-lastrun');
			$plugin->prune(true);
			echo 'Prune force called';
			die();
		}
		// Show form
		include ('auto-prune-posts-adminpage.php');

		$plugin->content_end();
	}

	/**
	 * Uses transient instead of cronjob, will run on wp call in frontend AND backend, every 30 seconds (transient)
	 */
	function prune($forced = false) {
		$lastrun = get_transient('auto-prune-posts-lastrun');
		$i_delete = 0;

		if ($forced || false === $lastrun) {
			$force_delete = ($this->conf['settings']['force_delete'] == 0) ? false : true;

			// Walk
			foreach($this->conf['config'] as $cat_id => $type)
			{
				foreach($type as $the_type => $values)
				{				
					$period_php = $values['period_php']; // Will be in format so strtotime can handle this [int][space][string] example: "4 day" or "5 month"
	
					// Get all posts for this category
					//$myposts = get_posts('category=' . $cat_id.'&post_type='.$the_type.'&numberposts=-1');
				
					if($i_delete < 600)
					{
						if($cat_id > 0)
						{
							// Do only the last 50 (by date, for 1 cat)
							$myposts = get_posts('category=' . $cat_id.'&post_type='.$the_type.'&numberposts=75&order=ASC&orderby=post_date');
						}
						else
						{
							// Do only the last 50 (by date, ALL)
							$myposts = get_posts('post_type='.$the_type.'&numberposts=75&order=ASC&orderby=post_date');
						}
					
						foreach ($myposts AS $post) {
							$post_date_plus_visibleperiod = strtotime($post->post_date . " +" . $period_php);
							$now = strtotime("now");

							if ($post_date_plus_visibleperiod < $now) {
								// GOGOGO !
								$i_delete++;
								$this->delete_post_and_attachments($post->ID,$force_delete);
	
								// Mail admin?
								if(!empty($this->conf['settings']['admin_email']))
								{
									$body = "Deleting post ID : ".$post->ID. "\n";
									$body .= "Post title : ".$post->post_title. "\n";
									$body .= "Settings (Delete or Trash) : ".( ($force_delete) ? 'Delete' : 'Trash' ). "\n";
									wp_mail($this->conf['settings']['admin_email'],'Plugin auto prune posts notification',$body);
								}
							}
						}
					}
				}
			}
			set_transient('auto-prune-posts-lastrun', 'lastrun: '.time(), 300); // 300 seconds
		}
	}

	/**
	 * Actually deletes post and its attachments
	 */
	private function delete_post_and_attachments($post_id, $force_delete) {
		$atts = get_children(array (
         'post_parent' => $post_id,
         'post_status' => 'inherit',
         'post_type' => 'attachment'
         ));
         if ($atts) {
         	foreach ($atts as $att) {
         		// Deletes this attachment
         		wp_delete_attachment($att->ID, $force_delete);
         	}
         }

         // Now delete post
         wp_delete_post($post_id, $force_delete);
	}
}

// Admin only
if(mijnpress_plugin_framework::is_admin())
{
	add_action('admin_menu',  array('plugin_auto_prune_posts', 'addPluginSubMenu'));
	add_filter('plugin_row_meta',array('plugin_auto_prune_posts', 'addPluginContent'), 10, 2);
}



/**
 * Call scheduler
 */
function plugin_auto_prune_posts_activation() {
	$plugin_autopruneposts_initpage = new plugin_auto_prune_posts();
	$plugin_autopruneposts_initpage->prune();
}
add_action('after_setup_theme', 'plugin_auto_prune_posts_activation');
?>
