<div class="wrap">
	<?php self::load_view( 'backend/header' ); ?>
	<h3><?php _e( 'New Form', 'ultimate-form-builder-lite' ); ?></h3>
	<div class="ufbl-new-form-wrap">
		<div class="ufbl-add-field-wrap">
			<label><?php _e( 'Form Title', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field"><input type="text" class="ufbl-form-title" placeholder="<?php _e( 'Contact Form', 'ultimate-form-builder-lite' ); ?>"></div>
			<div class="ufbl-field-note"><?php _e( 'Please enter the form title', 'ultimate-form-builder-lite' ); ?></div>
		</div>
		<div class="ufbl-add-field-wrap ufbl-add-submit-wrap">
			<input type="button" class="ufbl-form-add-btn button-primary" value="Add Form"><span class="ufbl-ajax-loader" style="display: none"></span><span class="ufbl-msg" style="display:none;">Form Created.Redirecting...</span>
			<div class="ufbl-add-error ufbl-error" style="display: none;"></div>
		</div>
	</div>

</div>

