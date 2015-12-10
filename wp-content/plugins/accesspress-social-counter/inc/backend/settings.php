<?php 
defined('ABSPATH') or die("No script kiddies please!");

$apsc_settings = $this->apsc_settings;
    //$this->print_array($apsc_settings);
?>
<div class="wrap">
    <div class="apsc-add-set-wrapper clearfix">
        <div class="apsc-panel">
            <div class="apsc-settings-header">
                <div class="apsc-logo">
                    <img src="<?php echo SC_IMAGE_DIR; ?>/logo.png" alt="<?php esc_attr_e('AccessPress Social Counter', 'accesspress-social-counter'); ?>" />
                </div>

                <div class="apsc-socials">
                    <p><?php _e('Follow us for new updates', 'accesspress-social-counter') ?></p>
                    <div class="ap-social-bttns">

                        <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowTransparency="true"></iframe>
                        &nbsp;&nbsp;
                        <a href="https://twitter.com/apthemes" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @apthemes</a>
                        <script>!function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, "script", "twitter-wjs");</script>

                    </div>
                </div>

                <div class="apsc-title"><?php _e('AccessPress Social Counter', 'accesspress-social-counter'); ?></div>
            </div>
            <div class="apsc-extra-note"><?php _e('Note: Please check the System Status if your server system status meets all the requirement for the plugin before you use the plugin.<br/><br/> If you find any unsual issues then please check by deleting cache using the "Delete Cache" button below.And if you found our plugin helpful and like our plugin then please don\'t forget to give us a rating <a href="https://wordpress.org/support/view/plugin-reviews/accesspress-social-counter#postform"> here </a>. Its the only way to keep us motivated to make the plugin even better.
','accesspress-social-counter');?></div>
            <?php if(isset($_SESSION['apsc_message'])){?><div class="apsc-success-message"><p><?php echo $_SESSION['apsc_message'];unset($_SESSION['apsc_message']);?></p></div><?php }?>
            <div class="apsc-boards-wrapper">
                <ul class="apsc-settings-tabs">
                    <li><a href="javascript:void(0)" id="social-profile-settings" class="apsc-tabs-trigger apsc-active-tab"><?php _e('Social Profiles', 'accesspress-social-counter') ?></a></li>
                    <li><a href="javascript:void(0)" id="display-settings" class="apsc-tabs-trigger"><?php _e('Display Settings', 'accesspress-social-counter'); ?></a></li>
                    <li><a href="javascript:void(0)" id="cache-settings" class="apsc-tabs-trigger"><?php _e('Cache Settings', 'accesspress-social-counter'); ?></a></li>
                    <li><a href="javascript:void(0)" id="system-status" class="apsc-tabs-trigger"><?php _e('System Status', 'accesspress-social-counter'); ?></a></li>
                    <li><a href="javascript:void(0)" id="how_to_use-settings" class="apsc-tabs-trigger"><?php _e('How to use', 'accesspress-social-counter'); ?></a></li>
                    <li><a href="javascript:void(0)" id="about-settings" class="apsc-tabs-trigger"><?php _e('About', 'anonymous-post'); ?></a></li>
                </ul>
                <div class="metabox-holder">
                    <div id="optionsframework" class="postbox" style="float: left;">
                        <form class="apsc-settings-form" method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
                            <input type="hidden" name="action" value="apsc_settings_action"/>
                            <?php
                    /**
                     * Social Profiles
                     * */
                    include_once('boards/social-profiles.php');
                    ?>

                    <?php
                    /**
                     * Display Settings
                     * */
                    include_once('boards/display-settings.php');
                    ?>

                    <?php
                    /**
                     * Captcha Settings
                     * */
                    include_once('boards/cache-settings.php');
                    ?>
                    
                    <?php
                    /**
                     * System Status
                     * */
                    include_once('boards/system-status.php');
                    ?>

                    <?php
                    /**
                     * Captcha Settings
                     * */
                    include_once('boards/how-to-use.php');
                    ?>


                    <?php
                    /**
                     * About Tab
                     * */
                    include_once('boards/about.php');
                    ?>
                    <?php
                    /**
                     * Nonce field
                     * */
                    wp_nonce_field('apsc_settings_action', 'apsc_settings_nonce');
                    ?>
                    <div id="optionsframework-submit" class="ap-settings-submit">
                    <input type="submit" class="button button-primary" value="Save all changes" name="ap_settings_submit"/>
                        <?php
                        $nonce = wp_create_nonce('apsc-restore-default-nonce');
                        $cache_nonce = wp_create_nonce('apsc-cache-nonce');
                        ?>
                        <a href="<?php echo admin_url() . 'admin-post.php?action=apsc_restore_default&_wpnonce=' . $nonce; ?>" onclick="return confirm('<?php _e('Are you sure you want to restore default settings?', 'accesspress-social-counter'); ?>')"><input type="button" value="<?php _e('Restore Default Settings','accesspress-social-counter');?>" class="ap-reset-button button button-primary"/></a>
                        <a href="<?php echo admin_url() . 'admin-post.php?action=apsc_delete_cache&_wpnonce=' . $cache_nonce; ?>" onclick="return confirm('<?php _e('Are you sure you want to delete cache?', 'accesspress-social-counter'); ?>')"><input type="button" value="<?php _e('Delete Cache','accesspress-social-counter');?>" class="ap-reset-button button button-primary"/></a>
                    </div>
                </form>   
            </div><!--optionsframework-->
</div>
        </div>
    
</div>
<div class="apsc-promoFloat">
        <img src="<?php echo SC_IMAGE_DIR . '/promo-top.png' ?>" alt="promo-top" />
        <div class="apsc-promo-buttons"><a href="http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-pro"  target="_blank"><img src="<?php echo SC_IMAGE_DIR . '/demo-btn.png' ?>" alt="demo link" /></a><a href="http://codecanyon.net/item/accesspress-social-pro/10429645?ref=AccessKeys" target="_blank"><img src="<?php echo SC_IMAGE_DIR . '/upgrade-btn.png' ?>" alt="upgrade link" /></a></div>
        <img src="<?php echo SC_IMAGE_DIR . '/promo-bottom.png' ?>" alt="promo-bottom" />
        <div class="apsc-promo-buttons"><a href="http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-pro"  target="_blank"><img src="<?php echo SC_IMAGE_DIR . '/demo-btn.png' ?>" alt="demo link" /></a><a href="http://codecanyon.net/item/accesspress-social-pro/10429645?ref=AccessKeys"  target="_blank"><img src="<?php echo SC_IMAGE_DIR . '/upgrade-btn.png' ?>" alt="upgrade link" /></a></div>
    </div>
    <div class="clear"></div>
</div>
</div><!--div class wrap-->