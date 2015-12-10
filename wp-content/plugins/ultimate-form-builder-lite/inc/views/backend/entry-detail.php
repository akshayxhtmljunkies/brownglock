<div class="ufbl-entry-detail-wrap">
	<div class="ufbl-relative">
		<span class="ufbl-entry-detail-close">X</span>
		<?php
		if ( $entry_row != NULL && !empty( $entry_row ) ) {
			?>
			<h3><?php echo esc_attr( $entry_row['form_title'] ); ?></h3>
			<div class="ufbl-entry-inner-wrap">
				<?php
				$form_detail = maybe_unserialize( $entry_row['form_detail'] );
				if ( !empty( $form_detail ) ) {
					$field_data = $form_detail['field_data'];
					$entry_detail = maybe_unserialize( $entry_row['entry_detail'] );
					$except_field_types = array( 'submit', 'captcha' );
					$field_count = 0;
					?>
					<div class="ufbl-entry-field-wrap <?php echo ($field_count % 2 == 0) ? 'ufbl-entry-even' : ''; ?>">
						<label><?php _e( 'Entry Posted Date', 'ultimate-form-builder-lite' ); ?></label>
						<div class="ufbl-entry-value"><?php echo esc_attr( $entry_row['entry_created'] ); ?></div>
					</div>
					<?php
					foreach ( $field_data as $field_key => $field_settings ) {
						$field_count++;
						if ( !in_array( $field_settings['field_type'], $except_field_types ) ) {
							$field_label = ($field_settings['field_label'] != '') ? esc_attr( $field_settings['field_label'] ) : __( 'Untitled', 'ultimate-form-builder-lite' ) . ' ' . $field_settings['field_type'];
							if ( isset( $entry_detail[$field_key] ) ) {
								if ( is_array( $entry_detail[$field_key] ) ) {
									$entry_detail[$field_key] = array_map( 'esc_attr', $entry_detail[$field_key] );
									$entry_value = implode( ', ', $entry_detail[$field_key] );
								} else {
									$entry_value = ($entry_detail[$field_key] != '') ? esc_attr( $entry_detail[$field_key] ) : '';
								}
							} else {
								$entry_value = '';
							}
							?>
							<div class="ufbl-entry-field-wrap <?php echo ($field_count % 2 == 0) ? 'ufbl-entry-even' : ''; ?>">
								<label><?php echo $field_label; ?></label>
								<div class="ufbl-entry-value"><?php echo $entry_value; ?></div>
							</div>
							<?php
						}
					}
					//self::print_array( $field_data );
					//self::print_array( $entry_detail );
				}
			} else {
				?>
				<p><?php _e( "It seems that you have deleted the form of this entry.Entry not found in database!", 'ultimate-form-builder-lite' ); ?></p>
				<?php
			}
			?>
		</div>
	</div>
</div>

