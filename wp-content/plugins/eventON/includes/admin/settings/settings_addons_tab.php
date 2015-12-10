<?php
/**
 * EventON Settings Tab for addons and licensing
 * 
 * @version 0.7
 * @updated 2015-8-25
 */

global $ajde;

?>
<div id="evcal_4" class="postbox evcal_admin_meta">	
	<?php
		// UPDATE eventon addons list
			$eventon->evo_updater->product->ADD_update_addons();		
	?>
	<div class='evo_button_h1'>
		<p><a href='http://www.myeventon.com/documentation/can-download-addon-updates/' class='evo_admin_btn btn_prime' target='_blank'>How to update EventON plugin and addons to latest version?</a></p>
	</div>

	<div class='evo_addons_page addons'>		
		<?php

			$admin_url = admin_url();
			$show_license_msg = true;

			//delete_option('_evo_products');
			
			// get all evo product licenses
			$evo_licenses = $eventon->evo_updater->product->get_products_array();

			$_evo_products = get_option('_evo_products');
			
			// ACTIVATED
				if($eventon->evo_updater->product->is_activated()):
					$_has_update = (!empty($_evo_products['eventon']['has_new_update']) && $_evo_products['eventon']['has_new_update'])? true:false;
					$new_update_details_btn = ($_has_update)?
						"<p class='links'><b>".__('New Update availale','eventon')."</b><br/><a href='".$admin_url."update-core.php'>Update Now</a> | <a class='thickbox' href='".BACKEND_URL."plugin-install.php?tab=plugin-information&plugin=eventon&section=changelog&TB_iframe=true&width=600&height=400'>Version Details</a></p>":null;

						$new_update_details_btn = "<a href='http://www.myeventon.com/documentation/' target='_blank'>Documentation</a><br/><a href='http://www.myeventon.com/news/' target='_blank'>News & Updates</a>";

					?>
						<div class="addon main activated <?php echo ($_has_update)? 'hasupdate':null;?>">
							<h2>EventON</h2>
							<p class='version'><?php echo $_evo_products['eventon']['version'];?></p>
							<p>License Status: <strong><?php _e('Activated','eventon');?></strong> | <a id='evoDeactLic' style='cursor:pointer'><?php _e('Deactivate','eventon');?></a></p>
							<p>Purchase Key: <strong><?php echo $eventon->evo_updater->product->get_partial_license();?></strong></p>
							<p><i><?php _e('Info: You have successfully activated this license on this site. You will need a seperate license to activate eventON for another site.','eventon');?></i><?php $ajde->wp_admin->echo_tooltips('EventON license you have purchased from Codecanyon, either regular or extended will allow you to install eventON in ONE site only. In order to install eventON in another site you will need a seperate license.');?></p>
							<p class='links'><?php echo $new_update_details_btn;?></p>
						</div>
					<?php 

			// NOT ACTIVATED
				else:
				?>
				<div id='evo_license_main' class="addon main">
					<h2>EventON</h2>
					<p class='version'><?php echo $_evo_products['eventon']['version'];?><span>/<?php echo $_evo_products['eventon']['remote_version'];?></span></p>
					<p class='status'><?php _e('License Status','eventon');?>: <strong><?php _e('Not Activated','eventon');?></strong></p>
					<p class='action'><a class='ajde_popup_trig evo_admin_btn btn_prime' dynamic_c='1' content_id='eventon_pop_content_001' poptitle='Activate EventON License'>Activate Now</a></p>
					<p class='activation_text'><i>EventON plugin and its addons should function 100% regardless license activation in here. Also we have cut down on autoupdate check times due to server crashes on our end and hope to resolve this in future versions. <a href='http://www.myeventon.com/documentation/how-to-find-eventon-license-key/' target='_blank'>How to find activation key</a><?php $eventon->throw_guide('EventON license you have purchased from Codecanyon, either regular or extended will allow you to install eventON in ONE site only. In order to install eventON in another site you will need a seperate license.');?></i></p>

						<div id='eventon_pop_content_001' class='evo_hide_this'>
							<p><?php _e('Enter your codecanyon Purchase Key','eventon');?>:<br/>
							<input class='eventon_license_key_val' type='text' style='width:100%'/>
							<input class='eventon_slug' type='hidden' value='eventon' />
							<input class='eventon_license_div' type='hidden' value='evo_license_main' /><br/><i>More information on <a href='http://www.myeventon.com/documentation/how-to-find-eventon-license-key/' target='_blank'><?php _e('How to find eventON purchase key','eventon');?></a></i></p>
							<p style='text-align:center'><a class='eventon_submit_license evo_admin_btn btn_prime'><?php _e('Activate Now','eventon');?></a></p>
						</div>
				</div>
				<?php
				endif;
		?>

		<?php // ADDONS 			
			global $wp_version; 

				//print_r($evo_licenses);
				$_activePlugins =get_option( 'active_plugins' );

				$_evo_products = base64_encode(serialize($_evo_products));
				$admin_url = get_admin_url();
				$_activePlugins = (!empty($_activePlugins))? base64_encode(serialize( $_activePlugins)): null;
			?>				
				<div id='evo_addons_list' data-products='<?php echo $_evo_products;?>' data-url='<?php echo AJDE_EVCAL_URL.'/includes/admin/settings/addon_details.php';?>' data-adminurl='<?php echo $admin_url;?>' data-active_plugins='<?php echo $_activePlugins;?>'></div>
			<?php

				// notice
				echo "<div class='clear'></div><p><i><b>NOTE:</b> if you are not able to activate eventon or its addons please try again later as the activation server may be overloaded. </i></p>";
			
		?>

		<div class="debugAct" style="padding:10px">
			<p style="font-size:20px;"><?php _e('Activation does not work?','eventon');?> </p>
			<p>NOTE: EventON and its addons should work <b>100%</b> despite activation.</p>
			<?php // DEBUG report for license activation -->			
				echo evo_debug_report();
			?>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php
		// Throw the output popup box html into this page		
		echo $ajde->wp_admin->lightbox_content(array('content'=>'Loading...', 'type'=>'padded'));
	?>
</div>