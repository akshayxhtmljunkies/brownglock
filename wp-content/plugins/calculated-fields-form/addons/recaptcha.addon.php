<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPCFF_reCAPTCHA' ) )
{
    class CPCFF_reCAPTCHA extends CPCFF_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-recaptcha-20151106";
		protected $name = "CFF - reCAPTCHA";
		protected $description;
		
		public function get_addon_settings()
		{
			if( isset( $_REQUEST[ 'cpcff_recaptcha' ] ) )
			{	
				check_admin_referer( 'session_id_'.session_id(), '_cpcff_nonce' );
				update_option( 'cpcff_recaptcha_sitekey', trim( $_REQUEST[ 'cpcff_recaptcha_sitekey' ] ) );
				update_option( 'cpcff_recaptcha_secretkey', trim( $_REQUEST[ 'cpcff_recaptcha_secretkey' ] ) );
			}	
			?>
			<form method="post">
				<div id="metabox_basic_settings" class="postbox" >
					<h3 class='hndle' style="padding:5px;"><span><?php print $this->name; ?></span></h3>
					<div class="inside"> 
						<table cellspacing="0" style="width:100%;">
							<tr>
								<td style="white-space:nowrap;width:200px;"><?php _e('Site Key', 'calculated-fields-form');?>:</td>
								<td>
									<input type="text" name="cpcff_recaptcha_sitekey" value="<?php echo ( ( $key = get_option( 'cpcff_recaptcha_sitekey' ) ) !== false ) ? $key : ''; ?>"  style="width:80%;" />
								</td>
							</tr>
							<tr>
								<td style="white-space:nowrap;width:200px;"><?php _e('Secret Key', 'calculated-fields-form');?>:</td>
								<td>
									<input type="text" name="cpcff_recaptcha_secretkey" value="<?php echo ( ( $key = get_option( 'cpcff_recaptcha_secretkey' ) ) !== false ) ? $key : ''; ?>" style="width:80%;" />
								</td>
							</tr>
						</table>
						<input type="submit" name="Save settings" />
					</div>
					<input type="hidden" name="cpcff_recaptcha" value="1" />
					<input type="hidden" name="_cpcff_nonce" value="<?php echo wp_create_nonce( 'session_id_'.session_id() ); ?>" />
				</div>
			</form>
			<?php
		}
		
		/************************ ADDON CODE *****************************/
        /************************ ATTRIBUTES *****************************/
        
		private $_recaptcha_inserted = false;
		private $_sitekey 	= '';
		private $_secretkey = '';
		
        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("The add-on allows to protect the forms with reCAPTCHA service of Google", 'calculated-fields-form');
			
            // Check if the plugin is active
			if( !$this->addon_is_active() ) return;
			
			// TO-DO
			// Insert action or filter for checking the captcha in the submitted information
			
			// If reCAPTCHA is enabled do not include the common captcha in the form
			add_filter( 'cpcff_get_option', array( &$this, 'get_form_options' ), 10, 3 );
			
			if( !is_admin() )
			{	
				if( $this->apply_addon() !== false )
				{	
					// Inserts the SCRIPT tag to import the reCAPTCHA on webpage
					add_action( 'wp_footer', array( &$this, 'insert_script' ), 99 );
					
					// Inserts the reCAPTCHA field in the form
					add_filter( 'cpcff_the_form', array( &$this, 'insert_recaptcha'), 99 );
					
					// Validate the form's submission
					add_filter( 'cpcff_valid_submission', array( &$this, 'validate_form' ) );
				}	
			}	
        } // End __construct
        
        /************************ PRIVATE METHODS *****************************/
		
		/**
		 * Check if the API keys have been defined and return the pair of keys or false 
		 */
        private function apply_addon()
		{
			if( 
				( $sitekey   = get_option( 'cpcff_recaptcha_sitekey' ) ) !== false && !empty( $sitekey ) &&
				( $secretkey = get_option( 'cpcff_recaptcha_secretkey' ) ) !== false && !empty( $secretkey )
			)
			{
				$this->_sitekey   = $sitekey;
				$this->_secretkey = $secretkey;
				
				return true;
			}
			return false;
		
		} // End apply_addon
		
		/************************ PUBLIC METHODS  *****************************/
        
		/**
         * Check if the reCAPTCHA is used in the form, and inserts the SCRIPT tag that includes its code
         */ 
        public function	insert_script( $params )
		{
			if( $this->_recaptcha_inserted )
				print '<script src="//www.google.com/recaptcha/api.js"></script>';	
		} // End insert_script
		
		/**
         * Check if the reCAPTCHA is used in the form, and inserts the reCAPTCHA tag
         */ 
        public function	insert_recaptcha( $form_code )
		{
			$this->_recaptcha_inserted = true;
			return preg_replace( '/<\/form>/i', '<div style="margin-top:20px;" class="g-recaptcha" data-sitekey="'.$this->_sitekey.'"></div></form>', $form_code );	
		} // End insert_script
		
		/**
         * Check if the reCAPTCHA is valid and return a boolean
         */ 
        public function	validate_form()
		{

			if( isset( $_POST[ 'g-recaptcha-response' ] ) )
			{	
				$response = wp_remote_post( 
					'https://www.google.com/recaptcha/api/siteverify', 
					array(
						'body' => array(
							'secret' 	=> $this->_secretkey,
							'response' 	=> $_POST[ 'g-recaptcha-response' ]
						)	
					) 
				);
				
				if( !is_wp_error( $response ) )
				{
					$response = json_decode( $response[ 'body' ] );
					if( !is_null( $response ) && isset( $response->success ) && $response->success )
					{
						return true;
					}	
						
				}	
					
			}
			return false;	
			
		} // End cpcff_valid_submission
		
		/**
         * Corrects the form options
         */
        public function get_form_options( $value, $field, $id )
        {
			if( $field == 'cv_enable_captcha' && $this->apply_addon() !== false ){
				return 0;
			}	
            return $value;    
		} // End get_form_options
		
    } // End Class
    
    // Main add-on code
    $cpcff_recaptcha_obj = new CPCFF_reCAPTCHA();
    
	// Add addon object to the objects list
	global $cpcff_addons_objs_list;
	$cpcff_addons_objs_list[ $cpcff_recaptcha_obj->get_addon_id() ] = $cpcff_recaptcha_obj;
}
?>