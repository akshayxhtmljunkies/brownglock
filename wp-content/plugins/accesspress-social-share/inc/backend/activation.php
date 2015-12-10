<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$apss_share_settings = array();
$share_options = array(
    'post',
    'page'
);
$apss_share_settings['share_options'] = $share_options;
$apss_share_settings['social_icon_set'] = '1';
$apss_share_settings['share_positions'] = 'below_content';
$social_networks = array(
    'facebook' => '1',
    'twitter' => '1',
    'google-plus' => '1',
    'pinterest' => '1',
    'linkedin' => '1',
    'digg' => '1',
    'email' => '0',
    'print' => '0',
);
$apss_share_settings['social_networks'] = $social_networks;
$apss_share_settings['disable_frontend_assets'] = '0';
$apss_share_settings['share_text'] = '';
$apss_share_settings['twitter_username'] = '';
$apss_share_settings['counter_enable_options'] = '0';
$apss_share_settings['total_counter_enable_options'] = '0';
$apss_share_settings['cache_period'] = '24';
$apss_share_settings['apss_social_counts_transients'] = array();
$apss_share_settings['dialog_box_options'] = '1';
$apss_share_settings['footer_javascript'] = '1';
$apss_share_settings['apss_email_subject'] = 'Please visit this link %%url%%';
$apss_share_settings['apss_email_body'] = 'Hey Buddy!, I found this information for you: "%%title%%". Here is the website link: %%permalink%%. Thank you.';
update_option( APSS_SETTING_NAME, $apss_share_settings );
