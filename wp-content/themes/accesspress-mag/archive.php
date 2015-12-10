<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					accesspress_mag_the_archive_title( '<h1 class="page-title"><span>', '</span></h1>' );
					//the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
                    get_template_part( 'content', 'archive' );
				?>

			<?php endwhile; wp_reset_query(); ?>

			<?php accesspress_mag_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
 $sidebar_option = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
 if( $sidebar_option != 'no-sidebar' ){
        $option_value = explode( '-', $sidebar_option ); 
        get_sidebar( $option_value[0] );
    }
?>
</div>

<?php do_action( 'accesspress_mag_after_body_content' ); ?>

<?php get_footer(); ?>