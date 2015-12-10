<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<?php
$apss_link_open_option = ($options['dialog_box_options'] == '1') ? "_blank" : "";
$twitter_user = $options['twitter_username'];
$counter_enable_options = $options['counter_enable_options'];
$icon_set_value = $options['social_icon_set'];
$url = get_permalink(); //$this->curPageURL();
$cache_period = ($options['cache_period'] != '') ? $options['cache_period'] * 60 * 60 : 24 * 60 * 60;

if ( isset( $options['total_counter_enable_options'] ) ) {
	if ( $options['total_counter_enable_options'] == '1' ) {
		$enable_counter = 1;
	}
} else {
	$enable_counter = 0;
}
?>


<?php if ( isset( $options['share_text'] ) && $options['share_text'] != '' ) { ?> <div class='apss-share-text'><?php echo $options['share_text']; ?></div> <?php } ?>

<?php
$total_count = 0;

foreach ( $options['social_networks'] as $key => $value ) {
	if ( intval( $value ) == '1' ) {
		$count = $this->get_count( $key, $url );
		$total_count += $count;
		switch ( $key ) {
			//counter available for facebook
			case 'facebook':
				$link = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
				$count = $this->get_count( $key, $url );
				?>
				<div class='apss-facebook apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on Facebook', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo $link; ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='fa fa-facebook'></i>
							<span class='apss-social-text'><?php _e( 'Share on Facebook', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo $url; ?>' data-social-network='<?php echo $key; ?>' data-social-detail="<?php echo $url . '_' . $key; ?>"><?php echo $count; ?></div>
						<?php } ?>
					</a>
				</div>
				<?php
				break;

			//counter available for twitter
			case 'twitter':
				$url_twitter = $url;
				$url_twitter = urlencode( $url_twitter );
				if ( isset( $twitter_user ) && $twitter_user != '' ) {
					$twitter_user = 'via=' . $twitter_user;
				}
				$link = "https://twitter.com/intent/tweet?text=$title&amp;url=$url_twitter&amp;$twitter_user";
				$count = $this->get_count( $key, $url );
				?>
				<div class='apss-twitter apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on Twitter', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href="<?php echo $link; ?>">
						<div class='apss-icon-block clearfix'>
							<i class='fa fa-twitter'></i>
							<span class='apss-social-text'><?php _e( 'Share on Twitter', 'accesspress-social-share' ); ?></span><span class='apss-share'><?php _e( 'Tweet', 'accesspress-social-share' ); ?></span>
						</div>
						<?php /*  if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo $url; ?>' data-social-network='<?php echo $key; ?>' data-social-detail="<?php echo $url . '_' . $key; ?>"><?php echo $count; ?></div>
						<?php } */ ?>
					</a>
				</div>
				<?php
				break;

			//counter available for google plus
			case 'google-plus':
				$link = 'https://plus.google.com/share?url=' . $url;
				$count = $this->get_count( $key, $url );
				?>
				<div class='apss-google-plus apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on Google Plus', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo $link; ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='fa fa-google-plus'></i>
							<span class='apss-social-text'><?php _e( 'Share on Google Plus', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo $url; ?>' data-social-network='<?php echo $key; ?>' data-social-detail="<?php echo $url . '_' . $key; ?>"><?php echo $count; ?></div>
						<?php } ?>
					</a>
				</div>
				<?php
				break;

			//counter available for pinterest
			case 'pinterest':
				$count = $this->get_count( $key, $url );
				?>

				<div class='apss-pinterest apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on Pinterest', 'accesspress-social-share' ); ?>" href='javascript:pinIt();'>
						<div class='apss-icon-block clearfix'>
							<i class='fa fa-pinterest'></i>
							<span class='apss-social-text'><?php _e( 'Share on Pinterest', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo $url; ?>' data-social-network='<?php echo $key; ?>' data-social-detail="<?php echo $url . '_' . $key; ?>"><?php echo $count; ?></div>
						<?php } ?>

					</a>
				</div>
				<?php
				break;

			//couter available for linkedin
			case 'linkedin':
				$link = "http://www.linkedin.com/shareArticle?mini=true&amp;title=" . $title . "&amp;url=" . $url . "&amp;summary=" . $excerpt;
				$count = $this->get_count( $key, $url );
				?>

				<div class='apss-linkedin apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on LinkedIn', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo $link; ?>'>
						<div class='apss-icon-block clearfix'><i class='fa fa-linkedin'></i>
							<span class='apss-social-text'><?php _e( 'Share on LinkedIn', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
						<?php if ( isset( $counter_enable_options ) && $counter_enable_options == '1' ) { ?>
							<div class='count apss-count' data-url='<?php echo $url; ?>' data-social-network='<?php echo $key; ?>' data-social-detail="<?php echo $url . '_' . $key; ?>"><?php echo $count; ?></div>
						<?php } ?>
					</a>
				</div>
				<?php
				break;

			//there is no counter available for digg
			case 'digg':
				$link = "http://digg.com/submit?phase=2%20&amp;url=" . $url . "&amp;title=" . $title;
				?>
				<div class='apss-digg apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Share on Digg', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo $link; ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='fa fa-digg'></i>
							<span class='apss-social-text'><?php _e( 'Share on Digg', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Share', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>

				<?php
				break;

			case 'email':
				if ( strpos( $options['apss_email_body'], '%%' ) || strpos( $options['apss_email_subject'], '%%' ) ) {
					$link = 'mailto:?subject=' . $options['apss_email_subject'] . '&amp;body=' . $options['apss_email_body'];
					$link = preg_replace( array( '#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#', '#%%url%%#' ), array( $title, get_site_url(), get_permalink(), $url ), $link );
				} else {
					$link = 'mailto:?subject=' . $options['apss_email_subject'] . '&amp;body=' . $options['apss_email_body'] . ": " . $url;
				}
				?>
				<div class='apss-email apss-single-icon'>
					<a rel='nofollow' class='share-email-popup' title="<?php _e( 'Share it on Email', 'accesspress-social-share' ); ?>" target='<?php echo $apss_link_open_option; ?>' href='<?php echo $link; ?>'>
						<div class='apss-icon-block clearfix'>
							<i class='fa  fa-envelope'></i>
							<span class='apss-social-text'><?php _e( 'Send email', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Mail', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>

				<?php
				break;

			case 'print':
				?>
				<div class='apss-print apss-single-icon'>
					<a rel='nofollow' title="<?php _e( 'Print', 'accesspress-social-share' ); ?>" href='javascript:void(0);' onclick='window.print();
				            return false;'>
						<div class='apss-icon-block clearfix'><i class='fa fa-print'></i>
							<span class='apss-social-text'><?php _e( 'Print', 'accesspress-social-share' ); ?></span>
							<span class='apss-share'><?php _e( 'Print', 'accesspress-social-share' ); ?></span>
						</div>
					</a>
				</div>
				<?php
				break;
		}
	}
}
if ( isset( $enable_counter ) && $enable_counter == '1' ) {
	?>
	<div class='apss-total-share-count'>
		<span class='apss-count-number'><?php echo $total_count; ?></span>
		<div class="apss-total-shares"><span class='apss-total-text'><?php echo _e( ' Total', 'accesspress-social-share' ); ?></span>
			<span class='apss-shares-text'><?php echo _e( ' Shares', 'accesspress-social-share' ); ?></span></div>
	</div>
<?php
}