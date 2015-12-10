<?php
/**
 * Contains all the functions related to sidebar and widget.
 *
 * @package AccessPress Mag
 * 
 */
 
 /**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function accesspress_mag_widgets_init() {
	
    register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-sidebar-right',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-sidebar-left',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Home top sidebar', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-home-top-sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
   	    'name'          => __( 'Home middle sidebar', 'accesspress-mag' ),
    	'id'            => 'accesspress-mag-home-middle-sidebar',
    	'description'   => '',
    	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</aside>',
    	'before_title'  => '<h1 class="widget-title"><span>',
    	'after_title'   => '</span></h1>',
    ) );
    
    register_sidebar( array(
   	    'name'          => __( 'Home bottom sidebar', 'accesspress-mag' ),
    	'id'            => 'accesspress-mag-home-bottom-sidebar',
    	'description'   => '',
    	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</aside>',
    	'before_title'  => '<h1 class="widget-title"><span>',
    	'after_title'   => '</span></h1>',
    ) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 1', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-footer-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 2', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-footer-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 3', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-footer-3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
    
    register_sidebar( array(
		'name'          => __( 'Footer 4', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-footer-4',
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
    
    register_sidebar( array(
		'name'          => __( 'Header Ad ', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-header-ad',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Article Ad', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-article-ad',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-ads">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage Inline Ad', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-homepage-inline-ad',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-ads">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage Sidebar Top Ad', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-homepage-sidebar-top-ad',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage Sidebar Middle Ad', 'accesspress-mag' ),
		'id'            => 'accesspress-mag-homepage-sidebar-middle-ad',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );
}
add_action( 'widgets_init', 'accesspress_mag_widgets_init' );