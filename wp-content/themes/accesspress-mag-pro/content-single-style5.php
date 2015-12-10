<?php
/**
 * Single post content for Style 5th
 * 
 * @package Accesspress Mag Pro
 */
 
 global $post;
 $article_ad = of_get_option( 'value_article_ad' );
 $show_featured_image = of_get_option( 'featured_image' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <?php 
            if ( get_post_format() == 'gallery' ){
                    get_template_part( 'parts/partial-gallery' );
                } 
        ?>
        <div class="post_content"><?php the_content(); ?></div>	        	
        <?php if( !empty( $article_ad )):?> <div class="article-ad-section"><?php echo $article_ad ; ?></div> <?php endif ;?>
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
