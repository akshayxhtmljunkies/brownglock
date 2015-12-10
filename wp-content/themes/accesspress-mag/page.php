<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package AccessPress Mag
 */

get_header(); ?>

<?php do_action( 'accesspress_mag_before_body_content' ); ?>

	<div class="apmag-container">
        <?php 
            $accesspress_mag_show_breadcrumbs = of_get_option( 'show_hide_breadcrumbs', '1' );
                if ( !empty( $accesspress_mag_show_breadcrumbs ) && $accesspress_mag_show_breadcrumbs == 1 ) {
    				    accesspress_mag_breadcrumbs();
                    }
        ?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->

<?php 
$page_sidebar = get_post_meta( $post->ID, 'accesspress_mag_page_sidebar_layout', true );
if( empty( $page_sidebar ) ) {
    $page_sidebar = 'right-sidebar';
}
    if( $page_sidebar != 'no-sidebar' ){
        $option_value = explode( '-', $page_sidebar ); 
        get_sidebar( $option_value[0] );
    } 
?>
</div>

<?php do_action( 'accesspress_mag_after_body_content' ); ?>

<?php get_footer(); ?>