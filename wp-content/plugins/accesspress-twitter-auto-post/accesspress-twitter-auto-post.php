<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Plugin Name: AccessPress Twitter Auto Post
 * Plugin URI: https://accesspressthemes.com/wordpress-plugins/accesspress-twitter-auto-post/
 * Description: A plugin to publish your wordpress posts to twitter
 * Version: 1.1.7
 * Author: AccessPress Themes
 * Author URI: http://accesspressthemes.com
 * Text Domain: atap
 * Domain Path: /languages/
 * License:     GPL2

 */
/**
 * Necessary constants define
 */
if (!defined('ATAP_CSS_DIR')) {
    define('ATAP_CSS_DIR', plugin_dir_url(__FILE__) . '/css');
}
if (!defined('ATAP_IMG_DIR')) {
    define('ATAP_IMG_DIR', plugin_dir_url(__FILE__) . '/images');
}
if (!defined('ATAP_JS_DIR')) {
    define('ATAP_JS_DIR', plugin_dir_url(__FILE__) . '/js');
}
if (!defined('ATAP_VERSION')) {
    define('ATAP_VERSION', '1.1.7');
}
if (!defined('ATAP_TD')) {
    define('ATAP_TD', 'accesspress-twitter-auto-post');
}
if (!defined('ATAP_PLUGIN_FILE')) {
    define('ATAP_PLUGIN_FILE', __FILE__);
}
if (!class_exists('ATAP_Class')) {

    /**
     * Declaration of plugin main class
     * */
    class ATAP_Class {

        /**
         * Constructor
         */
        function __construct() {
            register_activation_hook(__FILE__, array($this, 'activation_tasks')); //fired when plugin is activated
            add_action('admin_init', array($this, 'plugin_init')); //starts the session and loads plugin text domain on admin_init hook
            add_action('admin_menu', array($this, 'atap_admin_menu')); //For plugin admin menu
            add_action('admin_enqueue_scripts', array($this, 'register_admin_assets')); //registers js and css for plugin
            add_action('admin_post_atap_form_action', array($this, 'atap_form_action')); //action to save settings
            add_action('admin_init', array($this, 'auto_post_trigger')); // auto post trigger
            add_action('admin_post_atap_clear_log', array($this, 'atap_clear_log')); //clears log from log table
            add_action('admin_post_atap_delete_log', array($this, 'delete_log')); //clears log from log table
            add_action('admin_post_atap_restore_settings', array($this, 'restore_settings')); //clears log from log table
            add_action('add_meta_boxes', array($this, 'add_atap_meta_box')); //adds plugin's meta box
            add_action('save_post', array($this, 'save_atap_meta_value')); //saves meta value 
        }

        /**
         * Activation Tasks
         */
        function activation_tasks() {
            $atap_settings = $this->get_default_settings();
            $atap_extra_settings = array('authorize_status' => 0);
            if (!get_option('atap_settings')) {
                update_option('atap_settings', $atap_settings);
            }

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();
            $log_table_name = $wpdb->prefix . "atap_logs";


            $log_tbl_query = "CREATE TABLE IF NOT EXISTS $log_table_name (
                                log_id INT NOT NULL AUTO_INCREMENT,
                                PRIMARY KEY(log_id),
                                post_id INT NOT NULL,
                                log_status INT NOT NULL,
                                log_time VARCHAR(255),
                                log_details TEXT
                              ) $charset_collate;";
            //echo $log_tbl_query;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($log_tbl_query);
            //die();
        }

        /**
         * Starts session on admin_init hook
         */
        function plugin_init() {
            if (!session_id()) {
                session_start();
            }
            load_plugin_textdomain('accesspress-twitter-auto-post', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        /**
         * Returns Default Settings
         */
        function get_default_settings() {
            $default_settings = array('auto_publish' => 0,
                'api_key' => '',
                'api_secret' => '',
                'access_token' => '',
                'access_token_secret' => '',
                'message_format' => '',
                'short_urls' => 0,
                'bitly_username' => '',
                'bitly_api_key' => '',
                'post_types' => array(),
                'category' => array());
            return $default_settings;
        }

        /**
         * Registers Admin Menu
         */
        function atap_admin_menu() {
            add_menu_page(__('AccessPress Twitter Auto Post', 'accesspress-twitter-auto-post'), __('AccessPress Twitter Auto Post', 'accesspress-twitter-auto-post'), 'manage_options', 'atap', array($this, 'plugin_settings'),'dashicons-twitter');
        }

        /**
         * Plugin Settings Page
         */
        function plugin_settings() {
            include('inc/main-page.php');
        }

        /**
         * Registers Admin Assets
         */
        function register_admin_assets() {
            if (isset($_GET['page']) && $_GET['page'] == 'atap') {
                wp_enqueue_style('atap-fontawesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', ATAP_VERSION);
                wp_enqueue_style('atap-admin-css', ATAP_CSS_DIR . '/admin-style.css', array(), ATAP_VERSION);
                wp_enqueue_script('atap-admin-js', ATAP_JS_DIR . '/admin-script.js', array('jquery'), ATAP_VERSION);
            }
        }

        /**
         * Returns all registered post types
         */
        function get_registered_post_types() {
            $post_types = get_post_types();
            unset($post_types['revision']);
            unset($post_types['attachment']);
            unset($post_types['nav_menu_item']);
            return $post_types;
        }

        /**
         * Prints array in pre format
         */
        function print_array($array) {
            echo "<pre>";
            print_r($array);
            echo "</pre>";
        }

        /**
         * Action to save settings
         */
        function atap_form_action() {
            if (!empty($_POST) && wp_verify_nonce($_POST['atap_form_nonce'], 'atap_form_action')) {
                include('inc/cores/save-settings.php');
            } else {
                die('No script kiddies please!!');
            }
        }

        /**
         * Auto Post Trigger
         * */
        function auto_post_trigger() {
            $post_types = $this->get_registered_post_types();
            foreach ($post_types as $post_type) {
                $publish_action = 'publish_' . $post_type;
                add_action($publish_action, array($this, 'auto_post'), 10, 2);
            }
        }

        /**
         * Auto Post Action
         * */
        function auto_post($id, $post) {
            $auto_post = $_POST['atap_auto_post'];
            if ($auto_post == 'yes' || $auto_post == '') {
                include_once('api/codebird.php');  // twitter api library
                include('inc/cores/auto-post.php');
                $check = update_post_meta($post->ID, 'atap_auto_post', 'no');
                $_POST['atap_auto_post'] = 'no';
            }
        }

        /**
         * Clears Log from Log Table
         */
        function atap_clear_log() {
            if (!empty($_GET) && wp_verify_nonce($_GET['_wpnonce'], 'atap-clear-log-nonce')) {
                global $wpdb;
                $log_table_name = $wpdb->prefix . 'atap_logs';
                $wpdb->query("TRUNCATE TABLE $log_table_name");
                $_SESSION['atap_message'] = __('Logs cleared successfully.', 'accesspress-twitter-auto-post');
                wp_redirect(admin_url('admin.php?page=atap&tab=logs'));
                exit();
            } else {
                die('No script kiddies please!');
            }
        }

        /**
         * 
         * Delete Log
         */
        function delete_log() {
            if (!empty($_GET) && wp_verify_nonce($_GET['_wpnonce'], 'atap_delete_nonce')) {
                $log_id = $_GET['log_id'];
                global $wpdb;
                $table_name = $wpdb->prefix . 'atap_logs';
                $wpdb->delete($table_name, array('log_id' => $log_id), array('%d'));
                $_SESSION['atap_message'] = __('Log Deleted Successfully', 'accesspress-twitter-auto-post');
                wp_redirect(admin_url('admin.php?page=atap'));
            } else {
                die('No script kiddies please!');
            }
        }

        /**
         * Plugin's meta box
         * */
        function add_atap_meta_box($post_type) {
            add_meta_box(
                    'atap_meta_box'
                    , __('AccessPress Twitter Auto Post', 'accesspress-twitter-auto-post')
                    , array($this, 'render_meta_box_content')
                    , $post_type
                    , 'side'
                    , 'high'
            );
        }

        /**
         * atap_meta_box html
         * 
         * */
        function render_meta_box_content($post) {
            // Add an nonce field so we can check for it later.
            wp_nonce_field('atap_meta_box_nonce_action', 'atap_meta_box_nonce_field');

            // Use get_post_meta to retrieve an existing value from the database.
            $auto_post = get_post_meta($post->ID, 'atap_auto_post', true);
            //var_dump($auto_post);
            $auto_post = ($auto_post == '' || $auto_post == 'yes') ? 'yes' : 'no';

            // Display the form, using the current value.
            ?>
            <label for="atap_auto_post"><?php _e('Enable Auto Post', 'accesspress-twitter-auto-post'); ?></label>
            <p>
                <select name="atap_auto_post">
                    <option value="yes" <?php selected($auto_post, 'yes'); ?>><?php _e('Yes', 'accesspress-twitter-auto-post'); ?></option>
                    <option value="no" <?php selected($auto_post, 'no'); ?>><?php _e('No', 'accesspress-twitter-auto-post'); ?></option>
                </select>
            </p>
            <?php
        }

        /**
         * Saves meta value
         * */
        function save_atap_meta_value($post_id) {
            //$this->print_array($_POST);die('abc');
            /*
             * We need to verify this came from the our screen and with proper authorization,
             * because save_post can be triggered at other times.
             */

            // Check if our nonce is set.
            if (!isset($_POST['atap_auto_post']))
                return $post_id;

            $nonce = $_POST['atap_meta_box_nonce_field'];

            // Verify that the nonce is valid.
            if (!wp_verify_nonce($nonce, 'atap_meta_box_nonce_action'))
                return $post_id;

            // If this is an autosave, our form has not been submitted,
            //     so we don't want to do anything.
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;

            // Check the user's permissions.
            if ('page' == $_POST['post_type']) {

                if (!current_user_can('edit_page', $post_id))
                    return $post_id;
            } else {

                if (!current_user_can('edit_post', $post_id))
                    return $post_id;
            }

            /* OK, its safe for us to save the data now. */

            // Sanitize the user input.
            $auto_post = sanitize_text_field($_POST['atap_auto_post']);

            // Update the meta field.
            update_post_meta($post_id, 'atap_auto_post', $auto_post);
        }

        /**
         * Restores Default Settings
         */
        function restore_settings() {
            if(!empty($_GET) && wp_verify_nonce($_GET['_wpnonce'],'atap-restore-nonce')){
                $atap_settings = $this->get_default_settings();
                update_option('atap_settings', $atap_settings);
                $_SESSION['atap_message'] = __('Default Settings Restored Successfully', 'accesspress-twitter-auto-post');
                wp_redirect('admin.php?page=atap');
                exit();
            }else{
                die('No script kiddies please');
            }
        }
        
        /* make a URL small */

        function make_bitly_url($url, $login, $appkey, $format = 'xml', $version = '2.0.1') {
            //create the URL
            $bitly = 'http://api.bit.ly/shorten?version=' . $version . '&longUrl=' . urlencode($url) . '&login=' . $login . '&apiKey=' . $appkey . '&format=' . $format;

            //get the url
            //could also use cURL here
            $response = file_get_contents($bitly);
            //var_dump($response);
            //parse depending on desired format
            if (strtolower($format) == 'json') {
                $json = @json_decode($response, true);
                $result = $json['results'][$url]['shortUrl'];
            } else { //xml
                $xml = simplexml_load_string($response);
                $result = 'http://bit.ly/' . $xml->results->nodeKeyVal->hash;
            }
            $result = ($result == '') ? $url : $result;
            return $result;
        }


    }

    $atap_obj = new ATAP_Class();
}// class Termination




