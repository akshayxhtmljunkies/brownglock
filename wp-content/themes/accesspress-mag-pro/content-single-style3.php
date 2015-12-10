<?php
/**
 * Single post content for Style 3rd
 * 
 * @package Accesspress Mag Pro
 */
 
 global $post;
 $article_ad = of_get_option( 'value_article_ad' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <div class="post_content"><?php the_content(); ?></div>	        	
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
        <?php do_action( 'accesspress_mag_single_post_review' );?>
		<?php accesspress_mag_entry_footer(); ?>        
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
