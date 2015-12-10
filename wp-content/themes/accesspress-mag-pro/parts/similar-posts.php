<?php 
/**
 * The template for displaying similar posts.
 *
 * @package Accesspress Mag Pro
 */

global $post;
$post_id = get_the_ID(); 
$global_sidebar= of_get_option( 'global_post_sidebar' );
$post_sidebar = get_post_meta( $post_id, 'accesspress_mag_sidebar_layout', true );
$sa_section_title = of_get_option( 'trans_similar_articles', 'Similar Articles' );
$fallback_image_option = of_get_option( 'fallback_image_option', '1' );
$fallback_image = of_get_option( 'fallback_image', get_template_directory_uri(). '/images/no-image.jpg' );
$apmag_overlay_icon = of_get_option( 'apmag_overlay_icon', 'fa-external-link' );
if( $post_sidebar == 'global-sidebar' ){
    $sidebar_option = $global_sidebar;
} else {
    $sidebar_option = $post_sidebar;
}
$similar_posts = accesspress_mag_similar_posts_function(); 
if ( $similar_posts->have_posts() ): 
$article_count = 0 
?>

<h2 class="similar-posts-main-title"><span><?php echo esc_attr( $sa_section_title ); ?></span></h2>
<div class="similar-posts clearfix">
   <?php 
    while ( $similar_posts->have_posts() ) : 
    $similar_posts->the_post(); 
    $article_count++;
    $post_format = get_post_format( get_the_ID() );
    if( $post_format == 'video' ){
        $post_format_icon = 'fa-video-camera';
        $show_icon = 'on';
    } elseif( $post_format == 'audio' ){
        $post_format_icon = 'fa-music';
        $show_icon = 'on';
    } elseif( $post_format == 'gallery' ){
        $post_format_icon = 'fa-picture-o';
        $show_icon = 'on';
    } else{
        $show_icon = 'off';
    }
   ?>
   <div class="single_post <?php if( $sidebar_option != 'no-sidebar' ){ if( $article_count % 2 == 1 ){ echo 'left-post' ; } } else { if( $article_count % 3 == 1 ){ echo 'left-post' ; } } ; ?> non-zoomin clearfix">
      <?php
            $image_id = get_post_thumbnail_id();
            $image_path = wp_get_attachment_image_src( $image_id, 'accesspress-mag-block-big-thumb', true );
            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
      ?>
         <div class="post-image">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php if( has_post_thumbnail() ) { ?>
                    <img src="<?php echo $image_path[0];?>" alt="<?php echo $image_alt; ?>" />
                <?php } else { 
                            if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
                ?>
                    <img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php _e( 'Fallback Image',  'accesspress-mag' ); ?>" />
                <?php } } ?>
            </a>
            <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title();?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a>
            <?php if( $show_icon == 'on' ){?><span class="format_icon"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
         </div>
         <h4 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4><!--/.post-title-->
         <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
   </div><!--/.single_post-->
   <?php endwhile; ?>

</div><!--/.post-similar-->
<?php endif; ?>
<?php wp_reset_query(); ?>