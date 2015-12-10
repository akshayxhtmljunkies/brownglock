<?php
/* handle field output */
function wppb_country_select_handler( $output, $form_location, $field, $user_id, $field_check_errors, $request_data ){
    if ( $field['field'] == 'Select (Country)' ){
        $item_title = apply_filters( 'wppb_'.$form_location.'_country_select_custom_field_'.$field['id'].'_item_title', wppb_icl_t( 'plugin profile-builder-pro', 'custom_field_'.$field['id'].'_title_translation', $field['field-title'] ) );
        $item_description = wppb_icl_t( 'plugin profile-builder-pro', 'custom_field_'.$field['id'].'_description_translation', $field['description'] );

        $country_array = wppb_country_select_options( $form_location );

		$old_country_array = array( "Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia, Plurinational State of", "Bonaire, Sint Eustatius and Saba", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cabo Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote dIvoire", "Croatia", "Cuba", "Curacao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran, Islamic Republic of", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic Peoples Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao Peoples Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia, the former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine, State of", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena, Ascension and Tristan da Cunha", "Saint Kitts and Nevis", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre and Miquelon", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Sint Maarten (Dutch part)", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Timor-Leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela, Bolivarian Republic of", "Viet Nam", "Virgin Islands, British", "Virgin Islands, U.S.", "Wallis and Futuna", "Western Sahara", "Yemen", "Zambia", "Zimbabwe" );

		$extra_attr = apply_filters( 'wppb_extra_attribute', '', $field );

		if( $form_location != 'register' ) {
			// change current user country meta_value with country ISO code
			$user_country_option = wppb_user_meta_exists( $user_id, $field['meta-name'] );

			if( $user_country_option != null ) {
				if( in_array( $user_country_option->meta_value, $old_country_array ) ) {
					$country_iso = array_search( $user_country_option->meta_value, $country_array );

					update_user_meta( $user_id, $field['meta-name'], $country_iso );
				}
			}

			$input_value = ( ( $user_country_option != null ) ? $country_array[stripslashes( get_user_meta( $user_id, $field['meta-name'], true ) )] : $country_array[$field['default-option-country']] );
		} else {
			$input_value = ( ! empty( $field['default-option-country'] ) ? $country_array[trim( $field['default-option-country'] )] : '' );
		}

        $input_value = ( isset( $request_data[wppb_handle_meta_name( $field['meta-name'] )] ) ? $country_array[trim( $request_data[wppb_handle_meta_name( $field['meta-name'] )] )] : $input_value );

        if ( $form_location != 'back_end' ){
            $error_mark = ( ( $field['required'] == 'Yes' ) ? '<span class="wppb-required" title="'.wppb_required_field_error($field["field-title"]).'">*</span>' : '' );

            if ( array_key_exists( $field['id'], $field_check_errors ) )
                $error_mark = '<img src="'.WPPB_PLUGIN_URL.'assets/images/pencil_delete.png" title="'.wppb_required_field_error($field["field-title"]).'"/>';

            $output = '
				<label for="'.$field['meta-name'].'">'.$item_title.$error_mark.'</label>
				<select name="'.$field['meta-name'].'" id="'.$field['meta-name'].'" class="custom_field_country_select '. apply_filters( 'wppb_fields_extra_css_class', '', $field ) .'" '. $extra_attr .'>';

            foreach( $country_array as $iso => $country ){
                $output .= '<option value="'.$iso.'"';

                if ( $input_value === $country )
                    $output .= ' selected';

                $output .= '>'.$country.'</option>';
            }

            $output .= '
				</select>';
            if( !empty( $item_description ) )
                $output .= '<span class="wppb-description-delimiter">'.$item_description.'</span>';

        }else{
            $output = '
				<table class="form-table">
					<tr>
						<th><label for="'.$field['meta-name'].'">'.$item_title.'</label></th>
						<td>
							<select name="'.$field['meta-name'].'" class="custom_field_country_select" id="'.$field['meta-name'].'" '. $extra_attr .'>';

            foreach( $country_array as $iso => $country ){
                $output .= '<option value="'.$iso.'"';

                if ( $input_value === $country )
                    $output .= ' selected';

                $output .= '>'.$country.'</option>';
            }

            $output .= '</select>
							<span class="description">'.$item_description.'</span>
						</td>
					</tr>
				</table>';
        }

        return apply_filters( 'wppb_'.$form_location.'_country_select_custom_field_'.$field['id'], $output, $form_location, $field, $user_id, $field_check_errors, $request_data, $input_value );
    }
}
add_filter( 'wppb_output_form_field_select-country', 'wppb_country_select_handler', 10, 6 );
add_filter( 'wppb_admin_output_form_field_select-country', 'wppb_country_select_handler', 10, 6 );


/* handle field save */
function wppb_save_country_select_value( $field, $user_id, $request_data, $form_location ){
    if( $field['field'] == 'Select (Country)' ){
        if ( isset( $request_data[wppb_handle_meta_name( $field['meta-name'] )] ) )
            update_user_meta( $user_id, $field['meta-name'], $request_data[wppb_handle_meta_name( $field['meta-name'] )] );
    }
}
add_action( 'wppb_save_form_field', 'wppb_save_country_select_value', 10, 4 );
add_action( 'wppb_backend_save_form_field', 'wppb_save_country_select_value', 10, 4 );


/* handle field validation */
function wppb_check_country_select_value( $message, $field, $request_data, $form_location ){
    if( $field['field'] == 'Select (Country)' ){
        if( $field['required'] == 'Yes' ){
            if ( ( isset( $request_data[wppb_handle_meta_name( $field['meta-name'] )] ) && ( trim( $request_data[wppb_handle_meta_name( $field['meta-name'] )] ) == '' ) ) || !isset( $request_data[wppb_handle_meta_name( $field['meta-name'] )] ) ){
                return wppb_required_field_error($field["field-title"]);
            }
        }
    }

    return $message;
}
add_filter( 'wppb_check_form_field_select-country', 'wppb_check_country_select_value', 10, 4 );