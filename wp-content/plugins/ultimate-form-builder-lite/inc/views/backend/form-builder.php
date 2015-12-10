<div class="wrap">
	<?php
	/**
	 * Always use self::load_view to load view inside a view
	 * Header view loaded
	 */
	$data['form_row'] = $form_row;
	self::load_view( 'backend/header' );
	?>
	<div class="ufbl-shortcode-display-wrap">Shortcode: <input type="text" onfocus="this.select();" readonly="readonly" value="[ufbl form_id=&quot;<?php echo $_GET['form_id']?>&quot;]" class="shortcode-in-list-table wp-ui-text-highlight code"></div>
	<h2 class="nav-tab-wrapper">
		<a href="javascript:void(0);" class="nav-tab nav-tab-active ufbl-tab-trigger" data-id="form-builder"><?php _e( 'Form Builder', 'ultimate-form-builder-lite' ); ?></a>
		<a href="javascript:void(0);" class="nav-tab ufbl-tab-trigger" data-id="display"><?php _e( 'Display Settings', 'ultimate-form-builder-lite' ); ?></a>
		<a href="javascript:void(0);" class="nav-tab ufbl-tab-trigger" data-id='email'><?php _e( 'Email Settings', 'ultimate-form-builder-lite' ); ?></a>

	</h2>
	<?php if ( isset( $_SESSION['ufbl_message'] ) ) { ?>
		<div class="ufbl-message">
			<p>
				<?php
				echo $_SESSION['ufbl_message'];
				unset( $_SESSION['ufbl_message'] );
				?>
			</p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
	<?php } ?>
	<div class="ufbl-form-controls ufbl-text-align-right">
		<input type="button" class="button-primary ufbl-save-form" value="<?php _e( 'Save Form', 'ultimate-form-builder-lite' ); ?>"/>
		<a href="<?php echo site_url('?ufbl_form_preview=true&ufbl_form_id='.$form_row['form_id']);?>" target="_blank"><input type="button" class="button-primary" value="<?php _e( 'Preview', 'ultimate-form-builder-lite' ); ?>"/></a>
		<div class="ufbl-field-note"><?php _e( 'Note: Please save form before preview.', 'ultimate-form-builder-lite' ); ?></div>
	</div>
	<div class="ufbl-clear"></div>
	<div class="ufbl-tab-content-wrapper">
		<!--form builder reference fields-->
		<?php self::load_view( 'backend/boxes/form-fields-html' ); ?>
		<!--form builder reference fields-->

		<form class="ufbl-form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" data-changed="false">
			<!--Form Builder Section -->
			<?php self::load_view( 'backend/boxes/form-builder-main', $data ); ?>
			<!--Form Builder Section -->

			<!--Display Settings Section -->
			<?php self::load_view( 'backend/boxes/display-settings', $data ); ?>
			<!--Display Settings Section -->

			<!--Email Settings Section -->
			<?php self::load_view( 'backend/boxes/email-settings', $data ); ?>
			<!--Email Settings Section -->
		</form>	
	</div>
</div>

