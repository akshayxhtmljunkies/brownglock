<?php
global $library_obj;
$form_detail = maybe_unserialize( $form_row['form_detail'] );
$form_detail = empty( $form_detail ) ? $library_obj->get_default_detail() : $form_detail;
$email_settings = $form_detail['email_settings'];
?>
<div class="ufbl-tab-content" id="ufbl-email-tab" style="display: none;">
	<div class="ufbl-email-wrap">
		<label><?php _e( 'Email Reciever', 'ultimate-form-builder-lite' ); ?></label>
		<div class="ufbl-emails">
			<input type="button" value="<?php _e( 'Add email', 'ultimate-form-builder-lite' ); ?>" class="button-primary ufbl-email-adder"/>
			<?php
			$count = 0;
			foreach ( $email_settings['email_reciever'] as $email ) {
				$count++;
				?>
				<div class="ufbl-email-fields">
					<input type="text" name="email_settings[email_reciever][]" placeholder="test@abc.com" value="<?php echo esc_attr( $email ); ?>"/>
					<?php if ( $count != 1 ) {
						?>
						<span class="ufbl-email-remove">X</span>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<div class="ufbl-field-wrap">
		<label class="ufbl-field"><?php _e( 'From Email', 'ultimate-form-builder-lite' ); ?></label>
		<div class="ufbl-field">
			<input type="text" name="email_settings[from_email]" placeholder='test@xyz.com' value="<?php echo esc_attr( $email_settings['from_email'] ); ?>"/>
		</div>
	</div>
	<div class="ufbl-field-wrap">
		<label class="ufbl-field"><?php _e( 'From Name', 'ultimate-form-builder-lite' ); ?></label>
		<div class="ufbl-field">
			<input type="text" name="email_settings[from_name]" placeholder='John Corner' value="<?php echo esc_attr( $email_settings['from_name'] ); ?>"/>
		</div>
	</div>
	<div class="ufbl-field-wrap">
		<label class="ufbl-field"><?php _e( 'Email Subject', 'ultimate-form-builder-lite' ); ?></label>
		<div class="ufbl-field">
			<input type="text" name="email_settings[from_subject]" placeholder='<?php _e( 'New Form Submission', 'ultimate-form-builder-lite' ); ?>' value="<?php echo esc_attr( $email_settings['from_subject'] ); ?>"/>
		</div>
	</div>
	<div class="ufbl-form-controls">
		<input type="button" class="button-primary ufbl-save-form" value="<?php _e( 'Save Form', 'ultimate-form-builder-lite' ); ?>"/>
		<a href="<?php echo site_url( '?ufbl_form_preview=true&ufbl_form_id=' . $form_row['form_id'] ); ?>" target="_blank"><input type="button" class="button-primary" value="<?php _e( 'Preview', 'ultimate-form-builder-lite' ); ?>"/></a>
		<div class="ufbl-field-note"><?php _e( 'Note: Please save form before preview.', 'ultimate-form-builder-lite' ); ?></div>
	</div>
</div>

