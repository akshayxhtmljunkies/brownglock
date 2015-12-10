<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPCFF_SalesForce' ) )
{
    class CPCFF_SalesForce extends CPCFF_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-salesforce-20150311";
		protected $name = "CFF - SalesForce";
		protected $description;
		
		public function get_addon_form_settings( $form_id )
		{
			global $wpdb;
			
			// Insertion in database
			if( 
				isset( $_REQUEST[ 'cpcff_salesforce_oid' ] )
			)
			{
				$data = array();
				foreach( $_REQUEST[ 'cpcff_salesforce_attr' ] as $key => $attr )
				{
					$attr = trim( $attr );
					$value = trim( $_REQUEST[ 'cpcff_salesforce_field' ][ $key ] );
					if( !empty( $attr ) && !empty( $value ) ) $data[ $attr ] = $value;
				}

				$wpdb->delete( $wpdb->prefix.$this->form_salesforce_table, array( 'formid' => $form_id ), array( '%d' ) );
				$wpdb->insert( 	$wpdb->prefix.$this->form_salesforce_table, 
								array( 
									'formid' => $form_id,
									'oid'	 => trim( $_REQUEST[ 'cpcff_salesforce_oid' ] ),
									'debug'  => ( isset( $_REQUEST[ 'cpcff_salesforce_debug' ] ) ) ? 1 : 0,
									'debugemail' => trim( $_REQUEST[ 'cpcff_salesforce_debug_email' ] ),
									'data'	 => serialize( $data )	
								), 
								array( '%d', '%s', '%d', '%s', '%s' ) 
							);
			}
			
			$oid  	= '';
			$debug 	= 0;
			$debugemail = '';
			$data 	= array();
			$row 	= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->form_salesforce_table." WHERE formid=%d", $form_id ) );
		
			if( $row )
			{
				$oid 	= $row->oid;
				$debug 	= $row->debug;
				$debugemail = $row->debugemail;
				if( ( $tmp = @unserialize( $row->data ) ) != false ) $data = $tmp;
			}	
			?>
			<div id="metabox_basic_settings" class="postbox" >
				<h3 class='hndle' style="padding:5px;"><span><?php print $this->name; ?></span></h3>
				<div class="inside"> 
					<table cellspacing="0">
						<tr>
							<td style="white-space:nowrap;width:200px;"><?php _e('Organizational ID', 'calculated-fields-form');?>:</td>
							<td><input type="text" name="cpcff_salesforce_oid" value="<?php echo esc_attr( $oid ); ?>" ></td>
						</tr>
						<tr>
							<td style="white-space:nowrap;width:200px;"><?php _e('Enabling debug', 'calculated-fields-form');?>:</td>
							<td><input type="checkbox" name="cpcff_salesforce_debug" <?php echo ( ( $debug ) ? 'CHECKED' : '' ); ?> ></td>
						</tr>
						<tr>
							<td style="white-space:nowrap;width:200px;"><?php _e('Debug email', 'calculated-fields-form');?>:</td>
							<td><input type="text" name="cpcff_salesforce_debug_email" value="<?php echo esc_attr( $debugemail ); ?>" ></td>
						</tr>
						<tr><td colspan="2"><strong><?php _e('Lead Attributes', 'calculated-fields-form');?>:</strong></td></tr>
						<tr>
							<td colspan="2">
								<table>
									<?php
									$c = 1;
									$keys_arr = array_keys( $this->lead_attributes );
									foreach( $data as $attr => $value )
									{
										print '<tr><td style="position:relative;width:200px;">';
										
										$str = 	'<input type="text" name="cpcff_salesforce_attr['.$c.']" value="'.esc_attr( $attr ).'" placeholder="fieldname#" class="cpcff-salesforce-attribute" />';
										$str .= '<select class="cpcff-autocomplete" style="width:100%;"><option value=""></option>';
										foreach( $this->lead_attributes as $lead_attr_key => $lead_attr_title )
										{
											$str .= '<option value="'.esc_attr( $lead_attr_key ).'" '.( ( $lead_attr_key == $attr ) ? 'SELECTED' : '' ).'>'.$lead_attr_title.'</option>';
										}
										$str .= '</select>';
										
										print $str;	
										print '</td><td><input type="text" name="cpcff_salesforce_field['.$c.']" value="'.esc_attr( $value ).'"><input type="button" value="[ X ]" onclick="cpcff_salesforce_removeAttr( this );" /></td></tr>';
										$c++;
									}
									?>
									<tr>
										<td colspan="2">
											<input type="button" value="<?php esc_attr_e('Add attribute', 'calculated-fields-form');?>" onclick="cpcff_salesforce_addAttr( this );" />
										</td>
									</tr>
								</table>
							</td>	
						</tr>		
					</table>
				</div>
				<script>
					var cpcff_salesforce_attr_counter = <?php print $c; ?>;
					function cpcff_salesforce_addAttr( e )
					{
						try
						{
							var $   = jQuery,
								str = $( '<tr><td style="width:200px;position:relative;"><select name="cpcff_salesforce_attr['+cpcff_salesforce_attr_counter+']" style="width:100%;" class="cpcff-autocomplete"><option value=""></option><?php foreach( $this->lead_attributes as $key => $value ) print '<option value="'.esc_attr( $key ).'">'.$value.'</option>'; ?></select></td><td><input type="text" name="cpcff_salesforce_field['+cpcff_salesforce_attr_counter+']" value="" placeholder="fieldname#" ><input type="button" value="[ X ]" onclick="cpcff_salesforce_removeAttr( this );" /></td></tr>' );

							$( e ).closest( 'tr' )
								  .before( str );
								  
							str.find( '.cpcff-autocomplete' ).cpcffautocomplete();
							
							cpcff_salesforce_attr_counter++;
						}
						catch( err ){}	
					}
					
					function cpcff_salesforce_removeAttr( e )
					{
						try
						{
							var $   = jQuery;
							$( e ).closest( 'tr' ).remove();
						}
						catch( err ){}	
					}
					
				</script>
			</div>	
			<?php
		}
		
		/************************ ADDON CODE *****************************/
        /************************ ATTRIBUTES *****************************/
        
		private $form_salesforce_table = 'cp_calculated_fields_form_salesforce';
		private $salesforce_url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
		private $lead_attributes = array( 
			'salutation' 	=> 'Salutation', 
			'title' 		=> 'Title',
			'first_name'	=> 'First Name',
			'last_name'		=> 'Last Name',
			'email'			=> 'Email',
			'phone'			=> 'Phone',
			'mobile'		=> 'Mobile',
			'fax'			=> 'Fax',
			'street'		=> 'Street',
			'city'			=> 'City',
			'state'			=> 'State/Province (text only)',
			'state_code'	=> 'State Code',
			'country'		=> 'Country (text only)',
			'country_code'	=> 'Country Code',
			'zip'			=> 'ZIP',
			'URL'			=> 'URL',
			'description'	=> 'Description',
			'company'		=> 'Company',
			'industry'		=> 'Industry',
			'revenue'		=> 'Annual Revenue',
			'employees'		=> 'Employees',
			'lead_source'	=> 'Lead Source',
			'rating'		=> 'Rating',
			'Campaign_ID'	=> 'Campaign ID',
			'member_status'	=> 'Campaign Member Status',
			'emailOptOut'	=> 'Email Opt Out',
			'faxOptOut'		=> 'Fax Opt Out',
			'doNotCall'		=> 'Do Not Call'
		);
        
        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("The add-on allows create SalesForce leads with the submitted information", 'calculated-fields-form' );
            // Check if the plugin is active
			if( !$this->addon_is_active() ) return;
			
			// Create database tables
			$this->create_tables();
			
			// Load resources
            add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 10 );
			
			// Export the lead
			add_action( 'cp_calculatedfieldsf_process_data', array( &$this, 'export_lead' ) );
        } // End __construct
        
        /************************ PRIVATE METHODS *****************************/
        
		/**
         * Creates the database tables
         */
        private function create_tables()
		{
			global $wpdb;
			$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.$this->form_salesforce_table." (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					oid VARCHAR(250) DEFAULT '' NOT NULL,
					formid INT NOT NULL,
					data text,
					debug INT DEFAULT 0 NOT NULL,
					debugemail VARCHAR(250) DEFAULT '' NOT NULL,
					UNIQUE KEY id (id)
				);";
				
			$wpdb->query($sql);
		}
		
		/************************ PUBLIC METHODS  *****************************/
        
		/**
         * Enqueue all resources: CSS and JS files, required by the Addon
         */ 
        public function enqueue_scripts()
        {
			wp_enqueue_style( 'cpcff_salesforce_addon_css', plugins_url('/salesforce.addon/css/styles.css', __FILE__ ) );
			wp_enqueue_script( 'cpcff_salesforce_addon_js', plugins_url('/salesforce.addon/js/scripts.js',  __FILE__), array( 'jquery', 'jquery-ui-autocomplete' ) );
			
        } // End enqueue_scripts
		
		/**
         * Export the leads to the SalesForce account
         */ 
        public function	export_lead( $params )
		{
			global $wpdb, $wp_version;
			
			$form_id = @intval( $_REQUEST[ 'cp_calculatedfieldsf_id' ] );
			if( $form_id )
			{
				$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->form_salesforce_table." WHERE formid=%d", $form_id ) );
				if( $row && !empty( $row->oid ) )
				{
					$post = array( 'oid' => $row->oid );
					if( $row->debug ) $post[ 'debug' ] = 1;
					if( !empty( $row->debugemail ) ) $post[ 'debugEmail' ] = $row->debugemail;
					
					$attrs = unserialize( $row->data );
					foreach( $attrs as $key => $value )
					{
						$post[ $key ] = ( isset( $params[ $value ] ) ) ? $params[ $value ] : $value;
					}
					
					$body = preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($post) ); // remove php style arrays for array values [1]
					$args = array(
						'body' 		=> $body,
						'headers' 	=> array(
							'Content-Type' => 'application/x-www-form-urlencoded',
							'user-agent' => 'WordPress-to-Lead for calculated-fields-form plugin - WordPress/'.$wp_version.'; '.get_bloginfo( 'url' ),
						),
						'timeout' => 45,
						'sslverify'	=> false,
					);
				
					$result = wp_remote_post( $this->salesforce_url, $args );
				}	
			}	
		} // End export_lead
		
    } // End Class
    
    // Main add-on code
    $cpcff_salesforce_obj = new CPCFF_SalesForce();
    
	// Add addon object to the objects list
	global $cpcff_addons_objs_list;
	$cpcff_addons_objs_list[ $cpcff_salesforce_obj->get_addon_id() ] = $cpcff_salesforce_obj;
}
?>