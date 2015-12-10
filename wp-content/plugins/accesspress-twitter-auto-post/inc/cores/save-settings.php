<?php
defined('ABSPATH') or die('No script kiddies please!');

$account_details = $_POST['account_details'];
update_option('atap_settings',$account_details);
$_SESSION['atap_message'] = __('Settings saved successfully!','accesspress-twitter-auto-post');
wp_redirect(admin_url('admin.php?page=atap'));
exit();
