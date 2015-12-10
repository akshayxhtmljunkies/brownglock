<?php
/**
 * Uninstall file. If selected all data from popups plugin will be deleted
 */
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit();

$opts = get_option( 'spu_settings' );

if( isset( $opts['unistall']) && '1' == $opts['unistall'] ) {
	// delete settings
	delete_option('spu_hits_db_version');
	delete_option('spu_integrations');
	delete_option('spup_version');
	// delete popups
	global $wpdb;
	$table = $wpdb->prefix . 'spu_hits_logs';
	$wpdb->query( "DROP TABLE IF EXISTS $table");
}