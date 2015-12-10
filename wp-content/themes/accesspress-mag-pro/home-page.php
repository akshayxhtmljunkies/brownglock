<?php
/**
 * Template Name: Home Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Accesspress Mag Pro
 */

get_header();
$accesspress_mag_theme_option = get_option( 'accesspress-mag-theme' );
$slider_option = of_get_option( 'slider_option', 1 );
$slider_effect_optoin = of_get_option( 'slider_effect_option', '' );
$rev_slider_option = of_get_option( 'slider_layout', 'slider-default' );
$rev_slider_shortcode = of_get_option( 'apmag_rev_slider_shortcode', '' );
$homepage_layout_option = of_get_option( 'homepage_layout', 'home-default' );
?>

    <?php if( $slider_option == '1' && $accesspress_mag_theme_option !='' ){ ?>
        <section class="slider-wrapper">
            <div class="apmag-container"> 
                <?php  
                    if( !empty( $slider_effect_optoin ) && $slider_effect_optoin == '1' ) {
                        do_action( 'accesspress_mag_non_slider' );
                    } elseif( !empty( $rev_slider_option ) && $rev_slider_option == 'slider-rev' && !empty( $rev_slider_shortcode ) ) {
                        echo do_shortcode( $rev_slider_shortcode );
                    } else {
                        do_action( 'accesspress_mag_slider' );                     
                    }
                ?>
            </div>                  
        </section>
    <?php  } ?>
    <div class="apmag-container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <?php
                $popular_block_option = of_get_option( 'popular_section_option', '0' );
                if( $popular_block_option == 1 && $accesspress_mag_theme_option !='' ) {
            ?>
            <section class="popular-block wow fadeInUp clearfix" data-wow-delay="0.5s">
                <?php do_action( 'accesspress_mag_popular_block' ); ?>
            </section>
            <?php } ?>
            <?php 
                if( !empty( $homepage_layout_option ) ) {
                    get_template_part( 'content', $homepage_layout_option );
                } else {
                    get_template_part( 'content', 'home-default' );
                }
            ?>
            <?php if ( is_active_sidebar( 'apmag-home-middle-content-section' ) ) : ?>
                <div class="homepage-middle-content-section">
                    <?php dynamic_sidebar( 'apmag-home-middle-content-section' ); ?> 
                </div>
            <?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
$page_sidebar = get_post_meta( $post->ID, 'accesspress_mag_page_sidebar_layout', true );
    if( $page_sidebar != 'no-sidebar' ){
        get_sidebar( 'home' );
    } 
?>
</div>
<?php get_footer(); ?>
