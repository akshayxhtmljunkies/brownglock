<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package AccessPress Mag
 */
 
$accesspress_mag_theme_option = get_option( 'accesspress-mag-theme' );

$trans_ads = of_get_option( 'trans_advertisement', 'Advertisement' );
if( empty( $trans_ads ) ){ $trans_ads = __( 'Advertisement', 'accesspress-mag' ); }

$trans_editor = of_get_option( 'trans_editor_picks' );
if( empty( $trans_editor ) ){ $trans_editor = __( "Editor Pick's", "accesspress-mag" ); }

$page_sidebar = get_post_meta( $post->ID, 'accesspress_mag_page_sidebar_layout', true);

?>

<div id="secondary-<?php if( empty( $page_sidebar ) && ( $accesspress_mag_theme_option == '' ) ){ echo 'right-sidebar';}else{ echo $page_sidebar; } ?>" class="widget-area" role="complementary">
    <div id="secondary" class="secondary-wrapper">
        <?php if ( is_active_sidebar( 'accesspress-mag-home-top-sidebar' )) : ?>
        <div id="home-top-sidebar" class="widget-area wow fadeInUp" data-wow-delay="0.5s" role="complementary">
        	<?php dynamic_sidebar( 'accesspress-mag-home-top-sidebar' ); ?>
        </div><!-- #secondary -->
        <?php  endif ; ?>

        <?php if ( is_active_sidebar( 'accesspress-mag-homepage-sidebar-top-ad' ) ) : ?>
            <div class="sidebar-top-ad widget-area wow fadeInUp" data-wow-delay="0.5s">
                <h1 class="sidebar-title"><span><?php echo esc_attr( $trans_ads ) ;?></span></h1>
                <div class="ad_content">
                    <?php dynamic_sidebar( 'accesspress-mag-homepage-sidebar-top-ad' ); ?> 
                </div>
            </div><!--header ad-->
        <?php endif; ?>

        
        <?php if ( is_active_sidebar( 'accesspress-mag-home-middle-sidebar' )) : ?>
        <div id="home-middle-sidebar" class="widget-area wow fadeInRight" data-wow-delay="0.5s" role="complementary">
        	<?php dynamic_sidebar( 'accesspress-mag-home-middle-sidebar' ); ?>
        </div><!-- #secondary -->
        <?php endif ; ?>
        
        <div class="sidebar-editor-pick widget-area wow fadeInUp" data-wow-delay="0.5s">
            <div class="content-wrapper">
            <?php 
                $editor_cat = of_get_option('editor_pick_category');
                $editor_posts_per_page = of_get_option('posts_for_editor_pick');
                if(!empty($editor_cat)):
            ?>
                <h1 class="sidebar-title"><span><?php echo esc_attr( $trans_editor ) ;?></span></h1>
            <?php
                echo '<div class="sidebar-posts-wrapper">';
                $editor_args = array(
                                'category_name' => $editor_cat,
                                'post_status' => 'pubish',
                                'posts_per_page' => $editor_posts_per_page,
                                'order' => 'DESC'
                                );
                $editor_query = new WP_Query( $editor_args );
                $e_counter = 0;
                $total_posts_editor = $editor_query->found_posts;
                if($editor_query->have_posts()){
                    while($editor_query->have_posts()){
                        $e_counter++;
                        $editor_query->the_post();
                        $editor_image_id = get_post_thumbnail_id();
                        $editor_big_image_path = wp_get_attachment_image_src( $editor_image_id, 'accesspress-mag-block-big-thumb', true );
                        $editor_small_image_path = wp_get_attachment_image_src( $editor_image_id, 'accesspres-mag-block-small-thumb', true );
                        $editor_image_alt = get_post_meta( $editor_image_id, '_wp_attachment_image_alt', true );
            ?>
                <div class="single_post clearfix <?php if( $e_counter == 1 ){ echo 'first-post non-zoomin'; }?>">
                    <?php if(has_post_thumbnail()): ?>   
                        <div class="post-image">
                            <a href="<?php the_permalink();?>"><img src="<?php if($e_counter ==1){echo esc_url( $editor_big_image_path[0] );}else{ echo esc_url( $editor_small_image_path[0] ) ;}?>" alt="<?php echo esc_attr($editor_image_alt);?>" /></a>
                            <?php if($e_counter==1):?> <span class="big-image-overlay"><a href="<?php the_permalink();?>"><i class="fa fa-external-link"></i></a></span><?php endif ;?>
                        </div>                                
                    
                    <?php 
                        endif ;
                        if($e_counter>1){echo '<div class="post-desc-wrapper">';} 
                    ?>
                        <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                        <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                        <div class="block-poston"><?php do_action('accesspress_mag_block_post_on');?></div>
                        <?php if($e_counter>1){echo '</div>';} if($e_counter ==1 ):?><div class="post-content"><?php echo '<p>'. accesspress_mag_word_count( get_the_content(), 25) .'</p>' ;?></div><?php endif ;?>
                </div>
            <?php
                    }
                }
                echo '</div>';
            ?>
            <?php endif ; ?>    
            </div>
        </div>
        
        <?php if ( is_active_sidebar( 'accesspress-mag-homepage-sidebar-middle-ad' ) ) : ?>
            <div class="sidebar-top-ad widget-area wow fadeInUp" data-wow-delay="0.5s">
                <h1 class="sidebar-title"><span><?php echo esc_attr( $trans_ads ) ;?></span></h1>
                <div class="ad_content"><?php dynamic_sidebar( 'accesspress-mag-homepage-sidebar-middle-ad' ); ?></div>
            </div><!--header ad-->
        <?php endif; ?>
        
        <?php if ( is_active_sidebar( 'accesspress-mag-home-bottom-sidebar' )) : ?>
        <div id="home-bottom-sidebar" class="widget-area wow fadeInUp" data-wow-delay="0.5s" role="complementary">
        	<?php dynamic_sidebar( 'accesspress-mag-home-bottom-sidebar' ); ?>
        </div><!-- #secondary -->
        <?php endif ; ?> 
    </div>
</div><!--Secondary-right-sidebar-->