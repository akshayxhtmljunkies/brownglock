<?php
/**
 * The template for displaying Course activity
 *
 * Override this template by copying it to yourtheme/course/single/activity.php
 *
 * @author 		VibeThemes
 * @package 	vibe-course-module/templates
 * @version     1.8.2
 */
?>
<div class="activity">
<?php do_action( 'bp_before_course_activity_loop' ); ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php /* Show pagination if JS is not enabled, since the "Load More" link will do nothing */ ?>
	
	<?php if ( empty( $_POST['page'] ) ) : ?>

		<ul id="activity-stream" class="activity-list item-list">

	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php locate_template( array( 'activity/entry.php' ), true, false ); ?>

	<?php endwhile; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<li class="load-more">
			<a href="#more"><?php _e( 'Load More', 'vibe' ); ?></a>
		</li>

	<?php endif; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		</ul>

	<?php endif; ?>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'vibe' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_activity_loop' ); ?>

<form action="" name="activity-loop-form" id="activity-loop-form" method="post">

	<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

</form>
</div>