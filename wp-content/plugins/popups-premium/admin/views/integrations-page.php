<?php 
/**
 * Integrations page
 * @since  1.3
 */
?>
<div class="wrap">
	<h2>Popups <?php echo SocialPopup::VERSION;?> - Integrations</h2>

	<form name="spu-settings" method="post" action="<?php echo admin_url('edit.php?post_type=spucpt&page=spu_integrations');?>">
		<?php do_action( 'spu/integrations_page/before' ); ?>
		<div class="js-mailchimp collapse-div">
			<table class="form-table">
				<tr valign="top" class="">
					<th colspan="2"><h3><?php _e( 'MailChimp', $this->plugin_slug ); ?> <a href="#" class="toggle-provider"><i class="spu-icon spu-icon-angle-down"></i></a></h3></th>
				</tr>
				<tr valign="top" class="">
					<th><label for="ajax_mode"><?php _e( 'Api Key', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<label><input type="text" id="mc_api" name="spu_integrations[mailchimp][mc_api]" value="<?php echo @$opts['mailchimp']['mc_api'];?>" class="regular-text <?php echo !$mc_connected?:'spu_license_valid';?>" /> <?php
							if(!$mc_connected){
								echo '<button class="enable-api">Connect</button>';
							}?>
						<p class="help"><?php _e( 'Enter you <a href="https://us2.admin.mailchimp.com/account/api/" target="_blank">mailchimp API key</a>', $this->plugin_slug ); ?></p>
					</td>
				</tr>
			</table>
			<?php include_once('partials/mc-lists.php');?>
		</div>
		<div class="js-getresponse collapse-div">
			<table class="form-table">
				<tr valign="top" class="">
					<th colspan="2"><h3><?php _e( 'GetResponse', $this->plugin_slug ); ?> <a href="#" class="toggle-provider"><i class="spu-icon spu-icon-angle-down"></i></a></h3></th>
				</tr>
				<tr valign="top" class="">
					<th><label for="ajax_mode"><?php _e( 'Api Key', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<label><input type="text" id="gr_api" name="spu_integrations[getresponse][gr_api]" value="<?php echo @$opts['getresponse']['gr_api'];?>" class="regular-text <?php echo !$gr_connected?:'spu_license_valid';?>" /> <?php
							if(!$gr_connected){
								echo '<button class="enable-api">Connect</button>';
							}?>
						<p class="help"><?php _e( 'Enter you <a href="https://app.getresponse.com/account.html#api" target="_blank">GetResponse API key</a>', $this->plugin_slug ); ?></p>
					</td>
				</tr>
			</table>
			<?php include_once('partials/gr-lists.php');?>
		</div>
		<div class="js-aweber collapse-div">
			<table class="form-table">
				<tr valign="top" class="">
					<th colspan="2"><h3><?php _e( 'Aweber', $this->plugin_slug ); ?> <a href="#" class="toggle-provider"><i class="spu-icon spu-icon-angle-down"></i></a></h3></th>
				</tr>
				<?php if(!$aweber){?>
				<tr valign="top" class="">
					<th><label for=""><?php _e( 'Register with Aweber', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<a href="https://auth.aweber.com/1.0/oauth/authorize_app/d6e36403" class="button-primary" target="_blank">Authorize with Aweber</a>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top" class="">
					<th><label><?php _e( 'Authorization Code', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<label><textarea id="aweber_api" name="spu_integrations[aweber][aweber_auth]" class="regular-text <?php echo !$aweber?:'spu_license_valid';?>"><?php echo @$opts['aweber']['aweber_auth'];?></textarea>
							<input type="hidden" name="spu_integrations[aweber][access_token]" value="<?php echo @$opts['aweber']['access_token'];?>"/>
							<input type="hidden" name="spu_integrations[aweber][access_token_secret]" value="<?php echo @$opts['aweber']['access_token_secret'];?>"/><?php
							if(!$aweber){
								echo '<button class="enable-api">Connect</button>';
							} else {
								?><a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_aweber_disconnect', 'spu_nonce');?>" class="button "><?php _e( 'Disconnect', 'spup' ); ?></a><?php
							}?>
						<p class="help"><?php _e( 'Once registered with the Aweber app, paste the auth code above.', $this->plugin_slug ); ?></p>
					</td>
				</tr>

			</table>
			<?php include_once('partials/aweber-lists.php');?>
		</div>
		<div class="js-ccontact collapse-div">
			<table class="form-table">
				<tr valign="top" class="">
					<th colspan="2"><h3><?php _e( 'Constant Contact', $this->plugin_slug ); ?> <a href="#" class="toggle-provider"><i class="spu-icon spu-icon-angle-down"></i></a></h3></th>
				</tr>
				<?php if(!$ccontact){?>
				<tr valign="top" class="">
					<th><label for=""><?php _e( 'Register with Constant Contact', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<a href="https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize?response_type=token&client_id=n4emv3whbr6exxu4hs2x456d&
redirect_uri=<?php echo urlencode( 'https://timersys.com/popups/oauth/');?>" data-provider="ccontact" class="button-primary" target="_blank" onclick="ccontact(jQuery(this)); return false;">Authorize with Constant Contact</a>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top" class="">
					<th><label><?php _e( 'Authorization Code', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<label><input id="ccontact_api" name="spu_integrations[ccontact][ccontact_auth]" type="text" class="regular-text <?php echo !$ccontact?:'spu_license_valid';?>" value="<?php echo @$opts['ccontact']['ccontact_auth'];?>">
						<?php
							if(!$ccontact){
								echo '<button class="enable-api" id="ccontact_connect">Connect</button>';
							} else {
								?><a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_ccontact_disconnect', 'spu_nonce');?>" class="button "><?php _e( 'Disconnect', 'spup' ); ?></a><?php
							}?>
					</td>
				</tr>

			</table>
			<?php include_once('partials/ccontact-lists.php');?>
		</div>
		<?php /*
		<div class="js-infusion collapse-div">
			<table class="form-table">
				<tr valign="top" class="">
					<th colspan="2"><h3><?php _e( 'InfusionSoft', $this->plugin_slug ); ?> <a href="#" class="toggle-provider"><i class="spu-icon spu-icon-angle-down"></i></a></h3></th>
				</tr>
				<?php if(!$infusion){?>
				<tr valign="top" class="">
					<th><label for=""><?php _e( 'Register with InfusionSoft', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<a href="" data-provider="infusion" class="button-primary" target="_blank" onclick="">Authorize with InfusionSoft</a>
					</td>
				</tr>
				<?php } ?>
				<tr valign="top" class="">
					<th><label><?php _e( 'Authorization Code', $this->plugin_slug ); ?></label></th>
					<td colspan="3">
						<label><input id="infusion_api" name="spu_integrations[infusion][infusion_auth]" type="text" class="regular-text <?php echo !$infusion?:'spu_license_valid';?>" value="<?php echo @$opts['infusion']['infusion_auth'];?>">
						<?php
							if(!$infusion){
								echo '<button class="enable-api" id="infusion_connect">Connect</button>';
							} else {
								?><a href="<?php print wp_nonce_url(admin_url('edit.php?post_type=spucpt&page=spu_integrations'), 'spu_infusion_disconnect', 'spu_nonce');?>" class="button "><?php _e( 'Disconnect', 'spup' ); ?></a><?php
							}?>
					</td>
				</tr>

			</table>
			<?php include_once('partials/infusion-lists.php');?>
		</div>
	*/?>

		<?php do_action( 'spu/integrations_page/after' ); ?>
		<table class="form-table">
			<tr><td><input type="submit" class="button-primary" value="<?php _e( 'Save Integrations', $this->plugin_slug );?>"/></td>
			<?php wp_nonce_field('spu_save_settings','spu_nonce'); ?>
		</table>
	</form>
</div>