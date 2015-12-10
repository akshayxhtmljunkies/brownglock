<?php

defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

class wpui_options
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	
    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
	
	public function activate() {
        update_option($this->wpui_options, $this->data);
    }

    public function deactivate() {
        delete_option($this->wpui_options);
    }
	
    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_menu_page('WP UI Option Page', 'WPUI', 'manage_options', 'wpui-option', array( $this, 'create_admin_page' ), 'dashicons-art', 76);
        add_submenu_page('wpui-option', 'Login', 'Login', 'manage_options', 'wpui-login', array( $this, 'wpui_login_page' ));
        add_submenu_page('wpui-option', 'Global', 'Global', 'manage_options', 'wpui-global', array( $this, 'wpui_global_page'));
        add_submenu_page('wpui-option', 'Dashboard', 'Dashboard', 'manage_options', 'wpui-dashboard', array( $this,'wpui_dashboard_page'));
        add_submenu_page('wpui-option', 'Admin Menu', 'Admin Menu', 'manage_options', 'wpui-admin-menu', array( $this,'wpui_admin_menu_page'));
        add_submenu_page('wpui-option', 'Admin Bar', 'Admin Bar', 'manage_options', 'wpui-admin-bar', array( $this,'wpui_admin_bar_page'));
        add_submenu_page('wpui-option', 'Editor', 'Editor', 'manage_options', 'wpui-editor', array( $this,'wpui_editor_page'));
        add_submenu_page('wpui-option', 'Metaboxes', 'Metaboxes', 'manage_options', 'wpui-metaboxes', array( $this,'wpui_metaboxes_page'));
        add_submenu_page('wpui-option', 'Columns', 'Columns', 'manage_options', 'wpui-columns', array( $this,'wpui_columns_page'));
        add_submenu_page('wpui-option', 'Media Library', 'Media Library', 'manage_options', 'wpui-library', array( $this,'wpui_library_page'));
        add_submenu_page('wpui-option', 'Profil', 'Profil', 'manage_options', 'wpui-profil', array( $this,'wpui_profil_page'));
        add_submenu_page('wpui-option', 'Plugins', 'Plugins', 'manage_options', 'wpui-plugins', array( $this,'wpui_plugins_page'));
        add_submenu_page('wpui-option', 'Roles', 'Role Manager', 'manage_options', 'wpui-roles', array( $this,'wpui_roles_page'));
        add_submenu_page('wpui-option', 'Import / Export settings', 'Import / Export', 'manage_options', 'wpui-import-export', array( $this,'wpui_import_export_page'));
    }

    function wpui_login_page(){
        $this->options = get_option( 'wpui_login_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_login_option_group' );
        do_settings_sections( 'wpui-settings-admin-login' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_global_page(){
        $this->options = get_option( 'wpui_global_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_global_option_group' );
        do_settings_sections( 'wpui-settings-admin-global' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_dashboard_page(){
        $this->options = get_option( 'wpui_dashboard_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_dashboard_option_group' );
        do_settings_sections( 'wpui-settings-admin-dashboard' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_admin_menu_page(){
        $this->options = get_option( 'wpui_admin_menu_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_admin_menu_option_group' );
        do_settings_sections( 'wpui-settings-admin-menu' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_admin_bar_page(){
        $this->options = get_option( 'wpui_admin_bar_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_admin_bar_option_group' );
        do_settings_sections( 'wpui-settings-admin-bar' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_editor_page(){
        $this->options = get_option( 'wpui_editor_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_editor_option_group' );
        do_settings_sections( 'wpui-settings-admin-editor' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_metaboxes_page(){
        $this->options = get_option( 'wpui_metaboxes_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_metaboxes_option_group' ); ?>

        <div id="wpui-tabs">
                <h2 class="nav-tab-wrapper hide-if-no-js">
                    <ul>
                        <li><a href="#tab_wpui_metaboxes_posts" class="nav-tab"><?php _e( 'Posts metaboxes', 'wpui' ); ?></a></li>
                        <li><a href="#tab_wpui_metaboxes_pages" class="nav-tab"><?php _e( 'Pages metaboxes', 'wpui' ); ?></a></li>
                    </ul>
                </h2>
               
                <div id="wpui-tabs-settings">
                    <div class="wpui-tab" id="tab_wpui_metaboxes_posts"><?php do_settings_sections( 'wpui-settings-admin-metaboxes-posts' ); ?></div>
                    <div class="wpui-tab" id="tab_wpui_metaboxes_pages"><?php do_settings_sections( 'wpui-settings-admin-metaboxes-pages' ); ?></div>
                </div>
            </div>

        <?php submit_button(); ?>
        </form>
        <?php
    }
    function wpui_columns_page(){
        $this->options = get_option( 'wpui_columns_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
            <?php settings_fields( 'wpui_columns_option_group'); ?>

            <div id="wpui-tabs">
                <h2 class="nav-tab-wrapper hide-if-no-js">
                    <ul>
                        <li><a href="#tab_wpui_col_post" class="nav-tab"><?php _e( 'Posts columns', 'wpui' ); ?></a></li>
                        <li><a href="#tab_wpui_col_page" class="nav-tab"><?php _e( 'Pages columns', 'wpui' ); ?></a></li>
                        <li><a href="#tab_wpui_col_media" class="nav-tab"><?php _e( 'Media columns', 'wpui' ); ?></a></li>
                    </ul>
                </h2>
               
                <div id="wpui-tabs-settings">
                    <div class="wpui-tab" id="tab_wpui_col_post"><?php do_settings_sections( 'wpui-settings-admin-column-post' ); ?></div>
                    <div class="wpui-tab" id="tab_wpui_col_page"><?php do_settings_sections( 'wpui-settings-admin-column-page' ); ?></div>
                    <div class="wpui-tab" id="tab_wpui_col_media"><?php do_settings_sections( 'wpui-settings-admin-column-media' ); ?></div>
                </div>
            </div>
            <?php submit_button(); ?>
        </form>
        <?php
    }
    function wpui_library_page(){
        $this->options = get_option( 'wpui_library_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_library_option_group' );
        do_settings_sections( 'wpui-settings-admin-library' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_profil_page(){
        $this->options = get_option( 'wpui_profil_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_profil_option_group' );
        do_settings_sections( 'wpui-settings-admin-profil' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_plugins_page(){
        $this->options = get_option( 'wpui_plugins_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_plugins_option_group' );
        do_settings_sections( 'wpui-settings-admin-plugins' );
        submit_button(); ?>
        </form>
        <?php
    }
    function wpui_roles_page(){
        $this->options = get_option( 'wpui_roles_option_name' );
        ?>
        <form method="post" action="options.php" class="wpui-option">
        <?php settings_fields( 'wpui_roles_option_group' );
        do_settings_sections( 'wpui-settings-admin-roles' );
        submit_button(); ?>
        </form>
        <?php
    }    
    function wpui_import_export_page(){
        $this->options = get_option( 'wpui_import_export_option_name' );
        ?>
        <div class="metabox-holder">
            <div class="postbox">
                <h3><span><?php _e( 'Export Settings' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.' ); ?></p>
                    <form method="post">
                        <p><input type="hidden" name="wpui_action" value="export_settings" /></p>
                        <p>
                            <?php wp_nonce_field( 'wpui_export_nonce', 'wpui_export_nonce' ); ?>
                            <?php submit_button( __( 'Export' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->

            <div class="postbox">
                <h3><span><?php _e( 'Import Settings' ); ?></span></h3>
                <div class="inside">
                    <p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.' ); ?></p>
                    <form method="post" enctype="multipart/form-data">
                        <p>
                            <input type="file" name="import_file"/>
                        </p>
                        <p>
                            <input type="hidden" name="wpui_action" value="import_settings" />
                            <?php wp_nonce_field( 'wpui_import_nonce', 'wpui_import_nonce' ); ?>
                            <?php submit_button( __( 'Import' ), 'secondary', 'submit', false ); ?>
                        </p>
                    </form>
                </div><!-- .inside -->
            </div><!-- .postbox -->
        </div><!-- .metabox-holder -->
    <?php
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
	
        // Set class property
        $this->options = get_option( 'wpui_option_name' );
        ?>      
        <?php $wpui_info_version = get_plugin_data( plugin_dir_path( __FILE__ ).'/wpadminui.php'); ?>
        
            <div id="wpui-header">
				<div id="wpui-admin">
					<h3>
						<?php _e( 'WP Admin UI', 'wpui' ); ?>
					</h3>
					<span class="wpui-info-version"><?php print_r($wpui_info_version['Version']); ?></span>
					<div id="wpui-notice">
						<p><?php _e( 'The ultimate plugin to customize WordPress admin! More to come...', 'wpui' ); ?></p>
						<p class="small">
							<a href="http://twitter.com/wpcloudy" target="_blank">
								<div class="dashicons dashicons-twitter"></div>
								<?php _e( 'Follow us on Twitter!', 'wpui' ); ?>
							</a>
						</p>
					</div>
				</div>
			</div>
            <div class="wpui-sidebar">	

            </div>
        <?php
    }



    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'wpui_option_group', // Option group
            'wpui_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_login_option_group', // Option group
            'wpui_login_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_global_option_group', // Option group
            'wpui_global_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_dashboard_option_group', // Option group
            'wpui_dashboard_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_admin_menu_option_group', // Option group
            'wpui_admin_menu_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_admin_bar_option_group', // Option group
            'wpui_admin_bar_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_editor_option_group', // Option group
            'wpui_editor_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_metaboxes_option_group', // Option group
            'wpui_metaboxes_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_columns_option_group', // Option group
            'wpui_columns_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_library_option_group', // Option group
            'wpui_library_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_profil_option_group', // Option group
            'wpui_profil_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_plugins_option_group', // Option group
            'wpui_plugins_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        register_setting(
            'wpui_roles_option_group', // Option group
            'wpui_roles_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );        

        register_setting(
            'wpui_import_export_option_group', // Option group
            'wpui_import_export_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

		//LOGIN SECTION============================================================================
		add_settings_section( 
            'wpui_setting_section_login', // ID
            __("Login","wpui"), // Title
            array( $this, 'print_section_info_login' ), // Callback
            'wpui-settings-admin-login' // Page 
        ); 	

        add_settings_field(
            'wpui_login_custom_css', // ID
           __("Custom login CSS?","wpui"), // Title
            array( $this, 'wpui_login_custom_css_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section                  
        );

        add_settings_field(
            'wpui_login_logo_url', // ID
           __("Custom logo url?","wpui"), // Title
            array( $this, 'wpui_login_logo_url_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_logo', // ID
           __("Custom logo?","wpui"), // Title
            array( $this, 'wpui_login_logo_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_custom_logo_title', // ID
           __("Custom logo title?","wpui"), // Title
            array( $this, 'wpui_login_custom_logo_title_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_custom_bg_img', // ID
           __("Custom background image?","wpui"), // Title
            array( $this, 'wpui_login_custom_bg_img_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_always_checked', // ID
           __("Always checked remember me?","wpui"), // Title
            array( $this, 'wpui_login_always_checked_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        add_settings_field(
            'wpui_login_error_message', // ID
           __("Remove error message for security?","wpui"), // Title
            array( $this, 'wpui_login_error_message_callback' ), // Callback
            'wpui-settings-admin-login', // Page
            'wpui_setting_section_login' // Section           
        );

        //GLOBAL SECTION===============================================================================
        add_settings_section( 
            'wpui_setting_section_global', // ID
            __("Global","wpui"), // Title
            array( $this, 'print_section_info_global' ), // Callback
            'wpui-settings-admin-global' // Page
        );  

        add_settings_field(
            'wpui_global_custom_css', // ID
           __("Custom admin CSS?","wpui"), // Title
            array( $this, 'wpui_global_custom_css_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_version_footer', // ID
           __("Remove WordPress version in footer?","wpui"), // Title
            array( $this, 'wpui_global_version_footer_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_custom_version_footer', // ID
           __("Custom WordPress version in footer?","wpui"), // Title
            array( $this, 'wpui_global_custom_version_footer_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_credits_footer', // ID
           __("Remove WordPress credits in footer?","wpui"), // Title
            array( $this, 'wpui_global_credits_footer_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_custom_credits_footer', // ID
           __("Custom WordPress credits in footer?","wpui"), // Title
            array( $this, 'wpui_global_custom_credits_footer_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_custom_favicon', // ID
           __("Custom favicon in admin?","wpui"), // Title
            array( $this, 'wpui_global_custom_favicon_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_help_tab', // ID
           __("Remove help tab?","wpui"), // Title
            array( $this, 'wpui_global_help_tab_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_screen_options_tab', // ID
           __("Remove screen options tab?","wpui"), // Title
            array( $this, 'wpui_global_screen_options_tab_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_update_notification', // ID
           __("Disable WordPress updates notifications?","wpui"), // Title
            array( $this, 'wpui_global_update_notification_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );        

        add_settings_field(
            'wpui_global_password_notification', // ID
           __("Hide autogenerated password message?","wpui"), // Title
            array( $this, 'wpui_global_password_notification_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_trash', // ID
           __("Remove trash in posts, pages, custom post type...?","wpui"), // Title
            array( $this, 'wpui_global_trash_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_empty_trash', // ID
           __("Empty trash automatically after x days?","wpui"), // Title
            array( $this, 'wpui_global_empty_trash_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_autosave_interval', // ID
           __("Change default autosave interval? (in seconds, 0 to disable)","wpui"), // Title
            array( $this, 'wpui_global_autosave_interval_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        add_settings_field(
            'wpui_global_limit_posts_revisions', // ID
           __("Limit posts revisions? (0 to disable)","wpui"), // Title
            array( $this, 'wpui_global_limit_posts_revisions_callback' ), // Callback
            'wpui-settings-admin-global', // Page
            'wpui_setting_section_global' // Section           
        );

        //DASHBOARD SECTION============================================================================
        add_settings_section( 
            'wpui_setting_section_dashboard', // ID
            __("Dashboard","wpui"), // Title
            array( $this, 'print_section_info_dashboard' ), // Callback
            'wpui-settings-admin-dashboard' // Page
        );  

        add_settings_field(
            'wpui_dashboard_welcome_panel', // ID
           __("Remove Welcome widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_welcome_panel_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_quick_press', // ID
           __("Remove Quick Press widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_quick_press_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_activity', // ID
           __("Remove Activity widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_activity_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_incoming_links', // ID
           __("Remove Incoming Links widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_incoming_links_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_right_now', // ID
           __("Remove Right Now widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_right_now_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_plugins', // ID
           __("Remove Plugins widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_plugins_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_recent_drafts', // ID
           __("Remove Recent Drafts widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_recent_drafts_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_recent_comments', // ID
           __("Remove Recent Comments widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_recent_comments_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_primary', // ID
           __("Remove Primary widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_primary_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        add_settings_field(
            'wpui_dashboard_secondary', // ID
           __("Remove Secondary widget?","wpui"), // Title
            array( $this, 'wpui_dashboard_secondary_callback' ), // Callback
            'wpui-settings-admin-dashboard', // Page
            'wpui_setting_section_dashboard' // Section           
        );

        //ADMIN MENU SECTION===========================================================================
        add_settings_section( 
            'wpui_setting_section_admin_menu', // ID
            __("Admin menu","wpui"), // Title
            array( $this, 'print_section_info_admin_menu' ), // Callback
            'wpui-settings-admin-menu' // Page
        ); 

        add_settings_field(
            'wpui_admin_menu', // ID
           __("Menu Structure","wpui"), // Title
            array( $this, 'wpui_admin_menu_callback' ), // Callback
            'wpui-settings-admin-menu', // Page
            'wpui_setting_section_admin_menu' // Section           
        );

        //ADMIN BAR SECTION============================================================================
        add_settings_section( 
            'wpui_setting_section_admin_bar', // ID
            __("Admin bar","wpui"), // Title
            array( $this, 'print_section_info_admin_bar' ), // Callback
            'wpui-settings-admin-bar' // Page
        );  

        add_settings_field(
            'wpui_admin_bar_wp_logo', // ID
           __("Remove WordPress logo in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_wp_logo_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_site_name', // ID
           __("Remove Site Name in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_site_name_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_my_account', // ID
           __("Remove My Account in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_my_account_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_menu_toggle', // ID
           __("Remove Menu Toggle in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_menu_toggle_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_edit', // ID
           __("Remove Edit in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_edit_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_view', // ID
           __("Remove View in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_view_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_preview', // ID
           __("Remove Preview in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_preview_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_comments', // ID
           __("Remove Comments in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_comments_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_new_content', // ID
           __("Remove New Content in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_new_content_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_view_site', // ID
           __("Remove View Site in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_view_site_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        add_settings_field(
            'wpui_admin_bar_updates', // ID
           __("Remove Updates in admin bar?","wpui"), // Title
            array( $this, 'wpui_admin_bar_updates_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );      

        add_settings_field(
            'wpui_admin_bar_disable', // ID
           __("Disable admin bar in front-end?","wpui"), // Title
            array( $this, 'wpui_admin_bar_disable_callback' ), // Callback
            'wpui-settings-admin-bar', // Page
            'wpui_setting_section_admin_bar' // Section           
        );

        //EDITOR SECTION===============================================================================
        add_settings_section( 
            'wpui_setting_section_editor', // ID
            __("Editor","wpui"), // Title
            array( $this, 'print_section_info_editor' ), // Callback
            'wpui-settings-admin-editor' // Page
        );  

        add_settings_field(
            'wpui_admin_editor_full_tinymce', // ID
           __("Enable Full TinyMCE by default?","wpui"), // Title
            array( $this, 'wpui_admin_editor_full_tinymce_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );        

        add_settings_field(
            'wpui_admin_editor_font_size', // ID
           __("Add Font Size select?","wpui"), // Title
            array( $this, 'wpui_admin_editor_font_size_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_font_family', // ID
           __("Add Font Family select?","wpui"), // Title
            array( $this, 'wpui_admin_editor_font_family_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_custom_fonts', // ID
           __("Add custom Fonts select?","wpui"), // Title
            array( $this, 'wpui_admin_editor_custom_fonts_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_formats_select', // ID
           __("Add Formats select (styles)?","wpui"), // Title
            array( $this, 'wpui_admin_editor_formats_select_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );        

        add_settings_field(
            'wpui_admin_editor_get_shortlink', // ID
           __("Remove Get Shortlink button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_get_shortlink_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );    

        add_settings_field(
            'wpui_admin_editor_get_shortlink', // ID
           __("Remove Get Shortlink button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_get_shortlink_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_btn_newdocument', // ID
           __("Add New Document button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_btn_newdocument_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        ); 

        add_settings_field(
            'wpui_admin_editor_btn_cut', // ID
           __("Add Cut button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_btn_cut_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_btn_copy', // ID
           __("Add Copy button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_btn_copy_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_btn_paste', // ID
           __("Add Paste button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_btn_paste_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_btn_backcolor', // ID
           __("Add Backcolor button?","wpui"), // Title
            array( $this, 'wpui_admin_editor_btn_backcolor_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_media_insert', // ID
           __("Remove Insert Media in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_insert_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_upload', // ID
           __("Remove Upload Files in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_upload_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_library', // ID
           __("Remove Media Library in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_library_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_gallery', // ID
           __("Remove Create Gallery in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_gallery_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_playlist', // ID
           __("Remove Create Playlist in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_playlist_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        add_settings_field(
            'wpui_admin_editor_media_featured_img', // ID
           __("Remove Set Featured Image in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_featured_img_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );
        
        add_settings_field(
            'wpui_admin_editor_media_insert_url', // ID
           __("Remove Insert From URL in Media Modal?","wpui"), // Title
            array( $this, 'wpui_admin_editor_media_insert_url_callback' ), // Callback
            'wpui-settings-admin-editor', // Page
            'wpui_setting_section_editor' // Section           
        );

        //METABOXES SECTION============================================================================
        add_settings_section( 
            'wpui_setting_section_metaboxes_posts', // ID
            __("Metaboxes","wpui"), // Title
            array( $this, 'print_section_info_metaboxes' ), // Callback
            'wpui-settings-admin-metaboxes-posts' // Page
        ); 

        add_settings_section( 
            'wpui_setting_section_metaboxes_pages', // ID
            __("Metaboxes","wpui"), // Title
            array( $this, 'print_section_info_metaboxes' ), // Callback
            'wpui-settings-admin-metaboxes-pages' // Page
        );

        add_settings_field(
            'wpui_metaboxe_author_posts', // ID
           __("Remove Author metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_author_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section            
        );  

        add_settings_field(
            'wpui_metaboxe_categories_posts', // ID
           __("Remove Categories metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_categories_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section              
        );  

        add_settings_field(
            'wpui_metaboxe_comments_status_posts', // ID
           __("Remove Comments Status metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_comments_status_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section             
        ); 

        add_settings_field(
            'wpui_metaboxe_comments_posts', // ID
           __("Remove Comments metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_comments_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section              
        ); 

        add_settings_field(
            'wpui_metaboxe_formats_posts', // ID
           __("Remove Formats metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_formats_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section              
        );

        add_settings_field(
            'wpui_metaboxe_attributes_posts', // ID
           __("Remove Attributes metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_attributes_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section              
        ); 

        add_settings_field(
            'wpui_metaboxe_custom_fields_posts', // ID
           __("Remove custom fields metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_custom_fields_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section             
        ); 

        add_settings_field(
            'wpui_metaboxe_excerpt_posts', // ID
           __("Remove Excerpt metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_excerpt_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section               
        );

        add_settings_field(
            'wpui_metaboxe_featured_image_posts', // ID
           __("Remove Featured Image metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_featured_image_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section            
        ); 

        add_settings_field(
            'wpui_metaboxe_revisions_posts', // ID
           __("Remove Revisions metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_revisions_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section               
        );

        add_settings_field(
            'wpui_metaboxe_slug_posts', // ID
           __("Remove Slug metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_slug_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section             
        );

        add_settings_field(
            'wpui_metaboxe_submit_posts', // ID
           __("Remove Submit metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_submit_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section             
        );

        add_settings_field(
            'wpui_metaboxe_tags_posts', // ID
           __("Remove Tags metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_tags_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section               
        );

        add_settings_field(
            'wpui_metaboxe_trackbacks_posts', // ID
           __("Remove Trackbacks metabox in posts?","wpui"), // Title
            array( $this, 'wpui_metaboxe_trackbacks_posts_callback' ), // Callback
            'wpui-settings-admin-metaboxes-posts', // Page
            'wpui_setting_section_metaboxes_posts' // Section            
        );

        add_settings_field(
            'wpui_metaboxe_author_pages', // ID
           __("Remove Author metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_author_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section          
        );  

        add_settings_field(
            'wpui_metaboxe_comments_status_pages', // ID
           __("Remove Comments Status metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_comments_status_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section          
        ); 

        add_settings_field(
            'wpui_metaboxe_comments_pages', // ID
           __("Remove Comments metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_comments_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        ); 

        add_settings_field(
            'wpui_metaboxe_attributes_pages', // ID
           __("Remove Attributes metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_attributes_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        ); 

        add_settings_field(
            'wpui_metaboxe_custom_fields_pages', // ID
           __("Remove custom fields metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_custom_fields_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        ); 

        add_settings_field(
            'wpui_metaboxe_featured_image_pages', // ID
           __("Remove Featured Image metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_featured_image_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        ); 

        add_settings_field(
            'wpui_metaboxe_revisions_pages', // ID
           __("Remove Revisions metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_revisions_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        );

        add_settings_field(
            'wpui_metaboxe_slug_pages', // ID
           __("Remove Slug metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_slug_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        );

        add_settings_field(
            'wpui_metaboxe_submit_pages', // ID
           __("Remove Submit metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_submit_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        );

        add_settings_field(
            'wpui_metaboxe_trackbacks_pages', // ID
           __("Remove Trackbacks metabox in pages?","wpui"), // Title
            array( $this, 'wpui_metaboxe_trackbacks_pages_callback' ), // Callback
            'wpui-settings-admin-metaboxes-pages', // Page
            'wpui_setting_section_metaboxes_pages' // Section            
        );

        //Profil SECTION==================================================================================
        add_settings_section( 
            'wpui_setting_section_profil', // ID
            __("Profil","wpui"), // Title
            array( $this, 'print_section_info_profil' ), // Callback
            'wpui-settings-admin-profil' // Page
        );  

        add_settings_field(
            'wpui_profil_visual_editor', // ID
           __("Remove Disable the visual editor when writing?","wpui"), // Title
            array( $this, 'wpui_profil_visual_editor_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_admin_color_scheme', // ID
           __("Remove Admin Color Scheme?","wpui"), // Title
            array( $this, 'wpui_profil_admin_color_scheme_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_default_color_scheme', // ID
           __("Set a default admin color scheme?","wpui"), // Title
            array( $this, 'wpui_profil_default_color_scheme_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_keyword_shortcuts', // ID
           __("Remove Enable Keyword Shortcuts for comment moderation?","wpui"), // Title
            array( $this, 'wpui_profil_keyword_shortcuts_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );

        add_settings_field(
            'wpui_profil_show_toolbar', // ID
           __("Remove Show Toolbar when viewing site?","wpui"), // Title
            array( $this, 'wpui_profil_show_toolbar_callback' ), // Callback
            'wpui-settings-admin-profil', // Page
            'wpui_setting_section_profil' // Section            
        );  

        //Columns SECTION=================================================================================
        add_settings_section( 
            'wpui_setting_section_column_post', // ID
            __("Columns","wpui"), // Title
            array( $this, 'print_section_info_column' ), // Callback
            'wpui-settings-admin-column-post' // Page
        );       

        add_settings_section( 
            'wpui_setting_section_column_page', // ID
            __("Columns","wpui"), // Title
            array( $this, 'print_section_info_column' ), // Callback
            'wpui-settings-admin-column-page' // Page
        );         

        add_settings_section( 
            'wpui_setting_section_column_media', // ID
            __("Columns","wpui"), // Title
            array( $this, 'print_section_info_column' ), // Callback
            'wpui-settings-admin-column-media' // Page
        );  

        add_settings_field(
            'wpui_col_cb_posts', // ID
           __("Remove checkboxes column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_cb_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section            
        );

        add_settings_field(
            'wpui_col_title_posts', // ID
           __("Remove title column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_title_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section             
        );

        add_settings_field(
            'wpui_col_author_posts', // ID
           __("Remove author column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_author_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section            
        );

        add_settings_field(
            'wpui_col_categories_posts', // ID
           __("Remove categories column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_categories_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section           
        );

        add_settings_field(
            'wpui_col_tags_posts', // ID
           __("Remove tags column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_tags_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section           
        );

        add_settings_field(
            'wpui_col_comments_posts', // ID
           __("Remove comments column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_comments_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section            
        );

        add_settings_field(
            'wpui_col_date_posts', // ID
           __("Remove date column in posts list view?","wpui"), // Title
            array( $this, 'wpui_col_date_posts_callback' ), // Callback
            'wpui-settings-admin-column-post', // Page
            'wpui_setting_section_column_post' // Section             
        );

        add_settings_field(
            'wpui_col_cb_pages', // ID
           __("Remove checkboxes column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_cb_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section            
        );

        add_settings_field(
            'wpui_col_title_pages', // ID
           __("Remove title column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_title_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section             
        );

        add_settings_field(
            'wpui_col_author_pages', // ID
           __("Remove author column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_author_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section            
        );

        add_settings_field(
            'wpui_col_categories_pages', // ID
           __("Remove categories column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_categories_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section           
        );

        add_settings_field(
            'wpui_col_tags_pages', // ID
           __("Remove tags column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_tags_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section            
        );

        add_settings_field(
            'wpui_col_comments_pages', // ID
           __("Remove comments column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_comments_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section            
        );

        add_settings_field(
            'wpui_col_date_pages', // ID
           __("Remove date column in pages list view?","wpui"), // Title
            array( $this, 'wpui_col_date_pages_callback' ), // Callback
            'wpui-settings-admin-column-page', // Page
            'wpui_setting_section_column_page' // Section            
        );

        add_settings_field(
            'wpui_col_cb_media', // ID
           __("Remove checkboxes column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_cb_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        add_settings_field(
            'wpui_col_icon_media', // ID
           __("Remove icon column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_icon_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        add_settings_field(
            'wpui_col_title_media', // ID
           __("Remove title column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_title_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        add_settings_field(
            'wpui_col_author_media', // ID
           __("Remove author column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_author_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        add_settings_field(
            'wpui_col_parent_media', // ID
           __("Remove parent column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_parent_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        add_settings_field(
            'wpui_col_comments_media', // ID
           __("Remove comments column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_comments_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        add_settings_field(
            'wpui_col_date_media', // ID
           __("Remove date column in media list view?","wpui"), // Title
            array( $this, 'wpui_col_date_media_callback' ), // Callback
            'wpui-settings-admin-column-media', // Page
            'wpui_setting_section_column_media' // Section            
        );

        //Media Library SECTION===========================================================================
        add_settings_section( 
            'wpui_setting_section_library', // ID
            __("Media Library","wpui"), // Title
            array( $this, 'print_section_info_library' ), // Callback
            'wpui-settings-admin-library' // Page
        );  

        add_settings_field(
            'wpui_library_filters_pdf', // ID
           __("Add PDF filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_pdf_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_zip', // ID
           __("Add ZIP filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_zip_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_rar', // ID
           __("Add RAR filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_rar_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_7z', // ID
           __("Add 7Z filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_7z_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_tar', // ID
           __("Add TAR filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_tar_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_swf', // ID
           __("Add SWF filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_swf_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_doc', // ID
           __("Add DOC filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_doc_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_docx', // ID
           __("Add DOCX filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_docx_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_ppt', // ID
           __("Add PPT filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_ppt_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_pptx', // ID
           __("Add PPTX filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_pptx_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_xls', // ID
           __("Add XLS filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_xls_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        add_settings_field(
            'wpui_library_filters_xlsx', // ID
           __("Add XLSX filtering to media library?","wpui"), // Title
            array( $this, 'wpui_library_filters_xlsx_callback' ), // Callback
            'wpui-settings-admin-library', // Page
            'wpui_setting_section_library' // Section            
        );

        //Plugins SECTION=================================================================================
        add_settings_section( 
            'wpui_setting_section_plugins', // ID
            __("Plugins","wpui"), // Title
            array( $this, 'print_section_info_plugins' ), // Callback
            'wpui-settings-admin-plugins' // Page
        );  

        add_settings_field(
            'wpui_plugins_wp_seo_col', // ID
           __("Remove WP SEO columns in list view?","wpui"), // Title
            array( $this, 'wpui_plugins_wp_seo_col_callback' ), // Callback
            'wpui-settings-admin-plugins', // Page
            'wpui_setting_section_plugins' // Section            
        );

        add_settings_field(
            'wpui_plugins_wp_seo_pos', // ID
           __("Remove WP SEO columns in list view?","wpui"), // Title
            array( $this, 'wpui_plugins_wp_seo_pos_callback' ), // Callback
            'wpui-settings-admin-plugins', // Page
            'wpui_setting_section_plugins' // Section            
        );

        add_settings_field(
            'wpui_plugins_wpml', // ID
           __("Remove WPML advert in publish metabox?","wpui"), // Title
            array( $this, 'wpui_plugins_wpml_callback' ), // Callback
            'wpui-settings-admin-plugins', // Page
            'wpui_setting_section_plugins' // Section            
        );

        add_settings_field(
            'wpui_plugins_wpml_admin_bar', // ID
           __("Remove WPML in admin bar?","wpui"), // Title
            array( $this, 'wpui_plugins_wpml_admin_bar_callback' ), // Callback
            'wpui-settings-admin-plugins', // Page
            'wpui_setting_section_plugins' // Section           
        );        

        add_settings_field(
            'wpui_plugins_wpml_dashboard_widget', // ID
           __("Remove WPML in dashboard widget?","wpui"), // Title
            array( $this, 'wpui_plugins_wpml_dashboard_widget_callback' ), // Callback
            'wpui-settings-admin-plugins', // Page
            'wpui_setting_section_plugins' // Section           
        );         

        add_settings_field(
            'wpui_plugins_woo_updater', // ID
           __("Remove Install the WooThemes Updater plugin?","wpui"), // Title
            array( $this, 'wpui_plugins_woo_updater_callback' ), // Callback
            'wpui-settings-admin-plugins', // Page
            'wpui_setting_section_plugins' // Section           
        );   

        //Roles SECTION============================================================================================
        add_settings_section( 
            'wpui_setting_section_roles', // ID
            __("Roles","wpui"), // Title
            array( $this, 'print_section_info_roles' ), // Callback
            'wpui-settings-admin-roles' // Page
        );  

        add_settings_field(
            'wpui_roles_list_role', // ID
           __("Apply settings to specific roles:","wpui"), // Title
            array( $this, 'wpui_roles_list_role_callback' ), // Callback
            'wpui-settings-admin-roles', // Page
            'wpui_setting_section_roles' // Section            
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {	
        if( !empty( $input['wpui_login_custom_css'] ) )
        $input['wpui_login_custom_css'] = sanitize_text_field( $input['wpui_login_custom_css'] );

        if( !empty( $input['wpui_login_logo'] ) )
        $input['wpui_login_logo'] = sanitize_text_field( $input['wpui_login_logo'] );

        if( !empty( $input['wpui_login_logo_url'] ) )
        $input['wpui_login_logo_url'] = sanitize_text_field( $input['wpui_login_logo_url'] );

        if( !empty( $input['wpui_login_custom_logo_title'] ) )
        $input['wpui_login_custom_logo_title'] = sanitize_text_field( $input['wpui_login_custom_logo_title'] );

        if( !empty( $input['wpui_login_custom_bg_img'] ) )
        $input['wpui_login_custom_bg_img'] = sanitize_text_field( $input['wpui_login_custom_bg_img'] );

        if( !empty( $input['wpui_global_custom_css'] ) )
            $input['wpui_global_custom_css'] = sanitize_text_field( $input['wpui_global_custom_css'] );

        if( !empty( $input['wpui_global_empty_trash'] ) )
            $input['wpui_global_empty_trash'] = sanitize_text_field( $input['wpui_global_empty_trash'] );

        if( !empty( $input['wpui_global_autosave_interval'] ) )
            $input['wpui_global_autosave_interval'] = sanitize_text_field( $input['wpui_global_autosave_interval'] );

        if( !empty( $input['wpui_global_limit_posts_revisions'] ) )
            $input['wpui_global_limit_posts_revisions'] = sanitize_text_field( $input['wpui_global_limit_posts_revisions'] );
		
        return $input;
    }

    /** 
     * Print the Section text
     */
	 
	public function print_section_info_login()
    {
        print __('Login', 'wpui');
    }

    public function print_section_info_global()
    {
        print __('Global', 'wpui');
    }

    public function print_section_info_dashboard()
    {
        print __('Dashboard', 'wpui');
    }

    public function print_section_info_admin_menu()
    {
        print __('Drag each item into the order you prefer.<br />Click the arrow on the right of the item to reveal submenus.<br />Check an item to <strong>HIDE</strong> in WP admin.', 'wpui');
    }

    public function print_section_info_admin_bar()
    {
        print __('Admin bar', 'wpui');
    }

    public function print_section_info_editor()
    {
        print __('Editor', 'wpui');
    }

    public function print_section_info_metaboxes()
    {
        print __('Metaboxes', 'wpui');
    }

    public function print_section_info_profil()
    {
        print __('Profil', 'wpui');
    }

    public function print_section_info_column()
    {
        print __('Columns', 'wpui');
    }

    public function print_section_info_library()
    {
        print __('Media Library', 'wpui');
    }

    public function print_section_info_plugins()
    {
        print __('Third party plugins', 'wpui');
    }

    public function print_section_info_roles()
    {
        print __('Role manager', 'wpui');
    }

    public function print_section_info_import_export()
    {
        print __('Import / Export settings', 'wpui');
    }

    /** 
     * Get the settings option array and print one of its values
     */
	
    //Login
    public function wpui_login_custom_css_callback()
    {
        printf(
        '<textarea name="wpui_login_option_name[wpui_login_custom_css]">%s</textarea>',
        esc_html( $this->options['wpui_login_custom_css'])
        
        );
        
    } 

    public function wpui_login_logo_url_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_logo_url]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_login_logo_url'])
        
        );
        
    } 

    public function wpui_login_logo_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_logo]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_login_logo'])
        
        );
        
    } 

    public function wpui_login_custom_logo_title_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_custom_logo_title]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_login_custom_logo_title'])
        
        );
        
    } 

    public function wpui_login_custom_bg_img_callback()
    {
        printf(
        '<input name="wpui_login_option_name[wpui_login_custom_bg_img]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_login_custom_bg_img'])
        
        );
        
    } 

    public function wpui_login_always_checked_callback()
    {
        $options = get_option( 'wpui_login_option_name' );  
        
        $check = isset($options['wpui_login_always_checked']);
        
        echo '<input id="wpui_login_always_checked" name="wpui_login_option_name[wpui_login_always_checked]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_login_always_checked">'. __( 'Always checked remember me?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_login_always_checked'])) {
            esc_attr( $this->options['wpui_login_always_checked']);
        }
    } 

    public function wpui_login_error_message_callback()
    {
        $options = get_option( 'wpui_login_option_name' );  
        
        $check = isset($options['wpui_login_error_message']);
        
        echo '<input id="wpui_login_error_message" name="wpui_login_option_name[wpui_login_error_message]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_login_error_message">'. __( 'Remove error message for security?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_login_error_message'])) {
            esc_attr( $this->options['wpui_login_error_message']);
        }
    }     

    //Global
    public function wpui_global_custom_css_callback()
    {
        printf(
            '<textarea name="wpui_global_option_name[wpui_global_custom_css]">%s</textarea>',
            esc_html( $this->options['wpui_global_custom_css'])
        );
        
    }

    public function wpui_global_version_footer_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_version_footer']);
        
        echo '<input id="wpui_global_version_footer" name="wpui_global_option_name[wpui_global_version_footer]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_version_footer">'. __( 'Remove WordPress version in footer?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_version_footer'])) {
            esc_attr( $this->options['wpui_global_version_footer']);
        }
    } 

    public function wpui_global_custom_version_footer_callback()
    {
        printf(
            '<input name="wpui_global_option_name[wpui_global_custom_version_footer]" type="text" value="%s" />',
            esc_attr( $this->options['wpui_global_custom_version_footer'])
        );
    } 

    public function wpui_global_credits_footer_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_credits_footer']);
        
        echo '<input id="wpui_global_credits_footer" name="wpui_global_option_name[wpui_global_credits_footer]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_credits_footer">'. __( 'Remove WordPress credits in footer?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_credits_footer'])) {
            esc_attr( $this->options['wpui_global_credits_footer']);
        }
    } 

    public function wpui_global_custom_credits_footer_callback()
    {
        printf(
            '<input name="wpui_global_option_name[wpui_global_custom_credits_footer]" type="text" value="%s" />',
            esc_attr( $this->options['wpui_global_custom_credits_footer'])
        );
    } 

    public function wpui_global_custom_favicon_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_custom_favicon]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_global_custom_favicon'])
        
        );
        
    } 

    public function wpui_global_help_tab_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_help_tab']);
        
        echo '<input id="wpui_global_help_tab" name="wpui_global_option_name[wpui_global_help_tab]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_help_tab">'. __( 'Remove help tab?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_help_tab'])) {
            esc_attr( $this->options['wpui_global_help_tab']);
        }
    } 

    public function wpui_global_screen_options_tab_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_screen_options_tab']);
        
        echo '<input id="wpui_global_screen_options_tab" name="wpui_global_option_name[wpui_global_screen_options_tab]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_screen_options_tab">'. __( 'Remove screen options tab?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_screen_options_tab'])) {
            esc_attr( $this->options['wpui_global_screen_options_tab']);
        }
    } 

    public function wpui_global_update_notification_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_update_notification']);
        
        echo '<input id="wpui_global_update_notification" name="wpui_global_option_name[wpui_global_update_notification]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_update_notification">'. __( 'Remove WordPress update notifications?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_update_notification'])) {
            esc_attr( $this->options['wpui_global_update_notification']);
        }
    } 

    public function wpui_global_password_notification_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_password_notification']);
        
        echo '<input id="wpui_global_password_notification" name="wpui_global_option_name[wpui_global_password_notification]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_password_notification">'. __( 'Hide autogenerated password message?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_password_notification'])) {
            esc_attr( $this->options['wpui_global_password_notification']);
        }
    } 

    public function wpui_global_trash_callback()
    {
        $options = get_option( 'wpui_global_option_name' );  
        
        $check = isset($options['wpui_global_trash']);
        
        echo '<input id="wpui_global_trash" name="wpui_global_option_name[wpui_global_trash]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_global_trash">'. __( 'Disable trash feature?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_global_trash'])) {
            esc_attr( $this->options['wpui_global_trash']);
        }
    } 

    public function wpui_global_empty_trash_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_empty_trash]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_global_empty_trash'])
        
        );
    }

    public function wpui_global_autosave_interval_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_autosave_interval]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_global_autosave_interval'])
        
        );
    }

    public function wpui_global_limit_posts_revisions_callback()
    {
        printf(
        '<input name="wpui_global_option_name[wpui_global_limit_posts_revisions]" type="text" value="%s" />',
        esc_attr( $this->options['wpui_global_limit_posts_revisions'])
        
        );
    }

    //Dashboard
    public function wpui_dashboard_welcome_panel_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_welcome_panel']);
        
        echo '<input id="wpui_dashboard_welcome_panel" name="wpui_dashboard_option_name[wpui_dashboard_welcome_panel]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_welcome_panel">'. __( 'Remove Welcome Panel?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_welcome_panel'])) {
            esc_attr( $this->options['wpui_dashboard_welcome_panel']);
        }
    }

    public function wpui_dashboard_quick_press_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_quick_press']);
        
        echo '<input id="wpui_dashboard_quick_press" name="wpui_dashboard_option_name[wpui_dashboard_quick_press]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_quick_press">'. __( 'Remove Quick Press widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_quick_press'])) {
            esc_attr( $this->options['wpui_dashboard_quick_press']);
        }
    } 

    public function wpui_dashboard_activity_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_activity']);
        
        echo '<input id="wpui_dashboard_activity" name="wpui_dashboard_option_name[wpui_dashboard_activity]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_activity">'. __( 'Remove Activity widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_activity'])) {
            esc_attr( $this->options['wpui_dashboard_activity']);
        }
    } 

    public function wpui_dashboard_incoming_links_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_incoming_links']);
        
        echo '<input id="wpui_dashboard_incoming_links" name="wpui_dashboard_option_name[wpui_dashboard_incoming_links]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_incoming_links">'. __( 'Remove Incoming Links widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_incoming_links'])) {
            esc_attr( $this->options['wpui_dashboard_incoming_links']);
        }
    } 

    public function wpui_dashboard_right_now_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_right_now']);
        
        echo '<input id="wpui_dashboard_right_now" name="wpui_dashboard_option_name[wpui_dashboard_right_now]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_right_now">'. __( 'Remove Righ Now widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_right_now'])) {
            esc_attr( $this->options['wpui_dashboard_right_now']);
        }
    } 

    public function wpui_dashboard_plugins_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_plugins']);
        
        echo '<input id="wpui_dashboard_plugins" name="wpui_dashboard_option_name[wpui_dashboard_plugins]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_plugins">'. __( 'Remove Plugins widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_plugins'])) {
            esc_attr( $this->options['wpui_dashboard_plugins']);
        }
    } 

    public function wpui_dashboard_recent_drafts_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_recent_drafts']);
        
        echo '<input id="wpui_dashboard_recent_drafts" name="wpui_dashboard_option_name[wpui_dashboard_recent_drafts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_recent_drafts">'. __( 'Remove Recent Drafts widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_recent_drafts'])) {
            esc_attr( $this->options['wpui_dashboard_recent_drafts']);
        }
    } 

    public function wpui_dashboard_recent_comments_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_recent_comments']);
        
        echo '<input id="wpui_dashboard_recent_comments" name="wpui_dashboard_option_name[wpui_dashboard_recent_comments]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_recent_comments">'. __( 'Remove Recent Comments widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_recent_comments'])) {
            esc_attr( $this->options['wpui_dashboard_recent_comments']);
        }
    } 

    public function wpui_dashboard_primary_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_primary']);
        
        echo '<input id="wpui_dashboard_primary" name="wpui_dashboard_option_name[wpui_dashboard_primary]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_primary">'. __( 'Remove Primary widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_primary'])) {
            esc_attr( $this->options['wpui_dashboard_primary']);
        }
    } 

    public function wpui_dashboard_secondary_callback()
    {
        $options = get_option( 'wpui_dashboard_option_name' );  
        
        $check = isset($options['wpui_dashboard_secondary']);
        
        echo '<input id="wpui_dashboard_secondary" name="wpui_dashboard_option_name[wpui_dashboard_secondary]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_dashboard_secondary">'. __( 'Remove Secondary widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_dashboard_secondary'])) {
            esc_attr( $this->options['wpui_dashboard_secondary']);
        }
    } 

    //Admin Menu
    public function wpui_admin_menu_callback()
    {
        global $menu, $submenu, $pagenow;

        $options = get_option( 'wpui_admin_menu_option_name' );  
        $wpui_admin_menu_custom_list = get_option( 'wpui_admin_menu_list_option_name' );
        $wpui_admin_menu_slug_list = array();
        ob_start();
        $wpui_admin_menu_custom_list_order = $wpui_admin_menu_custom_list;
        $wpui_admin_menu_custom_list_default = $menu;

        if ($wpui_admin_menu_custom_list !='') {
            function wpui_admin_menu_order($wpui_admin_menu_custom_list_default, $wpui_admin_menu_custom_list_order) {
                $wpui_admin_menu_ordered = array();
                foreach($wpui_admin_menu_custom_list_order as $key) {
                    if(array_key_exists($key,$wpui_admin_menu_custom_list_default)) {
                        $wpui_admin_menu_ordered[$key] = $wpui_admin_menu_custom_list_default[$key];
                        unset($wpui_admin_menu_custom_list_default[$key]);
                    }
                }
                return $wpui_admin_menu_ordered + $wpui_admin_menu_custom_list_default;
            }
            $menu = wpui_admin_menu_order($wpui_admin_menu_custom_list_default, $wpui_admin_menu_custom_list_order);
        }

        echo '<div class="metabox-holder">';
            echo '<div id="side-sortables" class="accordion-container">';
                echo '<ul class="outer-border">';
                    foreach($menu as $menu_key => $menu_item):
                        if(!$menu_item[0]) { continue; }

                        $check = isset($options['wpui_admin_menu'][$menu_item[2]]);

                        echo '<li id="list_items_'.$menu_key.'" class="control-section accordion-section">';
                            echo '<h3 class="accordion-section-title hndle" tabindex="0">';
                                echo $menu_item[0];
                            echo '</h3>';
                            echo '<div class="accordion-section-content">';
                                echo '<div class="inside">';
                                    echo '<ul>';
                                        echo '<li>';
                                            echo '<input id="wpui_admin_menu['.$menu_key.']" type="checkbox" name="wpui_admin_menu_option_name[wpui_admin_menu]['.$menu_item[2].']"';
                                            if ($menu_item[2] == $check) echo 'checked="yes"'; 
                                            echo ' value="'.$menu_item[2].'"/>';
                                            echo '<label for="wpui_admin_menu['.$menu_key.']">'. $menu_item[0] .'</label>';
                                        echo '</li>';

                                        if (isset($this->options['wpui_admin_menu'][$menu_item[2]])) {
                                            esc_attr( $this->options['wpui_admin_menu'][$menu_item[2]]);
                                        }
                                        
                                        $wpui_admin_menu_slug_list_temp = array_push($wpui_admin_menu_slug_list, $menu_item[2]);
                                        update_option( 'wpui_admin_menu_slug', $wpui_admin_menu_slug_list );
                                        if( isset( $submenu[ $menu_item[2] ] ) ):
                                            foreach($submenu[ $menu_item[2] ] as $submenu_key => $submenu_item):

                                                $check = isset($options['wpui_admin_menu'][$menu_key][$menu_item[2]]['child'][$submenu_key]);

                                                echo '<li><input id="wpui_admin_menu['.$menu_key.']['.$submenu_key.']" type="checkbox"  name="wpui_admin_menu_option_name[wpui_admin_menu]['.$menu_key.']['.$menu_item[2].'][child]['.$submenu_key.']"';
                                                    if ($submenu_item[2] == $check) echo 'checked="yes"'; 
                                                    echo ' value="'.$submenu_item[2].'"/>';
                                                    echo '<label for="wpui_admin_menu['.$menu_key.']['.$submenu_key.']">'.$submenu_item[0].'</label>';
                                                echo '</li>';   

                                                if (isset($this->options['wpui_admin_menu'][$menu_key][$menu_item[2]]['child'][$submenu_key])) {
                                                    esc_attr( $this->options['wpui_admin_menu'][$menu_key][$menu_item[2]]['child'][$submenu_key]);
                                                }
                                            endforeach;  
                                        endif;
                                    echo '</ul>';
                                echo '</div>';   
                            echo '</div>';
                        echo '</li>';
                    endforeach;
                echo '</ul>';
            echo '</div>';
        echo '</div>';

        echo ob_get_clean();
    }

    //Admin bar
    public function wpui_admin_bar_wp_logo_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_wp_logo']);
        
        echo '<input id="wpui_admin_bar_wp_logo" name="wpui_admin_bar_option_name[wpui_admin_bar_wp_logo]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_wp_logo">'. __( 'Remove WordPress logo in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_wp_logo'])) {
            esc_attr( $this->options['wpui_admin_bar_wp_logo']);
        }
    } 

    public function wpui_admin_bar_site_name_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_site_name']);
        
        echo '<input id="wpui_admin_bar_site_name" name="wpui_admin_bar_option_name[wpui_admin_bar_site_name]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_site_name">'. __( 'Remove Site Name in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_site_name'])) {
            esc_attr( $this->options['wpui_admin_bar_site_name']);
        }
    }

    public function wpui_admin_bar_my_account_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_my_account']);
        
        echo '<input id="wpui_admin_bar_my_account" name="wpui_admin_bar_option_name[wpui_admin_bar_my_account]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_my_account">'. __( 'Remove My Account in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_my_account'])) {
            esc_attr( $this->options['wpui_admin_bar_my_account']);
        }
    } 
    
    public function wpui_admin_bar_menu_toggle_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_menu_toggle']);
        
        echo '<input id="wpui_admin_bar_menu_toggle" name="wpui_admin_bar_option_name[wpui_admin_bar_menu_toggle]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_menu_toggle">'. __( 'Remove Menu Toggle (hamburger icon in responsive mode)?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_menu_toggle'])) {
            esc_attr( $this->options['wpui_admin_bar_menu_toggle']);
        }
    } 

    public function wpui_admin_bar_edit_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_edit']);
        
        echo '<input id="wpui_admin_bar_edit" name="wpui_admin_bar_option_name[wpui_admin_bar_edit]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_edit">'. __( 'Remove Edit in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_edit'])) {
            esc_attr( $this->options['wpui_admin_bar_edit']);
        }
    } 
    
    public function wpui_admin_bar_preview_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_preview']);
        
        echo '<input id="wpui_admin_bar_preview" name="wpui_admin_bar_option_name[wpui_admin_bar_preview]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_preview">'. __( 'Remove Preview in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_preview'])) {
            esc_attr( $this->options['wpui_admin_bar_preview']);
        }
    }

    public function wpui_admin_bar_view_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_view']);
        
        echo '<input id="wpui_admin_bar_view" name="wpui_admin_bar_option_name[wpui_admin_bar_view]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_view">'. __( 'Remove View in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_view'])) {
            esc_attr( $this->options['wpui_admin_bar_view']);
        }
    } 
    
    public function wpui_admin_bar_comments_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_comments']);
        
        echo '<input id="wpui_admin_bar_comments" name="wpui_admin_bar_option_name[wpui_admin_bar_comments]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_comments">'. __( 'Remove Comments in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_comments'])) {
            esc_attr( $this->options['wpui_admin_bar_comments']);
        }
    } 
    
    public function wpui_admin_bar_new_content_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_new_content']);
        
        echo '<input id="wpui_admin_bar_new_content" name="wpui_admin_bar_option_name[wpui_admin_bar_new_content]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_new_content">'. __( 'Remove New Content in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_new_content'])) {
            esc_attr( $this->options['wpui_admin_bar_new_content']);
        }
    } 

    public function wpui_admin_bar_view_site_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_view_site']);
        
        echo '<input id="wpui_admin_bar_view_site" name="wpui_admin_bar_option_name[wpui_admin_bar_view_site]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_view_site">'. __( 'Remove View Site in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_view_site'])) {
            esc_attr( $this->options['wpui_admin_bar_view_site']);
        }
    }

    public function wpui_admin_bar_updates_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_updates']);
        
        echo '<input id="wpui_admin_bar_updates" name="wpui_admin_bar_option_name[wpui_admin_bar_updates]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_updates">'. __( 'Remove Updates in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_updates'])) {
            esc_attr( $this->options['wpui_admin_bar_updates']);
        }
    }

    public function wpui_admin_bar_disable_callback()
    {
        $options = get_option( 'wpui_admin_bar_option_name' );  
        
        $check = isset($options['wpui_admin_bar_disable']);
        
        echo '<input id="wpui_admin_bar_disable" name="wpui_admin_bar_option_name[wpui_admin_bar_disable]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_bar_disable">'. __( 'Disable admin bar in front-end?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_bar_disable'])) {
            esc_attr( $this->options['wpui_admin_bar_disable']);
        }
    }

    //Editor
    public function wpui_admin_editor_full_tinymce_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_full_tinymce']);
        
        echo '<input id="wpui_admin_editor_full_tinymce" name="wpui_editor_option_name[wpui_admin_editor_full_tinymce]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_full_tinymce">'. __( 'Enable full TinyMCE by default?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_full_tinymce'])) {
            esc_attr( $this->options['wpui_admin_editor_full_tinymce']);
        }
    }

    public function wpui_admin_editor_font_size_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_font_size']);
        
        echo '<input id="wpui_admin_editor_font_size" name="wpui_editor_option_name[wpui_admin_editor_font_size]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_font_size">'. __( 'Add Font Size select?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_font_size'])) {
            esc_attr( $this->options['wpui_admin_editor_font_size']);
        }
    }

    public function wpui_admin_editor_font_family_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_font_family']);
        
        echo '<input id="wpui_admin_editor_font_family" name="wpui_editor_option_name[wpui_admin_editor_font_family]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_font_family">'. __( 'Add Font Family select?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_font_family'])) {
            esc_attr( $this->options['wpui_admin_editor_font_family']);
        }
    }

    public function wpui_admin_editor_custom_fonts_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_custom_fonts']);
        
        echo '<input id="wpui_admin_editor_custom_fonts" name="wpui_editor_option_name[wpui_admin_editor_custom_fonts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_custom_fonts">'. __( 'Add Custom Fonts select?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_custom_fonts'])) {
            esc_attr( $this->options['wpui_admin_editor_custom_fonts']);
        }
    }

    public function wpui_admin_editor_formats_select_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_formats_select']);
        
        echo '<input id="wpui_admin_editor_formats_select" name="wpui_editor_option_name[wpui_admin_editor_formats_select]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_formats_select">'. __( 'Add Formats select (styles)?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_formats_select'])) {
            esc_attr( $this->options['wpui_admin_editor_formats_select']);
        }
    }

    public function wpui_admin_editor_get_shortlink_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_get_shortlink']);
        
        echo '<input id="wpui_admin_editor_get_shortlink" name="wpui_editor_option_name[wpui_admin_editor_get_shortlink]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_get_shortlink">'. __( 'Remove Get shortlink button?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_get_shortlink'])) {
            esc_attr( $this->options['wpui_admin_editor_get_shortlink']);
        }
    }

    public function wpui_admin_editor_btn_newdocument_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_newdocument']);
        
        echo '<input id="wpui_admin_editor_btn_newdocument" name="wpui_editor_option_name[wpui_admin_editor_btn_newdocument]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_newdocument">'. __( ' Add New Document button?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_newdocument'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_newdocument']);
        }
    }

        public function wpui_admin_editor_btn_cut_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_cut']);
        
        echo '<input id="wpui_admin_editor_btn_cut" name="wpui_editor_option_name[wpui_admin_editor_btn_cut]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_cut">'. __( ' Add Cut button?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_cut'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_cut']);
        }
    }

        public function wpui_admin_editor_btn_copy_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_copy']);
        
        echo '<input id="wpui_admin_editor_btn_copy" name="wpui_editor_option_name[wpui_admin_editor_btn_copy]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_copy">'. __( ' Add Copy button?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_copy'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_copy']);
        }
    }

    public function wpui_admin_editor_btn_paste_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_paste']);
        
        echo '<input id="wpui_admin_editor_btn_paste" name="wpui_editor_option_name[wpui_admin_editor_btn_paste]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_paste">'. __( ' Add Paste button?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_paste'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_paste']);
        }
    }

    public function wpui_admin_editor_btn_backcolor_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_btn_backcolor']);
        
        echo '<input id="wpui_admin_editor_btn_backcolor" name="wpui_editor_option_name[wpui_admin_editor_btn_backcolor]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_btn_backcolor">'. __( ' Add Backcolor button?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_btn_backcolor'])) {
            esc_attr( $this->options['wpui_admin_editor_btn_backcolor']);
        }
    }    

    public function wpui_admin_editor_media_insert_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_insert']);
        
        echo '<input id="wpui_admin_editor_media_insert" name="wpui_editor_option_name[wpui_admin_editor_media_insert]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_insert">'. __( 'Remove Insert Media in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_insert'])) {
            esc_attr( $this->options['wpui_admin_editor_media_insert']);
        }
    }

    public function wpui_admin_editor_media_upload_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_upload']);
        
        echo '<input id="wpui_admin_editor_media_upload" name="wpui_editor_option_name[wpui_admin_editor_media_upload]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_upload">'. __( 'Remove Upload Files in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_upload'])) {
            esc_attr( $this->options['wpui_admin_editor_media_upload']);
        }
    }

    public function wpui_admin_editor_media_library_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_library']);
        
        echo '<input id="wpui_admin_editor_media_library" name="wpui_editor_option_name[wpui_admin_editor_media_library]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_library">'. __( 'Remove Media Library in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_library'])) {
            esc_attr( $this->options['wpui_admin_editor_media_library']);
        }
    }

    public function wpui_admin_editor_media_gallery_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_gallery']);
        
        echo '<input id="wpui_admin_editor_media_gallery" name="wpui_editor_option_name[wpui_admin_editor_media_gallery]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_gallery">'. __( 'Remove Create Gallery in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_gallery'])) {
            esc_attr( $this->options['wpui_admin_editor_media_gallery']);
        }
    }    

    public function wpui_admin_editor_media_playlist_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_playlist']);
        
        echo '<input id="wpui_admin_editor_media_playlist" name="wpui_editor_option_name[wpui_admin_editor_media_playlist]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_playlist">'. __( 'Remove Create Playlist in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_playlist'])) {
            esc_attr( $this->options['wpui_admin_editor_media_playlist']);
        }
    }

    public function wpui_admin_editor_media_featured_img_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_featured_img']);
        
        echo '<input id="wpui_admin_editor_media_featured_img" name="wpui_editor_option_name[wpui_admin_editor_media_featured_img]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_featured_img">'. __( 'Remove Set Featured Image in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_featured_img'])) {
            esc_attr( $this->options['wpui_admin_editor_media_featured_img']);
        }
    }

    public function wpui_admin_editor_media_insert_url_callback()
    {
        $options = get_option( 'wpui_editor_option_name' );  
        
        $check = isset($options['wpui_admin_editor_media_insert_url']);
        
        echo '<input id="wpui_admin_editor_media_insert_url" name="wpui_editor_option_name[wpui_admin_editor_media_insert_url]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_admin_editor_media_insert_url">'. __( 'Remove Insert From URL in Media Modal?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_admin_editor_media_insert_url'])) {
            esc_attr( $this->options['wpui_admin_editor_media_insert_url']);
        }
    }

    //Metaboxes
    public function wpui_metaboxe_author_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_author_posts']);
        
        echo '<input id="wpui_metaboxe_author_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_author_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_author_posts">'. __( 'Remove Author metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_author_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_author_posts']);
        }        
    }

    public function wpui_metaboxe_categories_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_categories_posts']);
        
        echo '<input id="wpui_metaboxe_categories_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_categories_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_categories_posts">'. __( 'Remove Categories metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_categories_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_categories_posts']);
        }
    }

    public function wpui_metaboxe_comments_status_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_comments_status_posts']);
        
        echo '<input id="wpui_metaboxe_comments_status_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_comments_status_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_comments_status_posts">'. __( 'Remove Comments Status metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_comments_status_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_comments_status_posts']);
        }
    }

    public function wpui_metaboxe_comments_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_comments_posts']);
        
        echo '<input id="wpui_metaboxe_comments_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_comments_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_comments_posts">'. __( 'Remove Comments metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_comments_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_comments_posts']);
        }
    }

    public function wpui_metaboxe_formats_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_formats_posts']);
        
        echo '<input id="wpui_metaboxe_formats_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_formats_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_formats_posts">'. __( 'Remove Formats metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_formats_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_formats_posts']);
        }
    }

    public function wpui_metaboxe_attributes_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_attributes_posts']);
        
        echo '<input id="wpui_metaboxe_attributes_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_attributes_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_attributes_posts">'. __( 'Remove Attributes metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_attributes_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_attributes_posts']);
        }
    }

    public function wpui_metaboxe_custom_fields_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_custom_fields_posts']);
        
        echo '<input id="wpui_metaboxe_custom_fields_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_custom_fields_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_custom_fields_posts">'. __( 'Remove Custom fields metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_custom_fields_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_custom_fields_posts']);
        }
    }

    public function wpui_metaboxe_excerpt_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_excerpt_posts']);
        
        echo '<input id="wpui_metaboxe_excerpt_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_excerpt_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_excerpt_posts">'. __( 'Remove Excerpt metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_excerpt_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_excerpt_posts']);
        }
    }

    public function wpui_metaboxe_featured_image_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_featured_image_posts']);
        
        echo '<input id="wpui_metaboxe_featured_image_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_featured_image_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_featured_image_posts">'. __( 'Remove Featured Image metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_featured_image_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_featured_image_posts']);
        }
    }

    public function wpui_metaboxe_revisions_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_revisions_posts']);
        
        echo '<input id="wpui_metaboxe_revisions_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_revisions_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_revisions_posts">'. __( 'Remove Revisions metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_revisions_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_revisions_posts']);
        }
    }

    public function wpui_metaboxe_slug_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_slug_posts']);
        
        echo '<input id="wpui_metaboxe_slug_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_slug_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_slug_posts">'. __( 'Remove Slug metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_slug_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_slug_posts']);
        }
    }

    public function wpui_metaboxe_submit_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_submit_posts']);
        
        echo '<input id="wpui_metaboxe_submit_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_submit_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_submit_posts">'. __( 'Remove Submit metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_submit_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_submit_posts']);
        }
    }

    public function wpui_metaboxe_tags_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_tags_posts']);
        
        echo '<input id="wpui_metaboxe_tags_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_tags_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_tags_posts">'. __( 'Remove Tags metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_tags_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_tags_posts']);
        }
    }

    public function wpui_metaboxe_trackbacks_posts_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_trackbacks_posts']);
        
        echo '<input id="wpui_metaboxe_trackbacks_posts" name="wpui_metaboxes_option_name[wpui_metaboxe_trackbacks_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_trackbacks_posts">'. __( 'Remove Trackbacks metabox in posts?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_trackbacks_posts'])) {
            esc_attr( $this->options['wpui_metaboxe_trackbacks_posts']);
        }
    }

    public function wpui_metaboxe_author_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_author_pages']);
        
        echo '<input id="wpui_metaboxe_author_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_author_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_author_pages">'. __( 'Remove Author metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_author_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_author_pages']);
        }
    }

    public function wpui_metaboxe_comments_status_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_comments_status_pages']);
        
        echo '<input id="wpui_metaboxe_comments_status_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_comments_status_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_comments_status_pages">'. __( 'Remove Comments Status metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_comments_status_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_comments_status_pages']);
        }
    }

    public function wpui_metaboxe_comments_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_comments_pages']);
        
        echo '<input id="wpui_metaboxe_comments_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_comments_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_comments_pages">'. __( 'Remove Comments metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_comments_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_comments_pages']);
        }
    }

    public function wpui_metaboxe_attributes_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_attributes_pages']);
        
        echo '<input id="wpui_metaboxe_attributes_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_attributes_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_attributes_pages">'. __( 'Remove Attributes metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_attributes_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_attributes_pages']);
        }
    }

    public function wpui_metaboxe_custom_fields_pages_callback()
    {
        $options = get_option( 'wpui_option_name' );  
        
        $check = isset($options['wpui_metaboxes_metaboxe_custom_fields_pages']);
        
        echo '<input id="wpui_metaboxe_custom_fields_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_custom_fields_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_custom_fields_pages">'. __( 'Remove Custom fields metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_custom_fields_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_custom_fields_pages']);
        }
    }

    public function wpui_metaboxe_featured_image_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_featured_image_pages']);
        
        echo '<input id="wpui_metaboxe_featured_image_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_featured_image_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_featured_image_pages">'. __( 'Remove Featured Image metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_featured_image_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_featured_image_pages']);
        }
    }

    public function wpui_metaboxe_revisions_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_revisions_pages']);
        
        echo '<input id="wpui_metaboxe_revisions_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_revisions_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_revisions_pages">'. __( 'Remove Revisions metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_revisions_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_revisions_pages']);
        }
    }

    public function wpui_metaboxe_slug_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_slug_pages']);
        
        echo '<input id="wpui_metaboxe_slug_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_slug_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_slug_pages">'. __( 'Remove Slug metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_slug_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_slug_pages']);
        }
    }

    public function wpui_metaboxe_submit_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_submit_pages']);
        
        echo '<input id="wpui_metaboxe_submit_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_submit_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_submit_pages">'. __( 'Remove Submit metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_submit_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_submit_pages']);
        }
    }

    public function wpui_metaboxe_trackbacks_pages_callback()
    {
        $options = get_option( 'wpui_metaboxes_option_name' );  
        
        $check = isset($options['wpui_metaboxe_trackbacks_pages']);
        
        echo '<input id="wpui_metaboxe_trackbacks_pages" name="wpui_metaboxes_option_name[wpui_metaboxe_trackbacks_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_metaboxe_trackbacks_pages">'. __( 'Remove Trackbacks metabox in pages?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_metaboxe_trackbacks_pages'])) {
            esc_attr( $this->options['wpui_metaboxe_trackbacks_pages']);
        }
    }

    //Profil
    public function wpui_profil_visual_editor_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_visual_editor']);
        
        echo '<input id="wpui_profil_visual_editor" name="wpui_profil_option_name[wpui_profil_visual_editor]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_visual_editor">'. __( 'Remove Disable the visual editor when writing?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_visual_editor'])) {
            esc_attr( $this->options['wpui_profil_visual_editor']);
        }
    }

    public function wpui_profil_admin_color_scheme_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_admin_color_scheme']);
        
        echo '<input id="wpui_profil_admin_color_scheme" name="wpui_profil_option_name[wpui_profil_admin_color_scheme]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_admin_color_scheme">'. __( 'Remove Admin Color Scheme?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_admin_color_scheme'])) {
            esc_attr( $this->options['wpui_profil_admin_color_scheme']);
        }
    }

    public function wpui_profil_default_color_scheme_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );    
        
        if (isset($options['wpui_profil_default_color_scheme'])) { 
            $check = $options['wpui_profil_default_color_scheme'];
        }
        else {
            $check = 'none';
        }
        
        echo '<input id="wpui_profil_default_color_scheme_none" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('none' == $check) echo 'checked="yes"'; 
        echo ' value="none"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_none">'. __( 'None', 'wpui' ) .'</label>';
        
        echo '<br><br>';

        echo '<input id="wpui_profil_default_color_scheme_default" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('default' == $check) echo 'checked="yes"'; 
        echo ' value="default"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_default">'. __( 'Default', 'wpui' ) .'</label>';
        
        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_light" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('light' == $check) echo 'checked="yes"'; 
        echo ' value="light"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_light">'. __( 'Light', 'wpui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_blue" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('blue' == $check) echo 'checked="yes"'; 
        echo ' value="blue"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_blue">'. __( 'Blue', 'wpui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_coffee" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('coffee' == $check) echo 'checked="yes"'; 
        echo ' value="coffee"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_coffee">'. __( 'Coffee', 'wpui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_ectoplasm" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('ectoplasm' == $check) echo 'checked="yes"'; 
        echo ' value="ectoplasm"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_ectoplasm">'. __( 'Ectoplasm', 'wpui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_midnight" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('midnight' == $check) echo 'checked="yes"'; 
        echo ' value="midnight"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_midnight">'. __( 'Midnight', 'wpui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_ocean" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('ocean' == $check) echo 'checked="yes"'; 
        echo ' value="ocean"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_ocean">'. __( 'Ocean', 'wpui' ) .'</label>';

        echo '<br><br>';
        
        echo '<input id="wpui_profil_default_color_scheme_sunrise" name="wpui_profil_option_name[wpui_profil_default_color_scheme]" type="radio"';
        if ('sunrise' == $check) echo 'checked="yes"'; 
        echo ' value="sunrise"/>';
        
        echo '<label for="wpui_profil_default_color_scheme_sunrise">'. __( 'Sunrise', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_default_color_scheme'])) {
            esc_attr( $this->options['wpui_profil_default_color_scheme']);
        }
    }

    public function wpui_profil_keyword_shortcuts_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_keyword_shortcuts']);
        
        echo '<input id="wpui_profil_keyword_shortcuts" name="wpui_profil_option_name[wpui_profil_keyword_shortcuts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_keyword_shortcuts">'. __( 'Remove Enable Keyword Shortcuts for comment moderation?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_keyword_shortcuts'])) {
            esc_attr( $this->options['wpui_profil_keyword_shortcuts']);
        }
    }

    public function wpui_profil_show_toolbar_callback()
    {
        $options = get_option( 'wpui_profil_option_name' );  
        
        $check = isset($options['wpui_profil_show_toolbar']);
        
        echo '<input id="wpui_profil_show_toolbar" name="wpui_profil_option_name[wpui_profil_show_toolbar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_profil_show_toolbar">'. __( 'Remove Show Toolbar when viewing site?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_profil_show_toolbar'])) {
            esc_attr( $this->options['wpui_profil_show_toolbar']);
        }
    }

    //Columns
    public function wpui_col_cb_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_cb_posts']);
        
        echo '<input id="wpui_col_cb_posts" name="wpui_columns_option_name[wpui_col_cb_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_cb_posts">'. __( 'Remove checkboxes column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_cb_posts'])) {
            esc_attr( $this->options['wpui_col_cb_posts']);
        }
    }

    public function wpui_col_title_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_title_posts']);
        
        echo '<input id="wpui_col_title_posts" name="wpui_columns_option_name[wpui_col_title_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_title_posts">'. __( 'Remove title column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_title_posts'])) {
            esc_attr( $this->options['wpui_col_title_posts']);
        }
    }

    public function wpui_col_author_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_author_posts']);
        
        echo '<input id="wpui_col_author_posts" name="wpui_columns_option_name[wpui_col_author_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_author_posts">'. __( 'Remove author column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_author_posts'])) {
            esc_attr( $this->options['wpui_col_author_posts']);
        }
    }

    public function wpui_col_categories_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_categories_posts']);
        
        echo '<input id="wpui_col_categories_posts" name="wpui_columns_option_name[wpui_col_categories_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_categories_posts">'. __( 'Remove categories column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_categories_posts'])) {
            esc_attr( $this->options['wpui_col_categories_posts']);
        }
    }

    public function wpui_col_tags_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_tags_posts']);
        
        echo '<input id="wpui_col_tags_posts" name="wpui_columns_option_name[wpui_col_tags_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_tags_posts">'. __( 'Remove tags column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_tags_posts'])) {
            esc_attr( $this->options['wpui_col_tags_posts']);
        }
    }

    public function wpui_col_comments_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_comments_posts']);
        
        echo '<input id="wpui_col_comments_posts" name="wpui_columns_option_name[wpui_col_comments_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_comments_posts">'. __( 'Remove comments column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_comments_posts'])) {
            esc_attr( $this->options['wpui_col_comments_posts']);
        }
    }

    public function wpui_col_date_posts_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_date_posts']);
        
        echo '<input id="wpui_col_date_posts" name="wpui_columns_option_name[wpui_col_date_posts]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_date_posts">'. __( 'Remove date column in posts list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_date_posts'])) {
            esc_attr( $this->options['wpui_col_date_posts']);
        }
    }

    public function wpui_col_cb_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_cb_pages']);
        
        echo '<input id="wpui_col_cb_pages" name="wpui_columns_option_name[wpui_col_cb_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_cb_pages">'. __( 'Remove checkboxes column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_cb_pages'])) {
            esc_attr( $this->options['wpui_col_cb_pages']);
        }
    }

    public function wpui_col_title_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_title_pages']);
        
        echo '<input id="wpui_col_title_pages" name="wpui_columns_option_name[wpui_col_title_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_title_pages">'. __( 'Remove title column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_title_pages'])) {
            esc_attr( $this->options['wpui_col_title_pages']);
        }
    }

    public function wpui_col_author_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_author_pages']);
        
        echo '<input id="wpui_col_author_pages" name="wpui_columns_option_name[wpui_col_author_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_author_pages">'. __( 'Remove author column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_author_pages'])) {
            esc_attr( $this->options['wpui_col_author_pages']);
        }
    }

    public function wpui_col_categories_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_categories_pages']);
        
        echo '<input id="wpui_col_categories_pages" name="wpui_columns_option_name[wpui_col_categories_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_categories_pages">'. __( 'Remove categories column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_categories_pages'])) {
            esc_attr( $this->options['wpui_col_categories_pages']);
        }
    }

    public function wpui_col_tags_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_tags_pages']);
        
        echo '<input id="wpui_col_tags_pages" name="wpui_columns_option_name[wpui_col_tags_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_tags_pages">'. __( 'Remove tags column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_tags_pages'])) {
            esc_attr( $this->options['wpui_col_tags_pages']);
        }
    }

    public function wpui_col_comments_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_comments_pages']);
        
        echo '<input id="wpui_col_comments_pages" name="wpui_columns_option_name[wpui_col_comments_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_comments_pages">'. __( 'Remove comments column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_comments_pages'])) {
            esc_attr( $this->options['wpui_col_comments_pages']);
        }
    }

    public function wpui_col_date_pages_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_date_pages']);
        
        echo '<input id="wpui_col_date_pages" name="wpui_columns_option_name[wpui_col_date_pages]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_date_pages">'. __( 'Remove date column in pages list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_date_pages'])) {
            esc_attr( $this->options['wpui_col_date_pages']);
        }
    }

    public function wpui_col_cb_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_cb_media']);
        
        echo '<input id="wpui_col_cb_media" name="wpui_columns_option_name[wpui_col_cb_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_cb_media">'. __( 'Remove checkboxes column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_cb_media'])) {
            esc_attr( $this->options['wpui_col_cb_media']);
        }
    }

    public function wpui_col_icon_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_icon_media']);
        
        echo '<input id="wpui_col_icon_media" name="wpui_columns_option_name[wpui_col_icon_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_icon_media">'. __( 'Remove icon column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_icon_media'])) {
            esc_attr( $this->options['wpui_col_icon_media']);
        }
    }

    public function wpui_col_title_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_title_media']);
        
        echo '<input id="wpui_col_title_media" name="wpui_columns_option_name[wpui_col_title_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_title_media">'. __( 'Remove title column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_title_media'])) {
            esc_attr( $this->options['wpui_col_title_media']);
        }
    }

    public function wpui_col_author_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_author_media']);
        
        echo '<input id="wpui_col_author_media" name="wpui_columns_option_name[wpui_col_author_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_author_media">'. __( 'Remove author column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_author_media'])) {
            esc_attr( $this->options['wpui_col_author_media']);
        }
    }

    public function wpui_col_parent_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_parent_media']);
        
        echo '<input id="wpui_col_parent_media" name="wpui_columns_option_name[wpui_col_parent_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_parent_media">'. __( 'Remove parent column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_parent_media'])) {
            esc_attr( $this->options['wpui_col_parent_media']);
        }
    }

    public function wpui_col_comments_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_comments_media']);
        
        echo '<input id="wpui_col_comments_media" name="wpui_columns_option_name[wpui_col_comments_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_comments_media">'. __( 'Remove comments column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_comments_media'])) {
            esc_attr( $this->options['wpui_col_comments_media']);
        }
    }

    public function wpui_col_date_media_callback()
    {
        $options = get_option( 'wpui_columns_option_name' );  
        
        $check = isset($options['wpui_col_date_media']);
        
        echo '<input id="wpui_col_date_media" name="wpui_columns_option_name[wpui_col_date_media]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_col_date_media">'. __( 'Remove date column in media list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_col_date_media'])) {
            esc_attr( $this->options['wpui_col_date_media']);
        }
    }

    //Media Library
    public function wpui_library_filters_pdf_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_pdf']);
        
        echo '<input id="wpui_library_filters_pdf" name="wpui_library_option_name[wpui_library_filters_pdf]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_pdf">'. __( 'Add PDF filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_pdf'])) {
            esc_attr( $this->options['wpui_library_filters_pdf']);
        }
    }

    public function wpui_library_filters_zip_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_zip']);
        
        echo '<input id="wpui_library_filters_zip" name="wpui_library_option_name[wpui_library_filters_zip]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_zip">'. __( 'Add ZIP filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_zip'])) {
            esc_attr( $this->options['wpui_library_filters_zip']);
        }
    }

    public function wpui_library_filters_rar_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_rar']);
        
        echo '<input id="wpui_library_filters_rar" name="wpui_library_option_name[wpui_library_filters_rar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_rar">'. __( 'Add RAR filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_rar'])) {
            esc_attr( $this->options['wpui_library_filters_rar']);
        }
    }

    public function wpui_library_filters_7z_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_7z']);
        
        echo '<input id="wpui_library_filters_7z" name="wpui_library_option_name[wpui_library_filters_7z]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_7z">'. __( 'Add 7Z filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_7z'])) {
            esc_attr( $this->options['wpui_library_filters_7z']);
        }
    }

    public function wpui_library_filters_tar_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_tar']);
        
        echo '<input id="wpui_library_filters_tar" name="wpui_library_option_name[wpui_library_filters_tar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_tar">'. __( 'Add TAR filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_tar'])) {
            esc_attr( $this->options['wpui_library_filters_tar']);
        }
    }

    public function wpui_library_filters_swf_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_swf']);
        
        echo '<input id="wpui_library_filters_swf" name="wpui_library_option_name[wpui_library_filters_swf]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_swf">'. __( 'Add SWF filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_swf'])) {
            esc_attr( $this->options['wpui_library_filters_swf']);
        }
    }

    public function wpui_library_filters_doc_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_doc']);
        
        echo '<input id="wpui_library_filters_doc" name="wpui_library_option_name[wpui_library_filters_doc]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_doc">'. __( 'Add DOC filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_doc'])) {
            esc_attr( $this->options['wpui_library_filters_doc']);
        }
    }

    public function wpui_library_filters_docx_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_docx']);
        
        echo '<input id="wpui_library_filters_docx" name="wpui_library_option_name[wpui_library_filters_docx]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_docx">'. __( 'Add DOCX filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_docx'])) {
            esc_attr( $this->options['wpui_library_filters_docx']);
        }
    }

    public function wpui_library_filters_ppt_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_ppt']);
        
        echo '<input id="wpui_library_filters_ppt" name="wpui_library_option_name[wpui_library_filters_ppt]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_ppt">'. __( 'Add PPT filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_ppt'])) {
            esc_attr( $this->options['wpui_library_filters_ppt']);
        }
    }

    public function wpui_library_filters_pptx_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_pptx']);
        
        echo '<input id="wpui_library_filters_pptx" name="wpui_library_option_name[wpui_library_filters_pptx]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_pptx">'. __( 'Add PPTX filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_pptx'])) {
            esc_attr( $this->options['wpui_library_filters_pptx']);
        }
    }

    public function wpui_library_filters_xls_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_xls']);
        
        echo '<input id="wpui_library_filters_xls" name="wpui_library_option_name[wpui_library_filters_xls]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_xls">'. __( 'Add XLS filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_xls'])) {
            esc_attr( $this->options['wpui_library_filters_xls']);
        }
    }

    public function wpui_library_filters_xlsx_callback()
    {
        $options = get_option( 'wpui_library_option_name' );  
        
        $check = isset($options['wpui_library_filters_xlsx']);
        
        echo '<input id="wpui_library_filters_xlsx" name="wpui_library_option_name[wpui_library_filters_xlsx]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_library_filters_xlsx">'. __( 'Add XLSX filter?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_library_filters_xlsx'])) {
            esc_attr( $this->options['wpui_library_filters_xlsx']);
        }
    }

    //Plugins
    public function wpui_plugins_wp_seo_col_callback()
    {
        $options = get_option( 'wpui_plugins_option_name' );  
        
        $check = isset($options['wpui_plugins_wp_seo_col']);
        
        echo '<input id="wpui_plugins_wp_seo_col" name="wpui_plugins_option_name[wpui_plugins_wp_seo_col]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_plugins_wp_seo_col">'. __( 'Remove WP SEO columns in list view?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_plugins_wp_seo_col'])) {
            esc_attr( $this->options['wpui_plugins_wp_seo_col']);
        }
    }

    public function wpui_plugins_wp_seo_pos_callback()
    {
        $options = get_option( 'wpui_plugins_option_name' );  
        
        $check = isset($options['wpui_plugins_wp_seo_pos']);
        
        echo '<input id="wpui_plugins_wp_seo_pos" name="wpui_plugins_option_name[wpui_plugins_wp_seo_pos]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_plugins_wp_seo_pos">'. __( 'Move WP SEO Metabox to low position?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_plugins_wp_seo_pos'])) {
            esc_attr( $this->options['wpui_plugins_wp_seo_pos']);
        }
    }

    public function wpui_plugins_wpml_callback()
    {
        $options = get_option( 'wpui_plugins_option_name' );  
        
        $check = isset($options['wpui_plugins_wpml']);
        
        echo '<input id="wpui_plugins_wpml" name="wpui_plugins_option_name[wpui_plugins_wpml]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_plugins_wpml">'. __( 'Remove WPML advert in publish metabox?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_plugins_wpml'])) {
            esc_attr( $this->options['wpui_plugins_wpml']);
        }
    }    

    public function wpui_plugins_wpml_admin_bar_callback()
    {
        $options = get_option( 'wpui_plugins_option_name' );  
        
        $check = isset($options['wpui_plugins_wpml_admin_bar']);
        
        echo '<input id="wpui_plugins_wpml_admin_bar" name="wpui_plugins_option_name[wpui_plugins_wpml_admin_bar]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_plugins_wpml_admin_bar">'. __( 'Remove WPML in admin bar?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_plugins_wpml_admin_bar'])) {
            esc_attr( $this->options['wpui_plugins_wpml_admin_bar']);
        }
    }      

    public function wpui_plugins_wpml_dashboard_widget_callback()
    {
        $options = get_option( 'wpui_plugins_option_name' );  
        
        $check = isset($options['wpui_plugins_wpml_dashboard_widget']);
        
        echo '<input id="wpui_plugins_wpml_dashboard_widget" name="wpui_plugins_option_name[wpui_plugins_wpml_dashboard_widget]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_plugins_wpml_dashboard_widget">'. __( 'Remove WPML dashboard widget?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_plugins_wpml_dashboard_widget'])) {
            esc_attr( $this->options['wpui_plugins_wpml_dashboard_widget']);
        }
    }    

    public function wpui_plugins_woo_updater_callback()
    {
        $options = get_option( 'wpui_plugins_option_name' );  
        
        $check = isset($options['wpui_plugins_woo_updater']);
        
        echo '<input id="wpui_plugins_woo_updater" name="wpui_plugins_option_name[wpui_plugins_woo_updater]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpui_plugins_woo_updater">'. __( 'Remove Install the WooThemes Updater plugin?', 'wpui' ) .'</label>';
        
        if (isset($this->options['wpui_plugins_woo_updater'])) {
            esc_attr( $this->options['wpui_plugins_woo_updater']);
        }
    }
    
    //Roles
    public function wpui_roles_list_role_callback()
    {
        global $pagenow;
        if (( $pagenow == 'admin.php' ) && ($_GET['page'] == 'wpui-roles')) {
            $options = get_option( 'wpui_roles_option_name' );  
            
            global $wp_roles;
     
            $wpui_roles = $wp_roles->get_names();
            if (($key = array_search('Administrator', $wpui_roles)) !== false) {
                unset($wpui_roles[$key]);
            }
            foreach ($wpui_roles as $wpui_role_key => $wpui_role_value) {
                
                $check = isset($options['wpui_roles_list_role'][$wpui_role_key]);

                echo '<input id="wpui_roles_list_role'.$wpui_role_key.'" name="wpui_roles_option_name[wpui_roles_list_role]['.$wpui_role_key.']" type="checkbox"';
                if ($wpui_role_key == $check) echo 'checked="yes"'; 
                echo ' value="'.$wpui_role_key.'"/>';
                echo '<label for="wpui_roles_list_role'.$wpui_role_key.'">'.$wpui_role_value.'</label>';

                if (isset($this->options['wpui_roles_list_role'][$wpui_role_key])) {
                    esc_attr( $this->options['wpui_roles_list_role'][$wpui_role_key]);
                }
            }
        }
    }
}
	
if( is_admin() )
    $my_settings_page = new wpui_options();
	
?>