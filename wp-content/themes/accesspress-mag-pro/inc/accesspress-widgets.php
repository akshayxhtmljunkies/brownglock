<?php
/**
 * AccessPress Mag Custom Widgets
 *
 * @package AccessPress Mag Pro
 */
 
 /**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function accesspress_mag_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'accesspress-mag' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'accesspress-mag' ),
		'id'            => 'sidebar-right',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'accesspress-mag' ),
		'id'            => 'sidebar-left',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Home top sidebar', 'accesspress-mag' ),
		'id'            => 'apmag-home-top-sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
   	    'name'          => __( 'Home middle sidebar', 'accesspress-mag' ),
    	'id'            => 'apmag-home-middle-sidebar',
    	'description'   => '',
    	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</aside>',
    	'before_title'  => '<h1 class="widget-title"><span>',
    	'after_title'   => '</span></h1>',
    ) );
    
    register_sidebar( array(
   	    'name'          => __( 'Home bottom sidebar', 'accesspress-mag' ),
    	'id'            => 'apmag-home-bottom-sidebar',
    	'description'   => '',
    	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</aside>',
    	'before_title'  => '<h1 class="widget-title"><span>',
    	'after_title'   => '</span></h1>',
    ) );
    
    register_sidebar( array(
   	    'name'          => __( 'Home Page: Middle Content Section', 'accesspress-mag' ),
    	'id'            => 'apmag-home-middle-content-section',
    	'description'   => '',
    	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</aside>',
    	'before_title'  => '<h1 class="widget-title"><span>',
    	'after_title'   => '</span></h1>',
    ) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 1', 'accesspress-mag' ),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 2', 'accesspress-mag' ),
		'id'            => 'footer-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 3', 'accesspress-mag' ),
		'id'            => 'footer-3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 4', 'accesspress-mag' ),
		'id'            => 'footer-4',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Shop', 'accesspress-mag' ),
		'id'            => 'shop',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
}
add_action( 'widgets_init', 'accesspress_mag_widgets_init' );
 
/**
 * Include helper functions that display widget fields in the dashboard
 *
 * @since AccessPress Mag Pro Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-fields.php';

/**
 * Random posts
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-random-posts.php';

/**
 * Latest posts
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-latest-posts.php';

/**
 * Latest reivew posts
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-latest-reviews.php';

/**
 * Article Contributors
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-contributors.php';

/**
 * Flickr Stream Widget
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-flickr-stream.php';

/**
 * Tabbed Widget
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-tabbed.php';

/**
 * Featured Category Widget
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-featured-category.php';

/**
 * Youtube Videos Lists
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-youtube.php';

/**
 * News in Pictures
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-news-pictures.php';

/**
 * Sponsers
 *
 * @since AccessPress Mag Widget Pack 1.0
 */
require get_template_directory() . '/inc/widgets/widget-sponsers.php';