<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package AccessPress Mag
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'accesspress-mag' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'accesspress-mag' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'accesspress-mag' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'accesspress-mag' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'accesspress-mag' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'accesspress-mag' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'accesspress-mag' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'accesspress-mag' ); ?></p>
	<?php endif; ?>

	<?php 
        $args = array(
		'fields' => apply_filters(        
		'comment_form_default_fields', array(
		'author' =>'<div class="cmm-box-left"><div class="control-group"><div class="controls">'. '<input id="author" placeholder="'.__( 'Name *', 'accesspress-mag' ).'" name="author" type="text" value="' .
		esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" />'.
		'</div></div>',

		'email'  => '<div class="control-group"><div class="controls">' . '<input id="email" placeholder="'.__( 'Email Address *', 'accesspress-mag' ).'" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
		'" size="30" aria-required="true" />'  .
		'</div></div>',

		'url'    => '<div class="control-group"><div class="controls">' .
		'<input id="url" name="url" placeholder="'.__( 'Website', 'accesspress-mag' ).'" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /> ' .
		'</div></div></div>'
		)
		),

		'comment_field' => '<div class="cmm-box-right"><div class="control-group"><div class="controls">' .
		'<textarea id="comment" name="comment" placeholder="'.__( 'Comment *', 'accesspress-mag' ).'" cols="45" rows="8" aria-required="true"></textarea>' .
		'</div></div></div> <div class="clearfix"> </div>',
		'comment_notes_after' => '',
		'comment_notes_before' => '',
		);
        comment_form($args); 
    ?>

</div><!-- #comments -->
