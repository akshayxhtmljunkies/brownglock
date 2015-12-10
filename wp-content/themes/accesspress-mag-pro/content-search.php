<?php
/**
 * Content page for search result posts
 * 
 * @package Accesspress Mag Pro
 */
 global $post;
 
 $archive_template = of_get_option( 'global_archive_template' );
    switch ( $archive_template ) {
    	case 'archive-default':
    		$image_size = 'accesspress-mag-singlepost-default';
    		break;
    
    	case 'archive-style1':
    		$image_size = 'accesspress-mag-singlepost-style1';
            break;
    
    	case 'archive-style2':
    		$image_size = 'accesspress-mag-singlepost-style1';
    		break;
    	
    	default:
    		$image_size = 'accesspress-mag-singlepost-default';
    		break;
    }
    $image_path = wp_get_attachment_image_src( get_post_thumbnail_id(), $image_size , true );
    $image_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
    $apmag_overlay_icon = of_get_option( 'apmag_overlay_icon', 'fa-external-link' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
            <?php 
                if( is_author() || is_tag() || is_archive() ){
                    echo $post_categories = get_the_category_list();
                }
            ?>
			<?php accesspress_mag_posted_on(); ?>
            <?php do_action( 'accesspress_mag_post_meta' );?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
        <?php if(has_post_thumbnail()){ ?>
                <div class="post-image non-zoomin">
                    <a href="<?php the_permalink();?>"><img src="<?php echo $image_path[0];?>" alt="<?php echo esc_attr( $image_alt );?>" /></a>
                    <a class="big-image-overlay" href="<?php the_permalink();?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a>
                    <?php 
                        $post_format = get_post_format();
                        if( $post_format == 'video' ){
                            echo '<span class="format_icon"><i class="fa fa-video-camera"></i></span>';
                        }
                        elseif( $post_format == 'audio' ){
                            echo '<span class="format_icon"><i class="fa fa-music"></i></span>';
                        }
                        elseif( $post_format == 'gallery' ){
                            echo '<span class="format_icon"><i class="fa fa-picture-o"></i></span>';
                        }
                        else{ } 
                    ?>
                </div>
        <?php 
            }            
        ?>
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