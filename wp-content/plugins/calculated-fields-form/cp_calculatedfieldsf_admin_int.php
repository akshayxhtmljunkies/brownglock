<?php

if ( !is_admin() ) 
{
    _e( 'Direct access not allowed.', 'calculated-fields-form' );
    exit;
}

check_admin_referer( 'session_id_'.session_id(), '_cpcff_nonce' );
wp_enqueue_media();

if (!defined('CP_CALCULATEDFIELDSF_ID'))
    define ('CP_CALCULATEDFIELDSF_ID',intval($_GET["cal"]));
    

define('CP_CALCULATEDFIELDSF_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
define('CP_CALCULATEDFIELDSF_DEFAULT_fp_destination_emails', CP_CALCULATEDFIELDSF_DEFAULT_fp_from_email);

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_calculatedfieldsf_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>".__( 'Settings saved', 'calculated-fields-form' )."</strong></p></div>";

    
global $cpcff_default_texts_array;
$cpcff_texts_array = cp_calculatedfieldsf_get_option( 'vs_all_texts', $cpcff_default_texts_array );
$cpcff_texts_array = array_replace_recursive( 
        $cpcff_default_texts_array, 
        ( is_string( $cpcff_texts_array ) && is_array( unserialize( $cpcff_texts_array ) ) ) 
            ? unserialize( $cpcff_texts_array ) 
            : ( ( is_array( $cpcff_texts_array ) ) ? $cpcff_texts_array : array() )
    );

?>
<div class="wrap">
<h1><?php _e( 'Calculated Fields Form', 'calculated-fields-form' ); ?></h1>

<input type="button" name="backbtn" value="<?php esc_attr_e( 'Back to items list...', 'calculated-fields-form' ); ?>" onclick="document.location='options-general.php?page=cp_calculated_fields_form';">
<br /><br />

<form method="post" action="" name="cpformconf"> 
<input type="hidden" name="_cpcff_nonce" value="<?php echo wp_create_nonce( 'session_id_'.session_id() ); ?>" />
<input name="cp_calculatedfieldsf_post_options" type="hidden" value="1" />
<input name="cp_calculatedfieldsf_id" type="hidden" value="<?php echo CP_CALCULATEDFIELDSF_ID; ?>" />

   
<div id="normal-sortables" class="meta-box-sortables">

 <h2><?php _e( 'Form Settings', 'calculated-fields-form' ); ?>:</h2>
 <hr />
 <div><?php _e( '* Different form styles available on the tab Form Settings &gt;&gt; Form Template', 'calculated-fields-form' ); ?></div>
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Form Builder', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">   
     <div class="form-builder-error-messages"><?php
        global $cff_structure_error;
        if( !empty( $cff_structure_error ) )
        {
            echo $cff_structure_error;
        }
     ?></div>
     <input type="hidden" name="form_structure_crc" id="form_structure_crc" />
     <input type="hidden" name="form_structure" id="form_structure" value="<?php print esc_attr( json_encode( cp_calculatedfieldsf_get_option( 'form_structure', CP_CALCULATEDFIELDSF_DEFAULT_form_structure ) ) ); ?>" />
     <input type="hidden" name="templates" id="templates" value="<?php print esc_attr( json_encode( cp_calculatedfieldsf_available_templates() ) ); ?>" />
     
     <link href="<?php echo plugins_url('css/style.css', __FILE__); ?>" type="text/css" rel="stylesheet" />   
     <link href="<?php echo plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__); ?>" type="text/css" rel="stylesheet" />   
     <script type="text/javascript">
		try
		{
			// Flags
			var calculatedFieldsForm_first   = false,
				calculatedFieldsForm_counter = 10;
			
			function calculatedFieldsFormReady()
			{
				// Coupon code
				$calculatedfieldsfQuery("#dex_dc_expires").datepicker({ dateFormat: 'yy-mm-dd' }); 	
				
				$calculatedfieldsfQuery('#dex_nocodes_availmsg').load('<?php echo cp_calculatedfieldsf_get_site_url( true ); ?>/?cp_calculated_fields_form_post=loadcoupons&inAdmin=1&dex_item=<?php echo CP_CALCULATEDFIELDSF_ID; ?>');
				
				$calculatedfieldsfQuery('#dex_dc_subccode').click (
					function() {
					   var code = $calculatedfieldsfQuery('#dex_dc_code').val();
					   var discount = $calculatedfieldsfQuery('#dex_dc_discount').val();
					   var discounttype = $calculatedfieldsfQuery('#dex_dc_discounttype').val();
					   var expires = $calculatedfieldsfQuery('#dex_dc_expires').val();
					   if (code == '') { alert('Please enter a code'); return; }
					   if (parseInt(discount)+"" != discount) { alert('Please numeric discount percent'); return; }
					   if (expires == '') { alert('Please enter an expiration date for the code'); return; }
					   var params = '&cff_add_coupon=1&cff_coupon_expires='+encodeURIComponent(expires)+'&cff_discount='+encodeURIComponent(discount)+'&cff_discounttype='+encodeURIComponent(discounttype)+'&cff_coupon_code='+encodeURIComponent(code);
					   $calculatedfieldsfQuery('#dex_nocodes_availmsg').load('<?php echo cp_calculatedfieldsf_get_site_url( true ); ?>/?cp_calculated_fields_form_post=loadcoupons&inAdmin=1&dex_item=<?php echo CP_CALCULATEDFIELDSF_ID; ?>'+params);
					   $calculatedfieldsfQuery('#dex_dc_code').val();
					}
				);
                                     
				window[ 'dex_delete_coupon' ] = function (id)                             
				{
					$calculatedfieldsfQuery('#dex_nocodes_availmsg').load('<?php echo cp_calculatedfieldsf_get_site_url( true ); ?>/?cp_calculated_fields_form_post=loadcoupons&inAdmin=1&dex_item=<?php echo CP_CALCULATEDFIELDSF_ID; ?>&cff_delete_coupon=1&cff_coupon_code='+id);
				}
				
				// Form builder code
				var f = $calculatedfieldsfQuery("#fbuilder").fbuilder();
				f.fBuild.loadData( "form_structure", "templates" );
				
				$calculatedfieldsfQuery(".itemForm").click(function() {
				   f.fBuild.addItem($calculatedfieldsfQuery(this).attr("id"));
			   });  
			  
			   $calculatedfieldsfQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
			   $calculatedfieldsfQuery( "#fbuilder" ).droppable({
				   accept: ".button",
				   drop: function( event, ui ) {
					   f.fBuild.addItem(ui.draggable.attr("id"));				
				   }
			   });
			};
			
			function calculatedFieldsFormImport()
			{
				if( typeof jQuery == 'undefined' )
				{
					if( !calculatedFieldsForm_first )
					{
						document.write ("<"+"script type='text/javascript' src='<?php print CP_SCHEME; ?>ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></"+"script>");
						document.write ("<"+"script type='text/javascript' src='<?php print CP_SCHEME; ?>ajax.googleapis.com/ajax/libs/jqueryui/1.8.20/jquery-ui.min.js'></"+"script>");
					}
					if( calculatedFieldsForm_counter )
					{	
						calculatedFieldsForm_counter--;
						setTimeout( calculatedFieldsFormImport, 500 );
					}	
				}
				else
				{
					$calculatedfieldsfQuery = jQuery.noConflict();
					window.jQuery = window.$ = jQuery;
					
					var getScript = $calculatedfieldsfQuery.getScript;
					$calculatedfieldsfQuery.getScript = function( resources, callback ) {

						var // reference declaration & localization
						length = resources.length,
						handler = function() { counter++; },
						deferreds = [],
						counter = 0,
						idx = 0;

						for ( ; idx < length; idx++ ) {
							deferreds.push(
								getScript( resources[ idx ], handler )
							);
						}

						$calculatedfieldsfQuery.when.apply( null, deferreds ).then(function() {
							callback && callback();
						});
					};

					$calculatedfieldsfQuery.getScript(
						[
							'<?php echo plugins_url('js/jQuery.stringify.js', __FILE__); ?>',
							'<?php echo plugins_url('js/jquery.validate.js', __FILE__); ?>',
							'<?php echo plugins_url('/js/jquery.caret.js', __FILE__); ?>',
							'<?php echo cp_calculatedfieldsf_get_site_url( true ).'/?cp_cff_resources=admin'; ?>'
						], 
						function() 
						{
							$calculatedfieldsfQuery(document).ready( calculatedFieldsFormReady ).ready();
						}
					);
				}	
			};
		}
		catch( err ){}
        try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {}
	    if (typeof $calculatedfieldsfQuery == 'undefined')
        {
			calculatedFieldsFormImport();
	    }
		else
		{
			$calculatedfieldsfQuery(document).ready( calculatedFieldsFormReady );
		}	
     </script>
     <div style="background:#fafafa;" class="form-builder">
     
         <div class="column ctrlsColumn">
             <div id="tabs">
     			<ul>
     				<li><a href="#tabs-1"><?php _e( 'Add a Field', 'calculated-fields-form' ); ?></a></li>
     				<li><a href="#tabs-2"><?php _e( 'Field Settings', 'calculated-fields-form' ); ?></a></li>
     				<li><a href="#tabs-3"><?php _e( 'Form Settings', 'calculated-fields-form' ); ?></a></li>
     			</ul>
     			<div id="tabs-1">
     			    
     			</div>
     			<div id="tabs-2"></div>
     			<div id="tabs-3"></div>
     		</div>	
         </div>
         <div class="column dashboardColumn padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
         </div>
         <div class="clearer"></div>
         
     </div>        
   
  </div>    
 </div> 
 
 <p class="submit">
	<input type="submit" name="save" id="save" 	 class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'calculated-fields-form' ); ?>"  />
	<input type="button" name="previewbtn" id="previewbtn" class="button-primary" value="<?php esc_attr_e( 'Save & Preview', 'calculated-fields-form' ); ?>" onclick="jQuery.fbuilder.preview( this );" />
 </p>
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Define Texts', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">   
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e( 'Submit button label (text)', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_submitbtn" size="40" value="<?php $label = esc_attr(cp_calculatedfieldsf_get_option('vs_text_submitbtn', 'Submit')); echo ($label==''?'Submit':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <th scope="row"><?php _e( 'Previous button label (text)', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_previousbtn" size="40" value="<?php $label = esc_attr(cp_calculatedfieldsf_get_option('vs_text_previousbtn', 'Previous')); echo ($label==''?'Previous':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <th scope="row"><?php _e( 'Next button label (text)', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_nextbtn" size="40" value="<?php $label = esc_attr(cp_calculatedfieldsf_get_option('vs_text_nextbtn', 'Next')); echo ($label==''?'Next':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <td colspan="2"><?php _e( ' - The  <em>class="pbSubmit"</em> can be used to modify the button styles.', 'calculated-fields-form' ); ?> <br />
        <?php _e( '- The styles can be applied into any of the CSS files of your theme or into the CSS file <em>"calculated-fields-form\css\stylepublic.css"</em>.', 'calculated-fields-form' ); ?> <br />
        <?php _e( '- For further modifications the submit button is located at the end of the file <em>"cp_calculatedfieldsf_public_int.inc.php"</em>.', 'calculated-fields-form' ); ?><br />
        <?php _e( '- For general CSS styles modifications to the form and samples <a href="http://wordpress.dwbooster.com/faq/calculated-fields-form#q82" target="_blank">check this FAQ</a>.', 'calculated-fields-form' ); ?></td>
		</tr>
        <?php
         // Display all other text fields
         foreach( $cpcff_texts_array as $cpcff_text_index => $cpcff_text_attr )
         {
            print '
            <tr valign="top">
                <th scope="row">'.$cpcff_text_attr[ 'label' ].':</th>
                <td><input type="text" name="cpcff_text_array['.$cpcff_text_index.'][text]" size="40" value="'. esc_attr( $cpcff_text_attr[ 'text' ] ).'" /></td>
            </tr>
            ';
         }
        ?>
     </table>
  </div>    
 </div> 
  
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Validation Settings', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e( 'Use Validation?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('vs_use_validation', CP_CALCULATEDFIELDSF_DEFAULT_vs_use_validation); ?>
          <select name="vs_use_validation">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <!--<option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>-->
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is required" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_is_required" size="40" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_is_required', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_is_required)); ?>" /></td>
        </tr>             
         <tr valign="top">
        <th scope="row"><?php _e( '"is email" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_is_email" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_is_email', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_is_email)); ?>" /></td>
        </tr>       
        <tr valign="top">
        <th scope="row"><?php _e( '"is valid captcha" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="cv_text_enter_valid_captcha" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_text_enter_valid_captcha', CP_CALCULATEDFIELDSF_DEFAULT_cv_text_enter_valid_captcha)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( '"is valid date (mm/dd/yyyy)" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_datemmddyyyy" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_datemmddyyyy', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is valid date (dd/mm/yyyy)" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_dateddmmyyyy" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_dateddmmyyyy', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is number" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_number" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_number', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_number)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"only digits" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_digits" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_digits', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_digits)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"under maximum" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_max" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_max', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_max)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"over minimum" text', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="vs_text_min" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('vs_text_min', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>             
     </table>  
  </div>    
 </div>   
 
 <h2><?php _e( 'Form Processing and Payment Settings', 'calculated-fields-form' ); ?>:</h2>
 <hr />

 <div id="metabox_basic_settings" class="postbox">
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Paypal Payment Configuration', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">

    <table class="form-table">
        <tr valign="top">        
        <th scope="row"><?php _e( 'Display submit button?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('enable_submit',''); ?>
          <select name="enable_submit">
           <option value=""<?php if ($option == '') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="no"<?php if ($option == 'no') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>        
          <br />
          <em><?php _e( '* Note: To change the text or design of the submit button go the latest lines of the file "cp_calculatedfieldsf_public_int.inc.php" or ovewrite the CSS styles of the ID #cp_subbtn { }', 'calculated-fields-form' ); ?></em>
        </td>
        </tr>      
    
        <tr valign="top">
        <th scope="row"><?php _e( 'Enable Paypal Payments?', 'calculated-fields-form' ); ?></th>
        <td><select name="enable_paypal" onchange="cff_update_pp_payment_selection();">
             <option value="0" <?php if (!cp_calculatedfieldsf_get_option('enable_paypal',CP_CALCULATEDFIELDSF_DEFAULT_ENABLE_PAYPAL)) echo 'selected'; ?> ><?php _e( 'No', 'calculated-fields-form' ); ?></option>
             <option value="1" <?php if (cp_calculatedfieldsf_get_option('enable_paypal',CP_CALCULATEDFIELDSF_DEFAULT_ENABLE_PAYPAL) == '1') echo 'selected'; ?> ><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
             <option value="2" <?php if (cp_calculatedfieldsf_get_option('enable_paypal',CP_CALCULATEDFIELDSF_DEFAULT_ENABLE_PAYPAL) == '2') echo 'selected'; ?> ><?php _e( 'Optional', 'calculated-fields-form' ); ?></option>
            </select> 
            <br /><em style="font-size:11px;"><?php _e( 'Note: If "Optional" is selected, a radiobutton will appear in the form to select if the payment will be made with PayPal or not.', 'calculated-fields-form' ); ?></em>
            <div id="cff_paypal_options_label" style="display:none;margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:260px;">
              <?php _e( 'Label for the "<strong>Pay with PayPal</strong>" option', 'calculated-fields-form' ); ?>:<br />
              <input type="text" name="enable_paypal_option_yes" size="40" style="width:250px;" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('enable_paypal_option_yes',CP_CALCULATEDFIELDSF_PAYPAL_OPTION_YES)); ?>" />
              <br />
              <?php _e( 'Label for the "<strong>Pay later</strong>" option', 'calculated-fields-form' ); ?>:<br />
              <input type="text" name="enable_paypal_option_no" size="40" style="width:250px;"  value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('enable_paypal_option_no',CP_CALCULATEDFIELDSF_PAYPAL_OPTION_NO)); ?>" />
            </div>
         </td>
        </tr>        
        
        <tr valign="top">        
        <th scope="row"><?php _e( 'When should be sent the notification-confirmation emails?', 'calculated-fields-form' ); ?></th>
        <td><select name="paypal_notiemails">
             <option value="0" <?php if (cp_calculatedfieldsf_get_option('paypal_notiemails','0') != '0') echo 'selected'; ?>><?php _e( 'When paid: AFTER receiving the PayPal payment', 'calculated-fields-form' ); ?></option> 
             <option value="1" <?php if (cp_calculatedfieldsf_get_option('paypal_notiemails','1') == '1') echo 'selected'; ?>><?php _e( 'Always: BEFORE receiving the PayPal payment', 'calculated-fields-form' ); ?></option> 
            </select>
        </td>
        </tr>
        
        <tr valign="top">        
        <th scope="row"><?php _e( 'Paypal Mode', 'calculated-fields-form' ); ?></th>
        <td><select name="paypal_mode">
             <option value="production" <?php if (cp_calculatedfieldsf_get_option('paypal_mode',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_MODE) != 'sandbox') echo 'selected'; ?>><?php _e( 'Production - real payments processed', 'calculated-fields-form' ); ?></option> 
             <option value="sandbox" <?php if (cp_calculatedfieldsf_get_option('paypal_mode',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_MODE) == 'sandbox') echo 'selected'; ?>><?php _e( 'SandBox - PayPal testing sandbox area', 'calculated-fields-form' ); ?></option> 
            </select>
        </td>
        </tr>
    
        <tr valign="top">        
        <th scope="row"><?php _e( 'Paypal email', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="paypal_email" size="40" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('paypal_email',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_EMAIL)); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php _e( 'Request cost', 'calculated-fields-form' ); ?></th>
        <td><select name="request_cost" id="request_cost" def="<?php echo esc_attr(cp_calculatedfieldsf_get_option('request_cost', '')); ?>"></select></td>
        </tr>        
        
        
        <tr valign="top">
        <th scope="row"><?php _e( 'Currency', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="currency" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('currency',CP_CALCULATEDFIELDSF_DEFAULT_CURRENCY)); ?>" /></td>
        </tr>        
        
        <tr valign="top">        
        <th scope="row"><?php _e( 'A $0 amount to pay means', 'calculated-fields-form' ); ?>:</th>
        <td><select name="paypal_zero_payment">
             <option value="0" <?php if (cp_calculatedfieldsf_get_option('paypal_zero_payment',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_ZERO_PAYMENT) != '1') echo 'selected'; ?>><?php _e( 'Let the user enter any amount at PayPal (ex: for a donation)', 'calculated-fields-form' ); ?></option> 
             <option value="1" <?php if (cp_calculatedfieldsf_get_option('paypal_zero_payment',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_ZERO_PAYMENT) == '1') echo 'selected'; ?>><?php _e( 'Don\'t require any payment. Form is submitted skiping the PayPal page.', 'calculated-fields-form' ); ?></option>
			</select>
        </td>
        </tr>  
        
        <tr valign="top">        
        <th scope="row"><?php _e( 'Base amount', 'calculated-fields-form' ); ?>:</th>
        <td><input type="text" name="paypal_base_amount" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option( 'paypal_base_amount', '0.01' ) ); ?>" /> <?php _e( 'Minimum amount to charge. If the final price is lesser than this number, the base amount will be applied.', 'calculated-fields-form' ); ?>
        </td>
        </tr>  
        
        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal product name', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="paypal_product_name" size="50" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('paypal_product_name',CP_CALCULATEDFIELDSF_DEFAULT_PRODUCT_NAME)); ?>" /></td>
        </tr>        
        
        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal language', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="paypal_language" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('paypal_language',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_LANGUAGE)); ?>" /></td>
        </tr>         
        
        <tr valign="top">        
        <th scope="row"><?php _e( 'Payment frequency', 'calculated-fields-form' ); ?></th>
        <td><select name="paypal_recurrent" id="paypal_recurrent" onchange="cfwpp_update_recurrent();">
             <option value="0" <?php if (cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '0' || 
                                         cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == ''
                                        ) echo 'selected'; ?>><?php _e( 'One time payment (default option, user is billed only once)', 'calculated-fields-form' ); ?></option>
             <option value="1" <?php if (cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '1') echo 'selected'; ?>><?php _e( 'Bill the user every 1 month', 'calculated-fields-form' ); ?></option> 
             <option value="3" <?php if (cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '3') echo 'selected'; ?>><?php _e( 'Bill the user every 3 months', 'calculated-fields-form' ); ?></option> 
             <option value="6" <?php if (cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '6') echo 'selected'; ?>><?php _e( 'Bill the user every 6 months', 'calculated-fields-form' ); ?></option> 
             <option value="12" <?php if (cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '12') echo 'selected'; ?>><?php _e( 'Bill the user every 12 months', 'calculated-fields-form' ); ?></option> 
			 <option value="field" <?php if ( strpos( cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT),'field' ) !== false ) echo 'selected'; ?>><?php _e( 'From field', 'calculated-fields-form' ); ?></option> 
            </select>
			<select name="paypal_recurrent_field" style="display:<?php echo ( ( strpos( cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT),'field' ) !== false ) ? 'inline-block' : 'none' ); ?>" def="<?php echo esc_attr(cp_calculatedfieldsf_get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT)); ?>">
			</select>
			
			<!-- START:: added for first recurrent period -->
			<div id="cfwpp_setupfee" style="width:350px;margin-top:5px;padding:5px;background-color:#ddddff;display:none;border:1px dotted black;">
				<?php _e( 'First period price (ex: include setup fee here if any)', 'calculated-fields-form' ); ?>:<br />
				<input type="text" name="paypal_recurrent_setup" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('paypal_recurrent_setup','')); ?>" /><br />
				<?php _e( 'First period interval in days', 'calculated-fields-form' ); ?>:<br />
				<input type="text" name="paypal_recurrent_setup_days" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('paypal_recurrent_setup_days','15')); ?>" />
            </div> 
            <script type="text/javascript">
				function cfwpp_update_recurrent() {
					var f = document.getElementById( 'paypal_recurrent' );
					if( f.options[ f.options.selectedIndex ].value != '0' )
					{	
						document.getElementById( 'cfwpp_setupfee' ).style.display = '';
					}	
					else
					{	
						document.getElementById( 'cfwpp_setupfee' ).style.display = 'none';    
					}	
				} 
				cfwpp_update_recurrent();
            </script>
            <!-- END:: added for first recurrent period -->
			
        </td>
        </tr>        
        
        <tr valign="top">
        <th scope="row"><?php _e( 'Discount Codes', 'calculated-fields-form' ); ?></th>
        <td> 
           <div id="dex_nocodes_availmsg"><?php _e( 'Loading...', 'calculated-fields-form' ); ?></div>
          
           <br />               
           <strong><?php _e( 'Add new discount code', 'calculated-fields-form' ); ?>:</strong>
           <br />
           <nobr><?php _e( 'Code', 'calculated-fields-form' ); ?>: <input type="text" name="dex_dc_code" id="dex_dc_code" value="" /></nobr> &nbsp; &nbsp; &nbsp; 
           <nobr><?php _e( 'Discount', 'calculated-fields-form' ); ?>: <input type="text" size="3" name="dex_dc_discount" id="dex_dc_discount"  value="25" /><select name="dex_dc_discounttype" id="dex_dc_discounttype">
                   <option value="0"><?php _e( 'Percent', 'calculated-fields-form' ); ?></option>
                   <option value="1"><?php _e( 'Fixed Value', 'calculated-fields-form' ); ?></option>
                 </select></nobr>
                    &nbsp; &nbsp;
           <nobr><?php _e( 'Valid until', 'calculated-fields-form' ); ?>: <input type="text"  size="10" name="dex_dc_expires" id="dex_dc_expires" value="" /></nobr>&nbsp; &nbsp; &nbsp; 
           <input type="button" name="dex_dc_subccode" id="dex_dc_subccode" value="<?php esc_attr_e( 'Add', 'calculated-fields-form' ); ?>" />
           <br />
           <em><?php _e( 'Note: Expiration date based in server time. Server time now is', 'calculated-fields-form' ); ?> <?php echo date("Y-m-d H:i"); ?></em>
        </td>
        </tr>  
                   
     </table>  

  </div>    
 </div>
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Form Processing / Email Settings', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">    

        <tr valign="top">
        <th scope="row"><?php _e( 'Send email "From"', 'calculated-fields-form' ); ?> </th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('fp_emailfrommethod', "fixed"); ?>
           <select name="fp_emailfrommethod">
             <option value="fixed"<?php if ($option == 'fixed') echo ' selected'; ?>><?php _e( 'From fixed email address indicated below - Recommended option', 'calculated-fields-form' ); ?></option>
             <option value="customer"<?php if ($option == 'customer') echo ' selected'; ?>><?php _e( 'From the email address indicated by the customer', 'calculated-fields-form' ); ?></option>
            </select><br />
            <span style="font-size:10px;color:#666666">
            <?php _e( '* If you select "from fixed..." the customer email address will appear in the "to" address when you hit "reply", this is the recommended setting to avoid mail server restrictions.', 'calculated-fields-form' ); ?> 
            <br />
            <?php _e( '* If you select "from customer email" then the customer email will appear also visually when you receive the email, but this isn\'t supported by all hosting services, so this option isn\'t recommended in most cases.', 'calculated-fields-form' ); ?>
            </span>
        </td>
        </tr>
             
     
        <tr valign="top">
        <th scope="row"><?php _e( '"From" email (for fixed "from" addresses)', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_from_email" size="40" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('fp_from_email', CP_CALCULATEDFIELDSF_DEFAULT_fp_from_email)); ?>" /></td>
        </tr>             
        <tr valign="top">
        <th scope="row"><?php _e( 'Destination emails (comma separated)', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_destination_emails" size="40" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('fp_destination_emails', CP_CALCULATEDFIELDSF_DEFAULT_fp_destination_emails)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email subject', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_subject" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('fp_subject', CP_CALCULATEDFIELDSF_DEFAULT_fp_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Include additional information?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('fp_inc_additional_info', CP_CALCULATEDFIELDSF_DEFAULT_fp_inc_additional_info); ?>
          <select name="fp_inc_additional_info">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Thank you page (after sending the message)', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="fp_return_page" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('fp_return_page', CP_CALCULATEDFIELDSF_DEFAULT_fp_return_page)); ?>" /></td>
        </tr> 
        <tr valign="top">
        <th scope="row"><?php _e( 'Email format?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('fp_emailformat', CP_CALCULATEDFIELDSF_DEFAULT_email_format); ?>
          <select name="fp_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e( 'Plain Text (default)', 'calculated-fields-form' ); ?></option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e( 'HTML (use html in the textarea below)', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>         
        <tr valign="top">
        <th scope="row"><?php _e( 'Message', 'calculated-fields-form' ); ?></th>
        <td><textarea type="text" name="fp_message" rows="6" cols="80"><?php echo cp_calculatedfieldsf_get_option('fp_message', CP_CALCULATEDFIELDSF_DEFAULT_fp_message); ?></textarea><br><em><?php _e( 'In case to select HTML in the Email Format, use <strong>&lt;br&gt;</strong> tags for the changes of lines.', 'calculated-fields-form' ); ?></em></td>
        </tr>                                                               
     </table>  
  </div>    
 </div>   
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Email Copy to User', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e( 'Send confirmation/thank you message to user?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('cu_enable_copy_to_user', CP_CALCULATEDFIELDSF_DEFAULT_cu_enable_copy_to_user); ?>
          <select name="cu_enable_copy_to_user">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email field on the form', 'calculated-fields-form' ); ?></th>
        <td><select id="cu_user_email_field" name="cu_user_email_field[]" def="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cu_user_email_field', CP_CALCULATEDFIELDSF_DEFAULT_cu_user_email_field)); ?>" MULTIPLE></select></td>
        </tr>             
        <tr valign="top">
        <th scope="row"><?php _e( 'Email subject', 'calculated-fields-form' ); ?></th>
        <td><input type="text" name="cu_subject" size="70" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cu_subject', CP_CALCULATEDFIELDSF_DEFAULT_cu_subject)); ?>" /></td>
        </tr>  
        <tr valign="top">
        <th scope="row"><?php _e( 'Email format?', 'calculated-fields-form' ); ?></th>
        <td>
          <?php $option = cp_calculatedfieldsf_get_option('cu_emailformat', CP_CALCULATEDFIELDSF_DEFAULT_email_format); ?>
          <select name="cu_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e( 'Plain Text (default)', 'calculated-fields-form' ); ?></option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e( 'HTML (use html in the textarea below)', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>                         
        <tr valign="top">
        <th scope="row"><?php _e( 'Message', 'calculated-fields-form' ); ?></th>
        <td><textarea type="text" name="cu_message" rows="6" cols="80"><?php echo cp_calculatedfieldsf_get_option('cu_message', CP_CALCULATEDFIELDSF_DEFAULT_cu_message); ?></textarea><br><em><?php _e( 'In case to select HTML in the Email Format, use <strong>&lt;br&gt;</strong> tags for the changes of lines.', 'calculated-fields-form' ); ?></em></td>
        </tr>        
     </table>  
  </div>    
 </div>  
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Captcha Verification', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e( 'Use Captcha Verification?', 'calculated-fields-form' ); ?></th>
        <td colspan="5">
          <?php $option = cp_calculatedfieldsf_get_option('cv_enable_captcha', CP_CALCULATEDFIELDSF_DEFAULT_cv_enable_captcha); ?>
          <select name="cv_enable_captcha">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'calculated-fields-form' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'calculated-fields-form' ); ?></option>
          </select>
        </td>
        </tr>
        
        <tr valign="top">
         <th scope="row"><?php _e( 'Width', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_width" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_width', CP_CALCULATEDFIELDSF_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Height', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_height" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_height', CP_CALCULATEDFIELDSF_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Chars', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_chars" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_chars', CP_CALCULATEDFIELDSF_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>             

        <tr valign="top">
         <th scope="row"><?php _e( 'Min font size', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_min_font_size" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_min_font_size', CP_CALCULATEDFIELDSF_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Max font size', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_max_font_size" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_max_font_size', CP_CALCULATEDFIELDSF_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>        
         <td colspan="2" rowspan="">
           <?php _e( 'Preview', 'calculated-fields-form' ); ?>:<br />
             <br />
            <img src="<?php echo cp_calculatedfieldsf_get_site_url( true ); ?>/?cp_calculatedfieldsf=captcha&inAdmin=1"  id="captchaimg" alt="security code" border="0"  />            
         </td> 
        </tr>             
                
        <tr valign="top">
         <th scope="row"><?php _e( 'Noise', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_noise" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_noise', CP_CALCULATEDFIELDSF_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row"><?php _e( 'Noise Length', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_noise_length" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_noise_length', CP_CALCULATEDFIELDSF_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>        
        </tr>          
        
        <tr valign="top">
         <th scope="row"><?php _e( 'Background', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_background" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_background', CP_CALCULATEDFIELDSF_DEFAULT_cv_background)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row"><?php _e( 'Border', 'calculated-fields-form' ); ?>:</th>
         <td><input type="text" name="cv_border" size="10" value="<?php echo esc_attr(cp_calculatedfieldsf_get_option('cv_border', CP_CALCULATEDFIELDSF_DEFAULT_cv_border)); ?>" onblur="generateCaptcha();" /></td>        
        </tr>    
        
        <tr valign="top">
         <th scope="row"><?php _e( 'Font', 'calculated-fields-form' ); ?>:</th>
         <td>
            <select name="cv_font" onchange="generateCaptcha();" >
              <option value="font-1.ttf"<?php if ("font-1.ttf" == cp_calculatedfieldsf_get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font-2.ttf"<?php if ("font-2.ttf" == cp_calculatedfieldsf_get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font-3.ttf"<?php if ("font-3.ttf" == cp_calculatedfieldsf_get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font-4.ttf"<?php if ("font-4.ttf" == cp_calculatedfieldsf_get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
            </select>            
         </td>              
        </tr>                              
     </table>  
  </div>    
 </div>    

 <?php
	global $cpcff_addons_objs_list, $cpcff_addons_active_list;
	if( count( $cpcff_addons_active_list ) )
	{	
		_e( '<h2>Add-Ons Settings:</h2><hr />', 'calculated-fields-form' );
		foreach( $cpcff_addons_active_list as $addon_id ) if( isset( $cpcff_addons_objs_list[ $addon_id ] ) ) print $cpcff_addons_objs_list[ $addon_id ]->get_addon_form_settings( CP_CALCULATEDFIELDSF_ID );
	}
 ?>
 
<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Note', 'calculated-fields-form' ); ?></span></h3>
  <div class="inside">
   <?php _e( 'To insert this form in a post/page, use the dedicated icon', 'calculated-fields-form' ); ?>
   <?php print '<a href="javascript:cp_calculatedfieldsf_insertForm();" title="'.esc_attr__( 'Insert Calculated Fields Form', 'calculated-fields-form' ).'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.esc_attr__( 'Insert Calculated Fields Form', 'calculated-fields-form' ).'" /></a>';    ?>
   <?php _e( 'which has been added to your Upload/Insert Menu, just below the title of your Post/Page.', 'calculated-fields-form' ); ?>
   <br /><br />
  </div>
</div>     
</div> 
<p class="submit">
	<input type="submit" name="save" id="save" 	 class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'calculated-fields-form' ); ?>"  />
	<input type="button" name="previewbtn" id="previewbtn" class="button-primary" value="<?php esc_attr_e( 'Save & Preview', 'calculated-fields-form' ); ?>" onclick="jQuery.fbuilder.preview( this );" />
</p>

[<a href="http://wordpress.dwbooster.com/contact-us" target="_blank"><?php _e( 'Request Custom Modifications', 'calculated-fields-form' ); ?></a>] | [<a href="http://wordpress.dwbooster.com/forms/calculated-fields-form" target="_blank"><?php _e( 'Help', 'calculated-fields-form' ); ?></a>]
</form>
</div>
<script type="text/javascript">
	function generateCaptcha()
	{            
	   var d=new Date();
	   var f = document.cpformconf;    
	   var qs = "&width="+f.cv_width.value;
	   qs += "&height="+f.cv_height.value;
	   qs += "&letter_count="+f.cv_chars.value;
	   qs += "&min_size="+f.cv_min_font_size.value;
	   qs += "&max_size="+f.cv_max_font_size.value;
	   qs += "&noise="+f.cv_noise.value;
	   qs += "&noiselength="+f.cv_noise_length.value;
	   qs += "&bcolor="+f.cv_background.value;
	   qs += "&border="+f.cv_border.value;
	   qs += "&font="+f.cv_font.options[f.cv_font.selectedIndex].value;
	   qs += "&rand="+d;
	   
	   document.getElementById("captchaimg").src= "<?php echo cp_calculatedfieldsf_get_site_url( true ); ?>/?cp_calculatedfieldsf=captcha&inAdmin=1"+qs;
	}


	function cff_update_pp_payment_selection() 
	{
	   var f = document.cpformconf;
	   var ppoption = f.enable_paypal.options[f.enable_paypal.selectedIndex].value;     
	   if (ppoption == '2')
		   document.getElementById("cff_paypal_options_label").style.display = "";
	   else
		   document.getElementById("cff_paypal_options_label").style.display = "none";  
	}   

	cff_update_pp_payment_selection();
	
	generateCaptcha();
</script>