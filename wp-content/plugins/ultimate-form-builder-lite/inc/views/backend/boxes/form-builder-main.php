<div class="ufbl-tab-content" id="ufbl-form-builder-tab">
	<div class="ufbl-form-element-outerwrap">
		<div class="ufbl-form-elements-wrap">
			<div class="ufbl-form-elements-inner-wrap ufbl-relative">
				<span class="ufbl-form-element-title">Form Elements</span>
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Single Line Textfield', 'ultimate-form-builder-lite' ); ?>" data-field-type="textfield">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Multiple Line Textfield', 'ultimate-form-builder-lite' ); ?>" data-field-type="textarea">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Email Address', 'ultimate-form-builder-lite' ); ?>" data-field-type="email">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Dropdown Menu', 'ultimate-form-builder-lite' ); ?>" data-field-type="dropdown">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Radio Button', 'ultimate-form-builder-lite' ); ?>" data-field-type="radio">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Checkbox', 'ultimate-form-builder-lite' ); ?>" data-field-type="checkbox">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Password', 'ultimate-form-builder-lite' ); ?>" data-field-type="password">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Hidden Field', 'ultimate-form-builder-lite' ); ?>" data-field-type="hidden">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Number Field', 'ultimate-form-builder-lite' ); ?>" data-field-type="number">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Submit Button', 'ultimate-form-builder-lite' ); ?>" data-field-type="submit">
				<input type="button" class="button-primary ufbl-form-element" value="<?php _e( 'Captcha', 'ultimate-form-builder-lite' ); ?>" data-field-type="captcha">

			</div>

		</div>
	</div>

	<input type="hidden" name="action" value="ufbl_form_action"/>
	<div class="ufbl-form-wrap">
		<div class="ufbl-form-innner-wrap ufbl-relative">
			<span class="ufbl-form-title"><?php echo esc_attr( $form_row['form_title'] ); ?></span>
			<div class="ufbl-form-field-holder">
				<?php
				global $library_obj;
				//$library_obj->print_array( $form_row );
				$form_detail = maybe_unserialize( $form_row['form_detail'] );
				//$library_obj->print_array( $form_detail );
				if ( !empty( $form_detail ) ) {
					//$library_obj->print_array( $form_detail );
					foreach ( $form_detail['field_data'] as $key => $val ) {
//						$library_obj->print_array($val);
						switch ( $val['field_type'] ) {
							case 'textfield':
								?>
								<!--Text Field Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Texfield', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<input type="text" disabled="disabled"/>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Name', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field ufbl-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="required" <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Max Characters', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][max_chars]" placeholder='50' data-field-name="<?php echo $key; ?>" data-field-type="max_chars" value="<?php echo (isset( $val['max_chars'] )) ? esc_attr( $val['max_chars'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Min Characters', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][min_chars]" placeholder='20' data-field-name="<?php echo $key; ?>" data-field-type="min_chars" value="<?php echo (isset( $val['min_chars'] )) ? esc_attr( $val['min_chars'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill your name', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message" value="<?php echo (isset( $val['error_message'] )) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Placeholder', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][placeholder]" placeholder='<?php _e( 'Your Name here', 'ultimate-form-builder-lite' ); ?>' data-field-name="<?php echo $key; ?>" data-field-type="placeholder" value="<?php echo (isset( $val['placeholder'] )) ? esc_attr( $val['placeholder'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Pre filled value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][pre_filled_value]"  data-field-name="<?php echo $key; ?>" data-field-type="pre_filled_value" value="<?php echo (isset( $val['pre_filled_value'] )) ? esc_attr( $val['pre_filled_value'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id" value="<?php echo (isset( $val['field_id'] )) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class" value="<?php echo (isset( $val['field_class'] )) ? esc_attr( $val['field_class'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="textfield" data-field-name="<?php echo $key; ?>" data-field-type="field_type" value="<?php echo (isset( $val['field_type'] )) ? esc_attr( $val['field_type'] ) : ''; ?>"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Text Field Reference Field --->
								<?php
								break;
							case 'textarea':
								?>
								<!--Text Area Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Texfield', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<textarea disabled="disabled"></textarea>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Message', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_type" value="<?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="field_type" <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill your name', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message" value="<?php echo (isset( $val['error_message'] )) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Textarea Rows', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][textarea_rows]" placeholder='5' data-field-name="<?php echo $key; ?>" data-field-type="textarea_rows"  value="<?php echo (isset( $val['textarea_rows'] )) ? esc_attr( $val['textarea_rows'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Textarea Columns', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][textarea_columns]" placeholder='20' data-field-name="<?php echo $key; ?>" data-field-type="textarea_columns" value="<?php echo (isset( $val['textarea_columns'] )) ? esc_attr( $val['textarea_columns'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Max Characters', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][max_chars]" placeholder='50' data-field-name="<?php echo $key; ?>" data-field-type="max_chars" value="<?php echo (isset( $val['max_chars'] )) ? esc_attr( $val['max_chars'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Min Characters', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][min_chars]" placeholder='20' data-field-name="<?php echo $key; ?>" data-field-type="min_chars" value="<?php echo (isset( $val['min_chars'] )) ? esc_attr( $val['min_chars'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Placeholder', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][placeholder]" placeholder='<?php _e( 'Your message here', 'ultimate-form-builder-lite' ); ?>' data-field-name="<?php echo $key; ?>" data-field-type="placeholder" value="<?php echo (isset( $val['placeholder'] )) ? esc_attr( $val['placeholder'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Pre filled value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][pre_filled_value]" data-field-name="<?php echo $key; ?>" data-field-type="pre_filled_value" value="<?php echo (isset( $val['pre_filled_value'] )) ? esc_attr( $val['pre_filled_value'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id" value="<?php echo (isset( $val['field_id'] )) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class" value="<?php echo (isset( $val['field_class'] )) ? esc_attr( $val['field_class'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="textarea" data-field-name="<?php echo $key; ?>" data-field-type="field_type" value="<?php echo (isset( $val['field_type'] )) ? esc_attr( $val['field_type'] ) : ''; ?>"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Text area reference -->
								<?php
								break;
							case 'email':
								?>
								<!--Email Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Email', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<input type="email" disabled="disabled"/>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Email', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="required" <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill your name', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message"  value="<?php echo (isset( $val['error_message'] )) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Placeholder', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][placeholder]" placeholder='<?php _e( 'Your Email here', 'ultimate-form-builder-lite' ); ?>' data-field-name="<?php echo $key; ?>" data-field-type="placeholder" value="<?php echo (isset( $val['placeholder'] )) ? esc_attr( $val['placeholder'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Pre filled value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][pre_filled_value]" data-field-name="<?php echo $key; ?>" data-field-type="pre_filled_value" value="<?php echo (isset( $val['pre_filled_value'] )) ? esc_attr( $val['pre_filled_value'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id" value="<?php echo (isset( $val['field_id'] )) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_type" value="<?php echo (isset( $val['field_type'] )) ? esc_attr( $val['field_type'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="email" data-field-name="<?php echo $key; ?>" data-field-type="field_type" value="<?php echo (isset( $val['field_type'] )) ? esc_attr( $val['field_type'] ) : ''; ?>"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Email Field reference-->
								<?php
								break;
							case 'dropdown':
								?>
								<!--Dropdown Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Dropdown', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<select disabled="disabled">
												<option><?php _e( 'Option 1', 'ultimate-form-builder-lite' ) ?></option>
											</select>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Country', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo (isset( $val['field_label'] )) ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="field_type" <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill your name', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message" value="<?php echo isset( $val['error_message'] ) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap ufbl-full-width ufbl-op-wrap">
											<label><?php _e( 'Options', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="button" value="<?php _e( 'Add Option', 'ultimate-form-builder-lite' ); ?>" class="ufbl-option-value-adder button-primary" data-field-key="<?php echo $key; ?>"/>
												<div class="ufbl-option-value-wrap">
													<?php
													if ( isset( $val['option'], $val['value'] ) ) {
														$value_index = 0;
														foreach ( $val['option'] as $option ) {
															?>
															<div class="ufbl-each-option">
																<span class="ufbl-option-drag-arrow"><i class="fa fa-arrows"></i></span>
																<input type="text" name="field_data[<?php echo $key; ?>][option][]"  placeholder="Option" data-field-name="<?php echo $key; ?>" value="<?php echo $option; ?>"/>
																<input type="text" name="field_data[<?php echo $key; ?>][value][]"  placeholder="Value" data-field-name="<?php echo $key; ?>"  value="<?php echo $val['value'][$value_index]; ?>"/>
																<span class="ufbl-option-remover">X</span>
															</div>
															<?php
															$value_index++;
														}
													}
													?>
												</div>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Multiple', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][multiple]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="multiple" <?php checked($val['multiple'],true);?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id" value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class" value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="dropdown" data-field-name="<?php echo $key; ?>" data-field-type="field_type"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Dropdown Reference Field --->
								<?php
								break;
							case 'radio':
								?>
								<!--Radio Button Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Radio', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<label><input type="radio" checked="checked" disabled="disabled"><?php _e( 'Option 1', 'ultimate-form-builder-lite' ); ?></label>
											<label><input type="radio" disabled="disabled"><?php _e( 'Option 2', 'ultimate-form-builder-lite' ); ?></label>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Gender', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1"  <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill your name', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message"  value="<?php echo (isset( $val['error_message'] ) && $val['error_message'] != '') ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap ufbl-full-width ufbl-op-wrap">
											<label><?php _e( 'Options', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="button" value="<?php _e( 'Add Option', 'ultimate-form-builder-lite' ); ?>" class="ufbl-option-value-adder button-primary" data-field-key="<?php echo $key; ?>"/>
												<div class="ufbl-option-value-wrap">
													<?php
													if ( isset( $val['option'], $val['value'] ) ) {
														$value_index = 0;
														foreach ( $val['option'] as $option ) {
															?>
															<div class="ufbl-each-option">
																<span class="ufbl-option-drag-arrow"><i class="fa fa-arrows"></i></span>
																<input type="text" name="field_data[<?php echo $key; ?>][option][]"  placeholder="Option" data-field-name="<?php echo $key; ?>" value="<?php echo $option; ?>"/>
																<input type="text" name="field_data[<?php echo $key; ?>][value][]"  placeholder="Value" data-field-name="<?php echo $key; ?>"  value="<?php echo $val['value'][$value_index]; ?>"/>
																<span class="ufbl-option-remover">X</span>
															</div>
															<?php
															$value_index++;
														}
													}
													?>



												</div>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id" value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class"  value="<?php echo isset( $val['field_class'] ) ? esc_attr( $val['field_class'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="radio" data-field-name="<?php echo $key; ?>" data-field-type="field_type"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Radio Button Reference Field --->
								<?php
								break;
							case 'checkbox':
								?>
								<!--Checkbox Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Checkbox', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<label><input type="checkbox" disabled="disabled"><?php _e( 'Option 1', 'ultimate-form-builder-lite' ); ?></label>
											<label><input type="checkbox" disabled="disabled"><?php _e( 'Option 2', 'ultimate-form-builder-lite' ); ?></label>
											<label><input type="checkbox" disabled="disabled"><?php _e( 'Option 3', 'ultimate-form-builder-lite' ); ?></label>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Hobbies', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo isset( $val['field_label'] ) ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="field_label"  <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill your name', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message"  value="<?php echo isset( $val['field_label'] ) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap ufbl-full-width ufbl-op-wrap">
											<label><?php _e( 'Options', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="button" value="<?php _e( 'Add Option', 'ultimate-form-builder-lite' ); ?>" class="ufbl-option-value-adder button-primary" data-field-key="<?php echo $key; ?>"/>
												<div class="ufbl-option-value-wrap">
													<?php
													if ( isset( $val['option'], $val['value'] ) ) {
														$value_index = 0;
														foreach ( $val['option'] as $option ) {
															?>
															<div class="ufbl-each-option">
																<span class="ufbl-option-drag-arrow"><i class="fa fa-arrows"></i></span>
																<input type="text" name="field_data[<?php echo $key; ?>][option][]"  placeholder="Option" data-field-name="<?php echo $key; ?>" value="<?php echo $option; ?>"/>
																<input type="text" name="field_data[<?php echo $key; ?>][value][]"  placeholder="Value" data-field-name="<?php echo $key; ?>"  value="<?php echo $val['value'][$value_index]; ?>"/>
																<span class="ufbl-option-remover">X</span>
															</div>
															<?php
															$value_index++;
														}
													}
													?>
												</div>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id"   value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class"   value="<?php echo isset( $val['field_class'] ) ? esc_attr( $val['field_class'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="checkbox" data-field-name="<?php echo $key; ?>" data-field-type="field_type" />
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Checkbox Reference Field --->
								<?php
								break;
							case 'password':
								?>
								<!--Password Reference Field --->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Password', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<input type="password" disabled="disabled"/>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Password', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo isset( $val['field_label'] ) ? esc_attr( $val['field_label'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="field_label"  <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Max Characters', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][max_chars]" placeholder='50' data-field-name="<?php echo $key; ?>" data-field-type="max_chars"  value="<?php echo isset( $val['max_chars'] ) ? esc_attr( $val['max_chars'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Min Characters', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][min_chars]" placeholder='20' data-field-name="<?php echo $key; ?>" data-field-type="min_chars"   value="<?php echo isset( $val['min_chars'] ) ? esc_attr( $val['min_chars'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill number only', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message" value="<?php echo isset( $val['error_message'] ) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Placeholder', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][placeholder]" placeholder='<?php _e( 'Your Password here', 'ultimate-form-builder-lite' ); ?>' data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo isset( $val['placeholder'] ) ? esc_attr( $val['placeholder'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id"  value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class" value="<?php echo isset( $val['field_class'] ) ? esc_attr( $val['field_class'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="password" data-field-name="<?php echo $key; ?>" data-field-type="field_type"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Password Reference Field --->
								<?php
								break;
							case 'hidden':
								?>
								<!--Hidden Reference Field-->

								<div class="ufbl-each-form-field ufbl-submit-button-wrap ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Hidden', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<input type="hidden" disabled="disabled"/>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo isset( $val['field_label'] ) ? esc_attr( $val['field_label'] ) : '' ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Pre filled value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][pre_filled_value]" data-field-name="<?php echo $key; ?>" data-field-type="pre_filled_value" value="<?php echo isset( $val['pre_filled_value'] ) ? esc_attr( $val['pre_filled_value'] ) : '' ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id"  value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : '' ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class" value="<?php echo isset( $val['field_class'] ) ? esc_attr( $val['field_class'] ) : '' ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="hidden" data-field-name="<?php echo $key; ?>" data-field-type="field_label"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Hidden Reference Field-->
								<?php
								break;
							case 'number':
								?>
								<!--Number Reference Field-->

								<div class="ufbl-each-form-field ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Number', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">
											<input type="number" disabled="disabled"/>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Your Number', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="<?php echo $key; ?>" data-field-type="field_label" value="<?php echo esc_attr( $val['field_label'] ); ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Required', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="checkbox" name="field_data[<?php echo $key; ?>][required]" value="1" data-field-name="<?php echo $key; ?>" data-field-type="field_label"  <?php echo (isset( $val['required'] ) && $val['required'] == 1) ? 'checked="checked"' : ''; ?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Max Value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][max_value]" placeholder='50' data-field-name="<?php echo $key; ?>" data-field-type="max_value" value="<?php echo isset( $val['max_value'] ) ? $val['max_value'] : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Min Value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][min_value]" placeholder='20' data-field-name="<?php echo $key; ?>" data-field-type="min_value"  value="<?php echo isset( $val['min_value'] ) ? $val['min_value'] : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please fill number only', 'ultimate-form-builder-lite' ); ?>" data-field-name="<?php echo $key; ?>" data-field-type="error_message"  value="<?php echo esc_attr( $val['error_message'] ); ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Placeholder', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][placeholder]" placeholder='<?php _e( 'Your age here', 'ultimate-form-builder-lite' ); ?>' data-field-name="<?php echo $key; ?>" data-field-type="field_label"   value="<?php echo esc_attr( $val['placeholder'] ); ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Pre filled value', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][pre_filled_value]"  data-field-name="<?php echo $key; ?>" data-field-type="pre_filled_value" value="<?php echo (isset( $val['pre_filled_value'] )) ? esc_attr( $val['pre_filled_value'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_label"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_label"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="number" data-field-name="<?php echo $key; ?>" data-field-type="field_label"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Number Reference Field-->
								<?php
								break;
							case 'submit':
								?>
								<!--Submit Reference Field-->

								<div class="ufbl-each-form-field ufbl-submit-button-wrap ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<div class="ufbl-form-field">
											<input type="submit" disabled="disabled" class="button-primary ufbl-submit-reference" value="<?php echo ($val['button_label'] == '') ? __( 'Submit', 'ultimate-form-builder-lite' ) : esc_attr( $val['button_label'] ); ?>"/>
										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger" data-confirm-message="<?php _e( 'If you delete this element then data related with this element will also be deleted. Are you sure you want to delete this element?', 'ultimate-form-builder-lite' ); ?>">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Submit Button label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][button_label]" class="ufbl-submit-button" value="<?php echo esc_attr( $val['button_label'] ); ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Show Reset Button', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<?php $show_reset_button = isset($val['show_reset_button'])?1:0;?>
												<input type="checkbox" name="field_data[<?php echo $key; ?>][show_reset_button]" value="1" <?php checked( $show_reset_button,true);?>/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Reset Button label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][reset_label]" value="<?php echo isset($val['reset_label'])?esc_attr($val['reset_label']):'';?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="<?php echo $key; ?>" data-field-type="field_id" value="<?php echo esc_attr( $val['field_id'] ); ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="<?php echo $key; ?>" data-field-type="field_class"  value="<?php echo esc_attr( $val['field_class'] ); ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="submit" data-field-name="<?php echo $key; ?>" data-field-type="field_type"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<!--Submit Reference Field-->
								<?php
								break;
							case 'captcha':
								?>

								<div class="ufbl-each-form-field ufbl-submit-button-wrap ufbl-relative">
									<span class="ufbl-drag-arrow"><i class="fa fa-arrows"></i></span>
									<div class="ufbl-form-field-wrap">
										<label class="ufbl-field-label-ref"><?php echo (isset( $val['field_label'] ) && $val['field_label'] != '') ? esc_attr( $val['field_label'] ) : __( 'Untitled Captcha', 'ultimate-form-builder-lite' ); ?></label>
										<div class="ufbl-form-field">

										</div>
									</div><!--ufbl-form-field-wrap-->
									<div class="ufbl-field-controls">
										<a href="javascript:void(0)" class="ufbl-field-settings-trigger button-primary"><?php _e( 'Settings', 'ultimate-form-builder-lite' ); ?></a><span>+</span>
										<a href="javascript:void(0)" class="ufbl-field-delete-trigger">Delete</a>
									</div>
									<div class="ufbl-field-settings-wrap" style="display:none;">
										<span class="ufbl-up-arrow"></span>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Field Label', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_label]" placeholder="<?php _e( 'Human Check', 'ultimate-form-builder-lite' ); ?>" class="ufbl-field-label-field" data-field-name="ufbl_key" data-field-type="field_label" value="<?php echo esc_attr( $val['field_label'] ); ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Captcha Type', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<select name="field_data[<?php echo $key; ?>][captcha_type]" class="ufbl-captcha-type-dropdown">
													<option value="mathematical" <?php selected( $val['captcha_type'], 'mathematical' ); ?>><?php _e( 'Mathematical Captcha', 'ultimate-form-builder-lite' ); ?></option>
													<option value="google" <?php selected( $val['captcha_type'], 'google' ); ?>><?php _e( 'Google reCaptcha', 'ultimate-form-builder-lite' ); ?></option>
												</select>
											</div>
										</div>
										<div class="ufbl-captcha-field-ref" <?php if ( $val['captcha_type'] == 'mathematical' ) { ?>style="display:none;"<?php } ?>>
											<div class="ufbl-form-field-wrap">
												<label><?php _e( 'Site Key', 'ultimate-form-builder-lite' ) ?></label>
												<div class="ufbl-form-field">
													<input type="text" name="field_data[<?php echo $key; ?>][site_key]" value="<?php echo isset( $val['site_key'] ) ? esc_attr( $val['site_key'] ) : ''; ?>"/>
												</div>
											</div>
											<div class="ufbl-form-field-wrap">
												<label><?php _e( 'Secret Key', 'ultimate-form-builder-lite' ) ?></label>
												<div class="ufbl-form-field">
													<input type="text" name="field_data[<?php echo $key; ?>][secret_key]" value="<?php echo isset( $val['secret_key'] ) ? esc_attr( $val['secret_key'] ) : ''; ?>"/>
												</div>
											</div>
											<div class="ufbl-field-extra-note">
												<?php
												_e( 'Google Captcha will only show up in form filled the valid google captcha keys.Please visit <a href="https://www.google.com/recaptcha/admin" target="_blank">here</a> to get your site and secret key.', 'ultimate-form-builder-lite' );
												?>

											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Error Message', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][error_message]" placeholder="<?php _e( 'Please verify you are human.', 'ultimate-form-builder-lite' ); ?>" value="<?php echo isset( $val['error_message'] ) ? esc_attr( $val['error_message'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Placeholder', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][placeholder]" placeholder='<?php _e( 'Enter Sum', 'ultimate-form-builder-lite' ); ?>' value="<?php echo isset( $val['placeholder'] ) ? esc_attr( $val['placeholder'] ) : ''; ?>"/>
												<div class="ufbl-field-note"><?php _e( 'Note: Placeholder is only for the mathematical type captcha.' ); ?></div>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'ID of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_id]" data-field-name="ufbl_key" data-field-type="field_label"  value="<?php echo isset( $val['field_id'] ) ? esc_attr( $val['field_id'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="ufbl-form-field-wrap">
											<label><?php _e( 'Class of the field', 'ultimate-form-builder-lite' ); ?></label>
											<div class="ufbl-form-field">
												<input type="text" name="field_data[<?php echo $key; ?>][field_class]" data-field-name="ufbl_key" data-field-type="field_class"  value="<?php echo isset( $val['field_class'] ) ? esc_attr( $val['field_class'] ) : ''; ?>"/>
											</div>
										</div>
										<input type="hidden" name="field_data[<?php echo $key; ?>][field_type]" value="captcha" data-field-name="ufbl_key" data-field-type="field_label"/>
									</div>
								</div><!--ufbl-each-form-field-->

								<?php
								break;
							default:
								break;
						}
					}
				}
				?>
			</div>
			<input type="hidden" name="form_title" value="<?php echo esc_attr( $form_row['form_title'] ); ?>" class="ufbl-form-title-field"/>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $form_row['form_id'] ); ?>" class="ufbl-form-id"/>
			<input type="hidden" name="form_key_count" value="<?php echo (isset( $form_detail['form_key_count'] ) && $form_detail['form_key_count'] != '') ? $form_detail['form_key_count'] : 0; ?>" class="ufbl-form-key-count"/>
		</div>
	</div>
	<?php wp_nonce_field( 'ufbl-form-nonce', 'ufbl_form_nonce_field' ); ?>

	<div class="ufbl-clear"></div>
	<div class="ufbl-form-controls ufbl-text-align-right">
		<input type="button" class="button-primary ufbl-save-form" value="<?php _e( 'Save Form', 'ultimate-form-builder-lite' ); ?>"/>
		<a href="<?php echo site_url( '?ufbl_form_preview=true&ufbl_form_id=' . $form_row['form_id'] ); ?>" target="_blank"><input type="button" class="button-primary" value="<?php _e( 'Preview', 'ultimate-form-builder-lite' ); ?>"/></a>
		<div class="ufbl-field-note"><?php _e( 'Note: Please save form before preview.', 'ultimate-form-builder-lite' ); ?></div>
	</div>
	<div class="ufbl-clear"></div>
</div>

