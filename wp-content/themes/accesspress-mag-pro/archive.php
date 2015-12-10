<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Accesspress Mag Pro
 */

get_header(); ?>
<div class="apmag-container">
    <?php   
        $accesspress_mag_show_breadcrumbs = of_get_option('show_hide_breadcrumbs');
        if ((function_exists('accesspress_breadcrumbs') && $accesspress_mag_show_breadcrumbs == 1)) {
			    accesspress_breadcrumbs();
            }
    ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
            if ( have_posts() ) : 
        ?>

			<header class="page-header">
				<?php
					apmag_the_archive_title( '<h1 class="page-title"><span>', '</span></h1>' );
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
                    get_template_part( 'content', 'archive-default' );
				?>

			<?php endwhile; wp_reset_query(); ?>

			<?php accesspress_mag_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
    if( is_category() ) {
        $cat_id = get_query_var( 'cat' );
        $global_sidebar = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
        $category_sidebar = of_get_option( $cat_id.'_cat_sidebar', 'global-sidebar' );
        if( $category_sidebar == 'global-sidebar' ){
            $sidebar_option = $global_sidebar;
        } else {
            $sidebar_option = $category_sidebar;
        }
        if( $sidebar_option != 'no-sidebar' ){
            $option_value = explode( '-', $sidebar_option ); 
            get_sidebar( $option_value[0] );
        }
    } else {
        $sidebar_option = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
        if( $sidebar_option != 'no-sidebar' ){
               $option_value = explode( '-', $sidebar_option ); 
               get_sidebar( $option_value[0] );
        }
    }
?>
</div>
<?php get_footer(); ?>
