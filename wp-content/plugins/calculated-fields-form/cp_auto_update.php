<?php
add_action( 'admin_init', 'cpcff_active_auto_update', 1 );
if( !function_exists( 'cpcff_active_auto_update' ) )
{	
	function cpcff_active_auto_update()
	{
		$plugin_data 		= get_plugin_data( dirname( __FILE__ ).'/cp_calculatedfieldsf_dev.php' );
		$plugin_version 	= $plugin_data[ 'Version' ];
		$plugin_slug 		= plugin_basename( dirname( __FILE__ ).'/cp_calculatedfieldsf_dev.php' );
		$plugin_remote_path = 'http://wordpress.dwbooster.com/updates/update.php';
		$admin_action		= 'cp_calculatedfieldsf_register_user';
		new cpAutoUpdateClss( $plugin_version, $plugin_remote_path, $plugin_slug, $admin_action );
	}
}

//-------------------Auto-Update-Class-----------------
if( !class_exists( 'cpAutoUpdateClss' ) )
{
	class cpAutoUpdateClss
	{
		/**
		 * The plugin current version
		 * @var string
		 */
		public $current_version;
 
		/**
		 * The plugin remote update path
		 * @var string
		 */
		public $update_path;
 
		/**
		 * Plugin Slug (plugin_directory/plugin_file.php)
		 * @var string
		 */
		public $plugin_slug;
 
		/**
		 * Plugin name (plugin_file)
		 * @var string
		 */
		public $slug;
 
		/**
		 * Registered buyer
		 * @var string
		 */
		public $registered_buyer;
 
		/**
		 * Initialize a new instance of the WordPress Auto-Update class
		 * @param string $current_version
		 * @param string $update_path
		 * @param string $plugin_slug
		 * @param string $admin_action
		 */
		function __construct( $current_version, $update_path, $plugin_slug, $admin_action )
		{
			// Set the class public variables
			$this->current_version = $current_version;
			$this->update_path = $update_path;
			$this->plugin_slug = $plugin_slug;
			list( $t1, $t2 ) = explode( '/', $plugin_slug );
			$this->slug = str_replace( '.php', '', $t2 );
 
			// define the alternative API for updating checking
			add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_update' ) );
		 
			// Define the alternative response for information checking
			add_filter( 'plugins_api', array( &$this, 'check_info' ), 10, 3 );
			
			// Allows to use external resources host
			add_filter( 'http_request_host_is_external', array( &$this, 'allow_external_host' ), 10, 3 );
			
			// Adds an action to display a form to register the plugin
			add_action( $admin_action, array( &$this, 'register_plugin' ) );
			
			// Get the registered buyer
			$this->registered_buyer = trim( get_option( $this->slug.'buyer_email', '' ) );
		}
		
		/**
		 * Allows register the plugin with the email use for selling it
		 */
		public function register_plugin()
		{
			$field = $this->slug.'buyer_email';
			if( isset( $_REQUEST[ $field ] ) )
			{
				$this->registered_buyer = trim( $_REQUEST[ $field ] );
				update_option( $field,  $this->registered_buyer );
			}
			
			print '<input type="text" id="'.$field.'" name="'.$field.'" value="'.esc_attr( $this->registered_buyer ).'" />';
		}
		
		/**
		 * Add our self-hosted autoupdate plugin to the filter transient
		 *
		 * @param $transient
		 * @return object $ transient
		 */
		public function check_update( $transient )
		{
			if( empty( $transient->checked ) )
			{
				return $transient;
			}
 
			// Get the remote version
			$remote_version = $this->getRemote_version();
 
			// If a newer version is available, add the update
			if( version_compare( $this->current_version, $remote_version, '<' ) ) 
			{
				$obj = new stdClass();
				$obj->slug = $this->slug;
				$obj->new_version = $remote_version;
				$obj->url = $this->update_path.'?user='.$this->registered_buyer.'&slug='.$this->slug;
				$obj->package = $obj->url;
				$transient->response[ $this->plugin_slug ] = $obj;
			}
			return $transient;
		}
 
		/**
		 * Add our self-hosted description to the filter
		 *
		 * @param boolean $false
		 * @param array $action
		 * @param object $arg
		 * @return bool|object
		 */
		public function check_info( $false, $action, $arg )
		{
			if( array_key_exists( 'slug' , (array) $arg ) && array_key_exists( 'slug' , (array) $this ) && $arg->slug === $this->slug ) 
			{
				$information = $this->getRemote_information();
				return $information;
			}
			return false;
		}
		
		function allow_external_host( $allow, $host, $url ) 
		{
			$allow = true;	
			return $allow;
		}
		
		/**
		 * Return the remote version
		 * @return string $remote_version
		 */
		public function getRemote_version()
		{
			if( !empty( $this->registered_buyer ) )
			{	
				$request = wp_remote_post( $this->update_path, array( 'body' => array( 'action' => 'version', 'user' => $this->registered_buyer, 'slug' => $this->slug ) ) );
				if( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) 
				{
					return $request[ 'body' ];
				}
			}	
			return false;
		}
 
		/**
		 * Get information about the remote version
		 * @return bool|object
		 */
		public function getRemote_information()
		{
			if( !empty( $this->registered_buyer ) )
			{	
				$request = wp_remote_post( $this->update_path, array( 'body' => array( 'action' => 'info', 'user' => $this->registered_buyer, 'slug' => $this->slug ) ) );
				
				if( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) 
				{
					return unserialize( $request[ 'body' ] );
				}
			}	
			return false;
		}
 
		/**
		 * Return the status of the plugin licensing
		 * @return boolean $remote_license
		 */
		public function getRemote_license()
		{
			if( !empty( $this->registered_buyer ) )
			{	
				$request = wp_remote_post( $this->update_path, array( 'body' => array( 'action' => 'license', 'user' => $this->registered_buyer, 'slug' => $this->slug ) ) );
				
				if( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) 
				{
					return $request['body'];
				}
			}	
			return false;
		}
	} // End cpAutoUpdateClss Class	
}	

?>