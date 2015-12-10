<?php 
get_header( 'buddypress' ); 
global $wp_query;
$curauth = $wp_query->get_queried_object();

?>
<section id="memberstitle">
    <div class="container">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                   	<h1><?php _e('All Courses by ','vibe'); echo $curauth->display_name; ?> </h1>
                    <h5><?php 
                    if(function_exists('bp_course_get_instructor_description'))
                    	echo bp_course_get_instructor_description('instructor_id='.$curauth->ID);
                    else
                    	echo $curauth->description;
                    	?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
				<a class="button create-group-button full" href="<?php echo bp_core_get_user_domain( get_the_author_meta('ID')); ?>"><?php echo sprintf(__('%s profile','vibe'),bp_core_get_user_displayname(get_the_author_meta('ID'))); ?></a>
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div id="buddypress">
    <div class="container">

		<div class="padder">

		<div class="row">
			<div class="col-md-9 col-sm-8">
			<?php
				if ( have_posts() ) : while ( have_posts() ) : the_post();
				global $post;
				$style=apply_filters('wplms_instructor_courses_style','course2');
				echo '<div class="col-md-4 col-sm-6">'.thumbnail_generator($post,$style,'3','0',true,true).'</div>';
				endwhile;
				pagination();
				endif;
			?>
			</div>	
			<div class="col-md-3 col-sm-4">
				<?php
                    $sidebar = apply_filters('wplms_sidebar','buddypress');
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                <?php endif; ?>
			</div>
		</div>	
		<?php do_action( 'bp_after_directory_course' ); ?>

		</div><!-- .padder -->
	
	<?php do_action( 'bp_after_directory_course_page' ); ?>
</div><!-- #content -->
</div>
</section>

<?php get_footer( 'buddypress' ); ?>