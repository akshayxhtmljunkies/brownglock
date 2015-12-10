<?php

add_action( 'tgmpa_register', 'accesspress_mag_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to accesspress_pro_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into accesspress_pro_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
if( ! function_exists( 'accesspress_mag_required_plugins' ) ):
function accesspress_mag_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin pre-packaged with a theme.
        
         array(
            'name'      => __( 'Newsletter', 'accesspress-mag' ), //The plugin name
            'slug'      => 'newsletter',  // The plugin slug (typically the folder name)
            'required'  => false,  // If false, the plugin is only 'recommended' instead of required.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            ),
         array(
            'name'      => __( 'AccessPress Social Icons', 'accesspress-mag' ),
            'slug'      => 'accesspress-social-icons',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
         array(
            'name'      => __( 'AccessPress Social Counter', 'accesspress-mag' ),
            'slug'      => 'accesspress-social-counter',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
         array(
            'name'      => __( 'AccessPress Social Share', 'accesspress-mag' ),
            'slug'      => 'accesspress-social-share',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
         array(
            'name'      => __( 'AccessPress Instagram Feed', 'accesspress-mag' ),
            'slug'      => 'accesspress-instagram-feed',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
        array(
            'name'      => __( 'AccessPress Anonymous Post', 'accesspress-mag' ),
            'slug'      => 'accesspress-anonymous-post',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
        array(
            'name'      => __( 'AccessPress Facebook Auto Post', 'accesspress-mag' ),
            'slug'      => 'accesspress-facebook-auto-post',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
        array(
            'name'      => __( 'AccessPress Twitter Auto Post', 'accesspress-mag' ),
            'slug'      => 'accesspress-twitter-auto-post',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
        array(
            'name'      => __( 'Ultimate Form Builder Lite', 'accesspress-mag' ),
            'slug'      => 'ultimate-form-builder-lite',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            ),
    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
            'default_path' => '',                      // Default absolute path to pre-packaged plugins.
            'menu'         => 'accesspress-install-plugins', // Menu slug.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => true,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'accesspress-mag' ),
                'menu_title'                      => __( 'Install Plugins', 'accesspress-mag' ),
                'installing'                      => __( 'Installing Plugin: %s', 'accesspress-mag' ), // %s = plugin name.
                'oops'                            => __( 'Something went wrong with the plugin API.', 'accesspress-mag' ),
                'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'accesspress-mag' ), // %1$s = plugin name(s).
                'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'accesspress-mag' ),
                'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'accesspress-mag' ),
                'return'                          => __( 'Return to Required Plugins Installer', 'accesspress-mag' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'accesspress-mag' ),
                'complete'                        => __( 'All plugins installed and activated successfully. %s', 'accesspress-mag' ), // %s = dashboard link.
                'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
          )
    );
    tgmpa( $plugins, $config );
}
endif;