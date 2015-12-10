<?php
/**
 * Customer completed order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( "Hello. Your recent order on %s has been completed. Your order details are shown below for your reference:", 'woocommerce' ), get_option( 'blogname' ) ); ?></p>

<p>Thank you for becoming an BrownGlock Member. You can now access your courses <a href="http://learn.brownglock.com"> here. </a> As part of our service you have now full access to the Cyber Law Library, the most comprehensive curation of articles that inform and discuss the latest developments in Cyber Risk, Crime, Regulation and the Law. You can access the site by clicking <a href="http://cyberlawlibrary.com">here </a>.</p>

<p>Our Business members also have the opportunity to take the Cyber Risk Overview Course and obtain a BrownGlock Rating. This rating is used by our panel of underwriters when assessing the price and breadth of Insurance coverage available to you and your firm. Once you have your rating you will have access to a decision in principle and the option to commence cover. </p>

<p>Our eLearning is never static; all our courses are constantly evolving as the threat landscape, legal and regulatory landscape matures. You are notified if any modules change so you can be assured you are constantly upto date with developments. </p>

<p>We also hold monthly webinars and educational sessions which you’ll be notified of; I look forward to seeing you there!</p>

<p>Thanks again.</p>

<p><b>Mark Rothwell-Brooks</b></p>
<p>Managing Partner, BrownGlock</p>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<h2><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?></h2>

<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="text-align:left;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $order->email_order_items_table( true, false, true ); ?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<th class="td" scope="row" colspan="2" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['label']; ?></th>
						<td class="td" style="text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
