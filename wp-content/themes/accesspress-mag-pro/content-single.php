<?php
/**
 * The single post for default template
 * 
 * @package Accesspress Mag
 */
 
global $post;
$article_ad = of_get_option( 'value_article_ad' );
$show_featured_image = of_get_option( 'featured_image' );
$fallback_image_option = of_get_option( 'fallback_image_option', '1' );
$fallback_image = of_get_option( 'fallback_image', get_template_directory_uri(). '/images/no-image.jpg' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta clearfix">
            <?php echo $post_categories = get_the_category_list(); ?>
            <?php accesspress_mag_posted_on(); ?>
			<?php do_action( 'accesspress_mag_post_meta' );?>
		</div><!-- .entry-meta -->
        
	</header><!-- .entry-header -->

	<div class="entry-content">
            <?php   
                $post_format = get_post_format();
                $video_url = get_post_meta( $post->ID, 'post_embed_videourl', true );
                $audio_url = get_post_meta( $post->ID, 'post_embed_audiourl', true );
                $image_id = get_post_thumbnail_id();
                $image_path = wp_get_attachment_image_src( $image_id, 'singlepost-default', true );
                $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                if ( $post_format == 'gallery' ){
                    get_template_part( 'parts/partial-gallery' );
                } 
                elseif( $post_format == 'video' && !empty( $video_url ) ){
                    $embed_args = array(
                                'width'=>792,
                                //'height'=>356                                
                                );
                $embed_code = wp_oembed_get( $video_url, $embed_args );
                echo '<div class="single-videothumb">'. $embed_code .'</div>';
                }
                elseif( $post_format == 'audio' && !empty( $audio_url ) ) {
                    if( has_post_thumbnail() && $show_featured_image == 1 ){
                        echo '<div class="single-audiothumb">';
                        echo '<img src="'. $image_path[0]. '" alt="'. esc_attr( $image_alt ). '" />';
                        echo '<div class="archive-audiotumb">'. do_shortcode('[audio src="'.$audio_url. '"]').'</div>';
                        echo '</div>';
                    }
                    else{
                        echo '<div class="single-audiothumb">';
                        echo '<div class="archive-audiotumb">'. do_shortcode('[audio src="'.$audio_url. '"]').'</div>';
                        echo '</div>';
                    }
                } 
                else {
            ?>
            <div class="post_image">
                <?php  if( has_post_thumbnail() && $show_featured_image == 1 ) { ?>  
                    <img src="<?php echo esc_url( $image_path[0] ); ?>" alt="<?php echo esc_attr( $image_alt );?>" />
                <?php 
                    } else {
                        if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
                ?>
                    <img src="<?php echo esc_url( $fallback_image ); ?>" alt="Fallback Image" />
                <?php 
                        }
                    } 
                ?>
            </div>
           <?php } ?>     
        <div class="post-content"><?php the_content(); ?></div>
        
        <?php if( !empty( $article_ad ) ) { ?>
            <div class="article-ad-section">
                <?php echo $article_ad ; ?>
            </div>
        <?php } ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'accesspress-mag' ),
				'after'  => '</div>',
			) );
		?>        
	</div><!-- .entry-content -->

	<footer class="entry-footer">
        <?php do_action('accesspress_mag_single_post_review');?>
		<?php accesspress_mag_entry_footer(); ?>        
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
