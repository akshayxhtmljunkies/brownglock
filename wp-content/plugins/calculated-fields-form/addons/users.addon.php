<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPCFF_Users' ) )
{
    class CPCFF_Users extends CPCFF_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-users-20151013";
		protected $name = "CFF - Users Connection";
		protected $description;
		
		public function get_addon_form_settings( $form_id )
		{
			if( isset( $_REQUEST[ 'cpcff_user_registered' ] ) )
			{
				// Save the addon settings
				$settings = array(
					'registered' => ( $_REQUEST[ 'cpcff_user_registered' ] == 1 ) ? 1 : 0,
					'unique'	 => ( empty( $_REQUEST[ 'cpcff_user_unique' ] ) ) ? 0 : 1,
					'messages'	 => array(
						'unique_mssg' 		=> stripcslashes( $_REQUEST[ 'cpcff_user_messages' ][ 'unique_mssg' ] ),
						'privilege_mssg' 	=> stripcslashes( $_REQUEST[ 'cpcff_user_messages' ][ 'privilege_mssg' ] )
					),
					'user_ids'	 => $_REQUEST[ 'cpcff_user_ids' ],
					'user_roles' => $_REQUEST[ 'cpcff_user_roles' ],
					'actions'    => array(
						'delete' => ( !empty( $_REQUEST[ 'cpcff_user_actions' ] ) && !empty( $_REQUEST[ 'cpcff_user_actions' ][ 'delete' ] ) ) ? 1 : 0,
						'edit' 	 => ( !empty( $_REQUEST[ 'cpcff_user_actions' ] ) && !empty( $_REQUEST[ 'cpcff_user_actions' ][ 'edit' ] ) ) ? 1 : 0
					)  					
				);
				update_option( $this->var_name.'_'.$form_id, $settings );
			}
			else
			{	
				$settings = $this->get_form_settings( $form_id, array() );
				if( empty( $settings ) )
				{
					$settings = array(
						'registered' => false,
						'unique'	 => false,
						'messages'	 => array(
							'unique_mssg' 		=> "The form can be submitted only one time by user",
							'privilege_mssg' 	=> "You don't have sufficient privileges to access the form"
						),
						'user_ids'	 => array(),
						'user_roles' => array(),
						'actions'    => array(
							'delete' => 1,
							'edit' 	 => 1
						)  					
					);
				}
			}	
			?>
			<div id="metabox_basic_settings" class="postbox" >
				<h3 class='hndle' style="padding:5px;"><span><?php print $this->name; ?></span></h3>
				<div class="inside"> 
					<table cellspacing="0">
						<tr>
							<td style="white-space:nowrap;width:200px; vertical-align:top;font-weight:bold;"><?php _e('Display the form for', 'calculated-fields-form');?>:</td>
							<td>
								<input type="radio" name="cpcff_user_registered" value="1" <?php if( !empty( $settings[ 'registered' ] ) ) print 'CHECKED'; ?> /> <?php _e( 'Registered users only', 'calculated-fields-form' ); ?><br />
								<input type="radio" name="cpcff_user_registered" value="0" <?php if( empty( $settings[ 'registered'  ] ) ) print 'CHECKED'; ?> /> <?php _e( 'Anonymouse users', 'calculated-fields-form' ); ?>
							</td>
						</tr>
					</table>
					<h3><?php _e( 'For registered users only', 'calculated-fields-form' ); ?></h3>
					<table cellspacing="0">
						<tr>
							<td style="white-space:nowrap;width:200px;vertical-align:top;font-weight:bold;"><?php _e( 'The form may be submitted', 'calculated-fields-form' ); ?>:</td>
							<td>
								<input type="checkbox" name="cpcff_user_unique" value="1" <?php if( !empty( $settings[ 'unique' ] ) ) print 'CHECKED'; ?> /> <?php _e( 'only one time by user' );?>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="padding-top:20px;"><strong><?php _e('The form will be available only for users with the roles', 'calculated-fields-form');?>:</strong></td>
						</tr>
						<tr>
							<td style="white-space:nowrap;width:200px;vertical-align:top;font-weight:bold;"><?php _e( 'Roles', 'calculated-fields-form' ); ?>:</td>
							<td>
								<select MULTIPLE name="cpcff_user_roles[]"  style="min-width:350px;">
								<?php
									// Get the roles list
									global $wp_roles;
									if ( !isset( $wp_roles ) )
									{	
										$wp_roles = new WP_Roles();
									}	
									$roles = $wp_roles->get_names();
									
									foreach( $roles as $_role_value => $_role_name )
									{
										$_selected = '';
										if( 
											!empty( $settings[ 'user_roles' ] ) && 
											is_array( $settings[ 'user_roles' ] ) && 
											in_array( $_role_value, $settings[ 'user_roles' ] ) 
										)
										{
											$_selected = 'SELECTED'; 
										}
										print '<option value="'.$_role_value.'" '.$_selected.'>'.$_role_name.'</option>';
									}
								?>
								</select>
							</td>
						</tr>	
						<tr>
							<td colspan="2" style="padding-top:20px;">
								<strong><?php _e('Or for the specific users', 'calculated-fields-form');?>:</strong><br />
								<em><?php _e("The forms are always available for the website's administrators",'calculated-fields-form'); ?></em>
							</td>
						</tr>
						<tr>
							<td style="white-space:nowrap;width:200px;vertical-align:top;font-weight:bold;"><?php _e( 'Users', 'calculated-fields-form' ); ?>:</td>
							<td>
								<select MULTIPLE name="cpcff_user_ids[]" style="min-width:350px;">
								<?php
									// Get the users list
									$users = get_users( array( 'fields' => array( 'ID', 'display_name' ), 'orderby' => 'display_name' ) );							
									
									foreach( $users as $_user )
									{
										$_selected = '';
										if( 
											!empty( $settings[ 'user_ids' ] ) && 
											is_array( $settings[ 'user_ids' ] ) && 
											in_array( $_user->ID, $settings[ 'user_ids' ] ) 
										)
										{
											$_selected = 'SELECTED'; 
										}
										print '<option value="'.$_user->ID.'" '.$_selected.'>'.$_user->display_name.'</option>';
									}
								
								?>
								</select>
							</td>
						</tr>	
						<tr>
							<td colspan="2" style="padding-top:20px;">
								<strong><?php _e('Actions allowed over the forms submissions by the users', 'calculated-fields-form');?>:</strong><br />
								<?php _e('Uses the corresponding shortcodes to insert the forms submissions in the users profile', 'calculated-fields-form');?>
							</td>
						</tr>
						<tr>
							<td style="white-space:nowrap;width:200px;vertical-align:top;font-weight:bold;"><?php _e( 'Actions', 'calculated-fields-form' ); ?>:</td>
							<td>
								<input type="checkbox" name="cpcff_user_actions[edit]" value="1" <?php if( !empty( $settings[ 'actions' ] ) && !empty( $settings[ 'actions' ][ 'edit' ] ) ) print 'CHECKED'  ?> /> <?php _e('Edit the submitted data (Really is created a new entry, and the previous one is deactivated, but it is yet accessible for the administrators from the messages section)', 'calculated-fields-form'); ?><br />
								<input type="checkbox" name="cpcff_user_actions[delete]" value="1" <?php if( !empty( $settings[ 'actions' ] ) && !empty( $settings[ 'actions' ][ 'delete' ] ) ) print 'CHECKED'  ?> /> <?php _e('Delete the submitted data (The submissions are disabled. The submissions are deleted only from the messages section)', 'calculated-fields-form'); ?>
							</td>
						</tr>	
						<tr>
							<td colspan="2" style="padding-top:20px;">
								<strong><?php _e('Error messages', 'calculated-fields-form');?>:</strong><br />
								<?php _e('The messages are displayed instead of the form: if the user has no sufficient privileges, or if the form may be submitted only one time by registered user, and it has been submitted', 'calculated-fields-form');?>
							</td>
						</tr>
						<tr>
							<td style="white-space:nowrap;width:200px;vertical-align:top;font-weight:bold;"><?php _e( 'Messages', 'calculated-fields-form' ); ?>:</td>
							<td>
								<?php _e('The user has no sufficient privileges' );?>:<br />
								<textarea name="cpcff_user_messages[privilege_mssg]" cols="80" rows="6" ><?php if( !empty( $settings[ 'messages' ] ) && isset( $settings[ 'messages' ][ 'privilege_mssg' ] ) ) print $settings[ 'messages' ][ 'privilege_mssg' ]; ?></textarea><br />
								
								<?php _e('The user has no sufficient privileges' );?>:<br />
								<textarea name="cpcff_user_messages[unique_mssg]"  cols="80" rows="6" ><?php if( !empty( $settings[ 'messages' ] ) && isset( $settings[ 'messages' ][ 'unique_mssg' ] ) ) print $settings[ 'messages' ][ 'unique_mssg' ]; ?></textarea>
							</td>
						</tr>	
					</table>
					<div>The add-on includes a new shortcode: <strong>[CP_CALCULATED_FIELDS_USER_SUBMISSIONS_LIST]</strong>, to display the list of submissions belonging to an user. If the shortcode is inserted without attributes, the list of submissions will include those entries associated to the logged user. This shortcode accepts two attributes: id, for the user's id, and login, for the username (the id attribute has precedence over the login), in whose case the addon will list the submissions of the user selected.</div>
				</div>
			</div>	
			<?php
		}
		
		/************************ ADDON CODE *****************************/
        /************************ ATTRIBUTES *****************************/
        
		private $var_name 			= 'cp_cff_addon_users';
		private $post_user_table 	= 'cp_calculated_fields_user_submission';
		private $forms_settings		= array();
		
        /************************ CONSTRUCT *****************************/
		
        function __construct()
        {
			$this->description = __("The add-on allows restrict the form to: registered users, users with specific roles, or specific users. Furthermore, allows to associate the submitted information with the submitter, if it is a registered user.", 'calculated-fields-form' );
			
            // Check if the plugin is active
			if( !$this->addon_is_active() ) return;
			
			// Check for the existence of the 'refresh_opener' parameter
			if( isset( $_REQUEST[ 'refresh_opener' ] ) )
			{
				?>
				<script>
					window.opener.location.reload();
					window.close();
				</script>
				<?php	
				exit;
			}	
			
			// Check if the submission is being edited
			add_action( 'init', array( &$this, 'edit_submission' ), 1 );
			
			// Create database tables
			$this->create_tables();
			
			// Insert the entry in the database users-submission
			add_action( 'cp_calculatedfieldsf_process_data', array( &$this, 'insert_update' ) );
			
			// Decides if includes the form or a message
			add_filter( 'cpcff_the_form', array( &$this, 'the_form' ), 10, 2 );
			
			// Replace the shortcode with the list of submissions
			add_shortcode( 'CP_CALCULATED_FIELDS_USER_SUBMISSIONS_LIST', array( &$this, 'replace_shortcode' ) );
			
			if( is_admin() )
			{	
				// Deletes an user-submission entry if the administrator deletes it
				add_action( 'cpcff_delete_submission', array( &$this, 'delete' ), 10, 1 );
				
				/************************ MESSAGES & CSV SECTION ************************/
				
				// Insert new headers in the  messages section
				add_action( 'cpcff_messages_filters', array( &$this, 'messages_filters'), 10 );
				
				// Modifies the query for filtering messages to includes the users information
				add_filter( 'cpcff_messages_query', array( &$this, 'messages_query' ), 10, 1 );
				add_filter( 'cpcff_csv_query', array( &$this, 'messages_query' ), 10, 1 );
				
				// Insert new headers in the  messages section
				add_action( 'cpcff_messages_list_header', array( &$this, 'messages_header'), 10 );
				
				// Add the users data to the messages
				add_action( 'cpcff_message_row_data', array( &$this, 'messages_data'), 10, 1 );
			}
        } // End __construct
        
        /************************ PRIVATE METHODS *****************************/
        
		/**
         * Creates the database tables
         */
        private function create_tables()
		{
			global $wpdb;
			$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.$this->post_user_table." (
					submissionid INT NOT NULL,
					userid INT NOT NULL,
					active TINYINT(1) NOT NULL,
					PRIMARY KEY (userid,submissionid)
				);";
				
			$wpdb->query($sql);
		}
		
		/**
		 * Get the forms settings. Checks if the form's settings has been read previously, or get the value from the options
		 * $form_id, integer with the form's id
		 * Returns the form's settings
		 */
		private function get_form_settings( $form_id, $default = false )
		{
			if( empty( $this->forms_settings[ $form_id ] ) )
			{
				$this->forms_settings[ $form_id ] = get_option( $this->var_name.'_'.$form_id, array() );
				if( empty( $this->forms_settings[ $form_id ] ) && $default !== false )
				{
					$this->forms_settings[ $form_id ] = $default;
				}	
				elseif( empty( $this->forms_settings[ $form_id ][ 'actions' ] ) )
				{
					$this->forms_settings[ $form_id ][ 'actions' ] = array( 'delete' => false, 'edit' => false );
				}	
			}
			return $this->forms_settings[ $form_id ];
			
		} // End get_form_settings
		
		/**
		 * Generates an HTML table with all the submissions
		 */ 
		private function user_messages_list( $events, $forms )
		{
			$cellstyle   = 'border:1px solid #F0F0F0;border-top:0;border-left:0;';
			$actionstyle = 'cursor:pointer;color:#00a0d2;';
			
			$str = '
			<div id="dex_printable_contents" style="max-height:500px;overflow:auto;">
				<table cellspacing="0" style="border:0;">
					<thead style="padding-left:7px;font-weight:bold;white-space:nowrap;">
						<tr>
							<th  style="'.$cellstyle.'width:40px;">'.__( 'Id', 'calculated-fields-form' ).'</th>  
							<th  style="'.$cellstyle.'">'.__( 'Form', 'calculated-fields-form' ).'</th>  
							<th  style="'.$cellstyle.'">'.__( 'Date', 'calculated-fields-form' ).'</th>
							<th  style="'.$cellstyle.'border-right:0;">'.__( 'Options', 'calculated-fields-form' ).'</th>	
						</tr>
					</thead>
					<tbody id="the-list">
			';
			
			for( $i = 0; $i < count( $events ); $i++ )
			{
				$this->get_form_settings( $events[ $i ]->formid );
				
				// Check if the submission will be deleted, and if the form has been configured to allow delete the submissions
				if(
					!empty( $_REQUEST[ 'cpcff_addon_user_delete' ] ) &&  
					$_REQUEST[ 'cpcff_addon_user_delete' ] == $events[ $i ]->id &&
					!empty( $this->forms_settings[ $events[ $i ]->formid ] ) &&
					$this->forms_settings[ $events[ $i ]->formid ][ 'actions' ][ 'delete' ]
				)
				{
					$this->deactivate( $_REQUEST[ 'cpcff_addon_user_delete' ] );
					continue;
				}	
				
				$str .= '		
					<tr>
						<td style="'.$cellstyle.'font-weight:bold;">'.$events[$i]->id.'</td>
						<td style="'.$cellstyle.'">'.( ( !empty( $forms[ $events[ $i ]->formid ] ) ) ? $forms[ $events[ $i ]->formid ] : '' ).'</td>
						<td style="'.$cellstyle.'">'.substr($events[$i]->time,0,16).'</td>
						<td style="'.$cellstyle.'border-right:0;white-space:nowrap;">
				';
				
				if( 
					!empty( $this->forms_settings[ $events[ $i ]->formid ] )
				)
				{
					if( $this->forms_settings[ $events[ $i ]->formid ][ 'actions' ][ 'delete' ] )
					{
						$str .= '<span style="'.$actionstyle.'margin-right:5px;" onclick="cpcff_addon_user_deleteMessage('.$events[$i]->id.')">['.__( 'Delete', 'calculated-fields-form' ).']</span>';
					}
					
					if( $this->forms_settings[ $events[ $i ]->formid ][ 'actions' ][ 'edit' ] )
					{
						$str .= '<span style="'.$actionstyle.'" onclick="cpcff_addon_user_editMessage('.$events[$i]->id.')">['.__( 'Update', 'calculated-fields-form' ).']</span>';
					}
				}	
				$str .= '
						</td>
					</tr>
					<tr>
						<td colspan="4" style="'.$cellstyle.'border-right:0;">'.str_replace( array( '\"', "\'", "\n" ), array( '"', "'", "<br />" ), $events[$i]->data ); 
					
				// Add links
				$paypal_post = @unserialize( $events[ $i ]->paypal_post );
				if( $paypal_post !== false )
				{				
					foreach( $paypal_post as $_key => $_value )
					{
						if( strpos( $_key, '_url' ) )
						{
							if( is_array( $_value ) )
							{
								foreach( $_value as $_url )
								{
									$str .= '<p><a href="'.esc_attr( $_url ).'" target="_blank">'.$_url.'</a></p>';
								}
							}	
						}	
					}
				}
				$str .= '
						</td>
					</tr>
				';
			}
			
			$str .= '
					</tbody>
				</table>
			</div>	
			';
			
			// The javascript code
			$str .= '
				<script>
					function cpcff_addon_user_deleteMessage( submission )
					{
						if (confirm("'.esc_attr__( 'Do you want to delete the item?', 'calculated-fields-form' ).'"))
						{        
							jQuery("#cpcff_addon_user_delete_form").remove();
							jQuery("body").append( "<form id=\'cpcff_addon_user_delete_form\' method=\'POST\'><input type=\'hidden\' name=\'cpcff_addon_user_delete\' value=\'"+submission+"\'></form>" );
							jQuery("#cpcff_addon_user_delete_form").submit();
						}
					}
					function cpcff_addon_user_editMessage( submission )
					{
						var w = screen.width*0.8,
							h = screen.height*0.7,
							l = screen.width/2 - w/2,
							t = screen.height/2 - h/2,
							new_window = window.open("", "formpopup", "resizeable,scrollbars,width="+w+",height="+h+",left="+l+",top="+t);
							
						jQuery("#cpcff_addon_user_edit_form").remove();	
						jQuery("body").append( "<form id=\'cpcff_addon_user_edit_form\' method=\'POST\'  target=\'formpopup\'><input type=\'hidden\' name=\'cpcff_addon_user_edit\' value=\'"+submission+"\'></form>" );
						jQuery("#cpcff_addon_user_edit_form").submit();
					}
				</script>
			';
			return $str;
		} // End user_messages_list
		
		/************************ PUBLIC METHODS  *****************************/
        
		/**
		 * Checks if the submission is being edited, 
		 * if it corresponds to the logged user, 
		 * and if the edition action is associated to the form.
		 * Finally, displays the form with the submissions data.
		 */
		public function edit_submission()
		{
			// Edit submission. Checks if the submission belongs to the user, and if the user can edit it.
			if( isset( $_REQUEST[ 'cpcff_addon_user_edit' ] ) )
			{
				global $wpdb;
				
				$submission_id = intval( trim( @$_REQUEST[ 'cpcff_addon_user_edit' ] ) );
				
				// Get logged user
				$user_obj = wp_get_current_user();
				
				if( $user_obj->ID != 0 )
				{
					if( in_array( 'administrator',  $user_obj->roles ) )
					{
						// Get the form's id
						$form_data = $wpdb->get_row( $wpdb->prepare( "SELECT formid, paypal_post FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." WHERE id=%d", $submission_id ) );
					}	
					else
					{
						// Get the form id if exists and the submission belongs to the user
						$form_data = $wpdb->get_row( $wpdb->prepare( "SELECT submission.formid, submission.paypal_post FROM ".$wpdb->prefix.$this->post_user_table." as submission_user, ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." as submission WHERE submission_user.submissionid=%d AND submission_user.userid=%d AND submission.id=submission_user.submissionid AND submission_user.active=1", array( $submission_id, $user_obj->ID ) ) );
					}	
					
					if( !is_null( $form_data ) )
					{	
						
						$form_id = $form_data->formid;
						$submission_data = unserialize( $form_data->paypal_post );
						
						// Checks if the user can edit the submitted data.
						$this->get_form_settings( $form_id );
						if( $this->forms_settings[ $form_id ][ 'actions' ][ 'edit' ] )
						{	
							// Get the submitted data and generate a JSON object
					
							print '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'; 
							print '<script>if(typeof cpcff_default == "undefined") cpcff_default = {};
							cpcff_default[ 1 ] = '.json_encode( $submission_data ).';
							</script>';
							$html_content = cp_calculatedfieldsf_filter_content( array( 'id' => $form_id ) );
							$html_content = preg_replace( '/<\/form>/i', '<input type="hidden" name="cpcff_submission_id" value="'.$submission_id.'"></form>', $html_content );
							print( $html_content );
							wp_footer();
							print '</body></html>';
							exit;
						}	
					}	
				}
			}
		} // End edit_submission
		
		/**
		 * Checks the settings, and decides if display the form or the message
		 * $html_content, the HTML code of form, styles and scripts if corresponds
		 * $form_id, integer number_format
		 *
		 * Returns the same $html_content, or a message if the form is not available
		 */
		public function the_form( $html_content, $form_id )
		{
			global $wpdb;
			
			$settings = $this->get_form_settings( $form_id );
			if( !empty( $settings[ 'registered' ] ) )
			{
				
				$user_obj = wp_get_current_user();

				if( $user_obj->ID == 0 )
				{
					$error_mssg = 'privilege_mssg';
				}
				else
				{
					$roles = $user_obj->roles;
					
					// The current user is an administrator
					if( in_array( 'administrator', $roles ) )
					{
						return $html_content;
					}	

					if( 
						!empty( $settings[ 'unique' ] ) &&
						intval( @$wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM '.$wpdb->prefix.$this->post_user_table.' as addon, '.CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME.' as submissions WHERE addon.userid=%d AND addon.submissionid=submissions.id AND submissions.formid=%d', array( $user_obj->ID, $form_id ) ) ) )
					)
					{
						$error_mssg = 'unique_mssg';
					}
					else
					{	
						// The form is restricted by roles and the current user has at least one of them
						if( 
							!empty( $settings[ 'user_roles' ] ) && 
							count( array_intersect( $settings[ 'user_roles' ], $roles ) ) 
						)
						{
							return $html_content;
						}
						
						// The form is restricted by users and the current user is in the list
						if( 
							!empty( $settings[ 'user_ids' ] ) && 
							in_array( $user_obj->ID, $settings[ 'user_ids' ] ) 
						)
						{
							return $html_content;
						}
						
						// The form is restricted by users or roles and the current user does not satisfy the conditions
						if( 
							!empty( $settings[ 'user_ids' ] ) ||
							!empty( $settings[ 'user_roles' ] ) 
						)
						{
							$error_mssg = 'privilege_mssg';
						}
					}	
				}	
				
				if( !empty( $error_mssg ) )
				{
					return ( !empty( $settings[ 'messages' ] ) && !empty( $settings[ 'messages' ][ $error_mssg ] ) ) ? $settings[ 'messages' ][ $error_mssg ] : '';
				}	
			}	
			return $html_content;
		} // End the_form
		
		/**
		 * Used to modify the URL of the thank you page if the submission is being editd
		 */
		public function get_option( $value, $field )
		{
			if( $field == 'fp_return_page' )
			{
				$value .= ( ( strpos( $value, '?' ) === false ) ? '?' : '&' ).'refresh_opener=1';
			}	
			return $value;
		} // End get_option
		
		/**
         * Associate the submitted information to the user
         */ 
        public function	insert_update( $params )
		{
			global $wpdb;
			if( isset( $params[ 'itemnumber' ] ) )
			{
				$user_obj = wp_get_current_user();
				// Option available only for logged users
				if( $user_obj->ID != 0 )
				{	
					$user_id = $user_obj->ID;
					
					if( isset( $_REQUEST[ 'cpcff_submission_id' ] ) )
					{
						$user_submission = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.$this->post_user_table.' WHERE submissionid=%d', $_REQUEST[ 'cpcff_submission_id' ] ) );

						// If the submission is being edited the orginal submission is deactivated
						if( 
							!empty( $user_submission ) && 
							( in_array( 'administrator',  $user_obj->roles ) || $user_obj->ID == $user_submission->userid ) 
						)
						{
							$user_id = $user_submission->userid;
							$wpdb->update( 
								$wpdb->prefix.$this->post_user_table, 
								array( 'active' => 0 ), 
								array( 'submissionid' => $_REQUEST[ 'cpcff_submission_id' ] ), 
								'%d', '%d' 
							);
						}	
						// Add a filter hook to modify the URL to the thank you page
						add_filter( 'cpcff_get_option', array( &$this, 'get_option' ), 10, 2 );
					}	
					
					@$wpdb->insert( 
						$wpdb->prefix.$this->post_user_table, 
						array( 'submissionid' => $params[ 'itemnumber' ], 'userid' => $user_id, 'active' => 1 ), 
						array( '%d', '%d',  '%d') 
					);
				}	
			}	
		} // End insert
		
		/**
         * Deactivate an user-submission entry
         */ 
        public function	deactivate( $submission_id )
		{
			global $wpdb;
			@$wpdb->update(
				$wpdb->prefix.$this->post_user_table, 
				array( 'active' => 0), 
				array( 'submissionid' => $submission_id), 
				'%d', 
				'%d' 
			);
		} // End deactivate
		
		/**
         * Delete an user-submission entry
         */ 
        public function	delete( $submission_id )
		{
			global $wpdb;
			@$wpdb->delete( 
				$wpdb->prefix.$this->post_user_table, 
				array( 'submissionid' => $submission_id), 
				'%d'
			);
		} // End delete
		
		/**
		 * Replaces the shorcode to display the list of submission related with an user
		 */
		public function replace_shortcode( $atts )
		{
			if( !empty( $atts[ 'id' ] ) || !empty( $atts[ 'login' ] ) )
			{
				if( 
					!empty( $atts[ 'id' ] ) && 
					( $_user_id = intval( @$atts[ 'id' ] ) ) !== 0 &&
					get_user_by( 'ID', $_user_id ) !== false
				)
				{
					$user_id = $_user_id;
				}
				elseif( 
					!empty( $atts[ 'login' ] ) && 
					( $_user_obj = get_user_by( 'login', trim( $atts[ 'login' ] ) ) ) !== false
				)
				{
					$user_id = $_user_obj->ID;
				}
			}	
			else
			{
				$user_id = get_current_user_id();
			}	
				
			if( !empty( $user_id ) )
			{
				global $wpdb;
				$events = $wpdb->get_results( 
					$wpdb->prepare( 
						"SELECT * FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." as submission, ".$wpdb->prefix.$this->post_user_table." as user_submission WHERE submission.id=user_submission.submissionid AND user_submission.userid=%d AND user_submission.active=1 ORDER BY `time` DESC",
						$user_id
					)
				);
				if( count( $events ) )
				{
					$_forms = $wpdb->get_results( "SELECT id,form_name FROM ".$wpdb->prefix.CP_CALCULATEDFIELDSF_FORMS_TABLE );
					$forms = array();
					foreach($_forms as $_form )
					{
						$forms[ $_form->id ] = $_form->form_name;
					}
					return $this->user_messages_list( $events, $forms );
				}	
				else
				{
					return '<div>'.__( 'The list of submissions is empty', 'calculated-fields-form' ).'</div>';
				}	
			}
			else
			{
				return '';
			}	
		} // End replace_shortcode
		
		/************************ MESSAGES & CSV SECTION ************************/
		
		/**
         * Modifies the query of messages for including the information of users
         */ 
        public function	messages_query( $query )
		{
			global $wpdb;
			
			if( preg_match( '/DISTINCT/i', $query ) == 0 )
			{
				$query = preg_replace( '/SELECT/i', 'SELECT DISTINCT ', $query );
			}
			
			$query = preg_replace( '/WHERE/i', ' LEFT JOIN '.$wpdb->prefix.$this->post_user_table.' as user_submission ON user_submission.submissionid='.CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME.'.id LEFT JOIN '.$wpdb->prefix.'users as user ON user_submission.userid=user.ID WHERE', $query );
			
			if( !empty( $_REQUEST[ 'cpcff_addon_user_username' ] ) )
			{
				$username = '%'.$_REQUEST[ 'cpcff_addon_user_username' ].'%';
				$query = preg_replace( 
					'/WHERE/i', 
					$wpdb->prepare( 
						'WHERE (user.user_login LIKE %s OR user.user_nicename LIKE %s) AND ', 
						array( $username, $username ) 
					),
					$query
				);
			}	
			
			return $query;
		} // End messages_query
		
		/**
         * Print new <TH> tags for the header section for the table of messages.
         */ 
        public function	messages_header()
		{
			print '<TH style="padding-left:7px;font-weight:bold;">'.__( 'Registered User', 'calculated-fields-form' ).'</TH>';
		} // End messages_header
		
		/**
         * Print new <TD> tags with the users data in the table of messages.
         */ 
        public function	messages_data( $data )
		{
			$str = '';
			$data = (array)$data;
			if( !empty( $data[ 'userid' ] ) )
			{
				$str = '<a href="'.get_edit_user_link( $data[ 'userid' ] ).'" target="_blank">'.$data[ 'display_name' ].'</a>';
			}	
			print '<TD>'.$str.'</TD>';
		} // End messages_data
		
		/**
         * Includes new fields for filtering in the messages section
         */ 
        public function	messages_filters()
		{
			print '<div style="display:inline-block; white-space:nowrap; margin-right:20px;">'.__( 'Username', 'calculated-fields-form' ).': <input type="text" id="cpcff_addon_user_username" name="cpcff_addon_user_username" value="'.esc_attr( ( !empty( $_REQUEST[ 'cpcff_addon_user_username' ] ) ) ? $_REQUEST[ 'cpcff_addon_user_username' ] : '' ).'" /></div>';
		
		} // End messages_filters
		
		
    } // End Class
    
    // Main add-on code
    $cpcff_users_obj = new CPCFF_Users();
    
	// Add addon object to the objects list
	global $cpcff_addons_objs_list;
	$cpcff_addons_objs_list[ $cpcff_users_obj->get_addon_id() ] = $cpcff_users_obj;
}
?>