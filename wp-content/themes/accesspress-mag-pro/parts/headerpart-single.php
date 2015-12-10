<?php
/**
 * Header part for single post
 * 
 * @package Accesspress Mag Pro
 */
    $show_featured_image = of_get_option( 'featured_image' );
    $post_format = get_post_format();
    $video_url = get_post_meta( $post->ID, 'post_embed_videourl', true );
    $audio_url = get_post_meta( $post->ID, 'post_embed_audiourl', true );
    $image_id = get_post_thumbnail_id();
    $image_path = wp_get_attachment_image_src( $image_id, 'singlepost-large', true );
    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
    if ( (function_exists( 'accesspress_breadcrumbs' ) && $accesspress_mag_show_breadcrumbs == 1 && $content_value != 'single-style4' ) ) {
	    accesspress_breadcrumbs();
    }
    if( $content_value == 'single-style2' ){
?>
    <header class="entry-header header-style2">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>    
		<div class="entry-meta clearfix">
            <?php echo $post_categories = get_the_category_list(); ?>
            <?php accesspress_mag_posted_on(); ?>
			<?php do_action('accesspress_mag_post_meta');?>
		</div><!-- .entry-meta -->            
	</header><!-- .entry-header -->
<?php
    }
    elseif( $content_value == 'single-style3' ){
?>
	<header class="entry-header header-style3">
        <?php   
            if ( $post_format == 'gallery' ){
                get_template_part( 'parts/partial-gallery' );
            } 
            elseif( $post_format == 'video' && !empty( $video_url ) ){
                $embed_args = array(
                            'width'=>1132,
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
        <div class="post_image single-style3">
            <?php if( has_post_thumbnail() && $show_featured_image == 1 ){ ?>  
                <img src="<?php echo $image_path[0]; ?>" alt="<?php echo esc_attr( $image_alt );?>" />
            <?php } ?>
        </div>
        <?php } ?>
        <div class="single-style3 header-content">
            <div class="apmag-container">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

    		<div class="entry-meta clearfix">
                <?php echo $post_categories = get_the_category_list(); ?>
                <?php accesspress_mag_posted_on(); ?>
    			<?php do_action( 'accesspress_mag_post_meta' );?>
    		</div><!-- .entry-meta -->
            </div>
        </div>
        
	</header><!-- .entry-header -->
<?php
    } 
?>