<?php
/**
 * Accesspress Mag functions and definitions
 *
 * @package Accesspress Mag Pro
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'accesspress_mag_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function accesspress_mag_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Accesspress Mag, use a find and replace
	 * to change 'accesspress-mag' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'accesspress-mag', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
    
    add_image_size( 'accesspress-mag-slider-big-thumb', 765, 496, true); //Big image for homepage slider
    add_image_size( 'accesspress-mag-slider-small-thumb', 364, 164, true); //Small image for homepage slider
    add_image_size( 'accesspress-mag-block-big-thumb', 554, 305, true ); //Big thumb for homepage block
    add_image_size( 'accesspress-mag-block-small-thumb', 177, 118, true ); //Small thumb for homepage block
    add_image_size( 'accesspress-mag-singlepost-default', 1132, 509, true); //Default image size for single post
    add_image_size( 'accesspress-mag-singlepost-style1', 326, 235, true); //Style1 image size for single post 
    add_image_size( 'megamenu-thumb', 240, 172, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'accesspress-mag' ),
        'top_menu' => __( 'Top Menu', 'accesspress-mag' ),
        'top_menu_right' => __( 'Top Menu(Right)', 'accesspress-mag' ),
        'footer_menu' => __( 'Footer Menu', 'accesspress-mag' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'gallery', 'video', 'audio', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'accesspress_mag_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // accesspress_mag_setup
add_action( 'after_setup_theme', 'accesspress_mag_setup' );

/**
 * Enqueue scripts and styles.
 */
function accesspress_mag_scripts() {
    $font_args = array(
        'family' => 'Open+Sans:400|Oswald:400|Dosis:400',
    );
    wp_enqueue_style('accesspress-mag-google-fonts', add_query_arg( $font_args, "//fonts.googleapis.com/css" ) );
    $my_theme = wp_get_theme();
    $theme_version = $my_theme->get('Version'); 
    wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css');
    wp_enqueue_style( 'accesspress-mag-fontawesome-font', get_template_directory_uri(). '/css/font-awesome.min.css' );
    wp_enqueue_style( 'accesspress-mag-ticker', get_template_directory_uri() . '/css/ticker-style.css' );
    wp_enqueue_style( 'accesspress-mag-tooltipstyle', get_template_directory_uri() . '/css/tooltipster.css' );
    wp_enqueue_style( 'accesspress-mag-scroolstyle', get_template_directory_uri() . '/css/jquery.scrollbar.css' );
    wp_enqueue_style( 'accesspress-mag-owlCarouselStyle', get_template_directory_uri() . '/css/owl.carousel.css' );
    wp_enqueue_style( 'accesspress-mag-style', get_stylesheet_uri(), array(), esc_attr($theme_version) );
    wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css');

    $apmag_sticky_menu = of_get_option( 'sticky_menu', '1' );
    if( $apmag_sticky_menu == '1' ){
        wp_enqueue_script( 'accesspress-mag-sticky', get_template_directory_uri() . '/js/sticky/jquery.sticky.js', array('jquery'), '1.0.2', true );
        wp_enqueue_script( 'accesspress-mag-sticky-setting', get_template_directory_uri(). '/js/sticky/sticky-setting.js', array( 'accesspress-mag-sticky' ), esc_attr($theme_version), true );
    }
    $apmag_ligtbox_option = of_get_option( 'show_lightbox_effect', '1' );
    if( $apmag_ligtbox_option == '1' ) {
        wp_enqueue_style( 'accesspress-mag-nivolightbox-style', get_template_directory_uri(). '/js/lightbox/nivo-lightbox.css', '1.2.0' );
        wp_enqueue_script( 'accesspress-mag-nivolightbox', get_template_directory_uri() . '/js/lightbox/nivo-lightbox.min.js', array(), '1.2.0', true );
        wp_enqueue_script( 'accesspress-mag-nivolightbox-settings', get_template_directory_uri() . '/js/lightbox/lightbox-settings.js', array('accesspress-mag-nivoscript'), esc_attr( $theme_version ), true );
    }
    wp_enqueue_script( 'jquery-ui-tabs');
    wp_enqueue_script( 'accesspress-mag-bxslider-js', get_template_directory_uri(). '/js/jquery.bxslider.min.js', array('jquery'), '4.1.2', true );
    wp_enqueue_script( 'accesspress-mag-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'accesspress-mag-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );
	wp_enqueue_script( 'accesspress-mag-wow', get_template_directory_uri() . '/js/wow.min.js', array('jquery'), '1.0.1', true );
    wp_enqueue_script( 'accesspress-mag-news-ticker', get_template_directory_uri() . '/js/jquery.ticker.js', array('jquery'), '2.0.0', true );
    wp_enqueue_script( 'accesspress-mag-pacemin', get_template_directory_uri() . '/js/pace.min.js', '1.0.2', true );
    wp_enqueue_script( 'accesspress-mag-tooltip', get_template_directory_uri() . '/js/jquery.tooltipster.min.js', array(), '3.3.0', true );
    wp_enqueue_script( 'accesspress-mag-jScroll', get_template_directory_uri() . '/js/jquery.scrollbar.min.js', array(), '1.2.0', true );
    wp_enqueue_script( 'accesspress-mag-mscroll', get_template_directory_uri() . '/js/jquery.mCustomScrollbar.min.js', array(), '3.1.0', true );
    wp_enqueue_script( 'accesspress-mag-mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array(), '3.1.12', true );
    wp_enqueue_script( 'accesspress-mag-owlCarousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '1.3.3', true );
	wp_enqueue_script( 'accesspress-mag-custom-scripts', get_template_directory_uri() . '/js/custom-scripts.js', array('jquery'), '1.0.1', true );
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'accesspress_mag_scripts' );

function accesspress_dynamic_styles() {
    wp_enqueue_style( 'accesspress-mag-dynamic-style', get_template_directory_uri() . '/css/style.php' );
}
add_action( 'wp_enqueue_scripts', 'accesspress_dynamic_styles', 15 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Framework path
 */
require get_template_directory().'/inc/option-framework/options-framework.php';

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri(). '/inc/option-framework/' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/accesspress-functions.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Google Fonts.
 */
require get_template_directory() . '/inc/accesspress-google-fonts.php';

/**
 * Implement the custom metabox feature
 */
//require get_template_directory() . '/inc/custom-metabox.php';
require get_template_directory() . '/inc/metaboxes/post-review-meta.php'; // Metabox for Review System
require get_template_directory() . '/inc/metaboxes/post-format-meta.php'; // Metabox for Post Format
require get_template_directory() . '/inc/metaboxes/post-assorted-meta.php'; // Miscellaneous for Post Format
require get_template_directory() . '/inc/metaboxes/page-sidebar-meta.php'; // Post and page settins

/**
 * Load Options AP-Mag Widgets
 */
require get_template_directory() . '/inc/accesspress-widgets.php';

/**
 * Load Options Plugin Activation
 */
require get_template_directory() . '/inc/tgm/accesspress-plugin-activation.php';

/**
 * Load TGMA
 */
require get_template_directory() . '/inc/tgm/accesspress-tgm.php';

/**
 * Load Mega Menu
 */
require get_template_directory() . '/inc/accesspress-mega-menu.php';

/**
 * Load Shortcodes
 */
require get_template_directory() . '/inc/accesspress-shortcodes.php';

/**
 * Load Demo importer
 */
require get_template_directory() . '/inc/import/ap-importer.php';

/**
 *  Load Extra info about users
 */
require get_template_directory() . '/inc/users-extrainfo.php';

/**
 * Load More Theme Page
 */
require get_template_directory() . '/inc/more-themes.php';