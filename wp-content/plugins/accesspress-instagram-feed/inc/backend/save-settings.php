<?php
defined('ABSPATH') or die("No script kiddies please!");

//$this->print_array($_POST);die();
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// die();
/**
[instagram] => Array
        (
            [username] => sujit.kayastha
            [user_id] => asdfas
            [access_token] => asdfasdf
            [instagram_mosaic] => mosaic
        )

**/
foreach($_POST['instagram'] as $key=>$val){
	$$key = sanitize_text_field($val);
}

$apif_settings = array();
$apif_settings['username'] = $username;
$apif_settings['access_token'] = $access_token;
$apif_settings['instagram_mosaic'] = isset($instagram_mosaic)?$instagram_mosaic:'mosaic';
$apif_settings['user_id'] = $user_id;
$apif_settings['active'] = isset($active) ? $active : ' ';
update_option('apif_settings', $apif_settings);
$_SESSION['apif_message'] = __('Settings Saved Successfully','accesspress-instagram-feed');
wp_redirect(admin_url().'admin.php?page=if-instagram-feed');
exit();