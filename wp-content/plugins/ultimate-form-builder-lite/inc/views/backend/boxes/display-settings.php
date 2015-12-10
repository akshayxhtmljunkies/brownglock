<?php
global $library_obj;
$form_detail = maybe_unserialize( $form_row['form_detail'] );
$form_default_settings = $library_obj->get_default_detail();
$form_design = isset( $form_detail['form_design'] ) ? $form_detail['form_design'] : $form_default_settings['form_design'];
//$library_obj->print_array($form_default_settings);
?>
<div class="ufbl-tab-content" id="ufbl-display-tab" style="display:none">
	<div class="ufbl-display-sub-section">
		<div class="ufbl-field-wrap">
			<label><?php _e( 'Disable Plugin Styles', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field">
				<input type="checkbox" name="form_design[disable_plugin_style]" value='1' <?php echo (isset( $form_design['disable_plugin_style'] ) && $form_design['disable_plugin_style'] == 1) ? 'checked="checked"' : ''; ?>/>
				<div class="ufbl-side-note"><?php _e( 'Check if you want to disable all the plugin styles in the frontend.', 'ultimate-form-builder-lite' ); ?></div>
			</div>
		</div>
		<div class="ufbl-field-wrap">
			<label><?php _e( 'Hide Form Title', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field">
				<input type="checkbox" name="form_design[hide_form_title]" value='1' <?php echo (isset( $form_design['hide_form_title'] ) && $form_design['hide_form_title'] == 1) ? 'checked="checked"' : ''; ?>/>
				<div class="ufbl-side-note"><?php _e( 'Check to hide the form title in frontend form.', 'ultimate-form-builder-lite' ); ?></div>
			</div>
		</div>
		<div class="ufbl-field-wrap">
			<label><?php _e( 'Form Width', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field">
				<input type="text" name="form_design[form_width]" placeholder="500px or 100%" value="<?php echo esc_attr( $form_design['form_width'] ); ?>"/>
				<div class="ufbl-field-note"><?php _e( 'Please provide the width of form either in px or %.Default width is 100%.', 'ultimate-form-builder-lite' ); ?></div>
			</div>
		</div>
		<div class="ufbl-field-wrap">
			<label><?php _e( 'Form Submission Message', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field">
				<textarea name="form_design[form_submission_message]" placeholder="<?php _e( 'Form submitted successfully.', 'ultimate-form-builder-lite' ); ?>"><?php echo isset( $form_design['form_submission_message'] ) ? esc_attr( $form_design['form_submission_message'] ) : ''; ?></textarea>
			</div>
		</div>
		<div class="ufbl-field-wrap">
			<label><?php _e( 'Form Error Message', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field">
				<textarea name="form_design[form_error_message]" placeholder="<?php _e( 'Validation errors occurred in the form.', 'ultimate-form-builder-lite' ); ?>" ><?php echo isset( $form_design['form_error_message'] ) ? esc_attr( $form_design['form_error_message'] ) : ''; ?></textarea>
			</div>
		</div>
		<div class="ufbl-field-wrap">
			<label><?php _e( 'Form Template', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field">

				<select name="form_design[form_template]" class="ufbl-form-template-dropdown">
					<option value="ufbl-default-template" <?php selected( $form_design['form_template'], 'ufbl-default-template' ); ?>>Default Template</option>
					<?php for ( $i = 1; $i <= 5; $i++ ) {
						?>
						<option value="ufbl-template-<?php echo $i; ?>" <?php selected( $form_design['form_template'], 'ufbl-template-' . $i ); ?>>Template <?php echo $i; ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<div class="ufbl-form-controls">
			<input type="button" class="button-primary ufbl-save-form" value="<?php _e( 'Save Form', 'ultimate-form-builder-lite' ); ?>"/>
			<a href="<?php echo site_url( '?ufbl_form_preview=true&ufbl_form_id=' . $form_row['form_id'] ); ?>" target="_blank"><input type="button" class="button-primary" value="<?php _e( 'Preview', 'ultimate-form-builder-lite' ); ?>"/></a>
			<div class="ufbl-field-note"><?php _e( 'Note: Please save form before preview.', 'ultimate-form-builder-lite' ); ?></div>
		</div>
	</div>	
	<div class="ufbl-template-preview">
		<h3><?php _e( 'Template Preview', 'ultimate-form-builder-lite' ); ?></h3>
		<img src="<?php echo UFBL_IMG_DIR . '/previews/default-template.jpg' ?>" alt="Default Template" id="preview-ufbl-default-template" <?php if ( $form_design['form_template'] != 'ufbl-default-template' ) { ?>style="display:none"<?php } ?>/>
		<img src="<?php echo UFBL_IMG_DIR . '/previews/template-1.jpg' ?>" alt="Template 1" id="preview-ufbl-template-1" <?php if ( $form_design['form_template'] != 'ufbl-template-1' ) { ?>style="display:none"<?php } ?>/>
		<img src="<?php echo UFBL_IMG_DIR . '/previews/template-2.jpg' ?>" alt="Template 2" id="preview-ufbl-template-2" <?php if ( $form_design['form_template'] != 'ufbl-template-2' ) { ?>style="display:none"<?php } ?>/>
		<img src="<?php echo UFBL_IMG_DIR . '/previews/template-3.jpg' ?>" alt="Template 3" id="preview-ufbl-template-3" <?php if ( $form_design['form_template'] != 'ufbl-template-3' ) { ?>style="display:none"<?php } ?>/>
		<img src="<?php echo UFBL_IMG_DIR . '/previews/template-4.png' ?>" alt="Template 4" id="preview-ufbl-template-4" <?php if ( $form_design['form_template'] != 'ufbl-template-4' ) { ?>style="display:none"<?php } ?>/>
		<img src="<?php echo UFBL_IMG_DIR . '/previews/template-5.jpg' ?>" alt="Template 5" id="preview-ufbl-template-5" <?php if ( $form_design['form_template'] != 'ufbl-template-5' ) { ?>style="display:none"<?php } ?>/>

	</div>
	<div class="ufbl-clear"></div>
</div>

