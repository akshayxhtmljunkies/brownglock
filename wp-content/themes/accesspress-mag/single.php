<?php
/**
 * The template for displaying all single posts.
 *
 * @package AccessPress Mag
 */

get_header(); 
global $post;
wp_reset_postdata();
$accesspress_mag_show_breadcrumbs = of_get_option( 'show_hide_breadcrumbs', '1' );
$post_template_value = of_get_option( 'global_post_template', 'single' );
$accesspress_mag_post_template = get_post_meta( $post->ID, 'accesspress_mag_post_template_layout', true );
if( $accesspress_mag_post_template == 'global-template' || empty( $accesspress_mag_post_template ) ){
    $content_value = $post_template_value;
} else {
    $content_value = $accesspress_mag_post_template;
}
do_action( 'accesspress_mag_before_body_content' );

?>
<div class="apmag-container">
    <?php
        if ( !empty( $accesspress_mag_show_breadcrumbs ) && $accesspress_mag_show_breadcrumbs == '1' ) {
    	    accesspress_mag_breadcrumbs();
        }
    ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', $content_value ); ?>
            <?php 
                $accesspress_mag_show_author_box = of_get_option( 'show_author_box' );
                if( $accesspress_mag_show_author_box == 1 ):
            ?>
            <div class="author-metabox">
                <?php
                    $author_id = $post->post_author;
                    $author_avatar = get_avatar( $author_id, '106' );
                    $author_nickname = get_the_author_meta( 'display_name' );                
                ?>
                <div class="author-avatar">
                    <a class="author-image" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo $author_avatar; ?></a>
                </div>
                <div class="author-desc-wrapper">                
                    <a class="author-title" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo esc_attr( $author_nickname ); ?></a>
                    <div class="author-description"><?php echo get_the_author_meta('description');?></div>
                    <a href="<?php echo esc_url( get_the_author_meta( 'user_url' ) );?>" target="_blank"><?php echo esc_url( get_the_author_meta( 'user_url' ) );?></a>
                </div>
            </div><!--author-metabox-->
            <?php endif ;?>

			<?php 
                $show_post_navigation = of_get_option( 'show_post_nextprev', '1' );
                if( $show_post_navigation == '1' ) { 
                    accesspress_mag_post_navigation();
                }
                
                // If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
                
                //accesspress_mag_setPostViews( get_the_ID() ); 
            ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
    $global_sidebar = of_get_option( 'global_post_sidebar' );
    $post_sidebar = get_post_meta( $post -> ID, 'accesspress_mag_sidebar_layout', true );
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

<?php do_action( 'accesspress_mag_after_body_content' ); ?>

<?php get_footer(); ?>