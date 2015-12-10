<?php 
defined('ABSPATH') or die("No script kiddies please!");

$apif_settings = get_option( 'apif_settings' );
    //$this->print_array($apsc_settings);
?>

<div class="wrap">
    <div class="apsc-add-set-wrapper clearfix">
        <div class="apsc-panel">
            <div class="apsc-settings-header">

                <div class="apsc-logo">
                    <img src="<?php echo APIF_IMAGE_DIR; ?>/instagram.png" alt="<?php esc_attr_e('AccessPress Instagram Feed', 'accesspress-instagram-feed'); ?>" />
                </div>

                <div class="apsc-socials">
                    <p><?php _e('Follow us for new updates', 'accesspress-instagram-feed') ?></p>
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
                <div class="apsc-title"><?php _e('AccessPress Instagram Feed', 'accesspress-instagram-feed'); ?></div>
            </div>

            <?php if(isset($_SESSION['apif_message'])){?><div class="apsc-success-message"><p><?php echo $_SESSION['apif_message'];unset($_SESSION['apif_message']);?></p></div><?php }?>

            <div class="apsc-boards-wrapper">
                <ul class="apsc-settings-tabs">

                    <li><a href="javascript:void(0)" id="social-profile-settings" class="apsc-tabs-trigger apsc-active-tab"><?php _e('Instagram Profiles', 'accesspress-instagram-feed') ?></a></li>

                    <li><a href="javascript:void(0)" id="display-settings" class="apsc-tabs-trigger"><?php _e('Display Settings', 'accesspress-instagram-feed'); ?></a></li>
                    
                    <li><a href="javascript:void(0)" id="how_to_use-settings" class="apsc-tabs-trigger"><?php _e('How to use', 'accesspress-instagram-feed'); ?></a></li>

                    <li><a href="javascript:void(0)" id="about-settings" class="apsc-tabs-trigger"><?php _e('About', 'anonymous-post'); ?></a></li>

                </ul>

                <div class="metabox-holder">
                <div id="optionsframework" class="postbox" style="float: left;">
                    <form class="apsc-settings-form" method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
                        <input type="hidden" name="action" value="apif_settings_action"/>
                    <?php
                    /**
                     * Social Profiles
                     * */
                    include_once('boards/instagram-profiles.php');
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
                    wp_nonce_field('apif_settings_action', 'apif_settings_nonce');
                    ?>
                    <div id="optionsframework-submit" class="ap-settings-submit">
                        <input type="submit" class="button button-primary" value="Save all changes" name="ap_settings_submit"/>
                    </div>
                </form>   
            </div><!--optionsframework-->
</div>
        </div>
    
</div>
</div>
</div><!--div class wrap-->