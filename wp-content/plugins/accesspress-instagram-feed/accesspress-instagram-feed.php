<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
/*
Plugin name: AccessPress Instagram Feed
Plugin URI: https://accesspressthemes.com/wordpress-plugins/accesspress-instagram-feed/
Description: A plugin to add various instagram widgets with dynamic configuration options.
Version: 2.0.3
Author: AccessPress Themes
Author URI: http://accesspressthemes.com
Text Domain:accesspress-instagram-feed
Domain Path: /languages/
License: GPLv2 or later
*/
//Decleration of the necessary constants for plugin
if( !defined( 'APIF_VERSION' ) ) {
    define( 'APIF_VERSION', '2.0.3' );
}

if( !defined( 'APIF_IMAGE_DIR' ) ) {
    define( 'APIF_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images' );
}

if( !defined( 'APIF_JS_DIR' ) ) {
    define( 'APIF_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );
}

if( !defined( 'APIF_CSS_DIR' ) ) {
    define( 'APIF_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' );
}

if( !defined( 'APIF_INST_PATH' ) ) {
    define( 'APIF_INST_PATH', plugin_dir_path( __FILE__ ) );
}

if( !defined( 'APIF_LANG_DIR' ) ) {
    define( 'APIF_LANG_DIR', basename( dirname( __FILE__ ) ) . '/languages/' );
}

if( !defined( 'APIF_TEXT_DOMAIN' ) ) {
    define( 'APIF_TEXT_DOMAIN', 'accesspress-instagram-feed' );
}
//Include admin


/**
 * Register of widgets
 *
 */
include_once( 'inc/backend/widget.php' );
include_once( 'inc/backend/widgetside.php' );

if( !class_exists( 'IF_Class' ) ) {
    
    class IF_Class {
        
        var $apif_settings;
        /**
         * Initializes the plugin functions
         */
        function __construct() {
            $this->apif_settings = get_option( 'apif_settings' );
            register_activation_hook( __FILE__, array($this, 'load_default_settings') ); //loads default settings for the plugin while activating the plugin
            add_action( 'init', array($this, 'plugin_text_domain') ); //loads text domain for translation ready
            add_action( 'init', array($this, 'session_init') ); //starts the session
            add_action( 'admin_menu', array($this, 'add_if_menu') ); //adds plugin menu in wp-admin
            add_action( 'admin_enqueue_scripts', array($this, 'register_admin_assets') ); //registers admin assests such as js and css
            add_action( 'wp_enqueue_scripts', array($this, 'register_frontend_assets') ); //registers js and css for frontend
            add_action( 'admin_post_apif_settings_action', array($this, 'apif_settings_action') ); //recieves the posted values from settings form
            add_action( 'admin_post_apif_restore_default', array($this, 'apif_restore_default') ); //restores default settings;
            add_shortcode( 'ap_instagram_feed', array($this, 'ap_instagram_feed') );
            add_shortcode( 'ap_instagram_widget', array($this, 'ap_instagram_widget') );
            add_shortcode( 'ap_instagram_slider', array($this, 'ap_instagram_slider') );
            add_shortcode( 'ap_instagram_mosaic_lightview', array($this, 'ap_instagram_mosaic_light') );
            
            add_action( 'widgets_init', array($this, 'register_apif_widget') ); //registers the widget
            
        }
        /**
         * Plugin Translation
         */
        function plugin_text_domain() {
            load_plugin_textdomain( 'accesspress-instagram-feed', false, basename( dirname( __FILE__ ) ) . '/languages/' );
        }
        /**
         * Load Default Settings
         *
         */
        function load_default_settings() {
            if( !get_option( 'apif_settings' ) ) {
                $apif_settings = $this->get_default_settings();
                update_option( 'apif_settings', $apif_settings );
            }
        }
        /**
         * Plugin Admin Menu
         */
        function add_if_menu() {
            add_menu_page( __( 'AccessPress Instagram Feed', 'accesspress-instagram-feed' ), __( 'AccessPress Instagram Feed', 'accesspress-instagram-feed' ), 'manage_options', 'if-instagram-feed', array($this, 'main_page'), APIF_IMAGE_DIR . '/sc-icon.png' );
        }
        //plugins backend admin page
        function main_page() {
            include( 'inc/backend/main-page.php' );
        }
        /**
         * Starts the session
         */
        function session_init() {
            if( !session_id() && !headers_sent() ) {
                session_start();
            }
        }
        /**
         * Returns Default Settings
         */
        function get_default_settings() {
            $apif_settings = array('username' => '', 'access_token' => '', 'user_id' => '', 'instagram_mosaic' => 'mosaic');
            return $apif_settings;
        }
        /**
         * Saves settings to database
         */
        function apif_settings_action() {
            if( !empty( $_POST ) && wp_verify_nonce( $_POST['apif_settings_nonce'], 'apif_settings_action' ) ) {
                
                include( 'inc/backend/save-settings.php' );
            }
        }
        /**
         * Registering of backend js and css
         */
        function register_admin_assets() {
            if( isset( $_GET['page'] ) && $_GET['page'] == 'if-instagram-feed' ) {
                wp_enqueue_style( 'sc-admin-css', APIF_CSS_DIR . '/backend.css', array(), APIF_VERSION );
                wp_enqueue_script( 'sc-admin-js', APIF_JS_DIR . '/backend.js', array('jquery', 'jquery-ui-sortable'), APIF_VERSION );
            }
        }
        /**
         * Registers Frontend Assets
         *
         */
        function register_frontend_assets() {
            wp_enqueue_style( 'lightbox', APIF_CSS_DIR . '/lightbox.css', array(), APIF_VERSION );
            wp_enqueue_style( 'owl-theme', APIF_CSS_DIR . '/owl.theme.css', array(), APIF_VERSION );
            wp_enqueue_style( 'owl-carousel', APIF_CSS_DIR . '/owl.carousel.css', array(), APIF_VERSION );
            wp_enqueue_style( 'apif-frontend-css', APIF_CSS_DIR . '/frontend.css', array(), APIF_VERSION );
            wp_enqueue_style( 'apsc-font-awesome', APIF_CSS_DIR . '/font-awesome.min.css', array(), APIF_VERSION );
            wp_enqueue_script( 'lightbox-js', APIF_JS_DIR . '/lightbox.js', array('jquery'), '2.8.1', true );
            wp_enqueue_script( 'apif-isotope-pkgd-min-js', APIF_JS_DIR . '/isotope.pkgd.min.js', array('jquery'), '2.2.0', true );
            wp_enqueue_script( 'owl-carousel-js', APIF_JS_DIR . '/owl.carousel.js', array('jquery') );
            wp_enqueue_script( 'apif-frontend-js', APIF_JS_DIR . '/frontend.js', array('jquery'), '1.0' );
        }
        //instagram feed shortcode
        function ap_instagram_feed() {
            ob_start();
            include( 'inc/frontend/instagram-feed.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }
        // instagram widget shortcode
        function ap_instagram_widget() {
            ob_start();
            include( 'inc/frontend/instagram-widget.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }
        //mosaic light shortcode
        function ap_instagram_mosaic_light() {
            ob_start();
            include( 'inc/frontend/instagram-masaic-light.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }
        
        //slider shortcode
        function ap_instagram_slider() {
            ob_start();
            include( 'inc/frontend/instagram-slider.php' );
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }
        /**
         * AccessPress Instagram Feed Widget
         */
        function register_apif_widget() {
            register_widget( 'APIF_Widget' );
            register_widget( 'APIF_SideWidget' );
        }
    }
    $sc_object = new IF_Class(); //initialization of plugin
    
}
