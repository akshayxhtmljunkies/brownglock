<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
  Plugin Name: Ultimate Form Builder Lite
  Plugin URI:  https://accesspressthemes.com/wordpress-plugins/ultimate-form-builder-lite/
  Description: A plugin to build any type of forms
  Version:     1.0.8
  Author:      AccessPress Themes
  Author URI:  http://accesspressthemes.com
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
  Text Domain: ultimate-form-builder-lite
 */

/**
 * Necessary Constants for plugin
 */
global $wpdb;
defined( 'UFBL_VERSION' ) or define( 'UFBL_VERSION', '1.0.8' ); //plugin version
defined( 'UFBL_SLUG' ) or define( 'UFBL_SLUG', 'ufbl' ); //plugin admin slug
defined( 'UFBL_TD' ) or define( 'UFBL_TD', 'ultimate-form-builder-lite' ); //plugin's text domain
defined( 'UFBL_IMG_DIR' ) or define( 'UFBL_IMG_DIR', plugin_dir_url( __FILE__ ) . 'images' ); //plugin image directory
defined( 'UFBL_JS_DIR' ) or define( 'UFBL_JS_DIR', plugin_dir_url( __FILE__ ) . 'js' );  //plugin js directory
defined( 'UFBL_CSS_DIR' ) or define( 'UFBL_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' ); // plugin css dir
defined( 'UFBL_PATH' ) or define( 'UFBL_PATH', plugin_dir_path( __FILE__ ) );
defined( 'UFBL_FORM_TABLE' ) or define( 'UFBL_FORM_TABLE', $wpdb->prefix . 'ufbl_forms' );
defined( 'UFBL_ENTRY_TABLE' ) or define( 'UFBL_ENTRY_TABLE', $wpdb->prefix . 'ufbl_entries' );
defined( 'UFBL_ENTRY_LIMIT' ) or define( 'UFBL_ENTRY_LIMIT', 10 );

require_once UFBL_PATH . 'classes/ufbl-lib.php';
require_once UFBL_PATH . 'classes/ufbl-model.php';

/**
 * Plugin's main class
 */
if ( !class_exists( 'UFBL_Class' ) ) {

	class UFBL_Class {

		var $library;
		var $model;

		/**
		 * Plugin initialization hooks
		 */
		function __construct() {
			$this->library = new UFBL_Lib();
			$this->model = new UFBL_Model();
			add_action( 'init', array( $this, 'ufbl_init' ) ); //executes when init hook is fired
			add_action( 'admin_menu', array( $this, 'ufbl_menu' ) ); //adds plugin menu in wordpress backend
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) ); //registers admin assets
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) ); //registers admin assets

			/**
			 * Form Add Action
			 */
			add_action( 'wp_ajax_add_form_action', array( $this, 'add_form_ajax' ) ); //add form ajax action
			add_action( 'wp_ajax_nopriv_add_form_action', array( $this, 'no_permission' ) ); //preventing unauthorized ajax call

			/**
			 * Form Copy Action
			 */
			add_action( 'wp_ajax_copy_form_action', array( $this, 'copy_form_ajax' ) ); //copy form ajax action
			add_action( 'wp_ajax_nopriv_copy_form_action', array( $this, 'no_permission' ) ); //preventing unauthorized ajax call

			/**
			 * Front Form Action
			 */
			add_action( 'wp_ajax_ufbl_front_form_action', array( $this, 'front_form_action' ) ); //add form ajax action
			add_action( 'wp_ajax_nopriv_ufbl_front_form_action', array( $this, 'front_form_action' ) ); //preventing unauthorized ajax call

			/**
			 * Form Status Action
			 */
			add_action( 'wp_ajax_ufbl_form_status_action', array( $this, 'form_status_action' ) ); //add form ajax action
			add_action( 'wp_ajax_nopriv_ufbl_form_status_action', array( $this, 'no_permission' ) ); //preventing unauthorized ajax call

			/**
			 * Form Delete Action
			 */
			add_action( 'wp_ajax_ufbl_form_delete_action', array( $this, 'form_delete_action' ) ); //form delete ajax action
			add_action( 'wp_ajax_nopriv_ufbl_form_delete_action', array( $this, 'no_permission' ) ); //preventing unauthorized ajax call

			/**
			 * Entry Delete Action
			 */
			add_action( 'wp_ajax_ufbl_entry_delete_action', array( $this, 'entry_delete_action' ) ); //entry delete ajax action
			add_action( 'wp_ajax_nopriv_ufbl_form_delete_action', array( $this, 'no_permission' ) ); //preventing unauthorized ajax call

			/**
			 * Entry Detail Action
			 */
			add_action( 'wp_ajax_ufbl_get_entry_detail_action', array( $this, 'get_entry_detail_action' ) ); //entry detail ajax action
			add_action( 'wp_ajax_nopriv_ufbl_get_entry_detail_action', array( $this, 'no_permission' ) ); //preventing unauthorized ajax call

			register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) ); //executes when plugin is activated
			add_action( 'admin_post_ufbl_form_action', array( $this, 'ufbl_form_action' ) ); //form action

			/**
			 * Form Shortcode
			 */
			add_shortcode( 'ufbl', array( $this, 'shortcode_manager' ) );

			/**
			 * CSV Export Action
			 */
			add_action( 'admin_post_ufbl_csv_export', array( $this, 'export_csv' ) );

			/**
			 * Form Preview 
			 */
			add_action( 'template_redirect', array( $this, 'preview_form' ) );
		}

		/**
		 * Tasks to be done in init hook 
		 * Loads plugin for translation
		 * Starts session
		 */
		function ufbl_init() {
			load_plugin_textdomain( 'ultimate-form-builder-lite', false, basename( dirname( __FILE__ ) ) . '/languages' ); //Loads plugin text domain for the translation
			if ( !session_id() && !headers_sent() ) {
				session_start(); //starts session if already not started
			}
			do_action( 'ufbl_init' );
		}

		/**
		 * Adds Plugin menu in wordpress backend
		 */
		function ufbl_menu() {
			add_menu_page( __( 'Forms', 'ultimate-form-builder-lite' ), __( 'Forms', 'ultimate-form-builder-lite' ), 'manage_options', UFBL_SLUG, array( $this, 'forms_list' ),'dashicons-welcome-widgets-menus',35.7 );
			add_submenu_page( UFBL_SLUG, isset( $_GET['form_id'] ) ? __( 'Edit Form', 'ultimate-form-builder-lite' ) : __( 'All Forms', 'ultimate-form-builder-lite' ), __( 'All Forms', 'ultimate-form-builder-lite' ), 'manage_options', UFBL_SLUG, array( $this, 'forms_list' ) );
			add_submenu_page( UFBL_SLUG, __( 'New Form', 'ultimate-form-builder-lite' ), __( 'New Form', 'ultimate-form-builder-lite' ), 'manage_options', 'ufbl-new-form', array( $this, 'add_new_form' ) ); 
			add_submenu_page( UFBL_SLUG, __( 'Form Entries', 'ultimate-form-builder-lite' ), __( 'Form Entries', 'ultimate-form-builder-lite' ), 'manage_options', 'ufbl-form-entries', array( $this, 'forms_entries' ) );
			add_submenu_page( UFBL_SLUG, __( 'How to use', 'ultimate-form-builder-lite' ), __( 'How to use', 'ultimate-form-builder-lite' ), 'manage_options', 'ufbl-how-to-use', array( $this, 'how_to_use' ) );
			add_submenu_page( UFBL_SLUG, __( 'About', 'ultimate-form-builder-lite' ), __( 'About', 'ultimate-form-builder-lite' ), 'manage_options', 'ufbl-about', array( $this, 'about' ) );
		}

		/**
		 * Forms Listing
		 */
		function forms_list() {
			if ( isset( $_GET['action'], $_GET['form_id'] ) && $_GET['action'] == 'edit-form' ) {
				$form_id = sanitize_text_field( $_GET['form_id'] );
				$data['form_row'] = $this->model->get_form_detail( $form_id );
				if ( $data['form_row'] != null ) {
					$this->library->load_view( 'backend/form-builder', $data );
				} else {
					die( __( 'No form found for this form id.Please go back and create a new form.', 'ultimate-form-builder-lite' ) );
				}
			} else {
				$forms = $this->model->get_all_forms();
				$data['forms'] = $forms;
				$this->library->load_view( 'backend/form-list', $data );
			}
		}

		/**
		 * Forms Builder
		 */
		function forms_builder() {
			
		}

		/**
		 * Plugin on activation tasks
		 */
		function activate_plugin() {
			$this->library->load_core( 'activation' );
		}

		/**
		 * Registers admin assets
		 */
		function register_admin_assets() {
			$plugin_pages = array( UFBL_SLUG, 'ufbl-new-form', 'ufbl-form-entries', 'ufbl-how-to-use', 'ufbl-about' );
			$admin_ajax_nonce = wp_create_nonce( 'ufbl-admin-ajax-nonce' );
			$admin_ajax_object = array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => $admin_ajax_nonce );
			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $plugin_pages ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_style( 'ufbl-admin', UFBL_CSS_DIR . '/backend.css', array(), UFBL_VERSION );
				wp_enqueue_style( 'ufbl-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), UFBL_VERSION );
				wp_enqueue_script( 'ufbl-admin-js', UFBL_JS_DIR . '/backend.js', array( 'jquery', 'jquery-ui-sortable' ), UFBL_VERSION );
				wp_localize_script( 'ufbl-admin-js', 'ufbl_ajax_obj', $admin_ajax_object );
			}
		}

		/**
		 * Registers front assets
		 */
		function register_frontend_assets() {
			wp_enqueue_style( 'ufbl-custom-select-css', UFBL_CSS_DIR . '/jquery.selectbox.css', array(), UFBL_VERSION );
			wp_enqueue_style( 'ufbl-front-css', UFBL_CSS_DIR . '/frontend.css', array(), UFBL_VERSION );
			wp_enqueue_script( 'ufbl-custom-select-js', UFBL_JS_DIR . '/jquery.selectbox-0.2.min.js', array( 'jquery' ), UFBL_VERSION );
			wp_enqueue_script( 'ufbl-front-js', UFBL_JS_DIR . '/frontend.js', array( 'jquery','ufbl-custom-select-js' ), UFBL_VERSION );
			$frontend_js_obj = array(
				'default_error_message' => __( 'This field is required', 'ultimate-form-builder-lite' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'frontend-ajax-nonce' )
			);
			wp_localize_script( 'ufbl-front-js', 'frontend_js_obj', $frontend_js_obj );
		}

		/**
		 * Unauthorized ajax call
		 */
		function no_permission() {
			die( 'No script kiddies please!' );
		}
		
		/**
		 * Add New Form
		 */
		function add_new_form(){
			$this->library->load_view('backend/new-form');
		}

		/**
		 * Add Form Ajax Action
		 */
		function add_form_ajax() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				$this->model->add_form();
			} else {
				die( 'No script kiddies please' );
			}
		}

		/**
		 * Form status change action
		 */
		function form_status_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				$this->model->change_form_status();
			} else {
				die( 'No script kiddies please' );
			}
		}

		/**
		 * Form Save Action
		 */
		function form_save_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				$this->model->save_form();
			} else {
				die( 'No script kiddies please' );
			}
		}

		/**
		 * Form Delete Action
		 */
		function form_delete_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				$this->model->delete_form();
			} else {
				die( 'No script kiddies please' );
			}
		}

		/**
		 * UFBL Form Save Action
		 */
		function ufbl_form_action() {
			if ( isset( $_POST['ufbl_form_nonce_field'] ) && wp_verify_nonce( $_POST['ufbl_form_nonce_field'], 'ufbl-form-nonce' ) ) {
				$this->model->save_form();
			} else {
				die( 'No script kiddies please' );
			}
		}

		/**
		 * Form Entries Page
		 */
		function forms_entries() {
			$form_rows = $this->model->get_forms();
			$data['form_rows'] = $form_rows;
			$page = isset($_GET['page_num'])?$_GET['page_num']:1;
			$limit = UFBL_ENTRY_LIMIT;
			$offset = ($page-1)*$limit;
			if ( isset( $_GET['form_id'] ) ) {
				$form_id = sanitize_text_field( $_GET['form_id'] );
				$form_entries_row = $this->model->get_forms_entries( $form_id,$limit,$offset );
				$total_form_entries = $this->model->get_total_form_entries($form_id);
			} else {
				$form_entries_row = $this->model->get_forms_entries(NULL,$limit,$offset);
				$total_form_entries = $this->model->get_total_form_entries();
			}
			$total_pages = $total_form_entries/$limit;
			if($total_form_entries%$limit!=0){
				$total_pages = intval($total_pages)+1;
			}
			$data['form_entry_rows'] = $form_entries_row;
			$data['total_pages'] = $total_pages;
			$this->library->load_view( 'backend/form-entries-list', $data );
		}

		/**
		 * Form Shortcode
		 */
		function shortcode_manager( $atts = array() ) {
			if ( isset( $atts['form_id'] ) ) {
				$form_id = $atts['form_id'];
				$form_row = $this->model->get_form_row( $form_id );
				if ( $form_row != '' ) {
					if ( $form_row['form_status'] == 1 ) {

						$form_html = $this->generate_form( $form_row );
					} else {
						$close_message = isset( $atts['close_message'] ) ? $atts['close_message'] : __( 'This form is closed', 'ultimate-form-builder-lite' );
						$form_html = '<p>' . $close_message . '</p>';
					}
				} else {
					$form_html = '<p>' . __( 'Form couldn\'t be found for id ', 'ultimate-form-builder-lite' ) . $form_id . '</p>';
				}


				return $form_html;
			} else {
				return __( 'Form ID missing', 'ultimate-form-builder-lite' );
			}
		}

		/**
		 * Form HTML
		 * 
		 */
		function generate_form( $form_row ) {
			$data['form_row'] = $form_row;
			ob_start();
			$this->library->load_view( 'frontend/front-form', $data );
			$form_html = ob_get_contents();
			ob_clean();
			return $form_html;
		}

		/**
		 * Front Form Action
		 */
		function front_form_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'frontend-ajax-nonce' ) ) {
				//$this->library->print_array( $_POST );
				$this->library->do_form_process();
				do_action( 'ufbl_front_form_action' );
				die();
			} else {
				die( 'No script kiddies please!' );
			}
		}

		/**
		 * Entry Delete Action
		 */
		function entry_delete_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				//$this->library->print_array($_POST);die();
				$entry_id = sanitize_text_field( $_POST['entry_id'] );
				$delete = $this->model->delete_entry( $entry_id );
			} else {
				die( 'No script kiddies please!' );
			}
		}

		/**
		 * Get Entry Detail Action
		 */
		function get_entry_detail_action() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				//$this->library->print_array($_POST);die();
				$entry_id = sanitize_text_field( $_POST['entry_id'] );
				$entry_row = $this->model->get_entry_detail( $entry_id );
				$data['entry_row'] = $entry_row;
				$this->library->load_view( 'backend/entry-detail', $data );
				die();
			} else {
				die( 'No script kiddies please!' );
			}
		}

		/**
		 * Exports File to CSV
		 */
		function export_csv() {
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ufbl-csv-nonce' ) ) {
				if ( isset( $_GET['form_id'] ) ) {
					$form_id = sanitize_text_field( $_GET['form_id'] );
					$form_data = $this->model->get_form_data( $form_id );
					$entry_rows = $this->model->get_forms_entries( $form_id );
					$this->library->generate_csv( $form_data, $entry_rows );
				} else {
					wp_redirect( admin_url( 'admin.php?page=ufbl-form-entries' ) );
					exit;
				}
			} else {
				die( 'No script kiddies please!!' );
			}
		}

		/**
		 * Form copy action
		 */
		function copy_form_ajax() {
			if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ufbl-admin-ajax-nonce' ) ) {
				$this->model->copy_form();
			} else {
				die( 'No script kiddies please' );
			}
		}
		
		/**
		 * Form Preview 
		 */
		function preview_form(){
			if(isset($_GET['ufbl_form_preview'],$_GET['ufbl_form_id']) && is_user_logged_in()){
				$this->library->load_view('frontend/preview-form');
				exit();
			}
		}
		
		/**
		 * How to use page
		 */
		function how_to_use(){
			$this->library->load_view('backend/how-to-use');
		}
		
		/**
		 * About page
		 */
		function about(){
			$this->library->load_view('backend/about');
		}

	}

	/**
	 * Plugin initialization with object creation
	 */
	$ufbl_obj = new UFBL_Class();
	$library_obj = new UFBL_Lib();

	//class termination
}
