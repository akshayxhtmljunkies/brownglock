<?php



global $ql_options;



if ( is_admin () && ( ! is_multisite () || is_multisite () && ( "yes" != $ql_options [ "only_superadmins" ] || is_super_admin () ) ) ) {

  require_once "ql_edit.php";
  require_once "ql_export.php";
  require_once "ql_import.php";
  require_once "ql_settings.php";

  add_action ( 'admin_notices', 'ql_admin_notices' );
  add_action ( 'admin_menu', 'ql_add_menu_pages' );
  add_filter ( 'plugin_action_links', 'ql_plugin_action_links_filter', 10, 2 );

}



function ql_plugin_action_links_filter ( $links, $file ) {
	if ( $file == plugin_basename ( dirname ( __FILE__ ) . '/index.php' ) ) {
		$links [] = '<a href="admin.php?page=ql-home">' . __ ( "Start", "QL" ) . '</a>';
		$links [] = '<a href="admin.php?page=ql-export">' . __ ( "Export", "QL" ) . '</a>';
		$links [] = '<a href="admin.php?page=ql-import">' . __ ( "Import", "QL" ) . '</a>';
		$links [] = '<a href="admin.php?page=ql-settings">' . __ ( "Settings", "QL" ) . '</a>';
	}
	return $links;
}



function ql_admin_notices () {
	if ( ! current_user_can ( "administrator" ) ) return false;

	$ql_options = get_option ( 'ql_options' );
	if ( $ql_options [ 'version' ] !== QL_VERSION ) {
		echo '<div id="notice" class="updated fade"><p>';
		echo sprintf ( __ ( "<b>QL Version (%s):</b> upgraded successfully.", "QL" ), QL_VERSION );
		echo '</p></div>', "\n";
		$ql_options [ 'version' ] = QL_VERSION;
		update_option ( 'ql_options', $ql_options);
	}

	if ( ( "yes" == $ql_options [ 'collect_draft_translations_fe' ] || "yes" == $ql_options [ 'collect_draft_translations_be' ] ) && ! isset ( $_POST [ 'ql_save' ] ) ) {
		echo '<div id="notice" class="updated fade"><p>' . sprintf ( __ ( 'You are currently gathering <code>gettext</code> localisation entries. Go to the <a href="%s/wp-admin/admin.php?page=ql-settings">Settings</a> page to turn it off.', "QL" ), get_option ( 'home' ) ) . '</p></div>', "\n";
	}
}



function ql_add_menu_pages () {
	add_menu_page ( __ ( "Quick Localisation", "QL" ),	__ ( "Quick Localisation", "QL" ),	8, 'ql-home', 	'ql_edit_page', plugins_url ( 'images/select-language-16.png' , __FILE__ ) );
	add_submenu_page ( 'ql-home', __ ( "Edit", "QL" ),	__ ( "Edit", "QL" ),			8, 'ql-home',		'ql_edit_page' );
	add_submenu_page ( 'ql-home', __ ( "Export", "QL" ),	__ ( "Export", "QL" ),		8, 'ql-export',	'ql_export_page' );
	add_submenu_page ( 'ql-home', __ ( "Import", "QL" ),	__ ( "Import", "QL" ),		8, 'ql-import',	'ql_import_page' );
	add_submenu_page ( 'ql-home', __ ( "Settings", "QL" ),	__ ( "Settings", "QL" ),		8, 'ql-settings',	'ql_settings_page' );
}



?>