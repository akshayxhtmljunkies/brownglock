<?php
/**
 * The template for displaying search results pages.
 *
 * @package Accesspress Mag Pro
 */

get_header(); ?>
<div class="apmag-container">
     <?php   
        $accesspress_mag_show_breadcrumbs = of_get_option( 'show_hide_breadcrumbs' );
        if ( ( function_exists( 'accesspress_breadcrumbs' ) && $accesspress_mag_show_breadcrumbs == 1 ) ) {
			    accesspress_breadcrumbs();
            }
    ?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><span><?php printf( __( 'Search Results for: %s', 'accesspress-mag' ), '</span><span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );
				?>

			<?php endwhile; wp_reset_query(); ?>

			<?php accesspress_mag_paging_nav(); ?>

		<?php else : ?>

			<?php 
            
            get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
    $sidebar_option = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
    if( $sidebar_option != 'no-sidebar' ){
       $option_value = explode( '-', $sidebar_option ); 
       get_sidebar( $option_value[0] );
    }
?>
</div>
<?php get_footer(); ?>
