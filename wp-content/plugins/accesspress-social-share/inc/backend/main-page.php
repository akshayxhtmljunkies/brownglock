<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<div class="apss-wrapper-block">
	<div class="apss-setting-header clearfix">
		<div class="apss-headerlogo">
			<img src="<?php echo APSS_IMAGE_DIR; ?>/logo-old.png" alt="<?php esc_attr_e( 'AccessPress Social Share', 'accesspress-social-share' ); ?>" />
		</div>
		<div class="apss-header-icons">
			<p><?php _e( 'Follow us for new updates' ); ?></p>
			<div class="apss-social-bttns">
				<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowtransparency="true"></iframe>
				&nbsp;&nbsp;
				<iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/follow_button.5f46501ecfda1c3e1c05dd3e24875611.en.html#_=1421918256492&amp;dnt=true&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=apthemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" title="Twitter Follow Button" data-twttr-rendered="true" style="width: 126px; height: 20px;"></iframe>
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
		<div class="apss-header-title">
			<?php _e( 'AccessPress Social Share', 'accesspress-social-share' ); ?>
		</div>
	</div>
	<?php $options = get_option( APSS_SETTING_NAME );
	if ( isset( $_SESSION['apss_message'] ) ) {
		?>
		<div class="apss-message">
			<p><?php
				echo $_SESSION['apss_message'];
				unset( $_SESSION['apss_message'] );
				?></p>
		</div>
<?php } ?>

	<div class="apps-wrap">
		<form method="post" action="<?php echo admin_url() . 'admin-post.php' ?>">
			<input type="hidden" name="action" value="apss_save_options"/>

			<ul class="apss-setting-tabs clearfix">
				<li><a href="javascript:void(0)" id="apss-social-networks" class="apss-tabs-trigger apss-active-tab	"><?php _e( 'Social Networks', 'accesspress-social-share' ); ?></a></li>
				<li><a href="javascript:void(0)" id="apss-share-options" class="apss-tabs-trigger "><?php _e( 'Share Options', 'accesspress-social-share' ) ?></a></li>
				<li><a href="javascript:void(0)" id="apss-display-settings" class="apss-tabs-trigger"><?php _e( 'Display Settings', 'accesspress-social-share' ); ?></a></li>
				<li><a href="javascript:void(0)" id="apss-miscellaneous" class="apss-tabs-trigger"><?php _e( 'Miscellaneous', 'accesspress-social-share' ); ?></a></li>
				<li><a href="javascript:void(0)" id="apss-how-to-use" class="apss-tabs-trigger"><?php _e( 'How To Use', 'accesspress-social-share' ); ?></a></li>
				<li><a href="javascript:void(0)" id="apss-about" class="apss-tabs-trigger"><?php _e( 'About', 'accesspress-social-share' ); ?></a></li>
			</ul>
			<div class="apss-wrapper">
				<div class="apss-tab-contents apss-social-networks" id="tab-apss-social-networks" style='display:block'>
					<h2><?php _e( 'Social Media chooser:', 'accesspress-social-share' ); ?> </h2>
					<span class="social-text"><?php _e( 'Please choose the social media you want to display. Also you can order these social media\'s by drag and drop:', 'accesspress-social-share' ); ?></span>
					<div class="apps-opt-wrap clearfix">
						<?php
						$label_array = array( 
												'facebook' => ' <span class="media-icon"><i class="fa fa-facebook"></i></span> Facebook',
												'twitter' => ' <span class="media-icon"><i class="fa fa-twitter"></i></span> Twitter',
												'google-plus' => '<span class="media-icon"><i class="fa fa-google-plus"></i></span> Google Plus',
												'pinterest' => '<span class="media-icon"> <i class="fa fa-pinterest"></i> </span>Pinterest',
												'linkedin' => '<span class="media-icon"><i class="fa fa-linkedin"></i></span> Linkedin',
												'digg' => '<span class="media-icon"><i class="fa fa-digg"></i></span> Digg',
												'email' => '<span class="media-icon"><i class="fa  fa-envelope"></i></span> Email',
												'print' => '<span class="media-icon"><i class="fa fa-print"></i> </span>Print',
											);
						?>
						<?php foreach ( $options['social_networks'] as $key => $val ) {
							?>
							<div class="apss-option-wrapper">
								<div class="apss-option-field">
									<label class="clearfix"><span class="left-icon"><i class="fa fa-arrows"></i></span><span class="social-name"><?php echo $label_array[$key]; ?></span><input type="checkbox" data-key='<?php echo $key; ?>' name="social_networks[<?php echo $key; ?>]" value="1" <?php if ( $val == '1' ) {
								echo "checked='checked'";
							} ?> /></label>
								</div>
							</div>
<?php } ?>
					</div>
					<input type="hidden" name="apss_social_newtwork_order" id='apss_social_newtwork_order' value="<?php echo implode( ',', array_keys( $options['social_networks'] ) ); ?>"/>
				</div>

				<div class="apss-tab-contents apss-share-options" id="tab-apss-share-options" style='display:none'>
					<h2><?php _e( 'Share options:', 'accesspress-social-share' ); ?> </h2>
					<span class="social-text"><?php _e( 'Please choose the options where you want to display social share icons:', 'accesspress-social-share' ); ?></span>
					<p><input type="checkbox" id="apss_posts" value="post" name="apss_share_settings[share_options][]" <?php if ( in_array( "post", $options['share_options'] ) || in_array( "posts", $options['share_options'] ) ) {
	echo "checked='checked'";
} ?> ><label for="apss_posts"><?php _e( 'Posts', 'accesspress-social-share' ); ?> </label></p>
					<p><input type="checkbox" id="apss_pages" value="page" name="apss_share_settings[share_options][]" <?php if ( in_array( "page", $options['share_options'] ) || in_array( "pages", $options['share_options'] ) ) {
	echo "checked='checked'";
} ?> ><label for="apss_pages"><?php _e( 'Pages', 'accesspress-social-share' ); ?> </label></p>

					<p><input type="checkbox" id="apss_front_page" value="front_page" name="apss_share_settings[share_options][]" <?php if ( in_array( "front_page", $options['share_options'] ) ) {
	echo "checked='checked'";
} ?> ><label for="apss_front_page"><?php _e( 'Front Page', 'accesspress-social-share' ); ?></label></p>		
					<p><input type="checkbox" id="apss_archives" value="archives" name="apss_share_settings[share_options][]" <?php if ( in_array( "archives", $options['share_options'] ) ) {
	echo "checked='checked'";
} ?> ><label for="apss_archives"><?php _e( 'Archives', 'accesspress-social-share' ); ?></label></p>

					<p><input type="checkbox" id="apss_attachement" value="attachment" name="apss_share_settings[share_options][]" <?php if ( in_array( "attachment", $options['share_options'] ) ) {
	echo "checked='checked'";
} ?> ><label for="apss_attachment"><?php _e( 'Attachment pages', 'accesspress-social-share' ); ?></label></p>

					<p><input type="checkbox" id="apss_categories" value="categories" name="apss_share_settings[share_options][]" <?php if ( in_array( "categories", $options['share_options'] ) ) {
	echo "checked='checked'";
} ?> ><label for="apss_categories"><?php _e( 'Categories', 'accesspress-social-share' ); ?></label></p>
					<p><input type="checkbox" id="apss_all" value="all" name="apss_share_settings[share_options][]" <?php if ( in_array( "all", $options['share_options'] ) ) {
							echo "checked='checked'";
						} ?> ><label for="apss_all"><?php _e( 'Other (search results, etc)', 'accesspress-social-share' ); ?></label></p>

				</div>

				<div class="apss-tab-contents apss-display-settings" id="tab-apss-display-settings" style='display:none'>
					<div class=' apss-display-positions'>
						<h2><?php _e( 'Display position:', 'accesspress-social-share' ); ?></h2>
						<span class='social-text'><?php _e( 'Please choose the option where you want to display the social share:', 'accesspress-social-share' ); ?></span>
						<p><input type="radio" id="apss_below_content" name="apss_share_settings[social_share_position_options]" value="below_content" <?php if ( $options['share_positions'] == 'below_content' ) {
							echo "checked='checked'";
						} ?> /><label for='apss_below_content'><?php _e( 'Below content', 'accesspress-social-share' ); ?></label></p>
						<p><input type="radio" id="apss_above_content" name="apss_share_settings[social_share_position_options]"/ value="above_content" <?php if ( $options['share_positions'] == 'above_content' ) {
							echo "checked='checked'";
						} ?> /><label for='apss_above_content'><?php _e( 'Above content', 'accesspress-social-share' ); ?></label></p>
						<p><input type="radio" id="apss_below_above_content" id="below_above_content" name="apss_share_settings[social_share_position_options]" value="on_both" <?php if ( $options['share_positions'] == 'on_both' ) {
							echo "checked='checked'";
						} ?> /><label for='apss_below_above_content'><?php _e( 'Both(Below content and Above content)', 'accesspress-social-share' ); ?></label></p>
					</div>
					<div class=" apss-icon-sets">
						<h2><?php _e( 'Social icons sets: ', 'accesspress-social-share' ); ?> </h2>
							<?php _e( 'Please choose any one out of available icon themes:', 'accesspress-social-share' ); ?>
							<?php for ( $i = 1; $i <= 6; $i++ ) { ?>
							<p><input id="apss_icon_set_<?php echo $i; ?>" value="<?php echo $i; ?>" name="apss_share_settings[social_icon_set]" type="radio" <?php if ( $options['social_icon_set'] == $i ) {
								echo "checked='checked'";
							} ?> ><label for="apss_icon_set_<?php echo $i; ?>"><span class="apss_demo_icon apss_demo_icons_<?php echo $i; ?>"></span><?php _e( "Theme $i", 'accesspress-social-share' ); ?><div class="apss-theme-image"><img src='<?php echo APSS_IMAGE_DIR . "/theme/theme$i.jpg"; ?>'/></div></label></p>
						<?php } ?>
					</div>
				</div>

				<div class="apss-tab-contents apss-miscellaneous" id="tab-apss-miscellaneous" style='display:none'>
					<h2><?php _e( 'Miscellaneous settings: ', 'accesspress-social-share' ); ?> </h2>
					<h4><?php _e( 'Please setup these additional settings:', 'accesspress-social-share' ); ?></h4>

					<div class="apss-share-text-settings clearfix">
						<h4><?php _e( 'Disable the plugins frontend assets?', 'accesspress-social-share' ); ?> </h4>
						<div class="misc-opt"><input type="radio" id='disable_frontend_assets_n' name="apss_share_settings[disable_frontend_assets]" value="0" <?php if ( isset( $options['disable_frontend_assets'] ) && $options['disable_frontend_assets'] == '0' ) {
							echo "checked='checked'";
						} ?> /><label for="disable_frontend_assets_n"><?php _e( 'No', 'accesspress-social-share' ); ?></label></div>
						<div class="misc-opt"><input type="radio" id='disable_frontend_assets_y' name="apss_share_settings[disable_frontend_assets]" value="1" <?php if ( isset( $options['disable_frontend_assets'] ) && $options['disable_frontend_assets'] == '1' ) {
							echo "checked='checked'";
						} ?> /><label for="disable_frontend_assets_y"><?php _e( 'Yes', 'accesspress-social-share' ); ?></label></div>
						<br />
						<div class="apss_notes_cache_settings">
<?php _e( 'Please set this value if you don\'t want to use plguins frontend assets(js and css files).', 'accesspress-social-share' ); ?>
						</div>
					</div>
					<br />
					<div class="apss-share-text-settings clearfix">
<?php _e( 'Share text:', 'accesspress-social-share' ); ?> <input type="text" name="apss_share_settings[share_text]"  value="<?php if ( isset( $options['share_text'] ) ) {
	echo $options['share_text'];
} ?>" />
						<div class="apss_notes_cache_settings">
							<?php _e( 'Please enter the share text to make it appear above social share icons. Leave blank if you don\'t want to use share text.', 'accesspress-social-share' ); ?>
						</div>
					</div>
					<br />
					<div class="apss-twitter-settings clearfix">
<?php _e( 'Twitter username:', 'accesspress-social-share' ); ?> <input type="text" name="apss_share_settings[twitter_username]"  value="<?php echo $options['twitter_username']; ?>" />
					</div>

					<div class="apss-counter-settings clearfix">
						<h4><?php _e( 'Social share counter enable?', 'accesspress-social-share' ); ?> </h4>
						<div class="misc-opt"><input type="radio" id='counter_enable_options_n' name="apss_share_settings[counter_enable_options]" value="0" <?php if ( $options['counter_enable_options'] == '0' ) {
	echo "checked='checked'";
} ?> /><label for="counter_enable_options_n"><?php _e( 'No', 'accesspress-social-share' ); ?></label></div>
						<div class="misc-opt"><input type="radio" id='counter_enable_options_y' name="apss_share_settings[counter_enable_options]" value="1" <?php if ( $options['counter_enable_options'] == '1' ) {
	echo "checked='checked'";
} ?> /><label for="counter_enable_options_y"><?php _e( 'Yes', 'accesspress-social-share' ); ?></label></div>
					</div>

					<div class="apss-total-counter-settings clearfix">
						<h4><?php _e( 'Social share total counter enable?', 'accesspress-social-share' ); ?> </h4>
						<div class="misc-opt"><input type="radio" id='total_counter_enable_options_n' name="apss_share_settings[total_counter_enable_options]" value="0" <?php if ( isset( $options['total_counter_enable_options'] ) && $options['total_counter_enable_options'] == '0' ) {
	echo "checked='checked'";
} ?> /><label for="total_counter_enable_options_n"><?php _e( 'No', 'accesspress-social-share' ); ?></label></div>
						<div class="misc-opt"><input type="radio" id='total_counter_enable_options_y' name="apss_share_settings[total_counter_enable_options]" value="1" <?php if ( isset( $options['total_counter_enable_options'] ) && $options['total_counter_enable_options'] == '1' ) {
						echo "checked='checked'";
					} ?> /><label for="total_counter_enable_options_y"><?php _e( 'Yes', 'accesspress-social-share' ); ?></label></div>
					</div>

					<div class="apss-dialog-boxs clearfix">
						<h4><?php _e( 'Social share link options:', 'accesspress-social-share' ); ?> </h4>
						<div class="misc-opt"><input type="radio" id='dialog_box_options_1' name="apss_share_settings[dialog_box_options]" value="0" <?php if ( $options['dialog_box_options'] == '0' ) {
					echo "checked='checked'";
				} ?> /><label for="dialog_box_options_1"><?php _e( 'Open in same window', 'accesspress-social-share' ); ?></label></div>
						<div class="misc-opt"><input type="radio" id='dialog_box_options_2' name="apss_share_settings[dialog_box_options]" value="1" <?php if ( $options['dialog_box_options'] == '1' ) {
					echo "checked='checked'";
				} ?> /><label for="dialog_box_options_2"><?php _e( 'Open in new window/Tab', 'accesspress-social-share' ); ?></label></div>
					</div>

					<div class='cache-settings'>
						<h4><?php _e( 'Cache Settings: ', 'accesspress-social-share' ); ?> </h4>
						<label for="apss_cache_settings"><?php _e( 'Cache Period:', 'accesspress-social-share' ); ?></label>
						<input type='number' id="apss_cache_period" name='apss_share_settings[cache_settings]' value="<?php if ( isset( $options['cache_period'] ) ) {
					echo $options['cache_period'];
				} ?>" onkeyup="removeMe('invalid_cache_period');"/>
						<span class="error invalid_cache_period"></span>
						<div class="apss_notes_cache_settings">
<?php _e( 'Please enter the time in hours in which the social share counter should be updated from social networks. Default is 24 hours.', 'accesspress-social-share' ); ?>
						</div>
					</div>

					<div class="apss-email-settings">
						<h4><?php _e( 'Email Settings:', 'accesspress-social-share' ); ?></h4>
						<div class="app-email-sub email-setg">
							<label for='apss-email-subject'><?php _e( 'Email subject:', 'accesspress-social-share' ); ?></label>
							<input type='text' name="apss_share_settings[apss_email_subject]" value="<?php echo $options['apss_email_subject'] ?>" />
						</div>
						<div class="app-email-body email-setg">
							<label for='apss-email-body'><?php _e( 'Email body:', 'accesspress-social-share' ); ?></label>
							<textarea rows='30' cols='30' name="apss_share_settings[apss_email_body]"><?php echo $options['apss_email_body'] ?></textarea>
						</div>
					</div>
				</div>
				<div class="apss-tab-contents apss-how-to-use" id="tab-apss-how-to-use" style='display:none' >
<?php include_once('how-to-use.php'); ?>
				</div>

				<div class="apss-tab-contents apss-about" id="tab-apss-about" style='display:none' >
<?php include('about-apss.php'); ?>

				</div>
<?php wp_nonce_field( 'apss_nonce_save_settings', 'apss_add_nonce_save_settings' ); ?>
				<input type="submit" class="submit_settings button primary-button" value="<?php _e( 'Save settings', 'accesspress-social-share' ); ?>" name="apss_submit_settings" id="apss_submit_settings"/>
<?php
/**
 * Nonce field
 * */
wp_nonce_field( 'apss_settings_action', 'apss_settings_action' );
?>
<?php $nonce = wp_create_nonce( 'apss-restore-default-settings-nonce' ); ?>
<?php $nonce_clear = wp_create_nonce( 'apss-clear-cache-nonce' ); ?>
				<a href="<?php echo admin_url() . 'admin-post.php?action=apss_restore_default_settings&_wpnonce=' . $nonce; ?>" onclick="return confirm('<?php _e( 'Are you sure you want to restore default settings?', 'accesspress-social-share' ); ?>')"><input type="button" value="Restore Default Settings" class="apss-reset-button button primary-button"/></a>
				<a href="<?php echo admin_url() . 'admin-post.php?action=apss_clear_cache&_wpnonce=' . $nonce_clear; ?>" onclick="return confirm('<?php _e( 'Are you sure you want to clear cache share counter?', 'accesspress-social-share' ); ?>')"><input type="button" value="Clear Cache" class="apss-reset-button button primary-button"/></a>
			</div>
		</form>
	</div>
</div>
<div class="apss-promoFloat">
	<a href='https://accesspressthemes.com/wordpress-plugins/accesspress-social-pro/' target='_blank'><img src="<?php echo APSS_IMAGE_DIR . '/promo-top.png' ?>" alt="promo-top" /></a>
	<div class="apss-promo-buttons"><a target='_blank' href="http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-pro/" /><img src="<?php echo APSS_IMAGE_DIR . '/demo-btn.png' ?>" alt="demo link" /></a><a target='_blank' href="http://codecanyon.net/item/accesspress-social-pro/10429645?ref=AccessKeys" ><img src="<?php echo APSS_IMAGE_DIR . '/upgrade-btn.png' ?>" alt="upgrade link" /></a></div>
	<a href='https://accesspressthemes.com/wordpress-plugins/accesspress-social-pro/' target='_blank'><img src="<?php echo APSS_IMAGE_DIR . '/promo-bottom.png' ?>" alt="promo-bottom" /></a>
	<div class="apss-promo-buttons"><a target='_blank' href="http://demo.accesspressthemes.com/wordpress-plugins/accesspress-social-pro/" /><img src="<?php echo APSS_IMAGE_DIR . '/demo-btn.png' ?>" alt="demo link" /></a><a target='_blank' href="http://codecanyon.net/item/accesspress-social-pro/10429645?ref=AccessKeys" ><img src="<?php echo APSS_IMAGE_DIR . '/upgrade-btn.png' ?>" alt="upgrade link" /></a></div>
</div>
<div class="clear"></div>