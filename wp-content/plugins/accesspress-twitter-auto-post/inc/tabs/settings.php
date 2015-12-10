<?php 
$atap_settings = get_option('atap_settings');
//$this->print_array($atap_settings);
?>
<div class="asap-field-note">
<?php _e('Note: PHP version 5.3 or greater required.','accesspress-twitter-auto-post');?>
</div>
<div class="asap-section" id="asap-section-settings" <?php if ($active_tab != 'settings') { ?>style="display: none;"<?php } ?>>
    <div class="asap-network-wrap">
        <h4 class="asap-network-title"><?php _e('Twitter Account Details', 'accesspress-twitter-auto-post'); ?></h4>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="atap_form_action"/>
            <?php wp_nonce_field('atap_form_action', 'atap_form_nonce') ?>
            <div class="asap-network-inner-wrap">
                <div class="asap-network-field-wrap">
                    <label><?php _e('Auto Publish', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field"><input type="checkbox" value="1" name="account_details[auto_publish]" <?php if(isset($atap_settings['auto_publish'])){checked($atap_settings['auto_publish'],true);}?>/></div>
                </div>
                <div class="asap-network-field-wrap">
                    <label><?php _e('API Key', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field"><input type="text" name="account_details[api_key]" value="<?php echo esc_attr($atap_settings['api_key']);?>"/></div>
                </div>
                <div class="asap-network-field-wrap">
                    <label><?php _e('API Secret', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field"><input type="text" name="account_details[api_secret]" value="<?php echo esc_attr($atap_settings['api_secret']);?>"/></div>
                </div>
                <div class="asap-network-field-wrap">
                    <label><?php _e('Access Token', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field"><input type="text" name="account_details[access_token]" value="<?php echo esc_attr($atap_settings['access_token']);?>"/></div>
                </div>
                <div class="asap-network-field-wrap">
                    <label><?php _e('Access Token Secret', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field">
                        <input type="text" name="account_details[access_token_secret]"  value="<?php echo esc_attr($atap_settings['access_token_secret']);?>"/>
                        <div class="asap-field-note">
                            <?php
                            $site_url = site_url();
                            _e("Please visit <a href='https://apps.twitter.com/' target='_blank'>here</a> and create new app to get API Key, API Secret, Access Token and Access Token Secret keys.<br/><br/> Also please make sure you keep $site_url in the website field while creating the app.", 'accesspress-twitter-auto-post');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="asap-network-field-wrap">
                    <label><?php _e('Post Message Format', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field">
                        <textarea name="account_details[message_format]"><?php echo esc_attr($atap_settings['message_format']);?></textarea>
                        <div class="asap-field-note">
                            <?php _e('Note: Please use #post_title,#post_content,#post_excerpt,#post_link,#author_name for the corresponding post title, post content, post excerpt, post link, post author name respectively.<br/><br/>Please also make sure the message will be less or equal to 140 characters', 'accesspress-twitter-auto-post'); ?>
                        </div>
                    </div>
                </div>
                <div class="asap-network-field-wrap">
                    <label><?php _e('Use Short URLS', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field">
                        <label class="asap-full-width"><input type="checkbox" name="account_details[short_url]" value="1" class="asap-bitly-check" <?php if(isset($atap_settings['short_url'])){checked($atap_settings['short_url'],true);}?>/><?php _e('Check if you want to shorten the url using bitly', 'accesspress-twitter-auto-post'); ?></label>
                    </div>
                </div>
                <?php $short_url = isset($atap_settings['short_url'])?$atap_settings['short_url']:0;?>
                <div class="asap-network-field-wrap asap-bitly-ref" <?php if($short_url==0){?>style="display: none;"<?php }?>>
                    <label><?php _e('Bitly Username', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field">
                        <input type="text" name="account_details[bitly_username]" value="<?php echo esc_attr($atap_settings['bitly_username']);?>"/>
                    </div>
                </div>
                <div class="asap-network-field-wrap asap-bitly-ref"  <?php if($short_url==0){?>style="display: none;"<?php }?>>
                    <label><?php _e('Bitly API Key', 'accesspress-twitter-auto-post'); ?></label>
                    <div class="asap-network-field">
                        <input type="text" name="account_details[bitly_api_key]" value="<?php echo esc_attr($atap_settings['bitly_api_key']);?>"/>
                        <div class="asap-field-note">
                            <?php _e("Please visit <a href='https://bitly.com/a/your_api_key' target='_blank'>here</a> to get your bitly username and api key", 'accesspress-twitter-auto-post'); ?>
                        </div>
                    </div>
                </div>


            </div>
            <!--Post Settings Section-->
            <?php include('post-settings.php'); ?>
            <!--Post Settings Section-->
        </form>
    </div>
</div>