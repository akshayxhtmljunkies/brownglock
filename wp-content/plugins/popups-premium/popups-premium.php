<?php
/**
 * Popups Premium Plugin 
 *
 * @package   socialpopup
 * @author    Damian Logghe <info@timersys.com>
 * @license   GPL-2.0+
 * @link      http://wp.timersys.com/popups/
 * @copyright 2014 Damian Logghe
 *
 * @socialpopup
 * Plugin Name:       Popups Premium Plugin
 * Plugin URI:        http://www.timersys.com/popups/
 * Version: 		  1.4.2
 * Description: 	  Premium version for popups plugin https://wordpress.org/plugins/popups/
 * Author: 			  Damian Logghe
 * Author URI:        http://wp.timersys.com
 * Text Domain:       spu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}



/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

define( 'SPUP_PLUGIN_FILE' , __FILE__);
define( 'SPUP_PLUGIN_DIR' , plugin_dir_path(__FILE__) );
define( 'SPUP_PLUGIN_URL' , plugin_dir_url(__FILE__) );
define( 'SPUP_PLUGIN_HOOK' , basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );


require_once( plugin_dir_path( __FILE__ ) . 'public/class-popupsp.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'PopupsP', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PopupsP', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'PopupsP', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/


if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-popupsp-admin.php' );
	add_action( 'plugins_loaded', array( 'PopupsP_Admin', 'get_instance' ) );

}