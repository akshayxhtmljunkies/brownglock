<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package zerif
 */
if ( ! function_exists( 'zerif_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function zerif_paging_nav() {
	echo '<div class="clear"></div>';
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'zerif' ); ?></h1>
		<div class="nav-links">
			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'zerif' ) ); ?></div>
			<?php endif; ?>
			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'zerif' ) ); ?></div>
			<?php endif; ?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;
if ( ! function_exists( 'zerif_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function zerif_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'zerif' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'zerif' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'zerif' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;
if ( ! function_exists( 'zerif_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function zerif_posted_on() {
	$time_string = '<time class="entry-date published" itemprop="datePublished" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'zerif' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_day_link(get_the_date('Y'), get_the_date('m'), get_the_date('d')) ),
			$time_string
		),
		sprintf( '<span class="author vcard" itemprop="name"><a href="%1$s" class="url fn n author-link" itemscope="itemscope" itemtype="http://schema.org/Person" itemprop="author">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function zerif_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'zerif_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );
		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'zerif_categories', $all_the_cool_cats );
	}
	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so zerif_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so zerif_categorized_blog should return false.
		return false;
	}
}
/**
 * Flush out the transients used in zerif_categorized_blog.
 */
function zerif_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'zerif_categories' );
}
add_action( 'edit_category', 'zerif_category_transient_flusher' );
add_action( 'save_post',     'zerif_category_transient_flusher' );
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package wp_themeisle
 */
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function wp_themeisle_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'wp_themeisle_page_menu_args' );
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wp_themeisle_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	return $classes;
}
add_filter( 'body_class', 'wp_themeisle_body_classes' );
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function wp_themeisle_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;
	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );
	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}
	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'wp_themeisle' ), max( $paged, $page ) );
	}
	return $title;
}
add_filter( 'wp_title', 'wp_themeisle_wp_title', 10, 2 );
/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function wp_themeisle_setup_author() {
	global $wp_query;
	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'wp_themeisle_setup_author' );

/********************************/
/*********** HOOKS **************/
/********************************/

function zerif_404_title_function() {
	
	?><h1 class="entry-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'zerif' ); ?></h1><?php
	
}

function zerif_404_content_function() {
	
	?><p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'zerif' ); ?></p><?php
	
}

function zerif_page_header_function() {

	?><h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1><?php

}

function zerif_portfolio_header_function() {
	?><h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1><?php
}
