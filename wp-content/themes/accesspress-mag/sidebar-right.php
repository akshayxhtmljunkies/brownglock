<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package AccessPress Mag
 */

if ( ! is_active_sidebar( 'accesspress-mag-sidebar-right' ) ) {
	return;
}
?>

<div id="secondary-right-sidebar" class="widget-area" role="complementary">
	<div id="secondary">
		<?php dynamic_sidebar( 'accesspress-mag-sidebar-right' ); ?>
	</div>
</div><!-- #secondary -->