<?php
/**
 * 
 * Updater is created for every instance of eventon products
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Classes
 * @version     2.2.24
 */
 
class evo_updater{
   
	/** The plugin current version*/
    public $current_version;
	
    /** Plugin Slug (plugin_directory/plugin_file.php) */
    public $plugin_slug;
     /** Plugin name (plugin_file) */
    public $slug;
    public $remote_version;

    public $api_url;
  
    public $error_code ='00';	
	public $transient;
		
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     */
    function __construct($args){
        // Set the class public variables
        $this->current_version = $args['version'];
        $this->plugin_slug = $args['plugin_slug'];
       	$this->slug = $args['slug'];

        require_once(AJDE_EVCAL_PATH.'/includes/admin/class-evo-product.php');

    	$this->product = new evo_product(array(
    		'name'=>$args['name'],
    		'slug'=>$this->slug,
    		'version'=>$args['version'],
    		'guide_file'=>(!empty($args['guide_file'])? $args['guide_file']: null),
    	));

        // get api url
        $rand = rand(1,5);
        $this->api_url= 'http://get.myeventon.com/index_'.$rand.'.php';

		$this->init();
    }

    // Initiate everything
	    public function init(){
	    	
	    	// define the alternative API for updating checking
	        add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));

	        // Define the alternative response for information checking
	        add_filter('plugins_api', array(&$this, 'evo_check_info'), 10, 3);					
			// show new update notices		
			$this->new_update_notices();

	    	// update current of the product to product data
	    	$this->product->update_field($this->slug,'version', $this->current_version);
	    }

    // Add our self-hosted autoupdate plugin to the filter transient 
	    public function check_update($transient){  

	        // Get the remote version 
	        $this->remote_version = $this->get_remote_version();

	        // If a newer version is available, add the update
	        if (version_compare($this->current_version, $this->remote_version, '<')) {
	            $obj = new stdClass();
	            $obj->slug = $this->slug;
	            $obj->new_version = $this->remote_version;
	            $obj->url = $this->api_url;
	            $obj->package = $this->get_package_download_url();
	            $transient->response[$this->plugin_slug] = $obj;
	        }			
			
			return $transient;			
	    }

	// Custom update notice message -- if updates are avialable
		// CHECK for new update and if there are any show custom update notice message
		    public function new_update_notices(){
		    	$remot_version = $this->remote_version;
		    	if(version_compare($this->current_version, $remot_version, '<')){
					global $pagenow;

				    if( $pagenow == 'plugins.php' ){	       
				        add_action( 'in_plugin_update_message-' . $this->plugin_slug, array($this, 'in_plugin_update_message'), 10, 2 );
				       
				    }				
				}
		    }	
		// custom update notification message		
			function in_plugin_update_message($plugin_data, $r ){		    
			    ob_start();

			    // main eventon plugin
			    if($this->slug=='eventon'):
			    	?>
					<div class="evo-plugin-update-info">
						<p><strong>NOTE:</strong> You can activate your copy to get auto updates. <a href='http://www.myeventon.com/documentation/how-to-find-eventon-license-key/' target='_blank'>How to find eventON license key</a><br/>When you update eventON please be sure to clear all your website and browser cache to reflect style and javascript changes we have made.</p>
					</div>
			    <?php
			    	// addon
			    	else:
			   	?>
					<div class="evo-plugin-update-info">
						<p><strong>NOTE:</strong> You can activate your copy to get auto updates or you can grab the new update from <a href='http://www.myeventon.com/my-account' target='_blank'>myeventon.com</a></p>
					</div>
			   	<?php
			   	endif;

			    echo ob_get_clean();
			}

	// Get version information
		public function evo_check_info($false, $action, $args){
			if(!empty($args->slug)){
				// if the slug matches eventon product slug
				if ($args->slug === $this->slug) {  
		            $information = $this->getRemote_information($args);  
		            return $information;  
		        }  
		    }
	        return $false;
		}
		
    // Add self-hosted description to the filter
	    public function getRemote_information( $args){
			global $wp_version; 
			
			/*
			$plugin_info = get_site_transient('update_plugins');
			$current_version = $plugin_info->checked[$this->plugin_slug];
			*/
			$args->version = $this->current_version;
			
			$request_string = array(
					'body' => array(
						'action' => 'plugin_information', 
						'request' => serialize($args),
						'api-key' => md5(get_bloginfo('url'))
					),
					'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
				);
			
			$request = wp_remote_post($this->api_url, $request_string);

	        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {  	            
				$result = unserialize($request['body']);
				$result->download_link = $this->get_package_download_url();
				
				return  $result;
	        }  
	        return false; 
	    }
	
    // Return the remote version 
	    public function get_remote_version(){
	    	// if ok to check version remotely
	    	if($this->product->can_check_remotely()){
	    		global $wp_version;

	    		// update the last check time 
	    		$this->product->update_lastchecked();
			
				$args = array('slug' => $this->slug);
				$request_string = array(
					'body' => array(
						'action' => 'evo_latest_version', 
						'request' => serialize($args),
						'api-key' => md5(get_bloginfo('url'))
					),
					'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
				);				
			
		        $request = wp_remote_post($this->api_url, $request_string);
		        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
		            return $request['body'];
		        }else{
		        	// get locally saved remote version
	    			return $this->product->get_remote_version();
		        }
	    	}else{
	    		// get locally saved remote version
	    		return $this->product->get_remote_version();
	    	}				        
	    }
		
	// GET download URL for product update package
		function get_package_download_url(){
			$license = $this->product->get_license();
			
			if(!$license && $this->product->is_activated()) {
				return false;
			}else{
				global $wp_version;
				$status = $this->product->get_license_status();
				
				// if not activated and doesnt have key then dont waste remote trying
				if($status && $status=='active'){
					$args = array(
						'slug' => $this->slug,
						'key'=>$license,
						'type'=> ( ($this->slug=='eventon')? 'main':'addon'),
					);
					$request_string = array(
						'body' => array(
							'action' => 'get_download_link', 
							'request' => serialize($args),
							'api-key' => md5(get_bloginfo('url'))
						),
						'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
					);					
				
					$request = wp_remote_post($this->api_url, $request_string);
					if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
						return $request['body'];
					}
					return false;
				}else{
					// inactive status
					return false;
				}			
			}
		}

	// Verify License
	// @version 2.2.24
		public function verify_product_license($args){

			if($args['slug']=='eventon'){
				$api_key = 'vzfrb2suklzlq3r339k5t0r3ktemw7zi';
				$api_username ='ashanjay';

				$url = 'http://marketplace.envato.com/api/edge/'.$api_username.'/'.$api_key.'/verify-purchase:'.$args['key'].'.json';
				return $url;
			}else{
				// for addons
				
				$instance = !empty($args['instance'])?$args['instance']:1;
				
				$url='http://www.myeventon.com/woocommerce/?wc-api=software-api&request=activation&email='.$args['email'].'&licence_key='.$args['key'].'&product_id='.$args['product_id'].'&instance='.$instance;
				
				//echo $url;

				$request = wp_remote_get($url);

				if (!is_wp_error($request) && $request['response']['code']===200) { 

					$result = (!empty($request['body']))? json_decode($request['body']): $request; 
					//update_option('test1', json_decode($result));
					return $result;
				}else{	
					return false;
				}
			}	
		}
		
	
	// Check whether a product is activated
		public function is_activated(){
			return $this->product->is_activated();
		}

	// DEPRECATED functions since 2.2.24	
		// license verification via remote
			public function _verify_license_key($slug='', $key=''){
				global $eventon;
				$debug = false;

				$slug = (!empty($slug))? $slug: $this->slug;
				$saved_key = (!empty($key) )? $key: $this->product->get_license();
				
				if($saved_key!=false ){		
								
					global $wp_version;
					$siteurl = get_bloginfo('url');
					
					$args = array(
						'slug' => $this->slug,
						'key'=>$saved_key,
						'server'=>$_SERVER['SERVER_NAME'],
						'siteurl'=>$siteurl,
						'evoversion'=>$eventon->version,
					);

					$request_string = array(
						'body' => array(
							'action' => 'verify_envato_purchase', 
							'request' => serialize($args),
							'api-key' => md5(get_bloginfo('url'))
						),
						'user-agent' => 'WordPress/' . $wp_version . '; ' . $siteurl
					);				
				
					$request = wp_remote_post($this->api_url, $request_string);

					//print_r($request_string);
					//print_r($this->api_url);
					//print_r($request);
					//return $request;

					$backupmethod = false;

					echo ($debug)? 1:null;
						
					// wp_remote_post() works and return license validity
					if (!is_wp_error($request) ) {

						if( isset($request['response']) && $request['response']['code']=== 200 ){
							save_evoOPT('1', 'wp_remote_post','worked'); // record wp_remote_post status
							
							$license_check_status =  $request['body'];
							echo ($debug)? '-2':null;

							// if validation return 1 or if error code returned
							return ($license_check_status==1)? true:$license_check_status;
						
						}else{ // if wp_remote_post doesnt work
							$backupmethod = true;
							save_evoOPT('1', 'wp_remote_post','didnt_work'); // record wp_remote_post status
							echo ($debug)? '-3':null;
						}					
					}else{
						$backupmethod = true;
						save_evoOPT('1', 'wp_remote_post','didnt_work'); // record wp_remote_post status
						echo ($debug)? '-4':null;
					}

					// try remote get
					if($backupmethod){
						save_evoOPT('1', 'wp_remote_post','didnt_work'); // record wp_remote_post status
						
						//$wp_remote_test = evo_wp_remote_test('post');

						$url = 'http://update.myeventon.com/index.php?action=verify_envato_purchase&type=get';
						foreach($args as $f=>$v){
							$url .= '&'.$f.'='.$v;	
						}

						//
						$request = wp_remote_get($url);
						//print_r($request);

						if (!is_wp_error($request) && $request['response']['code']===200) { 
							save_evoOPT('1', 'wp_remote_get','worked');
							$license_check_status =  $request['body'];		

							echo ($debug)? '-5':null;

							// if validation return 1 or if error code returned
							return ($license_check_status==1)? true:$license_check_status;
						}else{// get didnt work
							save_evoOPT('1', 'wp_remote_get','didnt_worked');
							$this->error_code = '09';	
							$api_key = 'vzfrb2suklzlq3r339k5t0r3ktemw7zi';
							$api_username ='ashanjay';

							$url = 'http://marketplace.envato.com/api/edge/'.$api_username.'/'.$api_key.'/verify-purchase:'.$key.'.json';

							echo ($debug)? '-6':null;

							return $url;
						}
					}
				}	
			}
		// Deactivate eventon license
			public function deactivate_eventon_license(){
				
				global $eventon;
				$slug = 'eventon';

				$evoLicense = $this->product->get_product_array('eventon');
				
				// if there is saved license
				if($evoLicense!=false ){		
								
					global $wp_version;
					
					// if license was activated locally
					if(!empty($evoLicense['activatedtype']) && $evoLicense['activatedtype']=='locally'){
						return $this->product->deactivate($slug);

					// for licenses activated remotely
					}else{ 
						$args = array(	'key'=>$evoLicense['key'],);
						$request_string = array(
							'body' => array(
								'action' => 'deactivate_license', 
								'request' => serialize($args),
								'api-key' => md5(get_bloginfo('url'))
							),
							'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
						);
						
					
						$request = wp_remote_post($this->api_url, $request_string);
						if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
							$result =  $request['body'];
							
							// successfully inactive license
							if($result==1){
								$licenses =get_option('_evo_licenses');
													
								if(!empty($licenses) && count($licenses)>0 && !empty($licenses[$slug])){

									$new_lic = $licenses;
									unset($new_lic[$slug]['key']);
									$new_lic[$slug]['status']='inactive';
									
									update_option('_evo_licenses',$new_lic);

									return $new_lic;

								}else{	return false;	}
							}else{ 
								$this->error_code = '07'; 
								return false;}					
						}	
					}
							
				}else{
					$this->error_code = '06';
					return false;
				}	
				
			}

	// error code decipher
		public function error_code_($code=''){
			$code = (!empty($code))? $code: $this->error_code;
			$array = array(
				"00"=>'',
				'01'=>"No data returned from envato API",
				"02"=>'Your license is not a valid one!, please check and try again.',
				"03"=>'envato verification API is busy at moment, please try later.',
				"04"=>'This license is already registered with a different site.',
				"05"=>'Your EventON version is older than 2.2.17.',
				"06"=>'Eventon license key not passed correct!',
				"07"=>'Could not deactivate eventON license from remote server',
				'08'=>'http request failed, connection time out. Please contact your web provider!',
				'09'=>'wp_remote_post() method did not work to verify licenses, trying a backup method now..',


				'10'=>'License key is not in valid format, please try again.',
				'11'=>'Could not verify. Server might be busy, please try again LATER!',
				'12'=>'Activated successfully and synced w/ eventon server!',
				'13'=>'Remote validation did not work, but we have activated your copy within your site!',

				'101'=>'Invalid license key!',
				'102'=>'Addon has been deactivated!',
				'103'=>'You have exceeded maxium number of activations!',
				'104'=>'Invalid instance ID!',
				'105'=>'Invalid security key!',
				'100'=>'Invalid request!',
			);
			return $array[$code];
		}
	

}

