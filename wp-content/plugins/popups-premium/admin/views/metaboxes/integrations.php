<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;?>

	<table class="form-table">

		<?php do_action( 'spu/metaboxes/before_integrations', $opts );?>
		<tr valign="top">
			<th><label for="spu_optin"><?php _e( 'Optin Integration', $this->plugin_slug ); ?></label></th>
			<td>
				<select id="spu_optin" name="spu[optin]" class="widefat">
					<option value="" ><?php _e( 'None', $this->plugin_slug ); ?></option>
					<option value="custom" <?php selected($opts['optin'], 'custom', true);?> ><?php _e( 'Custom Code', $this->plugin_slug ); ?></option>
					<?php
					foreach( $this->spu_integrations as $key => $provider ) {
						$provider_name = $key == 'wysija' ? 'Mailpoet' : ucfirst($key);
						?><option value="<?php echo $key;?>" <?php selected($opts['optin'], $key, true);?>><?php echo $provider_name;?></option><?php
					}
					?>
				</select>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_list <?php echo ( !empty($opts['optin']) && 'postmatic' != $opts['optin'] ) ? 'visible' : '';?>">
			<th><label for="spu_optin_list"><?php _e( 'Email List', $this->plugin_slug ); ?></label></th>
			<td>
				<select id="spu_optin_list" name="spu[optin_list]" class="widefat">
					<?php
					if( !empty($opts['optin']) &&  !empty( $optin_lists ) ) {
						?><option value=""><?php _e( 'Choose one', $this->plugin_slug ); ?></option><?php
						if( !empty( $optin_lists ) ) {
							foreach ( $optin_lists as $l ) {
								?><option value="<?php echo esc_html( $l->id ); ?>" <?php
									if( isset($opts['optin_list'] ) ) selected( $opts['optin_list'], $l->id, true );
								?> ><?php echo esc_html( $l->name ); ?></option><?php
							}
						}
					} else {
						?><option value=""><?php _e( 'None', $this->plugin_slug ); ?></option><?php
					}
					?>
				</select>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_list_segments  <?php echo ( !empty($opts['optin']) && !empty($opts['optin_list']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_list_segments"><?php _e( 'Email List Segments', $this->plugin_slug ); ?></label></th>
			<td class="result">
				<?php
				if( !empty($opts['optin']) && !empty($opts['optin_list']) ) {
					$lists = $optin_lists;
					if( !empty( $optin_lists ) ) {
						foreach ( $optin_lists as $l ) {
							if ( $opts['optin_list'] == $l->id ) {
								if( !empty( $l->interest_groupings ) ) {
									foreach ( $l->interest_groupings as $group ) {
										if ( ! empty( $group->name ) ) {
											echo '<h4 class="grouping-name">' . $group->name . '</h4>';
											foreach ( $group->groups as $g ) {
												echo '<input type="checkbox" value="' . $g->name . '" name="spu[optin_list_segments][' . $group->id . '][]" ';
												if ( isset( $opts['optin_list_segments'][ $group->id ] ) && is_array( $opts['optin_list_segments'][ $group->id ] ) ) {
													checked( in_array( $g->name, $opts['optin_list_segments'][ $group->id ] ), 1, true );
												}
												echo '/>' . $g->name;
											}
										}
									}
								}else {
									_e("The list don't have any segment",  $this->plugin_slug);
								}
								break;
							}
						}
					}
				}
				?>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_theme <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_theme"><?php _e( 'Choose optin theme', $this->plugin_slug ); ?></label></th>
			<td>
				<div class="optin_themes">
					<div class="theme <?php echo $opts['optin_theme'] == 'simple' ?'selected':'';?>" data-theme="simple">
						<div class="thumb"><img src="<?php echo SPUP_PLUGIN_URL?>/admin/assets/img/simple.png" alt=""/></div>
						SIMPLE
					</div>
					<div class="theme <?php echo $opts['optin_theme'] == 'postal' ?'selected':'';?>" data-theme="postal">
						<div class="thumb"><img src="<?php echo SPUP_PLUGIN_URL?>/admin/assets/img/postal.png" alt=""/></div>
						POSTAL
					</div>
					<div class="theme <?php echo $opts['optin_theme'] == 'coupon' ?'selected':'';?>" data-theme="coupon">
						<div class="thumb"><img src="<?php echo SPUP_PLUGIN_URL?>/admin/assets/img/coupon.png" alt=""/></div>
						COUPON
					</div>

					<div class="theme <?php echo $opts['optin_theme'] == 'cta' ?'selected':'';?>" data-theme="cta">
						<div class="thumb"><img src="<?php echo SPUP_PLUGIN_URL?>/admin/assets/img/cta.png" alt=""/></div>
						CTA
					</div>
				</div>
				<input type="hidden" id="spu_optin_theme" name="spu[optin_theme]"  value="<?php echo $opts['optin_theme'];?>"/>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_placeholder <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_placeholder"><?php _e( 'Email placeholder', $this->plugin_slug ); ?></label></th>
			<td>
				<input type="text" id="spu_optin_placeholder" name="spu[optin_placeholder]" class="widefat" value="<?php echo $opts['optin_placeholder'];?>"/>
				<p class="help"><?php _e( 'Change email input field placeholder text', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_display_name <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_display_name"><?php _e( 'Display Name Field ?', $this->plugin_slug ); ?></label></th>
			<td>
				<select id="spu_optin_display_name" name="spu[optin_display_name]" class="widefat">
					<option value="0" <?php selected($opts['optin_display_name'], 0); ?>><?php _e( 'No', $this->plugin_slug ); ?></option>
					<option value="1"<?php selected($opts['optin_display_name'], 1); ?>><?php _e( 'Yes', $this->plugin_slug ); ?></option>
				</select>
				<p class="help"><?php _e( 'Asking just for email has better conversion', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_name_placeholder <?php echo ( $opts['optin_display_name'] == '1' && !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_name_placeholder"><?php _e( 'Name Placeholder', $this->plugin_slug ); ?></label></th>
			<td>
				<input type="text" id="spu_optin_name_placeholder" name="spu[optin_name_placeholder]" class="widefat" value="<?php echo $opts['optin_name_placeholder'];?>"/>
				<p class="help"><?php _e( 'Change name input field placeholder text', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_submit <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_submit"><?php _e( 'Submit button', $this->plugin_slug ); ?></label></th>
			<td>
				<input type="text" id="spu_optin_submit" name="spu[optin_submit]" class="widefat" value="<?php echo $opts['optin_submit'];?>"/>
				<p class="help"><?php _e( 'Change submit button text', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_success <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_success"><?php _e( 'Success message', $this->plugin_slug ); ?></label></th>
			<td>
				<input type="text" id="spu_optin_success" name="spu[optin_success]" class="widefat" value="<?php echo $opts['optin_success'];?>"/>
				<p class="help"><?php _e( 'Short message to display once form is submitted. Only if redirect url is not being used.', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_redirect <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_redirect"><?php _e( 'Redirect Url', $this->plugin_slug ); ?></label></th>
			<td>
				<input type="text" id="spu_optin_redirect" name="spu[optin_redirect]" class="widefat" value="<?php echo $opts['optin_redirect'];?>"/>
				<p class="help"><?php _e( 'Url to redirect users just after form is submitted', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<tr valign="top" class="optin_opts optin_pass_redirect <?php echo ( !empty($opts['optin']) ) ? 'visible' : '';?>">
			<th><label for="spu_optin_pass_redirect"><?php _e( 'Pass lead data to redirect url ?', $this->plugin_slug ); ?></label></th>
			<td>
				<select id="spu_optin_pass_redirect" name="spu[optin_pass_redirect]" class="widefat">
					<option value="0" <?php selected($opts['optin_pass_redirect'], 0); ?>><?php _e( 'No', $this->plugin_slug ); ?></option>
					<option value="1"<?php selected($opts['optin_pass_redirect'], 1); ?>><?php _e( 'Yes', $this->plugin_slug ); ?></option>
				</select>
				<p class="help"><?php _e( 'You can pass email and name as query string data to the redirect url', $this->plugin_slug ); ?></p>
			</td>
			<td colspan="2"></td>
		</tr>

		<?php do_action( 'spu/metaboxes/after_integrations', $opts );?>
	</table>

