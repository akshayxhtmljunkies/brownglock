<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Accesspress Mag Pro
 */
 
wp_reset_postdata();
global $post;
$accesspress_mag_theme_option = get_option( 'accesspress-mag-theme' );
$sidebar_top_ad = of_get_option( 'value_sidebar_top_ad' );
$sidebar_middle_ad = of_get_option( 'value_sidebar_middle_ad' ); 
$trans_ads = of_get_option( 'trans_advertisement', __( 'Advertisement', 'accesspress-mag' ) );
$page_sidebar = get_post_meta( $post->ID, 'accesspress_mag_page_sidebar_layout', true );
?>
<div id="secondary-<?php if( empty( $page_sidebar ) && ( $accesspress_mag_theme_option == '' ) ){ echo 'right-sidebar'; }else{ echo $page_sidebar; } ?>" class="widget-area" role="complementary">
    <div id="secondary" class="secondary-wrapper">
        <?php if ( is_active_sidebar( 'apmag-home-top-sidebar' )) { ?>
        <div id="home-top-sidebar" class="widget-area wow fadeInUp" data-wow-delay="0.5s" role="complementary">
        	<?php dynamic_sidebar( 'apmag-home-top-sidebar' ); ?>
        </div>
        <?php } ?>
        
        <?php if(!empty($sidebar_top_ad)){ ?>
        <div class="sidebar-top-ad widget-area wow fadeInUp" data-wow-delay="0.5s">
            <h1 class="widget-title"><span><?php echo esc_attr( $trans_ads ) ;?></span></h1>
            <div class="ad_content"><?php echo $sidebar_top_ad ;?></div>
        </div>
        <?php } ?>
        
        <?php if ( is_active_sidebar( 'apmag-home-middle-sidebar' )) { ?>
        <div id="home-top-sidebar" class="widget-area wow fadeInRight" data-wow-delay="0.5s" role="complementary">
        	<?php dynamic_sidebar( 'apmag-home-middle-sidebar' ); ?>
        </div>
        <?php } ?>
        
        <?php if( !empty( $sidebar_middle_ad ) ) { ?>
        <div class="sidebar-top-ad widget-area wow fadeInUp" data-wow-delay="0.5s">
            <h1 class="widget-title"><span><?php echo esc_attr( $trans_ads ) ;?></span></h1>
            <div class="ad_content"><?php echo $sidebar_middle_ad ;?></div>
        </div>
        <?php } ?>
        
        <?php if ( is_active_sidebar( 'apmag-home-bottom-sidebar' )) { ?>
        <div id="home-top-sidebar" class="widget-area wow fadeInUp" data-wow-delay="0.5s" role="complementary">
        	<?php dynamic_sidebar( 'apmag-home-bottom-sidebar' ); ?>
        </div>
        <?php } ?>    
    </div><!-- #secondary -->
</div><!--Secondary-right-sidebar-->

