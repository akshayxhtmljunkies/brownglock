<?php
defined('ABSPATH') or die('No script kiddies please!');

$id = $post->ID;
$account_details = get_option('atap_settings');
//$this->print_array($account_details);
//$this->print_array($account_extra_details);
$post_type = get_post_type($id);
$taxonomies = get_object_taxonomies($post_type);
$terms = wp_get_post_terms($id, $taxonomies);
$categories = isset($account_details['category']) ? $account_details['category'] : array();
//$this->print_array($categories);
//$this->print_array($terms);
$category_flag = false;
if (count($categories) == 0) {
    $category_flag = true;
} else if (in_array('all', $categories)) {
    $category_flag = true;
} else {
    foreach ($terms as $term) {
    if(in_array($term->term_id,$categories)){
        $category_flag = true;
    }    
    }
}
/**
 * Checking if the post type of this post is enabled in the account settings 
 * 
 * */
if (in_array($post_type, $account_details['post_types']) && $category_flag) {
    /**
     * Account Details
     * [api_key] => 8fmkZfRz92qMphfsXh7teotSp
      [api_secret] => BfqBWMgU2GyKxUVeBiF6cx3dTz0t9wVWyBCBKBlK3MPbEpd04t
      [access_token] => 2686728944-rGGJnh3mNBV6GCoNWQVClnA8gkKDim1BRarZbcw
      [access_token_secret] => wJtaBT6fqCQb4LqCeEZi6lICfvV4oWaMPfsGV3Uzw2sIV
      [message_format] => Test Post
      [include_image] => 1
      [post_image] => featured_image
      [custom_image_url] => adsfasdfasdf
      [short_url] => 1
      [bitly_username] => o_474f06bfg3
      [bitly_api_key] => R_7e2c42a779a14eab9cdcb137dd87ea27
      [post_types] => Array
      (
      [0] => post
      [1] => page
      )
     */
    foreach ($account_details as $key => $val) {
        $$key = $val; // converting each key into variable with its own value
    }
    $post_title = $post->post_title;
    $post_content = strip_tags($post->post_content);
    $post_content = str_replace('&nbsp;','',$post_content);
    $post_content = strip_shortcodes($post_content);
    $post_excerpt = $post->post_excerpt;
    $post_link = get_the_permalink($id);
    $post_author_id = $post->post_author;
    $author_name = get_the_author_meta('user_nicename', $post_author_id);
    $message_format = str_replace('#post_title', $post_title, $message_format);
    $message_format = str_replace('#post_content', $post_content, $message_format);
    $message_format = str_replace('#post_excerpt', $post_excerpt, $message_format);
    $message_format = str_replace('#post_link', $post_link, $message_format);
    $message_format = str_replace('#author_name', $author_name, $message_format);

    if (isset($short_url) && $short_url == 1) {  //shortnening the url using bitly
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if (preg_match($reg_exUrl, $message_format, $url)) {
            // Note: Won't work for localhost
            // make the urls hyper links
            $message_format = preg_replace($reg_exUrl, $this->make_bitly_url($url[0], $bitly_username, $bitly_api_key, 'json'), $message_format);
        }
    }
    \Codebird\Codebird::setConsumerKey($api_key, $api_secret);
    $cb = \Codebird\Codebird::getInstance();
    $cb->setToken($access_token, $access_token_secret);

    $params = array(
        'status' => $message_format
    );


    $reply = $cb->statuses_update($params);
//$this->print_array($reply);
    $post_id = $post->ID;
    $log_time = date('Y-m-d h:i:s A');
    if ($reply->httpstatus == 200) {
        //tweeted succesfully
        $log_status = 1;
        $log_details = __('Tweeted Successfully on ', 'accesspress-twitter-auto-post') . $reply->user->screen_name;
    } else {
        //there was error while tweeting
        //$error_message = $e->getMessage();
        if (isset($reply->errors)) {
            $error_message = $reply->errors[0]->message;
        } else {
            $error_message = 'httpstatus: ' . $reply->httpstatus . __('(Connection Timeout)', 'accesspress-twitter-auto-post');
        }
        $log_status = 0;
        $log_details = $error_message;
    }
    /**
     * Inserting log to logs table
     * */
    global $wpdb;
    $log_table_name = $wpdb->prefix . 'atap_logs';
    $wpdb->insert(
            $log_table_name, array(
        'post_id' => $post_id,
        'log_status' => $log_status,
        'log_time' => $log_time,
        'log_details' => $log_details,
            ), array(
        '%d',
        '%d',
        '%s',
        '%s'
            )
    );

//die();
}//post type check close