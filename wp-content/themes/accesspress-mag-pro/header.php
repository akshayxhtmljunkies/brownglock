<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Accesspress Mag Pro
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'accesspress-mag' ); ?></a>
    <header id="masthead" class="site-header" role="banner">
            <?php
                $apmag_top_menu_switch = of_get_option( 'top_menu_switch' ); 
                if($apmag_top_menu_switch=='1'){
                    get_template_part( 'parts/menu-top' );
                }
                
                $apmag_ticker_option = of_get_option( 'news_ticker_option' );
                if( $apmag_ticker_option == '1' ){
                    get_template_part( 'parts/ticker' );
                } 
                $apmag_header_style = of_get_option( 'header_style_option' );
                switch ($apmag_header_style) {
                    default:
                        // this is the default header configuration
                        // (logo + ad) + menu
                        get_template_part( 'parts/header-style-1' );
                        get_template_part( 'parts/menu-header' );
                        break;
                    
                    case '2':
                        // full width logo + menu
                        get_template_part( 'parts/header-style-2-logo' );
                        get_template_part( 'parts/menu-header' );
                        get_template_part( 'parts/header-style-2-ad' );
                        break;
                    
                    case '3':
                        // menu + (logo + ad)                    
                        get_template_part('parts/menu-header' );
                        get_template_part('parts/header-style-1' );
                        break;
                    
                    case '4':
                        // menu + full width logo
                        get_template_part( 'parts/menu-header' );
                        get_template_part( 'parts/header-style-2-logo' );
                        get_template_part( 'parts/header-style-2-ad' );
                        break;
                }
        ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">