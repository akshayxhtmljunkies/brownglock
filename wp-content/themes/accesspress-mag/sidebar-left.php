<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package AccessPress Mag
 */

if ( ! is_active_sidebar( 'accesspress-mag-sidebar-left' ) ) {
	return;
}
?>

<div id="secondary-left-sidebar" class="" role="complementary">
	<div id="secondary">
		<?php dynamic_sidebar( 'accesspress-mag-sidebar-left' ); ?>
	</div>
</div><!-- #secondary -->