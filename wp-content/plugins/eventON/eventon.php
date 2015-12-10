<?php
/**
 * Plugin Name: EventON
 * Plugin URI: http://www.myeventon.com/
 * Description: A beautifully crafted minimal calendar experience
 * Version: 2.3.10
 * Author: AshanJay
 * Author URI: http://www.ashanjay.com
 * Requires at least: 3.8
 * Tested up to: 4.3
 *
 * Text Domain: eventon
 * Domain Path: /lang/languages/
 *
 * @package EventON
 * @category Core
 * @author AJDE
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



// main eventon class
if ( ! class_exists( 'EventON' ) ) {

class EventON {
	public $version = '2.3.10';
	/**
	 * @var evo_generator
	 */
	public $evo_generator;	
	
	public $template_url;
	public $print_scripts=false;

	public $lang = '';

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Define constants
		$this->define_constants();	
		
		// Installation
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// Updates
		add_action( 'admin_init', array( $this, 'verify_plugin_version' ), 5 );
		
		// Include required files
		$this->includes();
				
		// Hooks
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );
		
		// Deactivation
		register_deactivation_hook( AJDE_EVCAL_FILE, array($this,'deactivate'));
	}

	/**
	 * Define EVO Constants
	 */
	public function define_constants() {
		if(!defined('EVO_VERSION'))
			define('EVO_VERSION', $this->version);

		define( "AJDE_EVCAL_DIR", WP_PLUGIN_DIR ); //E:\xampp\htdocs\WP/wp-content/plugins
		define( "AJDE_EVCAL_PATH", dirname( __FILE__ ) );// E:\xampp\htdocs\WP/wp-content/plugins/eventON/
		define( "AJDE_EVCAL_FILE", ( __FILE__ ) );
		define( "AJDE_EVCAL_URL", path_join(plugins_url(), basename(dirname(__FILE__))) );
		define( "AJDE_EVCAL_BASENAME", plugin_basename(__FILE__) ); //eventON/eventon.php
		define( "EVENTON_BASE", basename(dirname(__FILE__)) ); //eventON
		define( "BACKEND_URL", get_bloginfo('url').'/wp-admin/' ); 
		// save addon class file url so addons can access this
		$this->evo_url();
	}
	public function evo_url($resave=false){
		$init = get_option('eventon_addon_urls');
		if(empty($init) || $resave){
			$path = AJDE_EVCAL_PATH;
			$arr = array(
				'addons'=>$path.'/classes/class-evo-addons.php',
				'date'=> time()
			);
			update_option('eventon_addon_urls',$arr);
			$init = $arr;
		}
		return $init;
	}	
	
	/**
	 * Include required files
	 * 
	 * @access private
	 * @return void
	 * @since  0.1
	 */
	private function includes(){		

		// post types
		include_once( 'includes/class-evo-post-types.php' );
		include_once( 'includes/class-evo-datatime.php' );
		include_once( 'includes/class-evo-helper.php' );

		include_once('ajde/ajde.php' );
			
		include_once( 'includes/eventon-core-functions.php' );
		include_once( 'includes/class-frontend.php' );		
		include_once( 'includes/class-map-styles.php' );		
		include_once( 'includes/class-calendar-shell.php' );
		include_once( 'includes/class-calendar-body.php' );		
		include_once( 'includes/class-calendar-helper.php' );
		include_once( 'includes/class-calendar_generator.php' );			

		if ( is_admin() ){			
			include_once('includes/admin/eventon-admin-functions.php' );
			include_once('includes/admin/eventon-admin-html.php' );
			include_once('includes/admin/eventon-admin-taxonomies.php' );
			include_once('includes/admin/post_types/ajde_events.php' );
			include_once('includes/admin/welcome.php' );				
			include_once('includes/admin/class-evo-event.php' );
			include_once('includes/admin/class-evo-admin.php' );			
		}
		if ( ! is_admin() || defined('DOING_AJAX') ){
			// Functions
			include_once( 'includes/eventon-functions.php' );
			include_once( 'includes/class-evo-shortcodes.php' );
			include_once( 'includes/class-evo-template-loader.php' );
		}
		if ( defined('DOING_AJAX') ){
			include_once( 'includes/class-evo-ajax.php' );	
		}
		
	}	
	
	/** Init eventON when WordPress Initialises.	 */
	public function init() {
		
		// Set up localisation
		$this->load_plugin_textdomain();
		
		$this->template_url = apply_filters('eventon_template_url','eventon/');
		
		$this->evo_generator	= new EVO_generator();	
		$this->frontend			= new evo_frontend();	

		// Classes/actions loaded for the frontend and for ajax requests
		if ( ! is_admin() || defined('DOING_AJAX') ) {
			// Class instances		
			$this->shortcodes	= new EVO_Shortcodes();			
		}
		if(is_admin()){
			$this->evo_event 	= new evo_event();
			$this->evo_admin 	= new evo_admin();
		}
		
		// roles and capabilities
		eventon_init_caps();
		
		global $pagenow;
		$__needed_pages = array('update-core.php','plugins.php', 'admin.php','admin-ajax.php', 'plugin-install.php','index.php');

		// only for admin Eventon updater
			if(is_admin() && !empty($pagenow) && in_array($pagenow, $__needed_pages) ){

				// Initiate eventon updater	
				require_once( 'includes/admin/class-evo-updater.php' );		
				$this->evo_updater = new evo_updater ( 
					array(
						'version'=>$this->version, 
						'slug'=> strtolower(EVENTON_BASE),
						'plugin_slug'=> AJDE_EVCAL_BASENAME,
						'name'=>EVENTON_BASE,
					)
				);						
			}
		
		// Init action
		do_action( 'eventon_init' );
	}
	/** register_widgets function. */
		function register_widgets() {
			include_once( 'includes/class-evo-widget-main.php' );
		}
	
	// MOVED functions
		/*** output the inpage popup window for eventon	 */
			public function output_eventon_pop_window($arg){
				global $ajde;
				$ajde->wp_admin->lightbox_content($arg);			
			}
		/*	Legend popup box across wp-admin	*/
			public function throw_guide($content, $position='', $echo=true){
				global $ajde;
				if(!is_admin()) return false;
				$content = $ajde->wp_admin->tooltips($content, $position);
				if($echo){ echo $content;  }else{ return $content; }			
			}
		/* EMAIL functions */
			public function get_email_part($part){
				return $this->frontend->get_email_part($part);
			}
		/**
		 * body part of the email template loading
		 * @update 2.2.24
		 */
			public function get_email_body($part, $def_location, $args='', $paths=''){
				return $this->frontend->get_email_body($part, $def_location, $args='', $paths='');
			}
		// since 2.3.6
			public function register_evo_dynamic_styles(){ 
				$this->frontend->register_evo_dynamic_styles();
			}public function load_dynamic_evo_styles(){
				$this->frontend->load_dynamic_evo_styles();
			}public function load_default_evo_scripts(){
				$this->frontend->load_default_evo_scripts();
			}public function load_default_evo_styles(){
				$this->frontend->load_default_evo_styles();
			}public function load_evo_scripts_styles(){
				$this->frontend->load_evo_scripts_styles();
			}public function evo_styles(){
				add_action('wp_head', array($this, 'load_default_evo_scripts'));
			}
	/** Activate function to store version.	 */
		public function activate(){
			set_transient( '_evo_activation_redirect', 1, 60 * 60 );		
			do_action('eventon_activate');
		}	
		// update function
			public function update(){
				//set_transient( '_evo_activation_redirect', 1, 60 * 60 );		
			}
		
	/** check plugin version **/
		public function verify_plugin_version(){
			
			$plugin_version = $this->version;
				
			// check installed version
			$installed_version = get_option('eventon_plugin_version');
			
			if($installed_version != $plugin_version){
				if (  isset( $_GET['page'] ) && 'eventon' == $_GET['page']   ){
					

					update_option('eventon_plugin_version', $plugin_version);
					wp_safe_redirect( admin_url( 'index.php?page=evo-about&evo-updated=true' ) );
				}
				//set_transient( '_evo_update_redirect', 1, 60 * 60 );
				//update_option( '_evo_updated', 'true');
				
			}else if(!$installed_version ){
				add_option('eventon_plugin_version', $plugin_version);			
			}else{
				update_option('eventon_plugin_version', $plugin_version);			
			}
			
			// delete options saved on previous version
			delete_option('evcal_plugin_version');
		}
	
	public function is_eventon_activated(){
		$licenses =get_option('_evo_licenses');

		if(!empty($licenses)){
			$status = $licenses['eventon']['status'];
			return ($status=='active')? true:false;
		}else{
			return false;
		}
		
	}
	
	// deactivate eventon
		public function deactivate(){
			//delete_option('evcal_options');		
			do_action('eventon_deactivate');
		}
	
	/** Ensure theme and server variable compatibility and setup image sizes.. */
		public function setup_environment() {
			// Post thumbnail support
			if ( ! current_theme_supports( 'post-thumbnails', 'ajde_events' ) ) {
				add_theme_support( 'post-thumbnails' );
				remove_post_type_support( 'post', 'thumbnail' );
				remove_post_type_support( 'page', 'thumbnail' );
			} else {
				add_post_type_support( 'ajde_events', 'thumbnail' );
			}
		}
		
	/** LOAD Backender UI and functionalities for settings. */
		public function load_ajde_backender(){			
			global $ajde;
			$ajde->load_ajde_backender();
		}	
		public function enqueue_backender_styles(){
			global $ajde;
			$ajde->load_ajde_backender();
		}
		public function register_backender_scripts(){
			global $ajde;
			$ajde->load_ajde_backender();			
		}
	
	
	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 * Admin Locale. Looks in:
	 * - WP_LANG_DIR/eventon/eventon-admin-LOCALE.mo
	 * - WP_LANG_DIR/plugins/eventon-admin-LOCALE.mo
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'eventon' );
		
		load_textdomain( 'eventon', WP_LANG_DIR . "/eventon/eventon-admin-".$locale.".mo" );
		load_textdomain( 'eventon', WP_LANG_DIR . "/plugins/eventon-admin-".$locale.".mo" );
		
		
		if ( is_admin() ) {
			load_plugin_textdomain( 'eventon', false, plugin_basename( dirname( __FILE__ ) ) . "/lang" );
		}

		// frontend
		/*
			this is controlled by myeventon settings> language		
		*/
	}
	
	public function get_current_version(){
		return $this->version;
	}	
	
	// return eventon option settings values **/
		public function evo_get_options($field, $array_field=''){
			if(!empty($array_field)){
				$options = get_option($field);
				$options = $options[$array_field];
			}else{
				$options = get_option($field);
			}		
			return !empty($options)?$options:null;
		}

	// deprecated function after 2.2.12
		public function addon_has_new_version($values){}

}

}// class exists

/** Init eventon class */
$GLOBALS['eventon'] = new EventON();

//include_once('admin/update-notifier.php');	
?>