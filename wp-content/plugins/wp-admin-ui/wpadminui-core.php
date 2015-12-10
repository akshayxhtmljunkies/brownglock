<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

    //Export WP Admin UI Settings in JSON
    function wpui_export_settings() {
        if( empty( $_POST['wpui_action'] ) || 'export_settings' != $_POST['wpui_action'] )
            return;
        if( ! wp_verify_nonce( $_POST['wpui_export_nonce'], 'wpui_export_nonce' ) )
            return;
        if( ! current_user_can( 'manage_options' ) )
            return;
        
        $settings["wpui_option_name"]               = get_option( 'wpui_option_name' );
        $settings["wpui_login_option_name"]         = get_option( 'wpui_login_option_name' );
        $settings["wpui_global_option_name"]        = get_option( 'wpui_global_option_name' );
        $settings["wpui_dashboard_option_name"]     = get_option( 'wpui_dashboard_option_name' );
        $settings["wpui_admin_menu_option_name"]    = get_option( 'wpui_admin_menu_option_name' );
        $settings["wpui_admin_bar_option_name"]     = get_option( 'wpui_admin_bar_option_name' );
        $settings["wpui_editor_option_name"]        = get_option( 'wpui_editor_option_name' );
        $settings["wpui_metaboxes_option_name"]     = get_option( 'wpui_metaboxes_option_name' );
        $settings["wpui_columns_option_name"]       = get_option( 'wpui_columns_option_name' );
        $settings["wpui_library_option_name"]       = get_option( 'wpui_library_option_name' );
        $settings["wpui_profil_option_name"]        = get_option( 'wpui_profil_option_name' );
        $settings["wpui_plugins_option_name"]       = get_option( 'wpui_plugins_option_name' );
        $settings["wpui_roles_option_name"]         = get_option( 'wpui_roles_option_name' );

        ignore_user_abort( true );
        nocache_headers();
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=wpui-settings-export-' . date( 'm-d-Y' ) . '.json' );
        header( "Expires: 0" );
        echo json_encode( $settings );
        exit;
    }
    add_action( 'admin_init', 'wpui_export_settings' );
 
    //Import WP Admin UI Settings from JSON
    function wpui_import_settings() {
        if( empty( $_POST['wpui_action'] ) || 'import_settings' != $_POST['wpui_action'] )
            return;
        if( ! wp_verify_nonce( $_POST['wpui_import_nonce'], 'wpui_import_nonce' ) )
            return;
        if( ! current_user_can( 'manage_options' ) )
            return;
        $extension = end( explode( '.', $_FILES['import_file']['name'] ) );
        if( $extension != 'json' ) {
            wp_die( __( 'Please upload a valid .json file' ) );
        }
        $import_file = $_FILES['import_file']['tmp_name'];
        if( empty( $import_file ) ) {
            wp_die( __( 'Please upload a file to import' ) );
        }

        $settings = (array) json_decode( file_get_contents( $import_file ), true );

        update_option( 'wpui_option_name', $settings["wpui_option_name"] ); 
        update_option( 'wpui_login_option_name', $settings["wpui_login_option_name"] ); 
        update_option( 'wpui_global_option_name', $settings["wpui_global_option_name"] ); 
        update_option( 'wpui_dashboard_option_name', $settings["wpui_dashboard_option_name"] ); 
        update_option( 'wpui_admin_menu_option_name', $settings["wpui_admin_menu_option_name"] ); 
        update_option( 'wpui_admin_bar_option_name', $settings["wpui_admin_bar_option_name"] ); 
        update_option( 'wpui_editor_option_name', $settings["wpui_editor_option_name"] ); 
        update_option( 'wpui_metaboxes_option_name', $settings["wpui_metaboxes_option_name"] ); 
        update_option( 'wpui_columns_option_name', $settings["wpui_columns_option_name"] );
        update_option( 'wpui_library_option_name', $settings["wpui_library_option_name"] );
        update_option( 'wpui_profil_option_name', $settings["wpui_profil_option_name"] );
        update_option( 'wpui_plugins_option_name', $settings["wpui_plugins_option_name"] );
        update_option( 'wpui_roles_option_name', $settings["wpui_roles_option_name"] );
         
        wp_safe_redirect( admin_url( 'admin.php?page=wpui-import-export' ) ); exit;
    }
    add_action( 'admin_init', 'wpui_import_settings' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Enable Settings for specific roles
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpui_roles_list_role() {
	$wpui_roles_list_role_option = get_option("wpui_roles_option_name");
	if ( ! empty ( $wpui_roles_list_role_option ) ) {
		foreach ($wpui_roles_list_role_option as $key => $wpui_roles_list_role_value)
			$options[$key] = $wpui_roles_list_role_value;
		 if (isset($wpui_roles_list_role_option['wpui_roles_list_role'])) { 
		 	return $wpui_roles_list_role_option['wpui_roles_list_role'];
		 }
	}
};



add_action('init', 'wpui_enable');
function wpui_enable() {
	global $pagenow;	
    if ( in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') ) ) {
	    
		
		    
		
///////////////////////////////////////////////////////////////////////////////////////////////////
//WPUI Core
///////////////////////////////////////////////////////////////////////////////////////////////////	

//Login
//=================================================================================================

//Custom login CSS
function wpui_login_custom_css() {
	$wpui_login_custom_css_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_custom_css_option ) ) {
		foreach ($wpui_login_custom_css_option as $key => $wpui_login_custom_css_value)
			$options[$key] = $wpui_login_custom_css_value;
		 if (isset($wpui_login_custom_css_option['wpui_login_custom_css'])) { 
		 	return $wpui_login_custom_css_option['wpui_login_custom_css'];
		 }
	}
};

if (wpui_login_custom_css() != '') {
	function wpui_custom_login_css() {
		?>
	    <style type="text/css">
	    	<?php echo wpui_login_custom_css(); ?>
	    </style>
	    <?php
	}
	add_action('login_head', 'wpui_custom_login_css');
}

//Custom login url logo
function wpui_login_url_logo() {
	$wpui_login_logo_url_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_logo_url_option ) ) {
		foreach ($wpui_login_logo_url_option as $key => $wpui_login_logo_url_value)
			$options[$key] = $wpui_login_logo_url_value;
		 if (isset($wpui_login_logo_url_option['wpui_login_logo_url'])) { 
		 	return $wpui_login_logo_url_option['wpui_login_logo_url'];
		 }
	}
};

if (wpui_login_url_logo() != '') {
	function wpui_logo_url_login(){
		return esc_url(wpui_login_url_logo()); 
	}
	add_filter('login_headerurl', 'wpui_logo_url_login', 9999);
}

//Custom login logo
function wpui_login_logo() {
	$wpui_login_logo_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_logo_option ) ) {
		foreach ($wpui_login_logo_option as $key => $wpui_login_logo_value)
			$options[$key] = $wpui_login_logo_value;
		 if (isset($wpui_login_logo_option['wpui_login_logo'])) { 
		 	return $wpui_login_logo_option['wpui_login_logo'];
		 }
	}
};

if (wpui_login_logo() != '') {
	function wpui_logo_login(){
		?>
	    <style type="text/css">
	    	.login h1 a {background-image: url(<?php echo wpui_login_logo(); ?>);}
	    </style>
	    <?php
	}
	add_filter('login_headerurl', 'wpui_logo_login');
}

//Custom login logo title
function wpui_login_custom_logo_title() {
	$wpui_login_custom_logo_title_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_custom_logo_title_option ) ) {
		foreach ($wpui_login_custom_logo_title_option as $key => $wpui_login_custom_logo_title_value)
			$options[$key] = $wpui_login_custom_logo_title_value;
		 if (isset($wpui_login_custom_logo_title_option['wpui_login_custom_logo_title'])) { 
		 	return $wpui_login_custom_logo_title_option['wpui_login_custom_logo_title'];
		 }
	}
};

if (wpui_login_custom_logo_title() != '') {
	function wpui_login_logo_url_title() {
	    return wpui_login_custom_logo_title();
	}
	add_filter( 'login_headertitle', 'wpui_login_logo_url_title' );
}

//Custom bg img
function wpui_login_custom_bg_img() {
	$wpui_login_custom_bg_img_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_custom_bg_img_option ) ) {
		foreach ($wpui_login_custom_bg_img_option as $key => $wpui_login_custom_bg_img_value)
			$options[$key] = $wpui_login_custom_bg_img_value;
		 if (isset($wpui_login_custom_bg_img_option['wpui_login_custom_bg_img'])) { 
		 	return $wpui_login_custom_bg_img_option['wpui_login_custom_bg_img'];
		 }
	}
};

if (wpui_login_custom_bg_img() != '') {
	function wpui_login_bg_img() {
	    ?>
	    <style type="text/css">
	    	body {background: url(<?php echo wpui_login_custom_bg_img(); ?>) no-repeat 50% 50% / cover;}
	    </style>
	    <?php
	}
	add_filter( 'login_headertitle', 'wpui_login_bg_img' );
}

//Remember me
function wpui_login_always_checked() {
	$wpui_login_always_checked_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_always_checked_option ) ) {
		foreach ($wpui_login_always_checked_option as $key => $wpui_login_always_checked_value)
			$options[$key] = $wpui_login_always_checked_value;
		 if (isset($wpui_login_always_checked_option['wpui_login_always_checked'])) { 
		 	return $wpui_login_always_checked_option['wpui_login_always_checked'];
		 }
	}
};

if (wpui_login_always_checked() == '1') {
	function wpui_login_checked_remember_me() {
		add_filter( 'login_footer', 'wpui_rememberme_checked' );
	}
	add_action( 'init', 'wpui_login_checked_remember_me' );

	function wpui_rememberme_checked() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	}
}

//Remove error message
function wpui_login_error_message() {
	$wpui_login_error_message_option = get_option("wpui_login_option_name");
	if ( ! empty ( $wpui_login_error_message_option ) ) {
		foreach ($wpui_login_error_message_option as $key => $wpui_login_error_message_value)
			$options[$key] = $wpui_login_error_message_value;
		 if (isset($wpui_login_error_message_option['wpui_login_error_message'])) { 
		 	return $wpui_login_error_message_option['wpui_login_error_message'];
		 }
	}
};

if (wpui_login_error_message() == '1') {
	add_filter('login_errors','wpui_login_custom_error_message');

	function wpui_login_custom_error_message($error){
		$error = __('Your credentials are incorrect','wpui');
		return $error;
	}
}

	} //Login Screen
	elseif (is_admin()){
	    global $wp_roles;
		//Get current user role
		if(isset(wp_get_current_user()->roles[0])) {
			$wpui_user_role = wp_get_current_user()->roles[0];
		
		//If current user role matchs values from wpui settings then apply
		if (wpui_roles_list_role() != '' ) {
			if( in_array( $wpui_user_role, wpui_roles_list_role())) {

//Global
//=================================================================================================

//Custom admin CSS
function wpui_global_custom_css() {
	$wpui_global_custom_css_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_custom_css_option ) ) {
		foreach ($wpui_global_custom_css_option as $key => $wpui_global_custom_css_value)
			$options[$key] = $wpui_global_custom_css_value;
		 if (isset($wpui_global_custom_css_option['wpui_global_custom_css'])) { 
		 	return $wpui_global_custom_css_option['wpui_global_custom_css'];
		 }
	}
};

if (wpui_global_custom_css() != '') {
	function wpui_load_custom_admin_css() {
		?>
	    <style type="text/css">
	    	<?php echo wpui_global_custom_css(); ?>
	    </style>
	    <?php
	}
	add_action( 'admin_enqueue_scripts', 'wpui_load_custom_admin_css' );
}

//WP version in footer
function wpui_global_version_footer() {
	$wpui_global_version_footer_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_version_footer_option ) ) {
		foreach ($wpui_global_version_footer_option as $key => $wpui_global_version_footer_value)
			$options[$key] = $wpui_global_version_footer_value;
		 if (isset($wpui_global_version_footer_option['wpui_global_version_footer'])) { 
		 	return $wpui_global_version_footer_option['wpui_global_version_footer'];
		 }
	}
};

if (wpui_global_version_footer() == '1') {
	function wpui_remove_version_footer() {
		remove_filter( 'update_footer', 'core_update_footer' ); 
	}

	add_action( 'admin_menu', 'wpui_remove_version_footer' );
}

//Custom WP version in footer
function wpui_global_custom_version_footer() {
	$wpui_global_custom_version_footer_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_custom_version_footer_option ) ) {
		foreach ($wpui_global_custom_version_footer_option as $key => $wpui_global_custom_version_footer_value)
			$options[$key] = $wpui_global_custom_version_footer_value;
		 if (isset($wpui_global_custom_version_footer_option['wpui_global_custom_version_footer'])) { 
		 	return $wpui_global_custom_version_footer_option['wpui_global_custom_version_footer'];
		 }
	}
};

if (wpui_global_custom_version_footer() != '') {
	function wpui_custom_version_footer() {
		return  wpui_global_custom_version_footer();
	}

	add_action( 'update_footer', 'wpui_custom_version_footer' );
}

//Remove WP credits in footer
function wpui_global_credits_footer() {
	$wpui_global_credits_footer_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_credits_footer_option ) ) {
		foreach ($wpui_global_credits_footer_option as $key => $wpui_global_credits_footer_value)
			$options[$key] = $wpui_global_credits_footer_value;
		 if (isset($wpui_global_credits_footer_option['wpui_global_credits_footer'])) { 
		 	return $wpui_global_credits_footer_option['wpui_global_credits_footer'];
		 }
	}
};

if (wpui_global_credits_footer() == '1') {
	function wpui_remove_credits_footer() {
		return '';
	}
	add_filter('admin_footer_text', 'wpui_remove_credits_footer');
}

//Custom WP custom credits in footer
function wpui_global_custom_credits_footer() {
	$wpui_global_custom_credits_footer_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_custom_credits_footer_option ) ) {
		foreach ($wpui_global_custom_credits_footer_option as $key => $wpui_global_custom_credits_footer_value)
			$options[$key] = $wpui_global_custom_credits_footer_value;
		 if (isset($wpui_global_custom_credits_footer_option['wpui_global_custom_credits_footer'])) { 
		 	return $wpui_global_custom_credits_footer_option['wpui_global_custom_credits_footer'];
		 }
	}
};

if (wpui_global_custom_credits_footer() != '') {
	function wpui_custom_credits_footer() {
		return wpui_global_custom_credits_footer();
	}
	add_filter('admin_footer_text', 'wpui_custom_credits_footer');
}

//Custom Favicon
function wpui_global_custom_favicon() {
	$wpui_global_custom_favicon_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_custom_favicon_option ) ) {
		foreach ($wpui_global_custom_favicon_option as $key => $wpui_global_custom_favicon_value)
			$options[$key] = $wpui_global_custom_favicon_value;
		 if (isset($wpui_global_custom_favicon_option['wpui_global_custom_favicon'])) { 
		 	return $wpui_global_custom_favicon_option['wpui_global_custom_favicon'];
		 }
	}
};

if (wpui_global_custom_favicon() != '') {
	function wpui_admin_favicon() {
		echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.wpui_global_custom_favicon().'" />';
	}
	add_action('admin_head', 'wpui_admin_favicon');
}

//Remove help tab
function wpui_global_help_tab() {
	$wpui_global_help_tab_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_help_tab_option ) ) {
		foreach ($wpui_global_help_tab_option as $key => $wpui_global_help_tab_value)
			$options[$key] = $wpui_global_help_tab_value;
		 if (isset($wpui_global_help_tab_option['wpui_global_help_tab'])) { 
		 	return $wpui_global_help_tab_option['wpui_global_help_tab'];
		 }
	}
};

if (wpui_global_help_tab() == '1') {
	add_filter( 'contextual_help', 'wpui_remove_help', 999, 3 );

	function wpui_remove_help( $old_help, $screen_id, $screen ){
		    $screen->remove_help_tabs();
		    return $old_help;
	}
}

//Remove screen options tab
function wpui_global_screen_options_tab() {
	$wpui_global_screen_options_tab_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_screen_options_tab_option ) ) {
		foreach ($wpui_global_screen_options_tab_option as $key => $wpui_global_screen_options_tab_value)
			$options[$key] = $wpui_global_screen_options_tab_value;
		 if (isset($wpui_global_screen_options_tab_option['wpui_global_screen_options_tab'])) { 
		 	return $wpui_global_screen_options_tab_option['wpui_global_screen_options_tab'];
		 }
	}
};

if (wpui_global_screen_options_tab() == '1') {
	add_filter('screen_options_show_screen', '__return_false');
}

//Remove WP update notifications
function wpui_global_update_notification() {
	$wpui_global_update_notification_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_update_notification_option ) ) {
		foreach ($wpui_global_update_notification_option as $key => $wpui_global_update_notification_value)
			$options[$key] = $wpui_global_update_notification_value;
		 if (isset($wpui_global_update_notification_option['wpui_global_update_notification'])) { 
		 	return $wpui_global_update_notification_option['wpui_global_update_notification'];
		 }
	}
};

if (wpui_global_update_notification() == '1') {
	add_action('after_setup_theme','wpui_remove_core_updates');

	remove_action('load-update-core.php','wp_update_plugins');
	add_filter('pre_site_transient_update_plugins','__return_null');

	function wpui_remove_core_updates(){
		global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
	}
	add_filter('pre_site_transient_update_core','wpui_remove_core_updates');
	add_filter('pre_site_transient_update_plugins','wpui_remove_core_updates');
	add_filter('pre_site_transient_update_themes','wpui_remove_core_updates');

	add_action('admin_menu', 'wpui_wphidenag');
	function wpui_wphidenag() {
		remove_action('admin_notices', 'update_nag', 3);
	}
}


//Hide autogenerated password message
function wpui_global_password_notification() {
	$wpui_global_password_notification_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_password_notification_option ) ) {
		foreach ($wpui_global_password_notification_option as $key => $wpui_global_password_notification_value)
			$options[$key] = $wpui_global_password_notification_value;
		 if (isset($wpui_global_password_notification_option['wpui_global_password_notification'])) { 
		 	return $wpui_global_password_notification_option['wpui_global_password_notification'];
		 }
	}
};

if (wpui_global_password_notification() == '1') {
	function wpui_stop_password_nag( $val ){
		return 0;
	}
	add_filter( 'get_user_option_default_password_nag' ,'wpui_stop_password_nag' , 10 );
}

//Remove trash feature
function wpui_global_trash() {
	$wpui_global_trash_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_trash_option ) ) {
		foreach ($wpui_global_trash_option as $key => $wpui_global_trash_value)
			$options[$key] = $wpui_global_trash_value;
		 if (isset($wpui_global_trash_option['wpui_global_trash'])) { 
		 	return $wpui_global_trash_option['wpui_global_trash'];
		 }
	}
};

if (wpui_global_trash() == '1') {
	define('EMPTY_TRASH_DAYS', 0 );
}

//Clean automatically trash
function wpui_global_empty_trash() {
	$wpui_global_empty_trash_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_empty_trash_option ) ) {
		foreach ($wpui_global_empty_trash_option as $key => $wpui_global_empty_trash_value)
			$options[$key] = $wpui_global_empty_trash_value;
		 if (isset($wpui_global_empty_trash_option['wpui_global_empty_trash'])) { 
		 	return $wpui_global_empty_trash_option['wpui_global_empty_trash'];
		 }
	}
};

if (wpui_global_empty_trash() != '') {
	define('EMPTY_TRASH_DAYS', wpui_global_empty_trash() );
}

//Autosave interval
function wpui_global_autosave_interval() {
	$wpui_global_autosave_interval_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_autosave_interval_option ) ) {
		foreach ($wpui_global_autosave_interval_option as $key => $wpui_global_autosave_interval_value)
			$options[$key] = $wpui_global_autosave_interval_value;
		 if (isset($wpui_global_autosave_interval_option['wpui_global_autosave_interval'])) { 
		 	return $wpui_global_autosave_interval_option['wpui_global_autosave_interval'];
		 }
	}
};

if (wpui_global_autosave_interval() != '') {
	define('AUTOSAVE_INTERVAL', wpui_global_autosave_interval() );
}

//Limit posts revisions
function wpui_global_limit_posts_revisions() {
	$wpui_global_limit_posts_revisions_option = get_option("wpui_global_option_name");
	if ( ! empty ( $wpui_global_limit_posts_revisions_option ) ) {
		foreach ($wpui_global_limit_posts_revisions_option as $key => $wpui_global_limit_posts_revisions_value)
			$options[$key] = $wpui_global_limit_posts_revisions_value;
		 if (isset($wpui_global_limit_posts_revisions_option['wpui_global_limit_posts_revisions'])) { 
		 	return $wpui_global_limit_posts_revisions_option['wpui_global_limit_posts_revisions'];
		 }
	}
};

if (wpui_global_limit_posts_revisions() != '') {
	define('WP_POST_REVISIONS', wpui_global_limit_posts_revisions() );
}

//Dashboard
//=================================================================================================

//Welcome Panel
function wpui_dashboard_welcome_panel() {
	$wpui_dashboard_welcome_panel_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_welcome_panel_option ) ) {
		foreach ($wpui_dashboard_welcome_panel_option as $key => $wpui_dashboard_welcome_panel_value)
			$options[$key] = $wpui_dashboard_welcome_panel_value;
		 if (isset($wpui_dashboard_welcome_panel_option['wpui_dashboard_welcome_panel'])) { 
		 	return $wpui_dashboard_welcome_panel_option['wpui_dashboard_welcome_panel'];
		 }
	}
};

//QuickPress
function wpui_dashboard_quick_press() {
	$wpui_dashboard_quick_press_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_quick_press_option ) ) {
		foreach ($wpui_dashboard_quick_press_option as $key => $wpui_dashboard_quick_press_value)
			$options[$key] = $wpui_dashboard_quick_press_value;
		 if (isset($wpui_dashboard_quick_press_option['wpui_dashboard_quick_press'])) { 
		 	return $wpui_dashboard_quick_press_option['wpui_dashboard_quick_press'];
		 }
	}
};

//Activity
function wpui_dashboard_activity() {
	$wpui_dashboard_activity_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_activity_option ) ) {
		foreach ($wpui_dashboard_activity_option as $key => $wpui_dashboard_activity_value)
			$options[$key] = $wpui_dashboard_activity_value;
		 if (isset($wpui_dashboard_activity_option['wpui_dashboard_activity'])) { 
		 	return $wpui_dashboard_activity_option['wpui_dashboard_activity'];
		 }
	}
};

//Incoming Links
function wpui_dashboard_incoming_links() {
	$wpui_dashboard_incoming_links_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_incoming_links_option ) ) {
		foreach ($wpui_dashboard_incoming_links_option as $key => $wpui_dashboard_incoming_links_value)
			$options[$key] = $wpui_dashboard_incoming_links_value;
		 if (isset($wpui_dashboard_incoming_links_option['wpui_dashboard_incoming_links'])) { 
		 	return $wpui_dashboard_incoming_links_option['wpui_dashboard_incoming_links'];
		 }
	}
};

//Right Now
function wpui_dashboard_right_now() {
	$wpui_dashboard_right_now_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_right_now_option ) ) {
		foreach ($wpui_dashboard_right_now_option as $key => $wpui_dashboard_right_now_value)
			$options[$key] = $wpui_dashboard_right_now_value;
		 if (isset($wpui_dashboard_right_now_option['wpui_dashboard_right_now'])) { 
		 	return $wpui_dashboard_right_now_option['wpui_dashboard_right_now'];
		 }
	}
};

//Plugins
function wpui_dashboard_plugins() {
	$wpui_dashboard_plugins_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_plugins_option ) ) {
		foreach ($wpui_dashboard_plugins_option as $key => $wpui_dashboard_plugins_value)
			$options[$key] = $wpui_dashboard_plugins_value;
		 if (isset($wpui_dashboard_plugins_option['wpui_dashboard_plugins'])) { 
		 	return $wpui_dashboard_plugins_option['wpui_dashboard_plugins'];
		 }
	}
};

//Recent Drafts
function wpui_dashboard_recent_drafts() {
	$wpui_dashboard_recent_drafts_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_recent_drafts_option ) ) {
		foreach ($wpui_dashboard_recent_drafts_option as $key => $wpui_dashboard_recent_drafts_value)
			$options[$key] = $wpui_dashboard_recent_drafts_value;
		 if (isset($wpui_dashboard_recent_drafts_option['wpui_dashboard_recent_drafts'])) { 
		 	return $wpui_dashboard_recent_drafts_option['wpui_dashboard_recent_drafts'];
		 }
	}
};

//Recent Comments
function wpui_dashboard_recent_comments() {
	$wpui_dashboard_recent_comments_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_recent_comments_option ) ) {
		foreach ($wpui_dashboard_recent_comments_option as $key => $wpui_dashboard_recent_comments_value)
			$options[$key] = $wpui_dashboard_recent_comments_value;
		 if (isset($wpui_dashboard_recent_comments_option['wpui_dashboard_recent_comments'])) { 
		 	return $wpui_dashboard_recent_comments_option['wpui_dashboard_recent_comments'];
		 }
	}
};

//Primary
function wpui_dashboard_primary() {
	$wpui_dashboard_primary_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_primary_option ) ) {
		foreach ($wpui_dashboard_primary_option as $key => $wpui_dashboard_primary_value)
			$options[$key] = $wpui_dashboard_primary_value;
		 if (isset($wpui_dashboard_primary_option['wpui_dashboard_primary'])) { 
		 	return $wpui_dashboard_primary_option['wpui_dashboard_primary'];
		 }
	}
};

//Secondary
function wpui_dashboard_secondary() {
	$wpui_dashboard_secondary_option = get_option("wpui_dashboard_option_name");
	if ( ! empty ( $wpui_dashboard_secondary_option ) ) {
		foreach ($wpui_dashboard_secondary_option as $key => $wpui_dashboard_secondary_value)
			$options[$key] = $wpui_dashboard_secondary_value;
		 if (isset($wpui_dashboard_secondary_option['wpui_dashboard_secondary'])) { 
		 	return $wpui_dashboard_secondary_option['wpui_dashboard_secondary'];
		 }
	}
};

//Remove Dashboard widgets
function wpui_dashboard_remove_widgets() {
	global $wp_meta_boxes;
	if (wpui_dashboard_welcome_panel() == '1') {
		remove_action( 'welcome_panel', 'wp_welcome_panel' ); //Welcome Panel
	}
	if (wpui_dashboard_quick_press() == '1') {
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); //QuickPress
	}
	if (wpui_dashboard_activity() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']); //Activity
	}
	if (wpui_dashboard_incoming_links() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); //Incoming Links
	}
	if (wpui_dashboard_right_now() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); //Right Now
	}
	if (wpui_dashboard_plugins() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); //Plugins
	}
	if (wpui_dashboard_recent_drafts() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']); //Recent Drafs
	}
	if (wpui_dashboard_recent_comments() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); //Recent Comments
	}
	if (wpui_dashboard_primary() == '1') {
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); //Primary
	}
	if (wpui_dashboard_secondary() == '1') {
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); //Secondary
	}
}

add_action('wp_dashboard_setup', 'wpui_dashboard_remove_widgets' );

//Admin menu
//=================================================================================================

//Hide menu page
function wpui_admin_menu_remove_pages(){
	$wpui_admin_menu_option = get_option("wpui_admin_menu_option_name");

	$wpui_admin_menu_string_only = array_filter($wpui_admin_menu_option[wpui_admin_menu], 'is_string');

	if ( ! empty ( $wpui_admin_menu_option ) ) {
		foreach ($wpui_admin_menu_string_only as $wpui_admin_menu_key => $wpui_admin_menu_value) {
			remove_menu_page( $wpui_admin_menu_value );
		}
	}

	$wpui_admin_menu_numeric_only = array_intersect_key($wpui_admin_menu_option[wpui_admin_menu], array_flip(array_filter(array_keys($wpui_admin_menu_option[wpui_admin_menu]), 'is_numeric')));	

	foreach($wpui_admin_menu_numeric_only as $wpui_admin_menu_numeric_only_key=>$wpui_admin_menu_numeric_only_value){
		foreach($wpui_admin_menu_numeric_only_value as $_wpui_admin_menu_numeric_only_key=>$_wpui_admin_menu_numeric_only_value){
			foreach($_wpui_admin_menu_numeric_only_value as $__wpui_admin_menu_numeric_only_key=>$__wpui_admin_menu_numeric_only_value){
				foreach($__wpui_admin_menu_numeric_only_value as $___wpui_admin_menu_numeric_only_key=>$___wpui_admin_menu_numeric_only_value){
					remove_submenu_page( $_wpui_admin_menu_numeric_only_key, $___wpui_admin_menu_numeric_only_value);
				}
			}
		}
	}
}
add_action( 'admin_menu', 'wpui_admin_menu_remove_pages', 999 );

//Custom Admin Menu Order
function custom_menu_order() {
	$wpui_admin_menu_custom_list = get_option( 'wpui_admin_menu_slug' );
	return $wpui_admin_menu_custom_list;
}

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'custom_menu_order' );

//Admin bar
//=================================================================================================

//WP Logo
function wpui_admin_bar_wp_logo() {
	$wpui_admin_bar_wp_logo_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_wp_logo_option ) ) {
		foreach ($wpui_admin_bar_wp_logo_option as $key => $wpui_admin_bar_wp_logo_value)
			$options[$key] = $wpui_admin_bar_wp_logo_value;
		 if (isset($wpui_admin_bar_wp_logo_option['wpui_admin_bar_wp_logo'])) { 
		 	return $wpui_admin_bar_wp_logo_option['wpui_admin_bar_wp_logo'];
		 }
	}
};

//Site Name
function wpui_admin_bar_site_name() {
	$wpui_admin_bar_site_name_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_site_name_option ) ) {
		foreach ($wpui_admin_bar_site_name_option as $key => $wpui_admin_bar_site_name_value)
			$options[$key] = $wpui_admin_bar_site_name_value;
		 if (isset($wpui_admin_bar_site_name_option['wpui_admin_bar_site_name'])) { 
		 	return $wpui_admin_bar_site_name_option['wpui_admin_bar_site_name'];
		 }
	}
};

//My Account
function wpui_admin_bar_my_account() {
	$wpui_admin_bar_my_account_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_my_account_option ) ) {
		foreach ($wpui_admin_bar_my_account_option as $key => $wpui_admin_bar_my_account_value)
			$options[$key] = $wpui_admin_bar_my_account_value;
		 if (isset($wpui_admin_bar_my_account_option['wpui_admin_bar_my_account'])) { 
		 	return $wpui_admin_bar_my_account_option['wpui_admin_bar_my_account'];
		 }
	}
};

//Menu Toggle
function wpui_admin_bar_menu_toggle() {
	$wpui_admin_bar_menu_toggle_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_menu_toggle_option ) ) {
		foreach ($wpui_admin_bar_menu_toggle_option as $key => $wpui_admin_bar_menu_toggle_value)
			$options[$key] = $wpui_admin_bar_menu_toggle_value;
		 if (isset($wpui_admin_bar_menu_toggle_option['wpui_admin_bar_menu_toggle'])) { 
		 	return $wpui_admin_bar_menu_toggle_option['wpui_admin_bar_menu_toggle'];
		 }
	}
};

//Edit
function wpui_admin_bar_edit() {
	$wpui_admin_bar_edit_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_edit_option ) ) {
		foreach ($wpui_admin_bar_edit_option as $key => $wpui_admin_bar_edit_value)
			$options[$key] = $wpui_admin_bar_edit_value;
		 if (isset($wpui_admin_bar_edit_option['wpui_admin_bar_edit'])) { 
		 	return $wpui_admin_bar_edit_option['wpui_admin_bar_edit'];
		 }
	}
};

//View
function wpui_admin_bar_view() {
	$wpui_admin_bar_view_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_view_option ) ) {
		foreach ($wpui_admin_bar_view_option as $key => $wpui_admin_bar_view_value)
			$options[$key] = $wpui_admin_bar_view_value;
		 if (isset($wpui_admin_bar_view_option['wpui_admin_bar_view'])) { 
		 	return $wpui_admin_bar_view_option['wpui_admin_bar_view'];
		 }
	}
};

//Preview
function wpui_admin_bar_preview() {
	$wpui_admin_bar_preview_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_preview_option ) ) {
		foreach ($wpui_admin_bar_preview_option as $key => $wpui_admin_bar_preview_value)
			$options[$key] = $wpui_admin_bar_preview_value;
		 if (isset($wpui_admin_bar_preview_option['wpui_admin_bar_preview'])) { 
		 	return $wpui_admin_bar_preview_option['wpui_admin_bar_preview'];
		 }
	}
};

//Comments
function wpui_admin_bar_comments() {
	$wpui_admin_bar_comments_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_comments_option ) ) {
		foreach ($wpui_admin_bar_comments_option as $key => $wpui_admin_bar_comments_value)
			$options[$key] = $wpui_admin_bar_comments_value;
		 if (isset($wpui_admin_bar_comments_option['wpui_admin_bar_comments'])) { 
		 	return $wpui_admin_bar_comments_option['wpui_admin_bar_comments'];
		 }
	}
};

//New Content
function wpui_admin_bar_new_content() {
	$wpui_admin_bar_new_content_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_new_content_option ) ) {
		foreach ($wpui_admin_bar_new_content_option as $key => $wpui_admin_bar_new_content_value)
			$options[$key] = $wpui_admin_bar_new_content_value;
		 if (isset($wpui_admin_bar_new_content_option['wpui_admin_bar_new_content'])) { 
		 	return $wpui_admin_bar_new_content_option['wpui_admin_bar_new_content'];
		 }
	}
};

//View Site
function wpui_admin_bar_view_site() {
	$wpui_admin_bar_view_site_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_view_site_option ) ) {
		foreach ($wpui_admin_bar_view_site_option as $key => $wpui_admin_bar_view_site_value)
			$options[$key] = $wpui_admin_bar_view_site_value;
		 if (isset($wpui_admin_bar_view_site_option['wpui_admin_bar_view_site'])) { 
		 	return $wpui_admin_bar_view_site_option['wpui_admin_bar_view_site'];
		 }
	}
};

//Updates
function wpui_admin_bar_updates() {
	$wpui_admin_bar_updates_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_updates_option ) ) {
		foreach ($wpui_admin_bar_updates_option as $key => $wpui_admin_bar_updates_value)
			$options[$key] = $wpui_admin_bar_updates_value;
		 if (isset($wpui_admin_bar_updates_option['wpui_admin_bar_updates'])) { 
		 	return $wpui_admin_bar_updates_option['wpui_admin_bar_updates'];
		 }
	}
};


add_action( 'admin_bar_menu', 'wpui_admin_bar_remove_items', 999 );

function wpui_admin_bar_remove_items( $wp_admin_bar ) {
	if (wpui_admin_bar_wp_logo() == '1') {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}
	if (wpui_admin_bar_site_name() == '1') {
		$wp_admin_bar->remove_menu('site-name');
	}
	if (wpui_admin_bar_my_account() == '1') {
		$wp_admin_bar->remove_node( 'my-account' );
	}
	if (wpui_admin_bar_menu_toggle() == '1') {
		$wp_admin_bar->remove_node( 'menu-toggle' );
	}
	if (wpui_admin_bar_edit() == '1') {
		$wp_admin_bar->remove_menu( 'edit' );
	}
	if (wpui_admin_bar_preview() == '1') {
		$wp_admin_bar->remove_menu( 'preview' );
	}
	if (wpui_admin_bar_view() == '1') {
		$wp_admin_bar->remove_menu( 'view' );
	}
	if (wpui_admin_bar_comments() == '1') {
		$wp_admin_bar->remove_menu( 'comments' );
	}
	if (wpui_admin_bar_new_content() == '1') {
		$wp_admin_bar->remove_menu( 'new-content' );
	}
	if (wpui_admin_bar_view_site() == '1') {
		$wp_admin_bar->remove_menu( 'view-site' );
	}
	if (wpui_admin_bar_updates() == '1') {
		$wp_admin_bar->remove_menu( 'updates' );
	}
}

//Disable admin bar in FE
function wpui_admin_bar_disable() {
	$wpui_admin_bar_disable_option = get_option("wpui_admin_bar_option_name");
	if ( ! empty ( $wpui_admin_bar_disable_option ) ) {
		foreach ($wpui_admin_bar_disable_option as $key => $wpui_admin_bar_disable_value)
			$options[$key] = $wpui_admin_bar_disable_value;
		 if (isset($wpui_admin_bar_disable_option['wpui_admin_bar_disable'])) { 
		 	return $wpui_admin_bar_disable_option['wpui_admin_bar_disable'];
		 }
	}
};

if (wpui_admin_bar_disable() == '1') {
	add_filter('show_admin_bar', '__return_false');
}

//Editor
//=================================================================================================

//Full TinyMCE
function wpui_admin_editor_full_tinymce() {
	$wpui_admin_editor_full_tinymce_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_full_tinymce_option ) ) {
		foreach ($wpui_admin_editor_full_tinymce_option as $key => $wpui_admin_editor_full_tinymce_value)
			$options[$key] = $wpui_admin_editor_full_tinymce_value;
		 if (isset($wpui_admin_editor_full_tinymce_option['wpui_admin_editor_full_tinymce'])) { 
		 	return $wpui_admin_editor_full_tinymce_option['wpui_admin_editor_full_tinymce'];
		 }
	}
};

if (wpui_admin_editor_full_tinymce() == '1') {
	add_filter( 'tiny_mce_before_init', 'wpui_full_tinymce_editor' );
	function wpui_full_tinymce_editor( $in ) {
		$in['wordpress_adv_hidden'] = FALSE;
		return $in;
	}
}

//Font size
function wpui_admin_editor_font_size() {
	$wpui_admin_editor_font_size_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_font_size_option ) ) {
		foreach ($wpui_admin_editor_font_size_option as $key => $wpui_admin_editor_font_size_value)
			$options[$key] = $wpui_admin_editor_font_size_value;
		 if (isset($wpui_admin_editor_font_size_option['wpui_admin_editor_font_size'])) { 
		 	return $wpui_admin_editor_font_size_option['wpui_admin_editor_font_size'];
		 }
	}
};

if (wpui_admin_editor_font_size() == '1') {
	function wpui_font_size_select( $buttons ) {
		array_unshift( $buttons, 'fontsizeselect' );
		return $buttons;
	}
	add_filter( 'mce_buttons_2', 'wpui_font_size_select' );
}

//Font Family
function wpui_admin_editor_font_family() {
	$wpui_admin_editor_font_family_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_font_family_option ) ) {
		foreach ($wpui_admin_editor_font_family_option as $key => $wpui_admin_editor_font_family_value)
			$options[$key] = $wpui_admin_editor_font_family_value;
		 if (isset($wpui_admin_editor_font_family_option['wpui_admin_editor_font_family'])) { 
		 	return $wpui_admin_editor_font_family_option['wpui_admin_editor_font_family'];
		 }
	}
};

if (wpui_admin_editor_font_family() == '1') {
	function wpui_font_family_select( $buttons ) {
		array_unshift( $buttons, 'fontselect' );
		return $buttons;
	}
	add_filter( 'mce_buttons_2', 'wpui_font_family_select' );
}

//Custom Fonts
function wpui_admin_editor_custom_fonts() {
	$wpui_admin_editor_custom_fonts_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_custom_fonts_option ) ) {
		foreach ($wpui_admin_editor_custom_fonts_option as $key => $wpui_admin_editor_custom_fonts_value)
			$options[$key] = $wpui_admin_editor_custom_fonts_value;
		 if (isset($wpui_admin_editor_custom_fonts_option['wpui_admin_editor_custom_fonts'])) { 
		 	return $wpui_admin_editor_custom_fonts_option['wpui_admin_editor_custom_fonts'];
		 }
	}
};

if (wpui_admin_editor_custom_fonts() == '1') {
	function wpui_custom_fonts( $initArray ) {
	    $initArray['font_formats'] = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
	        return $initArray;
	}
	add_filter( 'tiny_mce_before_init', 'wpui_custom_fonts' );
}

//Formats
function wpui_admin_editor_formats_select() {
	$wpui_admin_editor_formats_select_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_formats_select_option ) ) {
		foreach ($wpui_admin_editor_formats_select_option as $key => $wpui_admin_editor_formats_select_value)
			$options[$key] = $wpui_admin_editor_formats_select_value;
		 if (isset($wpui_admin_editor_formats_select_option['wpui_admin_editor_formats_select'])) { 
		 	return $wpui_admin_editor_formats_select_option['wpui_admin_editor_formats_select'];
		 }
	}
};

if (wpui_admin_editor_formats_select() == '1') {
	function wpui_formats_select( $buttons ) {
		array_push( $buttons, 'styleselect' );
		return $buttons;
	}
	add_filter( 'mce_buttons', 'wpui_formats_select' );
}

//Shortlink
function wpui_admin_editor_get_shortlink() {
	$wpui_admin_editor_get_shortlink_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_get_shortlink_option ) ) {
		foreach ($wpui_admin_editor_get_shortlink_option as $key => $wpui_admin_editor_get_shortlink_value)
			$options[$key] = $wpui_admin_editor_get_shortlink_value;
		 if (isset($wpui_admin_editor_get_shortlink_option['wpui_admin_editor_get_shortlink'])) { 
		 	return $wpui_admin_editor_get_shortlink_option['wpui_admin_editor_get_shortlink'];
		 }
	}
};

if (wpui_admin_editor_get_shortlink() == '1') {
	add_filter( 'pre_get_shortlink', '__return_empty_string' );
}

//New Document button
function wpui_admin_editor_btn_newdocument() {
	$wpui_admin_editor_btn_newdocument_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_btn_newdocument_option ) ) {
		foreach ($wpui_admin_editor_btn_newdocument_option as $key => $wpui_admin_editor_btn_newdocument_value)
			$options[$key] = $wpui_admin_editor_btn_newdocument_value;
		 if (isset($wpui_admin_editor_btn_newdocument_option['wpui_admin_editor_btn_newdocument'])) { 
		 	return $wpui_admin_editor_btn_newdocument_option['wpui_admin_editor_btn_newdocument'];
		 }
	}
};

//Cut button
function wpui_admin_editor_btn_cut() {
	$wpui_admin_editor_btn_cut_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_btn_cut_option ) ) {
		foreach ($wpui_admin_editor_btn_cut_option as $key => $wpui_admin_editor_btn_cut_value)
			$options[$key] = $wpui_admin_editor_btn_cut_value;
		 if (isset($wpui_admin_editor_btn_cut_option['wpui_admin_editor_btn_cut'])) { 
		 	return $wpui_admin_editor_btn_cut_option['wpui_admin_editor_btn_cut'];
		 }
	}
};

//Copy button
function wpui_admin_editor_btn_copy() {
	$wpui_admin_editor_btn_copy_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_btn_copy_option ) ) {
		foreach ($wpui_admin_editor_btn_copy_option as $key => $wpui_admin_editor_btn_copy_value)
			$options[$key] = $wpui_admin_editor_btn_copy_value;
		 if (isset($wpui_admin_editor_btn_copy_option['wpui_admin_editor_btn_copy'])) { 
		 	return $wpui_admin_editor_btn_copy_option['wpui_admin_editor_btn_copy'];
		 }
	}
};

//Paste button
function wpui_admin_editor_btn_paste() {
	$wpui_admin_editor_btn_paste_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_btn_paste_option ) ) {
		foreach ($wpui_admin_editor_btn_paste_option as $key => $wpui_admin_editor_btn_paste_value)
			$options[$key] = $wpui_admin_editor_btn_paste_value;
		 if (isset($wpui_admin_editor_btn_paste_option['wpui_admin_editor_btn_paste'])) { 
		 	return $wpui_admin_editor_btn_paste_option['wpui_admin_editor_btn_paste'];
		 }
	}
};

//Backcolor button
function wpui_admin_editor_btn_backcolor() {
	$wpui_admin_editor_btn_backcolor_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_btn_backcolor_option ) ) {
		foreach ($wpui_admin_editor_btn_backcolor_option as $key => $wpui_admin_editor_btn_backcolor_value)
			$options[$key] = $wpui_admin_editor_btn_backcolor_value;
		 if (isset($wpui_admin_editor_btn_backcolor_option['wpui_admin_editor_btn_backcolor'])) { 
		 	return $wpui_admin_editor_btn_backcolor_option['wpui_admin_editor_btn_backcolor'];
		 }
	}
};

function wpui_add_more_buttons_tinymce($buttons) {
	if (wpui_admin_editor_btn_newdocument() == '1') {
		$buttons[] = 'newdocument';
	}
	if (wpui_admin_editor_btn_cut() == '1') {
		$buttons[] = 'cut';
	}
	if (wpui_admin_editor_btn_copy() == '1') {
		$buttons[] = 'copy';
	}
	if (wpui_admin_editor_btn_paste() == '1') {
		$buttons[] = 'paste';
	}
	if (wpui_admin_editor_btn_backcolor() == '1') {
		$buttons[] = 'backcolor';
	}
	return $buttons;
}
add_filter("mce_buttons", "wpui_add_more_buttons_tinymce");

//Insert Media
function wpui_admin_editor_media_insert() {
	$wpui_admin_editor_media_insert_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_insert_option ) ) {
		foreach ($wpui_admin_editor_media_insert_option as $key => $wpui_admin_editor_media_insert_value)
			$options[$key] = $wpui_admin_editor_media_insert_value;
		 if (isset($wpui_admin_editor_media_insert_option['wpui_admin_editor_media_insert'])) { 
		 	return $wpui_admin_editor_media_insert_option['wpui_admin_editor_media_insert'];
		 }
	}
};

//Upload Files
function wpui_admin_editor_media_upload() {
	$wpui_admin_editor_media_upload_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_upload_option ) ) {
		foreach ($wpui_admin_editor_media_upload_option as $key => $wpui_admin_editor_media_upload_value)
			$options[$key] = $wpui_admin_editor_media_upload_value;
		 if (isset($wpui_admin_editor_media_upload_option['wpui_admin_editor_media_upload'])) { 
		 	return $wpui_admin_editor_media_upload_option['wpui_admin_editor_media_upload'];
		 }
	}
};

//Media Library
function wpui_admin_editor_media_library() {
	$wpui_admin_editor_media_library_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_library_option ) ) {
		foreach ($wpui_admin_editor_media_library_option as $key => $wpui_admin_editor_media_library_value)
			$options[$key] = $wpui_admin_editor_media_library_value;
		 if (isset($wpui_admin_editor_media_library_option['wpui_admin_editor_media_library'])) { 
		 	return $wpui_admin_editor_media_library_option['wpui_admin_editor_media_library'];
		 }
	}
};

//Media Gallery
function wpui_admin_editor_media_gallery() {
	$wpui_admin_editor_media_gallery_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_gallery_option ) ) {
		foreach ($wpui_admin_editor_media_gallery_option as $key => $wpui_admin_editor_media_gallery_value)
			$options[$key] = $wpui_admin_editor_media_gallery_value;
		 if (isset($wpui_admin_editor_media_gallery_option['wpui_admin_editor_media_gallery'])) { 
		 	return $wpui_admin_editor_media_gallery_option['wpui_admin_editor_media_gallery'];
		 }
	}
};

//Media Playlist
function wpui_admin_editor_media_playlist() {
	$wpui_admin_editor_media_playlist_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_playlist_option ) ) {
		foreach ($wpui_admin_editor_media_playlist_option as $key => $wpui_admin_editor_media_playlist_value)
			$options[$key] = $wpui_admin_editor_media_playlist_value;
		 if (isset($wpui_admin_editor_media_playlist_option['wpui_admin_editor_media_playlist'])) { 
		 	return $wpui_admin_editor_media_playlist_option['wpui_admin_editor_media_playlist'];
		 }
	}
};

//Featured img
function wpui_admin_editor_media_featured_img() {
	$wpui_admin_editor_media_featured_img_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_featured_img_option ) ) {
		foreach ($wpui_admin_editor_media_featured_img_option as $key => $wpui_admin_editor_media_featured_img_value)
			$options[$key] = $wpui_admin_editor_media_featured_img_value;
		 if (isset($wpui_admin_editor_media_featured_img_option['wpui_admin_editor_media_featured_img'])) { 
		 	return $wpui_admin_editor_media_featured_img_option['wpui_admin_editor_media_featured_img'];
		 }
	}
};

//Insert URL
function wpui_admin_editor_media_insert_url() {
	$wpui_admin_editor_media_insert_url_option = get_option("wpui_editor_option_name");
	if ( ! empty ( $wpui_admin_editor_media_insert_url_option ) ) {
		foreach ($wpui_admin_editor_media_insert_url_option as $key => $wpui_admin_editor_media_insert_url_value)
			$options[$key] = $wpui_admin_editor_media_insert_url_value;
		 if (isset($wpui_admin_editor_media_insert_url_option['wpui_admin_editor_media_insert_url'])) { 
		 	return $wpui_admin_editor_media_insert_url_option['wpui_admin_editor_media_insert_url'];
		 }
	}
};

add_filter( 'media_view_strings', 'wpui_custom_media_uploader' );

function wpui_custom_media_uploader( $strings ) {
	if (wpui_admin_editor_media_insert() == '1') {
		unset( $strings['insertMediaTitle'] ); //Insert Media
	}
	if (wpui_admin_editor_media_upload() == '1') {
		unset( $strings['uploadFilesTitle'] ); //Upload Files
	}
	if (wpui_admin_editor_media_library() == '1') {
		unset( $strings['mediaLibraryTitle'] ); //Media Library
	}
	if (wpui_admin_editor_media_gallery() == '1') {
		unset( $strings['createGalleryTitle'] ); //Create Gallery
	}
	if (wpui_admin_editor_media_playlist() == '1') {
		unset( $strings['createPlaylistTitle'] ); //Create Playlist
	}
	if (wpui_admin_editor_media_featured_img() == '1') {
		unset( $strings['setFeaturedImageTitle'] ); //Set Featured Image
	}
	if (wpui_admin_editor_media_insert_url() == '1') {
		unset( $strings['insertFromUrlTitle'] ); //Insert from URL
	}
	return $strings;
}

//Metaboxes
//=================================================================================================

//Author posts
function wpui_metaboxe_author_posts() {
	$wpui_metaboxe_author_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_author_posts_option ) ) {
		foreach ($wpui_metaboxe_author_posts_option as $key => $wpui_metaboxe_author_posts_value)
			$options[$key] = $wpui_metaboxe_author_posts_value;
		 if (isset($wpui_metaboxe_author_posts_option['wpui_metaboxe_author_posts'])) { 
		 	return $wpui_metaboxe_author_posts_option['wpui_metaboxe_author_posts'];
		 }
	}
};

//Categories posts
function wpui_metaboxe_categories_posts() {
	$wpui_metaboxe_categories_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_categories_posts_option ) ) {
		foreach ($wpui_metaboxe_categories_posts_option as $key => $wpui_metaboxe_categories_posts_value)
			$options[$key] = $wpui_metaboxe_categories_posts_value;
		 if (isset($wpui_metaboxe_categories_posts_option['wpui_metaboxe_categories_posts'])) { 
		 	return $wpui_metaboxe_categories_posts_option['wpui_metaboxe_categories_posts'];
		 }
	}
};

//Comments status posts
function wpui_metaboxe_comments_status_posts() {
	$wpui_metaboxe_comments_status_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_comments_status_posts_option ) ) {
		foreach ($wpui_metaboxe_comments_status_posts_option as $key => $wpui_metaboxe_comments_status_posts_value)
			$options[$key] = $wpui_metaboxe_comments_status_posts_value;
		 if (isset($wpui_metaboxe_comments_status_posts_option['wpui_metaboxe_comments_status_posts'])) { 
		 	return $wpui_metaboxe_comments_status_posts_option['wpui_metaboxe_comments_status_posts'];
		 }
	}
};

//Comments posts
function wpui_metaboxe_comments_posts() {
	$wpui_metaboxe_comments_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_comments_posts_option ) ) {
		foreach ($wpui_metaboxe_comments_posts_option as $key => $wpui_metaboxe_comments_posts_value)
			$options[$key] = $wpui_metaboxe_comments_posts_value;
		 if (isset($wpui_metaboxe_comments_posts_option['wpui_metaboxe_comments_posts'])) { 
		 	return $wpui_metaboxe_comments_posts_option['wpui_metaboxe_comments_posts'];
		 }
	}
};

//Formats posts
function wpui_metaboxe_formats_posts() {
	$wpui_metaboxe_formats_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_formats_posts_option ) ) {
		foreach ($wpui_metaboxe_formats_posts_option as $key => $wpui_metaboxe_formats_posts_value)
			$options[$key] = $wpui_metaboxe_formats_posts_value;
		 if (isset($wpui_metaboxe_formats_posts_option['wpui_metaboxe_formats_posts'])) { 
		 	return $wpui_metaboxe_formats_posts_option['wpui_metaboxe_formats_posts'];
		 }
	}
};

//Attributes posts
function wpui_metaboxe_attributes_posts() {
	$wpui_metaboxe_attributes_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_attributes_posts_option ) ) {
		foreach ($wpui_metaboxe_attributes_posts_option as $key => $wpui_metaboxe_attributes_posts_value)
			$options[$key] = $wpui_metaboxe_attributes_posts_value;
		 if (isset($wpui_metaboxe_attributes_posts_option['wpui_metaboxe_attributes_posts'])) { 
		 	return $wpui_metaboxe_attributes_posts_option['wpui_metaboxe_attributes_posts'];
		 }
	}
};

//Custom fields posts
function wpui_metaboxe_custom_fields_posts() {
	$wpui_metaboxe_custom_fields_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_custom_fields_posts_option ) ) {
		foreach ($wpui_metaboxe_custom_fields_posts_option as $key => $wpui_metaboxe_custom_fields_posts_value)
			$options[$key] = $wpui_metaboxe_custom_fields_posts_value;
		 if (isset($wpui_metaboxe_custom_fields_posts_option['wpui_metaboxe_custom_fields_posts'])) { 
		 	return $wpui_metaboxe_custom_fields_posts_option['wpui_metaboxe_custom_fields_posts'];
		 }
	}
};

//Excerpt posts
function wpui_metaboxe_excerpt_posts() {
	$wpui_metaboxe_excerpt_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_excerpt_posts_option ) ) {
		foreach ($wpui_metaboxe_excerpt_posts_option as $key => $wpui_metaboxe_excerpt_posts_value)
			$options[$key] = $wpui_metaboxe_excerpt_posts_value;
		 if (isset($wpui_metaboxe_excerpt_posts_option['wpui_metaboxe_excerpt_posts'])) { 
		 	return $wpui_metaboxe_excerpt_posts_option['wpui_metaboxe_excerpt_posts'];
		 }
	}
};

//Featured img posts
function wpui_metaboxe_featured_image_posts() {
	$wpui_metaboxe_featured_image_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_featured_image_posts_option ) ) {
		foreach ($wpui_metaboxe_featured_image_posts_option as $key => $wpui_metaboxe_featured_image_posts_value)
			$options[$key] = $wpui_metaboxe_featured_image_posts_value;
		 if (isset($wpui_metaboxe_featured_image_posts_option['wpui_metaboxe_featured_image_posts'])) { 
		 	return $wpui_metaboxe_featured_image_posts_option['wpui_metaboxe_featured_image_posts'];
		 }
	}
};

//Revisions posts
function wpui_metaboxe_revisions_posts() {
	$wpui_metaboxe_revisions_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_revisions_posts_option ) ) {
		foreach ($wpui_metaboxe_revisions_posts_option as $key => $wpui_metaboxe_revisions_posts_value)
			$options[$key] = $wpui_metaboxe_revisions_posts_value;
		 if (isset($wpui_metaboxe_revisions_posts_option['wpui_metaboxe_revisions_posts'])) { 
		 	return $wpui_metaboxe_revisions_posts_option['wpui_metaboxe_revisions_posts'];
		 }
	}
};

//Slug posts
function wpui_metaboxe_slug_posts() {
	$wpui_metaboxe_slug_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_slug_posts_option ) ) {
		foreach ($wpui_metaboxe_slug_posts_option as $key => $wpui_metaboxe_slug_posts_value)
			$options[$key] = $wpui_metaboxe_slug_posts_value;
		 if (isset($wpui_metaboxe_slug_posts_option['wpui_metaboxe_slug_posts'])) { 
		 	return $wpui_metaboxe_slug_posts_option['wpui_metaboxe_slug_posts'];
		 }
	}
};

//Submit posts
function wpui_metaboxe_submit_posts() {
	$wpui_metaboxe_submit_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_submit_posts_option ) ) {
		foreach ($wpui_metaboxe_submit_posts_option as $key => $wpui_metaboxe_submit_posts_value)
			$options[$key] = $wpui_metaboxe_submit_posts_value;
		 if (isset($wpui_metaboxe_submit_posts_option['wpui_metaboxe_submit_posts'])) { 
		 	return $wpui_metaboxe_submit_posts_option['wpui_metaboxe_submit_posts'];
		 }
	}
};

//Tags posts
function wpui_metaboxe_tags_posts() {
	$wpui_metaboxe_tags_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_tags_posts_option ) ) {
		foreach ($wpui_metaboxe_tags_posts_option as $key => $wpui_metaboxe_tags_posts_value)
			$options[$key] = $wpui_metaboxe_tags_posts_value;
		 if (isset($wpui_metaboxe_tags_posts_option['wpui_metaboxe_tags_posts'])) { 
		 	return $wpui_metaboxe_tags_posts_option['wpui_metaboxe_tags_posts'];
		 }
	}
};

//Trackbacks posts
function wpui_metaboxe_trackbacks_posts() {
	$wpui_metaboxe_trackbacks_posts_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_trackbacks_posts_option ) ) {
		foreach ($wpui_metaboxe_trackbacks_posts_option as $key => $wpui_metaboxe_trackbacks_posts_value)
			$options[$key] = $wpui_metaboxe_trackbacks_posts_value;
		 if (isset($wpui_metaboxe_trackbacks_posts_option['wpui_metaboxe_trackbacks_posts'])) { 
		 	return $wpui_metaboxe_trackbacks_posts_option['wpui_metaboxe_trackbacks_posts'];
		 }
	}
};

function wpui_remove_meta_boxes_posts() {
	if (wpui_metaboxe_author_posts() == '1') {
		remove_meta_box('authordiv', 'post', 'normal'); //Auhor
	}
	if (wpui_metaboxe_categories_posts() == '1') {
		remove_meta_box('categorydiv', 'post', 'normal'); //Categories
	}
	if (wpui_metaboxe_comments_status_posts() == '1') {
		remove_meta_box('commentstatusdiv', 'post', 'normal'); //Comments status
	}
	if (wpui_metaboxe_comments_posts() == '1') {
		remove_meta_box('commentsdiv', 'post', 'normal'); //Comments
	}
	if (wpui_metaboxe_formats_posts() == '1') {
		remove_meta_box('formatdiv', 'post', 'normal'); //Formats
	}
	if (wpui_metaboxe_attributes_posts() == '1') {
		remove_meta_box('pageparentdiv', 'post', 'normal'); //Attributes
	}
	if (wpui_metaboxe_custom_fields_posts() == '1') {
		remove_meta_box('postcustom', 'post', 'normal'); //Custom fields
	}
	if (wpui_metaboxe_excerpt_posts() == '1') {
		remove_meta_box('postexcerpt', 'post', 'normal'); //Excerpt
	}
	if (wpui_metaboxe_featured_image_posts() == '1') {
		remove_meta_box('postimagediv', 'post', 'normal'); //Featured img
	}
	if (wpui_metaboxe_revisions_posts() == '1') {
		remove_meta_box('revisionsdiv', 'post', 'normal'); //Revisions
	}
	if (wpui_metaboxe_submit_posts() == '1') {
		remove_meta_box('submitdiv', 'post', 'normal'); //Submit
	}
	if (wpui_metaboxe_tags_posts() == '1') {
		remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); //Tags
	}
	if (wpui_metaboxe_trackbacks_posts() == '1') {
		remove_meta_box('trackbacksdiv', 'post', 'normal'); //Trackbacks
	}
}
add_action( 'admin_menu', 'wpui_remove_meta_boxes_posts' );

//Author pages
function wpui_metaboxe_author_pages() {
	$wpui_metaboxe_author_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_author_pages_option ) ) {
		foreach ($wpui_metaboxe_author_pages_option as $key => $wpui_metaboxe_author_pages_value)
			$options[$key] = $wpui_metaboxe_author_pages_value;
		 if (isset($wpui_metaboxe_author_pages_option['wpui_metaboxe_author_pages'])) { 
		 	return $wpui_metaboxe_author_pages_option['wpui_metaboxe_author_pages'];
		 }
	}
};

//Comments status pages
function wpui_metaboxe_comments_status_pages() {
	$wpui_metaboxe_comments_status_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_comments_status_pages_option ) ) {
		foreach ($wpui_metaboxe_comments_status_pages_option as $key => $wpui_metaboxe_comments_status_pages_value)
			$options[$key] = $wpui_metaboxe_comments_status_pages_value;
		 if (isset($wpui_metaboxe_comments_status_pages_option['wpui_metaboxe_comments_status_pages'])) { 
		 	return $wpui_metaboxe_comments_status_pages_option['wpui_metaboxe_comments_status_pages'];
		 }
	}
};

//Comments pages
function wpui_metaboxe_comments_pages() {
	$wpui_metaboxe_comments_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_comments_pages_option ) ) {
		foreach ($wpui_metaboxe_comments_pages_option as $key => $wpui_metaboxe_comments_pages_value)
			$options[$key] = $wpui_metaboxe_comments_pages_value;
		 if (isset($wpui_metaboxe_comments_pages_option['wpui_metaboxe_comments_pages'])) { 
		 	return $wpui_metaboxe_comments_pages_option['wpui_metaboxe_comments_pages'];
		 }
	}
};

//Attributes pages
function wpui_metaboxe_attributes_pages() {
	$wpui_metaboxe_attributes_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_attributes_pages_option ) ) {
		foreach ($wpui_metaboxe_attributes_pages_option as $key => $wpui_metaboxe_attributes_pages_value)
			$options[$key] = $wpui_metaboxe_attributes_pages_value;
		 if (isset($wpui_metaboxe_attributes_pages_option['wpui_metaboxe_attributes_pages'])) { 
		 	return $wpui_metaboxe_attributes_pages_option['wpui_metaboxe_attributes_pages'];
		 }
	}
};

//Custom fields pages
function wpui_metaboxe_custom_fields_pages() {
	$wpui_metaboxe_custom_fields_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_custom_fields_pages_option ) ) {
		foreach ($wpui_metaboxe_custom_fields_pages_option as $key => $wpui_metaboxe_custom_fields_pages_value)
			$options[$key] = $wpui_metaboxe_custom_fields_pages_value;
		 if (isset($wpui_metaboxe_custom_fields_pages_option['wpui_metaboxe_custom_fields_pages'])) { 
		 	return $wpui_metaboxe_custom_fields_pages_option['wpui_metaboxe_custom_fields_pages'];
		 }
	}
};

//Featured img pages
function wpui_metaboxe_featured_image_pages() {
	$wpui_metaboxe_featured_image_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_featured_image_pages_option ) ) {
		foreach ($wpui_metaboxe_featured_image_pages_option as $key => $wpui_metaboxe_featured_image_pages_value)
			$options[$key] = $wpui_metaboxe_featured_image_pages_value;
		 if (isset($wpui_metaboxe_featured_image_pages_option['wpui_metaboxe_featured_image_pages'])) { 
		 	return $wpui_metaboxe_featured_image_pages_option['wpui_metaboxe_featured_image_pages'];
		 }
	}
};

//Revisions pages
function wpui_metaboxe_revisions_pages() {
	$wpui_metaboxe_revisions_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_revisions_pages_option ) ) {
		foreach ($wpui_metaboxe_revisions_pages_option as $key => $wpui_metaboxe_revisions_pages_value)
			$options[$key] = $wpui_metaboxe_revisions_pages_value;
		 if (isset($wpui_metaboxe_revisions_pages_option['wpui_metaboxe_revisions_pages'])) { 
		 	return $wpui_metaboxe_revisions_pages_option['wpui_metaboxe_revisions_pages'];
		 }
	}
};

//Slug pages
function wpui_metaboxe_slug_pages() {
	$wpui_metaboxe_slug_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_slug_pages_option ) ) {
		foreach ($wpui_metaboxe_slug_pages_option as $key => $wpui_metaboxe_slug_pages_value)
			$options[$key] = $wpui_metaboxe_slug_pages_value;
		 if (isset($wpui_metaboxe_slug_pages_option['wpui_metaboxe_slug_pages'])) { 
		 	return $wpui_metaboxe_slug_pages_option['wpui_metaboxe_slug_pages'];
		 }
	}
};

//Submit pages
function wpui_metaboxe_submit_pages() {
	$wpui_metaboxe_submit_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_submit_pages_option ) ) {
		foreach ($wpui_metaboxe_submit_pages_option as $key => $wpui_metaboxe_submit_pages_value)
			$options[$key] = $wpui_metaboxe_submit_pages_value;
		 if (isset($wpui_metaboxe_submit_pages_option['wpui_metaboxe_submit_pages'])) { 
		 	return $wpui_metaboxe_submit_pages_option['wpui_metaboxe_submit_pages'];
		 }
	}
};

//Trackbacks pages
function wpui_metaboxe_trackbacks_pages() {
	$wpui_metaboxe_trackbacks_pages_option = get_option("wpui_metaboxes_option_name");
	if ( ! empty ( $wpui_metaboxe_trackbacks_pages_option ) ) {
		foreach ($wpui_metaboxe_trackbacks_pages_option as $key => $wpui_metaboxe_trackbacks_pages_value)
			$options[$key] = $wpui_metaboxe_trackbacks_pages_value;
		 if (isset($wpui_metaboxe_trackbacks_pages_option['wpui_metaboxe_trackbacks_pages'])) { 
		 	return $wpui_metaboxe_trackbacks_pages_option['wpui_metaboxe_trackbacks_pages'];
		 }
	}
};

function wpui_remove_meta_boxes_pages() {
	if (wpui_metaboxe_author_pages() == '1') {
		remove_meta_box('authordiv', 'page', 'normal'); //Auhor
	}
	if (wpui_metaboxe_comments_status_pages() == '1') {
		remove_meta_box('commentstatusdiv', 'page', 'normal'); //Comments status
	}
	if (wpui_metaboxe_comments_pages() == '1') {
		remove_meta_box('commentsdiv', 'page', 'normal'); //Comments
	}
	if (wpui_metaboxe_attributes_pages() == '1') {
		remove_meta_box('pageparentdiv', 'page', 'normal'); //Attributes
	}
	if (wpui_metaboxe_custom_fields_pages() == '1') {
		remove_meta_box('postcustom', 'page', 'normal'); //Custom fields
	}
	if (wpui_metaboxe_featured_image_pages() == '1') {
		remove_meta_box('postimagediv', 'page', 'normal'); //Featured img
	}
	if (wpui_metaboxe_revisions_pages() == '1') {
		remove_meta_box('revisionsdiv', 'page', 'normal'); //Revisions
	}
	if (wpui_metaboxe_submit_pages() == '1') {
		remove_meta_box('submitdiv', 'page', 'normal'); //Submit
	}
	if (wpui_metaboxe_trackbacks_pages() == '1') {
		remove_meta_box('trackbacksdiv', 'page', 'normal'); //Trackbacks
	}
}
add_action( 'admin_menu', 'wpui_remove_meta_boxes_pages' );

//Columns
//=================================================================================================

//Checkboxes column posts
function wpui_col_cb_posts() {
	$wpui_col_cb_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_cb_posts_option ) ) {
		foreach ($wpui_col_cb_posts_option as $key => $wpui_col_cb_posts_value)
			$options[$key] = $wpui_col_cb_posts_value;
		 if (isset($wpui_col_cb_posts_option['wpui_col_cb_posts'])) { 
		 	return $wpui_col_cb_posts_option['wpui_col_cb_posts'];
		 }
	}
};

//Title column posts
function wpui_col_title_posts() {
	$wpui_col_title_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_title_posts_option ) ) {
		foreach ($wpui_col_title_posts_option as $key => $wpui_col_title_posts_value)
			$options[$key] = $wpui_col_title_posts_value;
		 if (isset($wpui_col_title_posts_option['wpui_col_title_posts'])) { 
		 	return $wpui_col_title_posts_option['wpui_col_title_posts'];
		 }
	}
};

//Author column posts
function wpui_col_author_posts() {
	$wpui_col_author_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_author_posts_option ) ) {
		foreach ($wpui_col_author_posts_option as $key => $wpui_col_author_posts_value)
			$options[$key] = $wpui_col_author_posts_value;
		 if (isset($wpui_col_author_posts_option['wpui_col_author_posts'])) { 
		 	return $wpui_col_author_posts_option['wpui_col_author_posts'];
		 }
	}
};

//Categories column posts
function wpui_col_categories_posts() {
	$wpui_col_categories_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_categories_posts_option ) ) {
		foreach ($wpui_col_categories_posts_option as $key => $wpui_col_categories_posts_value)
			$options[$key] = $wpui_col_categories_posts_value;
		 if (isset($wpui_col_categories_posts_option['wpui_col_categories_posts'])) { 
		 	return $wpui_col_categories_posts_option['wpui_col_categories_posts'];
		 }
	}
};

//Tags column posts
function wpui_col_tags_posts() {
	$wpui_col_tags_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_tags_posts_option ) ) {
		foreach ($wpui_col_tags_posts_option as $key => $wpui_col_tags_posts_value)
			$options[$key] = $wpui_col_tags_posts_value;
		 if (isset($wpui_col_tags_posts_option['wpui_col_tags_posts'])) { 
		 	return $wpui_col_tags_posts_option['wpui_col_tags_posts'];
		 }
	}
};

//Comments column posts
function wpui_col_comments_posts() {
	$wpui_col_comments_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_comments_posts_option ) ) {
		foreach ($wpui_col_comments_posts_option as $key => $wpui_col_comments_posts_value)
			$options[$key] = $wpui_col_comments_posts_value;
		 if (isset($wpui_col_comments_posts_option['wpui_col_comments_posts'])) { 
		 	return $wpui_col_comments_posts_option['wpui_col_comments_posts'];
		 }
	}
};

//Date column posts
function wpui_col_date_posts() {
	$wpui_col_date_posts_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_date_posts_option ) ) {
		foreach ($wpui_col_date_posts_option as $key => $wpui_col_date_posts_value)
			$options[$key] = $wpui_col_date_posts_value;
		 if (isset($wpui_col_date_posts_option['wpui_col_date_posts'])) { 
		 	return $wpui_col_date_posts_option['wpui_col_date_posts'];
		 }
	}
};

//Checkboxes column pages
function wpui_col_cb_pages() {
	$wpui_col_cb_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_cb_pages_option ) ) {
		foreach ($wpui_col_cb_pages_option as $key => $wpui_col_cb_pages_value)
			$options[$key] = $wpui_col_cb_pages_value;
		 if (isset($wpui_col_cb_pages_option['wpui_col_cb_pages'])) { 
		 	return $wpui_col_cb_pages_option['wpui_col_cb_pages'];
		 }
	}
};

//Title column pages
function wpui_col_title_pages() {
	$wpui_col_title_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_title_pages_option ) ) {
		foreach ($wpui_col_title_pages_option as $key => $wpui_col_title_pages_value)
			$options[$key] = $wpui_col_title_pages_value;
		 if (isset($wpui_col_title_pages_option['wpui_col_title_pages'])) { 
		 	return $wpui_col_title_pages_option['wpui_col_title_pages'];
		 }
	}
};

//Author column pages
function wpui_col_author_pages() {
	$wpui_col_author_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_author_pages_option ) ) {
		foreach ($wpui_col_author_pages_option as $key => $wpui_col_author_pages_value)
			$options[$key] = $wpui_col_author_pages_value;
		 if (isset($wpui_col_author_pages_option['wpui_col_author_pages'])) { 
		 	return $wpui_col_author_pages_option['wpui_col_author_pages'];
		 }
	}
};

//Categories column pages
function wpui_col_categories_pages() {
	$wpui_col_categories_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_categories_pages_option ) ) {
		foreach ($wpui_col_categories_pages_option as $key => $wpui_col_categories_pages_value)
			$options[$key] = $wpui_col_categories_pages_value;
		 if (isset($wpui_col_categories_pages_option['wpui_col_categories_pages'])) { 
		 	return $wpui_col_categories_pages_option['wpui_col_categories_pages'];
		 }
	}
};

//Tags column pages
function wpui_col_tags_pages() {
	$wpui_col_tags_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_tags_pages_option ) ) {
		foreach ($wpui_col_tags_pages_option as $key => $wpui_col_tags_pages_value)
			$options[$key] = $wpui_col_tags_pages_value;
		 if (isset($wpui_col_tags_pages_option['wpui_col_tags_pages'])) { 
		 	return $wpui_col_tags_pages_option['wpui_col_tags_pages'];
		 }
	}
};

//Comments column pages
function wpui_col_comments_pages() {
	$wpui_col_comments_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_comments_pages_option ) ) {
		foreach ($wpui_col_comments_pages_option as $key => $wpui_col_comments_pages_value)
			$options[$key] = $wpui_col_comments_pages_value;
		 if (isset($wpui_col_comments_pages_option['wpui_col_comments_pages'])) { 
		 	return $wpui_col_comments_pages_option['wpui_col_comments_pages'];
		 }
	}
};

//Date column pages
function wpui_col_date_pages() {
	$wpui_col_date_pages_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_date_pages_option ) ) {
		foreach ($wpui_col_date_pages_option as $key => $wpui_col_date_pages_value)
			$options[$key] = $wpui_col_date_pages_value;
		 if (isset($wpui_col_date_pages_option['wpui_col_date_pages'])) { 
		 	return $wpui_col_date_pages_option['wpui_col_date_pages'];
		 }
	}
};

//Checkboxes column media
function wpui_col_cb_media() {
	$wpui_col_cb_media_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_cb_media_option ) ) {
		foreach ($wpui_col_cb_media_option as $key => $wpui_col_cb_media_value)
			$options[$key] = $wpui_col_cb_media_value;
		 if (isset($wpui_col_cb_media_option['wpui_col_cb_media'])) { 
		 	return $wpui_col_cb_media_option['wpui_col_cb_media'];
		 }
	}
};

//Icon column media
function wpui_col_icon_media() {
	$wpui_col_icon_media_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_icon_media_option ) ) {
		foreach ($wpui_col_icon_media_option as $key => $wpui_col_icon_media_value)
			$options[$key] = $wpui_col_icon_media_value;
		 if (isset($wpui_col_icon_media_option['wpui_col_icon_media'])) { 
		 	return $wpui_col_icon_media_option['wpui_col_icon_media'];
		 }
	}
};

//Title column media
function wpui_col_title_media() {
	$wpui_col_title_media_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_title_media_option ) ) {
		foreach ($wpui_col_title_media_option as $key => $wpui_col_title_media_value)
			$options[$key] = $wpui_col_title_media_value;
		 if (isset($wpui_col_title_media_option['wpui_col_icon_media'])) { 
		 	return $wpui_col_title_media_option['wpui_col_icon_media'];
		 }
	}
};

//Author column media
function wpui_col_author_media() {
	$wpui_col_author_media_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_author_media_option ) ) {
		foreach ($wpui_col_author_media_option as $key => $wpui_col_author_media_value)
			$options[$key] = $wpui_col_author_media_value;
		 if (isset($wpui_col_author_media_option['wpui_col_author_media'])) { 
		 	return $wpui_col_author_media_option['wpui_col_author_media'];
		 }
	}
};

//Parent column media
function wpui_col_parent_media() {
	$wpui_col_author_parent_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_parent_media_option ) ) {
		foreach ($wpui_col_parent_media_option as $key => $wpui_col_parent_media_value)
			$options[$key] = $wpui_col_parent_media_value;
		 if (isset($wpui_col_parent_media_option['wpui_col_parent_media'])) { 
		 	return $wpui_col_parent_media_option['wpui_col_parent_media'];
		 }
	}
};

//Comments column media
function wpui_col_comments_media() {
	$wpui_col_comments_parent_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_comments_media_option ) ) {
		foreach ($wpui_col_comments_media_option as $key => $wpui_col_comments_media_value)
			$options[$key] = $wpui_col_comments_media_value;
		 if (isset($wpui_col_comments_media_option['wpui_col_comments_media'])) { 
		 	return $wpui_col_comments_media_option['wpui_col_comments_media'];
		 }
	}
};

//Date column media
function wpui_col_date_media() {
	$wpui_col_author_date_option = get_option("wpui_columns_option_name");
	if ( ! empty ( $wpui_col_date_media_option ) ) {
		foreach ($wpui_col_date_media_option as $key => $wpui_col_date_media_value)
			$options[$key] = $wpui_col_date_media_value;
		 if (isset($wpui_col_date_media_option['wpui_col_date_media'])) { 
		 	return $wpui_col_date_media_option['wpui_col_date_media'];
		 }
	}
};

add_filter( 'manage_posts_columns', 'wpui_remove_posts_columns' );

function wpui_remove_posts_columns( $columns ) {
	if (wpui_col_cb_posts() == '1') {
	    unset( $columns['cb'] );
	}
	if (wpui_col_title_posts() == '1') {
	    unset( $columns['title'] );
	}
	if (wpui_col_author_posts() == '1') {
	    unset( $columns['author'] );
	}
	if (wpui_col_categories_posts() == '1') {
	    unset( $columns['categories'] );
	}
	if (wpui_col_tags_posts() == '1') {
	    unset( $columns['tags'] );
	}
	if (wpui_col_comments_posts() == '1') {
	    unset( $columns['comments'] );
	}
	if (wpui_col_date_posts() == '1') {
	    unset( $columns['date'] );
	}
	    return $columns;
}

add_filter( 'manage_pages_columns', 'wpui_remove_pages_columns' );

function wpui_remove_pages_columns( $columns ) {
	if (wpui_col_cb_pages() == '1') {
	    unset( $columns['cb'] );
	}
	if (wpui_col_title_pages() == '1') {
	    unset( $columns['title'] );
	}
	if (wpui_col_author_pages() == '1') {
	    unset( $columns['author'] );
	}
	if (wpui_col_categories_pages() == '1') {
	    unset( $columns['categories'] );
	}
	if (wpui_col_tags_pages() == '1') {
	    unset( $columns['tags'] );
	}
	if (wpui_col_comments_pages() == '1') {
	    unset( $columns['comments'] );
	}
	if (wpui_col_date_pages() == '1') {
	    unset( $columns['date'] );
	}
	    return $columns;
}

add_filter( 'manage_media_columns', 'wpui_remove_media_columns' );

function wpui_remove_media_columns( $columns ) {
	if (wpui_col_cb_media() == '1') {
	    unset( $columns['cb'] );
	}
	if (wpui_col_icon_media() == '1') {
	    unset( $columns['icon'] );
	}
	if (wpui_col_title_media() == '1') {
	    unset( $columns['title'] );
	}
	if (wpui_col_author_media() == '1') {
	    unset( $columns['author'] );
	}
	if (wpui_col_parent_media() == '1') {
	    unset( $columns['parent'] );
	}
	if (wpui_col_comments_media() == '1') {
	    unset( $columns['comments'] );
	}
	if (wpui_col_date_media() == '1') {
	    unset( $columns['date'] );
	}
	    return $columns;
}

//Media Library
//=================================================================================================

//PDF Filter
function wpui_library_filters_pdf() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_pdf'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_pdf'];
		 }
	}
};

//ZIP Filter
function wpui_library_filters_zip() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_zip'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_zip'];
		 }
	}
};

//RAR Filter
function wpui_library_filters_rar() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_rar'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_rar'];
		 }
	}
};

//7Z Filter
function wpui_library_filters_7z() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_7z'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_7z'];
		 }
	}
};

//TAR Filter
function wpui_library_filters_tar() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_tar'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_tar'];
		 }
	}
};

//SWF Filter
function wpui_library_filters_swf() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_swf'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_swf'];
		 }
	}
};

//DOC Filter
function wpui_library_filters_doc() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_doc'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_doc'];
		 }
	}
};

//DOCX Filter
function wpui_library_filters_docx() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_docx'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_docx'];
		 }
	}
};

//PPT Filter
function wpui_library_filters_ppt() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_ppt'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_ppt'];
		 }
	}
};

//PPTX Filter
function wpui_library_filters_pptx() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_pptx'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_pptx'];
		 }
	}
};

//XLS Filter
function wpui_library_filters_xls() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_xls'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_xls'];
		 }
	}
};

//XLSX Filter
function wpui_library_filters_xlsx() {
	$wpui_library_filters_option = get_option("wpui_library_option_name");
	if ( ! empty ( $wpui_library_filters_option ) ) {
		foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
			$options[$key] = $wpui_library_filters_value;
		 if (isset($wpui_library_filters_option['wpui_library_filters_xlsx'])) { 
		 	return $wpui_library_filters_option['wpui_library_filters_xlsx'];
		 }
	}
};

function wpui_get_allowed_mime_types( $post_mime_types ) {
	if (wpui_library_filters_pdf() == '1') {
    	$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_zip() == '1') {
    	$post_mime_types['application/zip'] = array( __( 'ZIPs' ), __( 'Manage ZIPs' ), _n_noop( 'ZIP <span class="count">(%s)</span>', 'ZIPs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_rar() == '1') {
    	$post_mime_types['application/rar'] = array( __( 'RARs' ), __( 'Manage RARs' ), _n_noop( 'RAR <span class="count">(%s)</span>', 'RARs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_7z() == '1') {
    	$post_mime_types['application/x-7z-compressed'] = array( __( '7Zs' ), __( 'Manage 7Zs' ), _n_noop( '7Z <span class="count">(%s)</span>', '7Zs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_tar() == '1') {
    	$post_mime_types['application/x-tar'] = array( __( 'TARs' ), __( 'Manage TARs' ), _n_noop( 'TAR <span class="count">(%s)</span>', 'TARs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_swf() == '1') {
    	$post_mime_types['application/x-shockwave-flash'] = array( __( 'SWFs' ), __( 'Manage SWFs' ), _n_noop( 'SWF <span class="count">(%s)</span>', 'SWFs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_doc() == '1') {
    	$post_mime_types['application/msword'] = array( __( 'DOCs' ), __( 'Manage DOCs' ), _n_noop( 'DOC <span class="count">(%s)</span>', 'DOCs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_docx() == '1') {
    	$post_mime_types['application/vnd.openxmlformats-officedocument.wordprocessingml.document'] = array( __( 'DOCXs' ), __( 'Manage DOCXs' ), _n_noop( 'DOCX <span class="count">(%s)</span>', 'DOCXs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_ppt() == '1') {
    	$post_mime_types['application/vnd.ms-powerpoint'] = array( __( 'PPTs' ), __( 'Manage PPTs' ), _n_noop( 'PPT <span class="count">(%s)</span>', 'PPTs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_pptx() == '1') {
    	$post_mime_types['application/vnd.openxmlformats-officedocument.presentationml.presentation'] = array( __( 'PPTXs' ), __( 'Manage PPTXs' ), _n_noop( 'PPTX <span class="count">(%s)</span>', 'PPTXs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_xls() == '1') {
    	$post_mime_types['application/vnd.ms-excel'] = array( __( 'XLSs' ), __( 'Manage XLSs' ), _n_noop( 'XLS <span class="count">(%s)</span>', 'XLSs <span class="count">(%s)</span>' ) );
    }
    if (wpui_library_filters_xlsx() == '1') {
    	$post_mime_types['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'] = array( __( 'XLSXs' ), __( 'Manage XLSXs' ), _n_noop( 'XLSX <span class="count">(%s)</span>', 'XLSXs <span class="count">(%s)</span>' ) );
    }
    return $post_mime_types;
}

add_filter( 'post_mime_types', 'wpui_get_allowed_mime_types' );

//Profil
//=================================================================================================

//Visual Editor
function wpui_profil_visual_editor() {
	$wpui_profil_visual_editor_option = get_option("wpui_profil_option_name");
	if ( ! empty ( $wpui_profil_visual_editor_option ) ) {
		foreach ($wpui_profil_visual_editor_option as $key => $wpui_profil_visual_editor_value)
			$options[$key] = $wpui_profil_visual_editor_value;
		 if (isset($wpui_profil_visual_editor_option['wpui_profil_visual_editor'])) { 
		 	return $wpui_profil_visual_editor_option['wpui_profil_visual_editor'];
		 }
	}
};

if (wpui_profil_visual_editor() == '1') {
	add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_visual_editor' );
	add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_visual_editor' );

	function wpui_profil_remove_visual_editor( $hook ) {
	    ?>
	    <style type="text/css">
	        #your-profile .form-table .user-rich-editing-wrap { display:none!important;visibility:hidden!important; }
	    </style>
	    <?php
	} 
}

//Color Scheme
function wpui_profil_admin_color_scheme() {
	$wpui_profil_admin_color_scheme_option = get_option("wpui_profil_option_name");
	if ( ! empty ( $wpui_profil_admin_color_scheme_option ) ) {
		foreach ($wpui_profil_admin_color_scheme_option as $key => $wpui_profil_admin_color_scheme_value)
			$options[$key] = $wpui_profil_admin_color_scheme_value;
		 if (isset($wpui_profil_admin_color_scheme_option['wpui_profil_admin_color_scheme'])) { 
		 	return $wpui_profil_admin_color_scheme_option['wpui_profil_admin_color_scheme'];
		 }
	}
};

if (wpui_profil_admin_color_scheme() == '1') {
	remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
}

//Keyword shortcuts
function wpui_profil_keyword_shortcuts() {
	$wpui_profil_keyword_shortcuts_option = get_option("wpui_profil_option_name");
	if ( ! empty ( $wpui_profil_keyword_shortcuts_option ) ) {
		foreach ($wpui_profil_keyword_shortcuts_option as $key => $wpui_profil_keyword_shortcuts_value)
			$options[$key] = $wpui_profil_keyword_shortcuts_value;
		 if (isset($wpui_profil_keyword_shortcuts_option['wpui_profil_keyword_shortcuts'])) { 
		 	return $wpui_profil_keyword_shortcuts_option['wpui_profil_keyword_shortcuts'];
		 }
	}
};

if (wpui_profil_keyword_shortcuts() == '1') {
	add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_keyword_shortcuts' );
	add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_keyword_shortcuts' );

	function wpui_profil_remove_keyword_shortcuts( $hook ) {
	    ?>
	    <style type="text/css">
	        #your-profile .form-table .user-comment-shortcuts-wrap { display:none!important;visibility:hidden!important; }
	    </style>
	    <?php
	} 
}

//Toolbar
function wpui_profil_show_toolbar() {
	$wpui_profil_show_toolbar_option = get_option("wpui_profil_option_name");
	if ( ! empty ( $wpui_profil_show_toolbar_option ) ) {
		foreach ($wpui_profil_show_toolbar_option as $key => $wpui_profil_show_toolbar_value)
			$options[$key] = $wpui_profil_show_toolbar_value;
		 if (isset($wpui_profil_show_toolbar_option['wpui_profil_show_toolbar'])) { 
		 	return $wpui_profil_show_toolbar_option['wpui_profil_show_toolbar'];
		 }
	}
};

if (wpui_profil_show_toolbar() == '1') {
	add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_show_toolbar' );
	add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_show_toolbar' );

	function wpui_profil_remove_show_toolbar( $hook ) {
	    ?>
	    <style type="text/css">
	        #your-profile .form-table .show-admin-bar { display:none!important;visibility:hidden!important; }
	    </style>
	    <?php
	} 
}

if (wpui_profil_show_toolbar() == '1' && wpui_profil_keyword_shortcuts() == '1' && wpui_profil_admin_color_scheme() == '1' && wpui_profil_visual_editor() == '1') {
	add_action( 'admin_print_styles-profile.php', 'wpui_profil_remove_title' );
	add_action( 'admin_print_styles-user-edit.php', 'wpui_profil_remove_title' );

	function wpui_profil_remove_title( $hook ) {
	    ?>
	    <style type="text/css">
	        #your-profile p+h3 { display:none!important;visibility:hidden!important; }
	    </style>
	    <?php
	} 
}

//Default color scheme
function wpui_profil_default_color_scheme() {
	$wpui_profil_default_color_scheme_option = get_option("wpui_profil_option_name");
	if ( ! empty ( $wpui_profil_default_color_scheme_option ) ) {
		foreach ($wpui_profil_default_color_scheme_option as $key => $wpui_profil_default_color_scheme_value)
			$options[$key] = $wpui_profil_default_color_scheme_value;
		 if (isset($wpui_profil_default_color_scheme_option['wpui_profil_default_color_scheme'])) { 
		 	return $wpui_profil_default_color_scheme_option['wpui_profil_default_color_scheme'];
		 }
	}
};

if (wpui_profil_default_color_scheme() != 'none') {
	function wpui_set_default_color_scheme() {
		$users = get_users();
		foreach ($users as $user) {
			if (!user_can( $user->ID, 'administrator' )) {
				update_user_meta($user->ID, 'admin_color', wpui_profil_default_color_scheme());
			}
		}
	}
	add_action('after_setup_theme','wpui_set_default_color_scheme');
}

/* WPUI One */
wp_admin_css_color(
	'wpui-one',
   	__('WPUI Algua'),
   	plugins_url( 'css/color-schemes/wpui-one/colors.min.css', __FILE__ ),
   	array('#247ba0', '#70c1b3', '#ff1654', '#ffffff')
);

/* WPUI Two */
wp_admin_css_color(
	'wpui-two',
   	__('WPUI Dark'),
   	plugins_url( 'css/color-schemes/wpui-two/colors.min.css', __FILE__ ),
   	array('#011627', '#fdfffc', '#2ec4b6', '#e71d36', '#ff9f1c')
);

/* WPUI Three */
wp_admin_css_color(
	'wpui-three',
   	__('WPUI Teal'),
   	plugins_url( 'css/color-schemes/wpui-three/colors.min.css', __FILE__ ),
   	array('#114b5f', '#fdfffc', '#028090', '#f45b69')
);

/* WPUI Four */
wp_admin_css_color(
	'wpui-four',
   	__('WPUI Ice'),
   	plugins_url( 'css/color-schemes/wpui-four/colors.min.css', __FILE__ ),
   	array('#007EA7', '#00A7EB', '#00161F', '#ffffff')
);

/* WPUI Five */
wp_admin_css_color(
	'wpui-five',
   	__('WPUI Army'),
   	plugins_url( 'css/color-schemes/wpui-five/colors.min.css', __FILE__ ),
   	array('#487D58', '#FAF3D9', '#65B0AB', '#F3F3F3')
);

/* WPUI Six */
wp_admin_css_color(
	'wpui-six',
   	__('WPUI Bayonne'),
   	plugins_url( 'css/color-schemes/wpui-six/colors.min.css', __FILE__ ),
   	array('#990D35', '#D52941', '#FCD581', '#ffffff')
);

/* WPUI Seven */
wp_admin_css_color(
	'wpui-seven',
   	__('WPUI Fashion'),
   	plugins_url( 'css/color-schemes/wpui-seven/colors.min.css', __FILE__ ),
   	array('#554971', '#b8f3ff', '#36213e', '#63768d')
);

/* WPUI Eight */
wp_admin_css_color(
	'wpui-eight',
   	__('WPUI Café'),
   	plugins_url( 'css/color-schemes/wpui-eight/colors.min.css', __FILE__ ),
   	array('#181818', '#B0966C', '#FDD692', '#ffffff')
);



//Third plugins
//=================================================================================================

//WP SEO col
function wpui_plugins_wp_seo_col() {
	$wpui_plugins_wp_seo_col_option = get_option("wpui_plugins_option_name");
	if ( ! empty ( $wpui_plugins_wp_seo_col_option ) ) {
		foreach ($wpui_plugins_wp_seo_col_option as $key => $wpui_plugins_wp_seo_col_value)
			$options[$key] = $wpui_plugins_wp_seo_col_value;
		 if (isset($wpui_plugins_wp_seo_col_option['wpui_plugins_wp_seo_col'])) { 
		 	return $wpui_plugins_wp_seo_col_option['wpui_plugins_wp_seo_col'];
		 }
	}
};

if (wpui_plugins_wp_seo_col() == '1') {
	add_filter( 'wpseo_use_page_analysis', '__return_false' );
}

//WP SEO position metabox
function wpui_plugins_wp_seo_pos() {
	$wpui_plugins_wp_seo_pos_option = get_option("wpui_plugins_option_name");
	if ( ! empty ( $wpui_plugins_wp_seo_pos_option ) ) {
		foreach ($wpui_plugins_wp_seo_pos_option as $key => $wpui_plugins_wp_seo_pos_value)
			$options[$key] = $wpui_plugins_wp_seo_pos_value;
		 if (isset($wpui_plugins_wp_seo_pos_option['wpui_plugins_wp_seo_pos'])) { 
		 	return $wpui_plugins_wp_seo_pos_option['wpui_plugins_wp_seo_pos'];
		 }
	}
};

if (wpui_plugins_wp_seo_pos() == '1') {
	function wpui_yoast_bottom() {
		return 'low';
	}
	add_filter( 'wpseo_metabox_prio', 'wpui_yoast_bottom');
}

//WPML ad
function wpui_plugins_wpml() {
	$wpui_plugins_wpml_option = get_option("wpui_plugins_option_name");
	if ( ! empty ( $wpui_plugins_wpml_option ) ) {
		foreach ($wpui_plugins_wpml_option as $key => $wpui_plugins_wpml_value)
			$options[$key] = $wpui_plugins_wpml_value;
		 if (isset($wpui_plugins_wpml_option['wpui_plugins_wpml'])) { 
		 	return $wpui_plugins_wpml_option['wpui_plugins_wpml'];
		 }
	}
};

if (wpui_plugins_wpml() == '1') {
	define('ICL_DONT_PROMOTE', true);
}

//WPML admin bar
function wpui_plugins_wpml_admin_bar() {
	$wpui_plugins_wpml_admin_bar_option = get_option("wpui_plugins_option_name");
	if ( ! empty ( $wpui_plugins_wpml_admin_bar_option ) ) {
		foreach ($wpui_plugins_wpml_admin_bar_option as $key => $wpui_plugins_wpml_admin_bar_value)
			$options[$key] = $wpui_plugins_wpml_admin_bar_value;
		 if (isset($wpui_plugins_wpml_admin_bar_option['wpui_plugins_wpml_admin_bar'])) { 
		 	return $wpui_plugins_wpml_admin_bar_option['wpui_plugins_wpml_admin_bar'];
		 }
	}
};

if (wpui_plugins_wpml_admin_bar() == '1') {
	add_action( 'wp_before_admin_bar_render', 'wpui_plugins_wpml_admin_bar_fn', 999 );

	function wpui_plugins_wpml_admin_bar_fn( $wp_admin_bar ) {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'WPML_ALS' );
	
	}

}

//WPML Widget Dashboard
function wpui_plugins_wpml_dashboard_widget() {
	$wpui_plugins_wpml_dashboard_widget_option = get_option("wpui_plugins_option_name");
	if ( ! empty ( $wpui_plugins_wpml_dashboard_widget_option ) ) {
		foreach ($wpui_plugins_wpml_dashboard_widget_option as $key => $wpui_plugins_wpml_dashboard_widget_value)
			$options[$key] = $wpui_plugins_wpml_dashboard_widget_value;
		 if (isset($wpui_plugins_wpml_dashboard_widget_option['wpui_plugins_wpml_dashboard_widget'])) { 
		 	return $wpui_plugins_wpml_dashboard_widget_option['wpui_plugins_wpml_dashboard_widget'];
		 }
	}
};

function wpui_plugins_wpml_dashboard_widget_fn() {
	global $wp_meta_boxes;
	if (wpui_plugins_wpml_dashboard_widget() == '1') {
		unset($wp_meta_boxes['dashboard']['normal']['core']['icl_dashboard_widget']); //Recent Comments
	}
}
add_action('wp_dashboard_setup', 'wpui_plugins_wpml_dashboard_widget_fn' );

//WooThemes Updater
function wpui_plugins_woo_updater() {
	$wpui_plugins_woo_updater_option = get_option("wpui_plugins_option_name");
	if ( ! empty ( $wpui_plugins_woo_updater_option ) ) {
		foreach ($wpui_plugins_woo_updater_option as $key => $wpui_plugins_woo_updater_value)
			$options[$key] = $wpui_plugins_woo_updater_value;
		 if (isset($wpui_plugins_woo_updater_option['wpui_plugins_woo_updater'])) { 
		 	return $wpui_plugins_woo_updater_option['wpui_plugins_woo_updater'];
		 }
	}
};

if (wpui_plugins_woo_updater() == '1') {
	remove_action('admin_notices', 'woothemes_updater_notice');
}
				}
			}	
		}
	}
}