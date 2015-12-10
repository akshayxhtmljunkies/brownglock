<?php 
defined('ABSPATH') or die('No direct access permitted');

// probably load this after all plugins are loaded. 
class maxIntegrations
{


	static function init() 
	{
		// check and init after plugin loaded. 
		add_action('plugins_loaded', array('maxIntegrations', 'load_integrations'), 999); 

		self::doDirectInit();  // integrations that fire right now, like ones that are based on actions and filters. 
		
		//remove_action( 'wp_ajax_fusion_pallete_elements', array( $instance,'get_pallete_elements') );		
	}
	
	
	static function load_integrations()
	{
 
 		// pro
		//require_once( MB()->get_plugin_path(true) . "assets/integrations/fusion_builder/fusion_builder.php"); 
	
	}
	
	static function doDirectInit() 
	{
		require_once( MB()->get_plugin_path() . "assets/integrations/siteorigins_builder/sitebuilder.php"); 
	
	}




} // class
