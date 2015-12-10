<?php
$form_id = $atts['form_id'];
global $wpdb;
$table_name = UFBL_FORM_TABLE;
$form_row = $wpdb->get_row( "SELECT * FROM $table_name WHERE form_id = $form_id" );
self::print_array($form_row);
