<?php

class WPLMS_Dashboard{

	function __construct(){
		register_activation_hook(__FILE__, array($this,'activate'));
		register_deactivation_hook(__FILE__,array($this,'deactivate'));
		$this->init();
		//$this->setup_sidebars();
		add_action( 'plugins_loaded', array($this,'init_language' ));
		add_action( 'bp_setup_nav', array($this,'setup_nav' ));
		add_action('bp_after_dashboard_body',array($this,'add_security_parameter'));
		add_filter('wplms_logged_in_top_menu',array($this,'add_dashboard_in_menu'));
		add_post_type_support( 'news', 'front-end-editor' );
		add_filter('wplms_course_nav_menu',array($this,'wplms_course_news_menu'));
		add_filter('wplms_course_locate_template',array($this,'wplms_course_news_template'),10,2);
		add_action('wplms_load_templates',array($this,'wplms_course_show_news'));
	}

	function init(){ 
		if ( !defined( 'WPLMS_DASHBOARD_SLUG' ) )
		define ( 'WPLMS_DASHBOARD_SLUG', 'dashboard' );
	}

	function init_language(){
		$locale = apply_filters("plugin_locale", get_locale(), 'wplms-dashboard');
		if ( file_exists( dirname( __FILE__ ) . '/languages/wplms-dashboard-' . $locale . '.mo' ) ){
		    load_textdomain( 'wplms-dashboard', dirname( __FILE__ ) . '/languages/wplms-dashboard-' .$locale . '.mo' );
		} 
	}

	function activate(){

	}

	function deactivate(){
		
	}

	function setup_nav(){
		global $bp;
		$access= 0;
		if(function_exists('bp_is_my_profile'))
			$access = apply_filters('wplms_student_dashboard_access',bp_is_my_profile());

        bp_core_new_nav_item( array( 
            'name' => __('Dashboard', 'wplms-dashboard' ), 
            'slug' => WPLMS_DASHBOARD_SLUG, 
            'position' => 4,
            'screen_function' => 'wplms_dashboard_template', 
            'show_for_displayed_user' => $access
      	) );
	}
	
	function add_security_parameter(){
		wp_nonce_field( 'vibe_security', 'security');
	}
	function add_dashboard_in_menu($menu){
		$dash_menu['dashboard']=array(
                          'icon' => 'icon-meter',
                          'label' => __('Dashboard','wplms-dashboard'),
                          'link' => bp_loggedin_user_domain().WPLMS_DASHBOARD_SLUG
                          );
		foreach($menu as $key=>$item){
			$dash_menu[$key]=$item;
		}
		return $dash_menu;
	}
	function wplms_course_news_menu($links){

		if(function_exists('vibe_get_option')){
			$show_news = vibe_get_option('show_news');
			if(isset($show_news) && $show_news){	
				$links['news'] = array(
                        'id' => 'news',
                        'label'=>__('News','wplms-dashboard'),
                        'action' => 'news',
                        'link'=>bp_get_course_permalink(),
                    );
      
			}
		}
		return $links;
	}
	function wplms_course_news_template($template,$action){
      if($action == 'news'){ 
          $template= array(get_template_directory('course/single/plugins.php'));
      }
      return $template;
    }
    function wplms_course_show_news(){
    	$course_id=get_the_ID();
      	if(!isset($_GET['action']) || ($_GET['action'] != 'news'))
        return;

    	require_once('news_template.php');
    }
}

new WPLMS_Dashboard();


?>