<?php

/**

 * @package zerif

 */

?>



<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">

	<header class="entry-header">

		<?php zerif_portfolio_header_trigger(); ?>

	</header><!-- .entry-header -->



	<?php	

	if ( is_singular( 'portofolio' ) ) {

		$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') );

		echo '<img src="'.$url.'" />';



	}

	?>

	

	<div class="entry-content" itemprop="text">

		<?php the_content(); ?>

		<?php

			wp_link_pages( array(

				'before' => '<div class="page-links">' . __( 'Pages:', 'zerif' ),

				'after'  => '</div>',

			) );

		?>

	</div><!-- .entry-content -->



	<footer class="entry-footer">

		<?php edit_post_link( __( 'Edit', 'zerif' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

