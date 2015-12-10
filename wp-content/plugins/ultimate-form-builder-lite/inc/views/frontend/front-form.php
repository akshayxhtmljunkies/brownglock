<?php
$form_detail = maybe_unserialize( $form_row['form_detail'] );
$form_design = $form_detail['form_design'];
$form_template_class = (!isset( $form_design['disable_plugin_style'] )) ? $form_design['form_template'] : 'ufbl-no-design-template';
$form_width = (isset( $form_design['form_width'] ) && $form_design['form_width'] != '') ? esc_attr( $form_design['form_width'] ) : '100%';
//self::print_array( $form_design );
?>
<div class="ufbl-form-wrapper <?php echo $form_template_class; ?>" style="width:<?php echo $form_width; ?>;">
    <form method="post" action="" class="ufbl-front-form">
		<?php if ( !isset( $form_design['hide_form_title'] ) ) { ?><div class="ufbl-form-title"><?php echo (isset( $form_row['form_title'] ) && $form_row['form_title'] != '') ? esc_attr( $form_row['form_title'] ) : __( 'Contact Form', 'ultimate-form-builder-lite' ); ?></div><?php } ?>
		<?php do_action( 'ufbl_form_start' ); ?>
		<input type="hidden" name="form_id" value="<?php echo $form_row['form_id']; ?>" class="form-id"/>
		<?php
		foreach ( $form_detail['field_data'] as $key => $val ) {
			if ( isset( $val['field_type'] ) ) {
				$class = (isset( $val['required'] ) && $val['required'] == 1) ? 'ufbl-required' : '';
				switch ( $val['field_type'] ) {
					case 'textfield':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<input type="text" name="<?php echo $key; ?>" class="ufbl-form-textfield <?php echo $class; ?>" data-max-chars="<?php echo esc_attr( $val['max_chars'] ); ?>" data-min-chars="<?php echo esc_attr( $val['min_chars'] ); ?>" data-error-message="<?php echo esc_attr( $val['error_message'] ); ?>" placeholder="<?php echo esc_attr( $val['placeholder'] ); ?>" value="<?php echo esc_attr( $val['pre_filled_value'] ); ?>"/>
								<div class="ufbl-error"  data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>	
						<?php
						break;
					case 'textarea':
						$rows = ($val['textarea_rows'] == '') ? 5 : $val['textarea_rows'];
						$cols = ($val['textarea_columns'] == '') ? 10 : $val['textarea_columns'];
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<textarea name="<?php echo $key; ?>" class="ufbl-form-textarea <?php echo $class; ?>" data-max-chars="<?php echo esc_attr( $val['max_chars'] ); ?>" data-min-chars="<?php echo esc_attr( $val['min_chars'] ); ?>" data-error-message="<?php echo esc_attr( $val['error_message'] ); ?>" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" placeholder="<?php echo $val['placeholder']; ?>"><?php echo esc_attr( $val['pre_filled_value'] ); ?></textarea>
								<div class="ufbl-error"  data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					case 'email':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<input type="email" name="<?php echo $key; ?>" class="ufbl-email-field <?php echo $class; ?>" data-error-message="<?php esc_attr( $val['error_message'] ); ?>" placeholder="<?php echo esc_attr( $val['placeholder'] ); ?>" value="<?php echo esc_attr( $val['pre_filled_value'] ); ?>"/>
								<div class="ufbl-error"  data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					case 'dropdown':
						$multiple = (isset( $val['multiple'] ) && $val['multiple'] == 1) ? 'multiple' : '';
						$name = (isset( $val['multiple'] ) && $val['multiple'] == 1) ? $key . '[]' : $key;
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<select name="<?php echo $name; ?>" class="ufbl-form-dropdown <?php echo $class; ?>" <?php echo $multiple;?>>
									<?php
									if ( isset( $val['option'] ) && count( $val['option'] ) > 0 ) {
										$count = 0;
										foreach ( $val['option'] as $option ) {
											?>
											<option value="<?php echo $val['value'][$count] ?>"><?php echo $option; ?></option>
											<?php
											$count++;
										}
									}
									?>
								</select>

								<div class="ufbl-error" data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					case 'radio':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<?php
								if ( isset( $val['option'] ) && count( $val['option'] ) > 0 ) {
									$count = 0;
									foreach ( $val['option'] as $option ) {
										$for_id = $form_row['form_id'] . '-' . $key . '-' . $count;
										?>
										<div class="ufbl-sub-field-wrap"><input type="radio" value="<?php echo $val['value'][$count] ?>" name="<?php echo $key ?>" class="ufbl-form-radio <?php echo $class; ?>" id="<?php echo $for_id; ?>"/><label for="<?php echo $for_id; ?>"><?php echo $option; ?></label></div>
										<?php
										$count++;
									}
								}
								?>
								<div class="ufbl-error"  data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					case 'checkbox':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<?php
								if ( isset( $val['option'] ) && count( $val['option'] ) > 0 ) {
									$count = 0;
									foreach ( $val['option'] as $option ) {
										$for_id = $form_row['form_id'] . '-' . $key . '-' . $count;
										?>
										<div class="ufbl-sub-field-wrap"><input type="checkbox" value="<?php echo $val['value'][$count] ?>" name="<?php echo $key ?>[]" class="ufbl-form-checkbox <?php echo $class; ?>" id="<?php echo $for_id; ?>"/><label for="<?php echo $for_id; ?>"><?php echo $option; ?></label></div>
										<?php
										$count++;
									}
								}
								?>
								<div class="ufbl-error" data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					case 'password':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<input type="password" name="<?php echo $key; ?>" class="ufbl-form-password <?php echo $class; ?>" data-max-chars="<?php echo esc_attr( $val['max_chars'] ); ?>" data-min-chars="<?php echo esc_attr( $val['min_chars'] ); ?>" data-error-message="<?php echo esc_attr( $val['error_message'] ); ?>" placeholder="<?php echo esc_attr( $val['placeholder'] ); ?>"/>
								<div class="ufbl-error"  data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>	
						<?php
						break;
					case 'hidden':
						?>
						<input type="hidden" name="<?php echo $key ?>" value="<?php echo esc_attr( $val['pre_filled_value'] ); ?>" id="<?php echo esc_attr( $val['field_id'] ); ?>" class="<?php echo esc_attr( $val['field_class'] ); ?>"/>
						<?php
						break;
					case 'number':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<input type="number" name="<?php echo $key; ?>" class="ufbl-number-field <?php echo $class; ?>" data-error-message="<?php echo esc_attr( $val['error_message'] ); ?>" placeholder="<?php echo esc_attr( $val['placeholder'] ); ?>" value="<?php echo esc_attr( $val['pre_filled_value'] ); ?>" data-max-chars="<?php echo esc_attr( $val['max_value'] ); ?>" data-min-chars="<?php echo esc_attr( $val['min_value'] ); ?>"/>
								<div class="ufbl-error" data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					case 'submit':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<div class="ufbl-form-field">
								<input type="submit" class="ufbl-form-submit" name="<?php echo $key; ?>" value="<?php echo (isset( $val['button_label'] ) && $val['button_label'] != '') ? esc_attr( $val['button_label'] ) : __( 'Submit', 'ultimate-form-builder-lite' ); ?>"/>
								<?php if(isset($val['show_reset_button'])){?>
								<input type="reset" class="ufbl-form-reset" value="<?php echo (isset( $val['reset_label'] ) && $val['reset_label'] != '') ? esc_attr( $val['reset_label'] ) : __( 'Reset', 'ultimate-form-builder-lite' ); ?>"/>
								<?php }?>
								<span class="ufbl-form-loader" style="display:none"></span>
							</div>
						</div>
						<?php
						break;
					case 'captcha':
						?>
						<div class="ufbl-form-field-wrap <?php echo ($val['field_class'] != '') ? esc_attr( $val['field_class'] ) : ''; ?>" <?php echo ($val['field_id'] != '') ? 'id="' . esc_attr( $val['field_id'] ) . '"' : ''; ?>>
							<label><?php echo esc_attr( $val['field_label'] ); ?></label>
							<div class="ufbl-form-field">
								<?php
								if ( $val['captcha_type'] == 'mathematical' ) {
									$num1 = rand( 0, 9 );
									$num2 = rand( 0, 9 );
									?>
									<div class="ufbl-math-captcha-wrap">
										<span class="ufbl-captcha-num"><?php echo $num1; ?></span> + <span><?php echo $num2; ?></span> = <input type="text" name="<?php echo $key; ?>" class="ufbl-math-captcha-ans" placeholder="<?php echo (isset( $val['placeholder'] ) && $val['placeholder'] != '') ? esc_attr( $val['placeholder'] ) : __( 'Enter Sum', 'ultimate-form-builder-lite' ); ?>"/>
										<input type="hidden" name="<?php echo $key ?>_num_1" value="<?php echo $num1 ?>"/>
										<input type="hidden" name="<?php echo $key ?>_num_2" value="<?php echo $num2 ?>"/>
									</div>
								<?php } else {
									?>
									<script src="https://www.google.com/recaptcha/api.js"></script>
									<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $val['site_key'] ); ?>"></div>
									<input type="hidden" name="<?php echo $key ?>"/>
								<?php }
								?>

								<div class="ufbl-error" data-error-key="<?php echo $key; ?>"></div>
							</div>
						</div>
						<?php
						break;
					default:
						break;
				} //switch close
			}//foreach close
		}
		?>
		<div class="ufbl-form-message" style="display: none;"></div>

		<?php do_action( 'ufbl_form_end' ); ?>
	</form>

</div>

