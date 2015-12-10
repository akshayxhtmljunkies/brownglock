<?php
/*
Plugin Name: WP Admin UI
Plugin URI: http://wpadminui.net/
Description: WP Admin UI
Version: 0.8
Author: Benjamin DENIS
Author URI: http://wpadminui.net/
License: GPLv2
*/

/*  Copyright 2015 - Benjamin DENIS  (email : contact@wpadminui.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}

function wpui_activation() {
}
register_activation_hook(__FILE__, 'wpui_activation');
function wpui_deactivation() {
}
register_deactivation_hook(__FILE__, 'wpui_deactivation');

load_plugin_textdomain('wpui', false, basename( dirname( __FILE__ ) ) . '/lang' );

define( 'WPUI_VERSION', '0.8' ); 
        
///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('plugin_action_links', 'wpui_plugin_action_links', 10, 2);

function wpui_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wpui-option">'.__("Settings","wpui").'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Admin + Core
///////////////////////////////////////////////////////////////////////////////////////////////////

if ( is_admin() )
	require_once dirname( __FILE__ ) . '/wpadminui-admin.php';
    require_once dirname( __FILE__ ) . '/wpadminui-core.php';

///////////////////////////////////////////////////////////////////////////////////////////////////
//Translation
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpui_init() {
  load_plugin_textdomain( 'wpui', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); 
}
add_action('plugins_loaded', 'wpui_init');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS in admin
///////////////////////////////////////////////////////////////////////////////////////////////////

//WPUI Options page
function wpui_add_admin_options_scripts() {
	wp_register_style( 'wpui-admin', plugins_url('css/wpadminui.css', __FILE__));
    wp_enqueue_style( 'wpui-admin' );
	
	//Tabs
	if (isset($_GET['page']) && (($_GET['page'] == 'wpui-columns') || ($_GET['page'] == 'wpui-metaboxes') )) {
		wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
	}

    //Accordeon
    if (isset($_GET['page']) && (($_GET['page'] == 'wpui-admin-menu'))) {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script( 'wpadminui-custom', plugins_url( 'js/wpadminui-custom.js', __FILE__ ), array( 'jquery' ) );
    }
}

add_action('admin_enqueue_scripts', 'wpui_add_admin_options_scripts', 10, 1);

///////////////////////////////////////////////////////////////////////////////////////////////////
//WPUI Menu Ajax
///////////////////////////////////////////////////////////////////////////////////////////////////
include(dirname(__FILE__) . '/inc/wpui-ajax.php'); 



?>