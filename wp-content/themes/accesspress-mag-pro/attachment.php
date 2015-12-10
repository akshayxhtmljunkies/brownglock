<?php
/**
 * The template for displaying attachment posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AccessPress Mag Pro
 */

get_header(); 
$article_ad = of_get_option( 'value_article_ad' );
$accesspress_mag_show_breadcrumbs = of_get_option( 'show_hide_breadcrumbs', '1' );
$accesspress_mag_show_author_box = of_get_option( 'show_author_box', '1' );
$show_post_navigation = of_get_option( 'show_post_nextprev', '1' );
$show_similar_article = of_get_option( 'show_similar_article', '1' );
?>

    <div class="apmag-container">
        <?php
            if ( ( function_exists( 'accesspress_breadcrumbs' ) && $accesspress_mag_show_breadcrumbs == 1 ) ) {
        	    accesspress_breadcrumbs();
            }
        ?>
    	<div id="primary" class="content-area">
    		<main id="main" class="site-main" role="main">
    
    		
            <?php while ( have_posts() ) : the_post(); ?>
    
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
                            $image_id = get_post_thumbnail_id();
                            $image_path = wp_get_attachment_image_src( $image_id, 'full', true );
                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                        ?>
                            <div class="post_image">
                                <figure><img src="<?php echo esc_url( $image_path[0] );?>" alt="<?php echo esc_attr( $image_alt ); ?>" /></figure>
                            </div>
                        <?php
                            the_content(); 
                        ?>
                		<?php
                			wp_link_pages( array(
                				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'underscores' ),
                				'after'  => '</div>',
                			) );
                            if( !empty( $show_post_navigation ) && $show_post_navigation == '1' ) {
                		?>
                            <nav id="image-navigation" class="navigation image-navigation">
                                <div class="nav-links">
                                <?php previous_image_link( 'full', '<div class="previous-image">' . __( 'Previous Image', 'accesspress-mag' ) . '</div>' ); ?>
                                <?php next_image_link( 'full', '<div class="next-image">' . __( 'Next Image', 'accesspress-mag' ) . '</div>' ); ?>
                                </div><!-- .nav-links -->
                            </nav><!-- #image-navigation -->
                        <?php 
                            }
                            if( !empty( $article_ad ) ) { ?>
                            <div class="article-ad-section">
                                <?php echo $article_ad ; ?>
                            </div>
                        <?php } ?>
                	</div><!-- .entry-content -->
                
                	<?php 
                        if( $accesspress_mag_show_author_box == 1 && !empty( $accesspress_mag_show_author_box ) ) {
                            get_template_part( 'parts/post-author-box' );
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
                </article><!-- #post-## -->
    
    		<?php endwhile; // End of the loop. ?>
    
    		</main><!-- #main -->
    	</div><!-- #primary -->
        <?php 
            $sidebar_option = of_get_option( 'global_post_sidebar', 'right-sidebar' );
            if( $sidebar_option != 'no-sidebar' ){
                $option_value = explode( '-', $sidebar_option ); 
                get_sidebar( $option_value[0] );
            }
        ?>
    </div>
<?php get_footer(); ?>
