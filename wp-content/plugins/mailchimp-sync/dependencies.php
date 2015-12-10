<?php

// check for mailchimp for wordpress pro
if( defined( 'MC4WP_VERSION' ) && version_compare( MC4WP_VERSION, '2.5.5', '>=' ) ) {
	return true;
}

// check for mailchimp for wordpress lite
if( defined( 'MC4WP_LITE_VERSION' ) && version_compare( MC4WP_LITE_VERSION, '2.2.3', '>=' ) ) {
	return true;
}

// check for MailChimp for WordPress core
if( defined( 'MC4WP_VERSION' ) && version_compare( MC4WP_VERSION, '3.0', '>=' ) ) {
	return true;
}

add_action( 'admin_notices', function() {
	?>
	<div class="updated">
		<p><?php printf( __( 'Please install <a href="%s">%s</a> in order to use %s.', 'mailchimp-sync' ), 'https://wordpress.org/plugins/mailchimp-for-wp/', 'MailChimp for WordPress', 'MailChimp Sync' ); ?></p>
	</div>
<?php
} );

// Tell plugin not to proceed
return false;