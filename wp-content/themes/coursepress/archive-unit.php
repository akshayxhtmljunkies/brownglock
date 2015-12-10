<?php
/**
 * The units archive template file
 *
 * @package CoursePress
 */
global $coursepress;
$course_id	 = do_shortcode( '[get_parent_course_id]' );
$course_id = (int) $course_id;
$progress			 = do_shortcode( '[course_progress course_id="' . $course_id . '"]' );
//redirect to the parent course page if not enrolled
$coursepress->check_access( $course_id );
global $wpdb , $current_user;
get_currentuserinfo();
$user_id = $current_user->ID;
$annual_turnover = get_user_meta( $user_id, 'annual_turnover', true );
$data_records = get_user_meta( $user_id, 'data_records', true );
get_header();




function numbertokmb($n)
{
	if ($n < 1000000) {
    // Anything less than a million
    $n_format = number_format($n / 1000,2) . 'K';
} else if ($n < 1000000000) {
    // Anything less than a billion
    $n_format = number_format($n / 1000000,2) . 'M';
} else {
    // At least a billion
    $n_format = number_format($n / 1000000000,2) . 'B';
}
return str_replace('.00', '', $n_format);
}
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
    	 <table>
        	<tr>
        		<td style="width:300px;"><h2><?php echo "BrownGlock";//get_bloginfo(); //get_the_title( $course_id ) ?></h2></td>
        		<td style="width:210px;">Records</br><?php if($data_records != ''){echo numbertokmb($data_records);}else{ echo "0"; }  ?></td>
        		<td style="width:210px;">Turnover</br><?php if($annual_turnover != ''){echo numbertokmb($annual_turnover);}else{ echo "0"; }  ?></td>
        		<td style="width:210px;">Cost of Beach</br><?php if($data_records != ''){echo "$".numbertokmb($data_records*158);}else{ echo "0"; }  ?></td>
        		<td style="width:210px;">BG Rating</td>
        	</tr>
        </table>
	    <!-- <h1><?php //echo get_the_title( $course_id ) ?></h1> -->
        <div class="instructors-content">
			<?php
			// Flat hyperlinked list of instructors
			echo do_shortcode( '[course_instructors style="list-flat" link="true" course_id="' . $course_id . '"]' );
			?>
        </div>

		<?php
		echo do_shortcode( '[course_unit_archive_submenu]' ) . '&nbsp;';
		?>
		<?php
		if ( 100 == (int) $progress ) {
			echo sprintf( '<div class="unit-archive-course-complete">%s %s</div>', '<i class="fa fa-check-circle"></i>', __( 'Course Complete', 'cp' ) );
		}
		?>

        <div class="clearfix"></div>
       
        <ul class="units-archive-list">
			<?php if ( have_posts() ) { ?>
				<?php
				$args	 = array(
					'order'			 => 'ASC',
					'post_type'		 => 'unit',
					'post_status'	 => (current_user_can( 'manage_options' ) ? 'any' : 'publish'),
					'meta_key'		 => 'unit_order',
					'orderby'		 => 'meta_value_num',
					'posts_per_page' => '-1',
					'meta_query'	 => array(
						'relation' => 'AND',
						array(
							'key'	 => 'course_id',
							'value'	 => $course_id
						),
					)
				);
				$posts	 = query_posts( $args );
				while ( have_posts() ) {
					the_post();

					$additional_class	 = '';
					$additional_li_class = '';

					$unit_id = get_the_ID();
					$is_unit_available = Unit::is_unit_available( $unit_id );

					if ( !$is_unit_available ) {
						$additional_class	 = 'locked-unit';
						$additional_li_class = 'li-locked-unit';
					}

					$unit_progress = do_shortcode( '[course_unit_percent course_id="' . $course_id . '" unit_id="' . $unit_id . '" format="true" style="extended"]' );

					?>
					<li class="<?php echo $additional_li_class; ?>">
						<div class='<?php echo $additional_class; ?>'></div>
						<div class="unit-archive-single">
							<?php //echo do_shortcode( '[course_unit_details field="percent" format="true" style="extended"]' ); ?>
							<?php echo $unit_progress; ?>
							<?php echo do_shortcode('[course_unit_title link="yes" last_page="yes"]'); ?>
							<!-- <a class="unit-archive-single-title" href="<?php //echo do_shortcode( '[course_unit_details field="permalink" last_visited="true" unit_id="' . get_the_ID() . '"]' ); ?>" rel="bookmark"><?php the_title() . ' ' . (get_post_status() !== 'publish' && current_user_can( 'manage_options' ) ? _e( ' [DRAFT]', 'cp' ) : ''); ?></a> -->
							<?php echo do_shortcode( '[module_status format="true" course_id="' . $course_id . '" unit_id="' . $unit_id . '"]' ); ?>
							
						</div>
					</li>
						
					
					
					
					
					
					<?php
				}
			} else {
				?>
				<h1 class="zero-course-units"><?php _e( "0 units in the course currently. Please check back later." ); ?></h1>
				<?php
			}
			wp_reset_postdata();
			?>
        </ul>
    </main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar( 'footer' ); ?>
<?php get_footer(); ?>