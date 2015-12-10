<?php
/*
Plugin Name: WPLMS EventOn
Plugin URI: http://www.VibeThemes.com
Description: Project Management Plugin by VibeThemes
Version: 0.1
Requires at least: WP 3.8, BuddyPress 1.9 
Tested up to: 2.0.1
License: (Themeforest License : http://themeforest.net/licenses)
Author: Mr.Vibe 
Author URI: http://www.VibeThemes.com
Network: true
*/


define( 'WPLMS_EVENTON_VERSION', '1.0' );


function wplms_eventon_init() {
	global $wpdb;

	if ( is_multisite() && BP_ROOT_BLOG != $wpdb->blogid )
		return;
	if ( version_compare( BP_VERSION, '1.8', '>' ) ){
		global $eventon_dv;
		if(is_object($eventon_dv))
			$eventon_dv->plugin_url = plugins_url(__FILE__).'includes/eventon-daily-view/';
		
		require( dirname( __FILE__ ) . '/includes/init.php' );
		require( dirname( __FILE__ ) . '/includes/migrate.php' );	
		require( dirname( __FILE__ ) . '/includes/eventon-daily-view/eventon-daily-view.php' );
		require( dirname( __FILE__ ) . '/includes/dashboard_widget.php' );
	}
}
add_action( 'bp_include', 'wplms_eventon_init' );

add_action('plugins_loaded','wplms_eventon_translations');
function wplms_eventon_translations(){
	$locale = apply_filters("plugin_locale", get_locale(), 'wplms-eventon');
	$lang_dir = dirname( __FILE__ ) . '/languages/';
	$mofile        = sprintf( '%1$s-%2$s.mo', 'wplms-eventon', $locale );

	$mofile_local  = $lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;
	if ( file_exists( $mofile_global ) ) {
		load_textdomain( 'wplms-eventon', $mofile_global );
	} else {
		load_textdomain( 'wplms-eventon', $mofile_local );
	}	
}
