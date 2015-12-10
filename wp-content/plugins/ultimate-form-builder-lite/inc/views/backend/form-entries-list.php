<div class="wrap">
	<?php
	/**
	 * Always use self::load_view to load view inside a view
	 * Header view loaded
	 */
	self::load_view( 'backend/header' );
	?>
	<div class="ufbl-entry-filter-wrap">
		<h3><?php _e( 'Form Entries', 'ultimate-form-builder-lite' ); ?></h3>

		<select class="ufbl-entry-filter-select" data-admin-url="<?php echo admin_url(); ?>">
			<option value=""><?php _e( 'All Form entries', 'ultimate-form-builder-lite' ); ?></option>
			<?php
			$form_id = isset( $_GET['form_id'] ) ? $_GET['form_id'] : '';
			if ( count( $form_rows ) > 0 ) {
				foreach ( $form_rows as $form_row ) {
					?>
					<option value="<?php echo $form_row['form_id'] ?>" <?php selected( $form_id, $form_row['form_id'] ); ?>><?php echo $form_row['form_title']; ?></option>
					<?php
				}
			}
			?>
		</select>
		<?php $csv_nonce = wp_create_nonce( 'ufbl-csv-nonce' ); ?>
		<?php if ( $form_id != '' ) { ?>
			<a href="<?php echo admin_url( 'admin-post.php?action=ufbl_csv_export&form_id=' . $form_id . '&_wpnonce=' . $csv_nonce ); ?>"><input type="button" class="ufbl-csv-export-trigger" value="<?php _e( 'Export to CSV', 'ultimate-form-builder-lite' ); ?>" data-admin-url="<?php echo admin_url(); ?>" data-form-id="<?php echo $form_id; ?>"/></a>
			<?php
		}

		$current_page = isset( $_GET['page_num'] ) ? $_GET['page_num'] : 1;
		$upper_page_limit = $current_page + 2;
		$upper_page_limit = ($upper_page_limit > $total_pages) ? $total_pages : $upper_page_limit;
		$lower_page_limit = $current_page - 2;
		$lower_page_limit = ($lower_page_limit <= 0) ? 1 : $lower_page_limit;
		if ( $total_pages > 1 ) {
			?>
			<div class="ufbl-entries-pagination-outerwrap">
				<div class="ufbl-entries-pagination-wrap">
					<?php
					$previous_page = $current_page - 1;
					$next_page = $current_page + 1;
					if ( $previous_page > 0 ) {
						if ( isset( $_GET['form_id'] ) ) {
							$page_link = admin_url( 'admin.php?page=ufbl-form-entries&form_id=' . $_GET['form_id'] . '&page_num=' . $previous_page );
						} else {
							$page_link = admin_url( 'admin.php?page=ufbl-form-entries&page_num=' . $previous_page );
						}
						?>
						<a class="ufbl-entry-previous-page ufbl-entry-page-link" href="<?php echo $page_link; ?>"><?php _e( 'Previous', 'ultimate-form-builder-lite' ); ?></a>
						<?php
					}
					for ( $page = $lower_page_limit; $page <= $upper_page_limit; $page++ ) {
						if ( isset( $_GET['form_id'] ) ) {
							$page_link = admin_url( 'admin.php?page=ufbl-form-entries&form_id=' . $_GET['form_id'] . '&page_num=' . $page );
						} else {
							$page_link = admin_url( 'admin.php?page=ufbl-form-entries&page_num=' . $page );
						}
						?>
						<a href="<?php echo $page_link; ?>" class="ufbl-entry-page-link <?php echo ($current_page == $page) ? 'ufbl-entry-current-page' : ''; ?>"><?php echo $page; ?></a>
						<?php
					}
					if ( $next_page <= $total_pages ) {
						if ( isset( $_GET['form_id'] ) ) {
							$page_link = admin_url( 'admin.php?page=ufbl-form-entries&form_id=' . $_GET['form_id'] . '&page_num=' . $next_page );
						} else {
							$page_link = admin_url( 'admin.php?page=ufbl-form-entries&page_num=' . $next_page );
						}
						?>
						<a class="ufbl-entry-next-page ufbl-entry-page-link" href="<?php echo $page_link; ?>"><?php _e( 'Next', 'ultimate-form-builder-lite' ); ?></a>
						<?php
					}
					?>
				</div>
			</div><?php
		}
		?>
	</div>
	<table class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th scope="col" id="title" class="manage-column column-shortcode">
					<?php _e( 'Form Title', 'ultimate-form-builder-lite' ); ?>
				</th>
				<th scope="col" id="shortcode" class="manage-column column-shortcode">
					<?php _e( 'Entry Recieved', 'ultimate-form-builder-lite' ); ?>
				</th>

			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" id="title" class="manage-column column-shortcode">
					<?php _e( 'Form Title', 'ultimate-form-builder-lite' ); ?>
				</th>
				<th scope="col" id="shortcode" class="manage-column column-shortcode">
					<?php _e( 'Entry Recieved', 'ultimate-form-builder-lite' ); ?>
				</th>

			</tr>
		</tfoot>

		<tbody id="the-list" data-wp-lists="list:post">
			<?php
			if ( count( $form_entry_rows ) > 0 ) {
				$form_counter = 1;
				foreach ( $form_entry_rows as $form_entry_row ) {
					$delete_nonce = wp_create_nonce( 'ufbl-delete-nonce' );
					$copy_nonce = wp_create_nonce( 'ufbl-copy-nonce' );
					?>
					<tr class="<?php if ( $form_counter % 2 != 0 ) { ?>alternate<?php } ?>">
						<td class="title column-title">
							<strong>
								
								<a class="row-title" href="<?php echo admin_url('admin.php?page=ufbl&action=edit-form&form_id='.$form_entry_row['form_id']);?>" title="Edit" data-entry-id="<?php echo $form_entry_row['entry_id']; ?>" target="_blank">
										<?php echo esc_attr( $form_entry_row['form_title'] ); ?>
									</a>
								
							</strong>
							<div class="row-actions ufbl-relative">
								<span class="delete ufbl-delete"><a href="javascript:void(0);">Delete</a> | </span>
								<span class="ufbl-view-entry"><a href="javascript:void(0);" data-entry-id="<?php echo $form_entry_row['entry_id']; ?>"><?php _e( 'View Entry', 'ultimate-form-builder-lite' ); ?></a></span>
								<div class="ufbl-delete-confirmation" style="display:none">
									<p><?php _e( 'Are you sure you want to delete this entry?', 'ultimate-form-builder-lite' ); ?></p>
									<input type="button" value="<?php _e( 'Yes', 'ultimate-form-builder-lite' ); ?>"  data-entry-id="<?php echo $form_entry_row['entry_id']; ?>" class="ufbl-delete-yes ufbl-delete-entry-yes"/>
									<input type="button" value="<?php _e( 'Cancel', 'ultimate-form-builder-lite' ); ?>" class="ufbl-delete-cancel"/>
									<span class="ufbl-ajax-loader" style="display:none;"></span>
								</div>
							</div>
						</td>
						<td class="shortcode column-shortcode"><?php echo $form_entry_row['entry_created']; ?></td>
					</tr>
					<?php
					$form_counter++;
				}
			} else {
				?>
				<tr><td colspan="2"><div class="ufbl-noresult"><?php _e( 'Entries not found for this form.', 'ultimate-form-builder-lite' ); ?></div></td></tr>
				<?php
			}
			?>
		</tbody>
	</table>

</div>
<div class="ufbl-entry-overlay" style="display:none"></div>
<div class="ufbl-entry-wrap"  style="display:none"><span class="ufbl-entry-ajax-loader"></span></div>

