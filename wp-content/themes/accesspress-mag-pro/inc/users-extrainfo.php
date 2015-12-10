<?php
/**
 * Add Extra social media field for users
 * 
 * @package Accesspress Mag Pro
 */
//add_action( 'show_user_profile', 'extra_user_profile_fields' );
//add_action( 'edit_user_profile', 'extra_user_profile_fields' );
//add_action( 'user_new_form', 'extra_user_profile_fields' );
$apmag_user_social_array = array(
        'behance' => 'Behance',
        'delicious' => 'Delicious',
        'deviantart' => 'Deviantart',
        'digg' => 'Digg',
        'dribbble' => 'Dribbble',
        'facebook' => 'Facebook',
        'flickr' => 'Flickr',
        'github' => 'Github',
        'google-plus' => 'Google+',
        'html5' => 'Html5',
        'instagram' => 'Instagram',
        'lastfm' => 'Lastfm',
        'linkedin' => 'Linkedin',
        'paypal' => 'Paypal',
        'pinterest' => 'Pinterest',
        'reddit' => 'Reddit',
        'rss' => 'RSS',
        'share' => 'Share',
        'skype' => 'Skype',
        'soundcloud' => 'Soundcloud',
        'spotify' => 'Spotify',
        'stack-exchange' => 'Stackexchange',
        'stack-overflow' => 'Stackoverflow',        
        'steam' => 'Steam',
        'stumbleupon' => 'StumbleUpon',
        'tumblr' => 'Tumblr',
        'twitter' => 'Twitter',
        'vimeo' => 'Vimeo',
        'vk' => 'VKontakte',
        'windows' => 'Windows',
        'wordpress' => 'Woordpress',
        'yahoo' => 'Yahoo',
        'youtube' => 'Youtube'
    );
    
add_filter('user_contactmethods', 'apmag_extra_contact_info_for_author');

function apmag_extra_contact_info_for_author() {
    global $apmag_user_social_array;
    foreach( $apmag_user_social_array as $icon_id => $icon_name ) {
        $contactmethods[$icon_id] = $icon_name;
    }
    return $contactmethods;
}
?>