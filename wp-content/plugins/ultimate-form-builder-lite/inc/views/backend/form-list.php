<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<div class="wrap">
	<?php
	/**
	 * Always use self::load_view to load view inside a view
	 */
	self::load_view( 'backend/header' );
	?>


	<div class="ufbl-form-list">
		<h2><?php _e( 'Forms', 'ultimate-form-builder-lite' ); ?><a href="javascript:void(0);" class="ufbl-add-form-trigger add-new-h2"><?php _e( 'Add New Form' ); ?></a></h2>

		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th scope="col" id="title" class="manage-column column-shortcode">
						<?php _e( 'Form Title', 'ultimate-form-builder-lite' ); ?>
					</th>
					<th scope="col" id="shortcode" class="manage-column column-shortcode">
						<?php _e( 'Shortcode', 'ultimate-form-builder-lite' ); ?>
					</th>
					<th scope="col" id="last-modified" class="manage-column column-shortcode">
						<?php _e( 'Last Modified', 'ultimate-form-builder-lite' ); ?>
					</th>
					<th scope="col" id="status" class="manage-column column-shortcode">
						<?php _e( 'Status', 'ultimate-form-builder-lite' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="title" class="manage-column column-shortcode" >
						<span><?php _e( 'Form Title', 'ultimate-form-builder-lite' ); ?>
					</th>
					<th scope="col" id="shortcode" class="manage-column column-shortcode">
						<?php _e( 'Shortcode', 'ultimate-form-builder-lite' ); ?>
					</th>
					<th scope="col" id="last-modified" class="manage-column column-shortcode">
						<?php _e( 'Last Modified', 'ultimate-form-builder-lite' ); ?>
					</th>
					<th scope="col" id="status" class="manage-column column-shortcode">
						<?php _e( 'Status', 'ultimate-form-builder-lite' ); ?>
					</th>
				</tr>
			</tfoot>
			<tbody id="the-list" data-wp-lists="list:post">
				<?php
				if ( count( $forms ) > 0 ) {
					$form_counter = 1;
					foreach ( $forms as $form ) {
						$delete_nonce = wp_create_nonce( 'ufbl-delete-nonce' );
						$copy_nonce = wp_create_nonce( 'ufbl-copy-nonce' );
						?>
						<tr class="<?php if ( $form_counter % 2 != 0 ) { ?>alternate<?php } ?>">
							<td class="title column-title">
								<strong>
									<a class="row-title" href="<?php echo admin_url('admin.php?page=ufbl&action=edit-form&form_id=' . $form->form_id); ?>" title="Edit">
										<?php echo esc_attr( $form->form_title ); ?>
									</a>
								</strong>
								<div class="row-actions ufbl-relative">
									<span class="edit"><a href="<?php echo admin_url() . 'admin.php?page=ufbl&action=edit-form&form_id=' . $form->form_id; ?>">Edit</a> | </span>
									<span class="ufbl-copy" data-form-id="<?php echo $form->form_id; ?>"><a href="javascript:void(0);">Copy</a> | </span>
									<span class="delete ufbl-delete"><a href="javascript:void(0);">Delete</a> | </span>
									<span class="ufbl-preview"><a href="<?php echo site_url( '?ufbl_form_preview=true&ufbl_form_id=' . $form->form_id ); ?>" target="_blank"><?php _e( 'Preview', 'ultimate-form-builder-lite' ); ?></a> | </span>
									<span class="ufbl-entries"><a href="<?php echo admin_url( 'admin.php?page=ufbl-form-entries&form_id=' . $form->form_id ); ?>"><?php _e( 'Entries', 'ultimate-form-builder-lite' ); ?></a></span>
									<div class="ufbl-delete-confirmation" style="display:none">
										<p><?php _e( 'Are you sure you want to delete this form?', 'ultimate-form-builder-lite' ); ?></p>
										<input type="button" value="<?php _e( 'Yes', 'ultimate-form-builder-lite' ); ?>"  data-form-id="<?php echo $form->form_id; ?>" class="ufbl-delete-yes ufbl-form-delete-yes"/>
										<input type="button" value="<?php _e( 'Cancel', 'ultimate-form-builder-lite' ); ?>"  data-form-id="<?php echo $form->form_id; ?>" class="ufbl-delete-cancel ufbl-form-cancel"/>
										<span class="ufbl-ajax-loader" style="display:none;"></span>
									</div>
								</div>
							</td>
							<td class="shortcode column-shortcode"><input type="text" onFocus="this.select();" readonly="readonly" value="[ufbl form_id=&quot;<?php echo $form->form_id; ?>&quot;]" class="shortcode-in-list-table wp-ui-text-highlight code"></td>
							<td class="shortcode column-shortcode"><?php echo $form->form_modified; ?></td>
							<td class="shortcode column-shortcode ufbl-relative">
								<div class="onoffswitch">
									<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch-<?php echo $form->form_id; ?>" <?php checked( $form->form_status, true ); ?> style="display:none;">
									<label class="onoffswitch-label" for="myonoffswitch-<?php echo $form->form_id; ?>"  data-form-id="<?php echo $form->form_id; ?>">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>

								</div>
								<span class="ufbl-ajax-loader ufbl-status-loader" style="display:none;"></span>
								<span class="ufbl-status-message" style="display:none;"></span>
							</td>

						</tr>
						<?php
						$form_counter++;
					}
				} else {
					?>
					<tr><td colspan="4"><div class="ufbl-noresult"><?php _e( 'Forms not added yet', 'ultimate-form-builder-lite' ); ?></div></td></tr>
					<?php
				}
				?>
			</tbody>
		</table>

	</div>
</div>
<div class="ufbl-popup-wrap" style="display: none">
	<div class="ufbl-overlay"></div>
	<div class="ufbl-add-form-wrap">
		<div class="ufbl-add-field-wrap">
			<label><?php _e( 'Form Title', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field"><input type="text" class="ufbl-form-title" placeholder="<?php _e( 'Contact Form', 'ultimate-form-builder-lite' ); ?>"/></div>
			<div class="ufbl-field-note"><?php _e( 'Please enter the form title', 'ultimate-form-builder-lite' ); ?></div>
		</div>
		<div class="ufbl-add-field-wrap">
			<input type="button" class="ufbl-form-add-btn button-primary" value="<?php _e( 'Add Form', 'ultimate-form-builder-lite' ); ?>"/><span class="ufbl-ajax-loader" style="display: none"></span><span class="ufbl-msg" style="display:none;"><?php _e( 'Form Created.Redirecting...' ); ?></span>
			<div class="ufbl-add-error ufbl-error" style="display: none;"></div>
		</div>
	</div>
</div>
<div class="ufbl-copy-popup-wrap" style="display: none">
	<div class="ufbl-overlay"></div>
	<div class="ufbl-add-form-wrap">
		<div class="ufbl-add-field-wrap">
			<label><?php _e( 'Copy Form Title', 'ultimate-form-builder-lite' ); ?></label>
			<div class="ufbl-field"><input type="text" class="ufbl-form-title" placeholder="<?php _e( 'Contact Form', 'ultimate-form-builder-lite' ); ?>"/></div>
			<div class="ufbl-field-note"><?php _e( 'Please enter the form title', 'ultimate-form-builder-lite' ); ?></div>
		</div>
		<div class="ufbl-add-field-wrap">
			<div class="ufbl-field">
				<select class="ufbl-copy-form-id"><?php foreach ( $forms as $form ) {
					?>
						<option value="<?php echo $form->form_id; ?>"><?php echo esc_attr( $form->form_title ); ?></option>
						<?php
					}
					?></select>
			</div>
			<div class="ufbl-field-note"><?php _e( 'Please choose a form to copy.', 'ultimate-form-builder-lite' ); ?></div>
		</div>
		<div class="ufbl-add-field-wrap">
			<input type="button" class="ufbl-form-copy-btn button-primary" value="<?php _e( 'Copy Form', 'ultimate-form-builder-lite' ); ?>"/><span class="ufbl-ajax-loader" style="display: none"></span><span class="ufbl-msg" style="display:none;"><?php _e( 'Form Copied.Redirecting...' ); ?></span>
			<div class="ufbl-add-error ufbl-error" style="display: none;"></div>
		</div>
	</div>
</div>