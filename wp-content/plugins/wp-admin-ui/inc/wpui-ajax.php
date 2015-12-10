<?php

function wpui_menu_save_order() {
	
	$wpui_admin_menu_custom_list = get_option( 'wpui_admin_menu_list_option_name' );
	
	$list = $wpui_admin_menu_custom_list;
	$new_order = $_POST['list_items'];
	$new_list = array();
	
	// update order
	foreach($new_order as $v) {
		$new_list[$v] = $v;
	}
	
	// save the new order
	update_option('wpui_admin_menu_list_option_name', $new_list);
	die();
}
add_action('wp_ajax_wpui_menu_update_order', 'wpui_menu_save_order');