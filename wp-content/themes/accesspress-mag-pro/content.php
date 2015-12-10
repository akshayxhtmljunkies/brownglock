<?php
/**
 * 
 * @package Accesspress Mag Pro
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
            <?php 
                if( is_author() || is_tag() || is_archive() || is_home() ){
                    echo $post_categories = get_the_category_list();
                }
            ?>
			<?php accesspress_mag_posted_on(); ?>
            <?php do_action( 'accesspress_mag_post_meta' );?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
        <?php 
            $post_format = get_post_format();
            $video_url = get_post_meta( $post->ID, 'post_embed_videourl', true );
            $audio_url = get_post_meta( $post->ID, 'post_embed_audiourl', true );
            $image_id = get_post_thumbnail_id();
            $image_path = wp_get_attachment_image_src( $image_id, 'singlepost-style1' ,true );
            $big_image_path = wp_get_attachment_image_src( $image_id, 'singlepost-default' ,true );
            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
         ?>
            <div class="post-image non-zoomin">
                <?php if( has_post_thumbnail() && $show_featured_image == 1 ) { ?>
                <a href="<?php the_permalink();?>">
                    <img src="<?php echo esc_url( $big_image_path[0] );?>" alt="<?php echo esc_attr( $image_alt );?>" />
                    <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title();?>"><i class="fa fa-external-link"></i></a>
                </a>
                <?php 
                    } else {
                        if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
                ?>
                <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url( $fallback_image ); ?>" alt="Fallback Image" />
                    <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title();?>"><i class="fa fa-external-link"></i></a>
                </a>    
                <?php
                        }
                    }
                ?>                    
            </div>
		<?php
			/* translators: %s: Name of current post */
			/*the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'accesspress-mag' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
            */
            accesspress_mag_excerpt();
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'accesspress-mag' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php accesspress_mag_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->