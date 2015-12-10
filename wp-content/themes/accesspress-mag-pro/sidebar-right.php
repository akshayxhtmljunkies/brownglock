<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Accesspress Mag Pro
 */

if ( ! is_active_sidebar( 'sidebar-right' ) ) {
	return;
}
?>

<div id="secondary-right-sidebar" class="widget-area" role="complementary">
	<div id="secondary" class="secondary-wrapper">
		<?php dynamic_sidebar( 'sidebar-right' ); ?>
	</div>
</div><!-- #secondary -->