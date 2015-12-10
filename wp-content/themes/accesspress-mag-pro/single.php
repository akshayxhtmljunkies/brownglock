<?php
/**
 * The template for displaying all single posts.
 *
 * @package Accesspress Mag Pro
 */

get_header();
wp_reset_postdata();
global $post;
$accesspress_mag_show_breadcrumbs = of_get_option( 'show_hide_breadcrumbs' );
$post_template_value = of_get_option( 'global_post_template', 'single' );
$apmag_post_template = get_post_meta( $post -> ID, 'accesspress_mag_post_template_layout', true );
if( $apmag_post_template == 'global-template' || empty( $apmag_post_template ) ){
    $content_value = $post_template_value;
} else {
    $content_value = $apmag_post_template;
}
if( $content_value == 'single-style4' ){
    $show_featured_image = of_get_option( 'featured_image' ); 
    $post_format = get_post_format();
    $video_url = get_post_meta( $post->ID, 'post_embed_videourl', true );     
    $audio_url = get_post_meta( $post->ID, 'post_embed_audiourl', true );
    $image_id = get_post_thumbnail_id();
    $image_path = wp_get_attachment_image_src( $image_id, 'singlepost-large', true );
    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
?>
 
<header class="entry-header header-style4">
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
        <div class="post_image single-style4">
            <?php if( has_post_thumbnail() && $show_featured_image == 1 ){ ?>  
                <img src="<?php echo $image_path[0]; ?>" alt="<?php echo esc_attr( $image_alt );?>" />
            <?php } ?>
        </div>
        
        <?php } ?>   
        <div class="single-style4 header-content"> 
            <div class="apmag-container">
            <?php 
                if ( (function_exists( 'accesspress_breadcrumbs' ) && $accesspress_mag_show_breadcrumbs == 1 ) ) {
            	    accesspress_breadcrumbs();
                }
                the_title( '<h1 class="entry-title">', '</h1>' ); 
            ?>    
    		<div class="entry-meta clearfix">
                <?php echo $post_categories = get_the_category_list(); ?>
                <?php accesspress_mag_posted_on(); ?>
    			<?php do_action( 'accesspress_mag_post_meta' );?>
    		</div><!-- .entry-meta -->
            </div>      
        </div>
        
	</header><!-- .entry-header -->    
<?php } ?>

<div class="apmag-container">
    <?php require_once( 'parts/headerpart-single.php' );?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', $content_value ); ?>
            <?php 
                $accesspress_mag_show_author_box = of_get_option( 'show_author_box', '1' );
                $show_post_navigation = of_get_option( 'show_post_nextprev', '1' );
                $show_similar_article = of_get_option( 'show_similar_article', '1' );
                
                if( $accesspress_mag_show_author_box == 1 && !empty( $accesspress_mag_show_author_box ) ) {
                    get_template_part( 'parts/post-author-box' );
                }
                
                if( !empty( $show_post_navigation ) && $show_post_navigation == '1' ) { 
                    accesspress_mag_post_navigation(); 
                }
                if ( $show_similar_article == 1 && !empty( $show_similar_article ) ) {
                    get_template_part( 'parts/similar-posts' );
                }
                
                // If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
                
                setPostViews( get_the_ID() );
             ?>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
    $global_sidebar= of_get_option( 'global_post_sidebar' );
    $post_sidebar = get_post_meta( $post->ID, 'accesspress_mag_sidebar_layout', true );
    if( $post_sidebar == 'global-sidebar' || empty( $post_sidebar ) ){
        $sidebar_option = $global_sidebar;
    } else {
        $sidebar_option = $post_sidebar;
    }
    if( $sidebar_option != 'no-sidebar' ){
        $option_value = explode( '-', $sidebar_option ); 
        get_sidebar( $option_value[0] );
    }
 ?>
</div>
<?php get_footer(); ?>
