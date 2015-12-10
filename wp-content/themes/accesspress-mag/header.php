<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package AccessPress Mag
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
<?php do_action( 'accesspress_mag_before' ); ?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'accesspress-mag' ); ?></a>
    <?php
        $accesspress_mag_logo_alt = of_get_option( 'logo_alt' );
        $accesspress_mag_logo_title = of_get_option( 'logo_title' );
        $accesspress_mag_ticker_option = of_get_option( 'news_ticker_option', '1' );
        $accesspress_mag_random_icon = of_get_option( 'random_icon_option', '1' );
    ?>  
	
    <header id="masthead" class="site-header">    
    
        <?php
            /**
             * Top menu section
             */ 
            if( has_nav_menu( 'top_menu' ) || has_nav_menu( 'top_menu_right' ) ){
                $top_menu_class = 'has_menu'; 
            } else {
                $top_menu_class = 'no_menu';
            }
        ?>
        <div class="top-menu-wrapper <?php echo esc_attr( $top_menu_class ); ?> clearfix">
            <div class="apmag-container">
            <?php 
                $apmag_date_option = of_get_option( 'header_current_date_option', '' );
                if( empty( $apmag_date_option ) && $apmag_date_option != '1' ) {
            ?>
            <div class="current-date"><?php echo date('l, F j, Y'); ?></div>
            <?php } ?>
            <?php if ( has_nav_menu( 'top_menu' ) ) { ?>   
                <nav id="top-navigation" class="top-main-navigation">
                            <button class="menu-toggle hide" aria-controls="menu" aria-expanded="false"><?php _e( 'Top Menu', 'accesspress-mag' ); ?></button>
                            <?php wp_nav_menu( array( 'theme_location' => 'top_menu', 'container_class' => 'top_menu_left' ) ); ?>
                </nav><!-- #site-navigation -->
            <?php } ?>
            <?php if ( has_nav_menu( 'top_menu_right' ) ) { ?>        
                <nav id="top-right-navigation" class="top-right-main-navigation">
                            <button class="menu-toggle hide" aria-controls="top-right-menu" aria-expanded="false"><?php _e( 'Top Menu Right', 'accesspress-mag' ); ?></button>
                            <?php wp_nav_menu( array( 'theme_location' => 'top_menu_right', 'container_class' => 'top_menu_right' ) ); ?>
                </nav><!-- #site-navigation -->
            <?php } ?>
            </div>
        </div><!-- .top-menu-wrapper -->
        
        <?php 
             // News Ticker section
             if( $accesspress_mag_ticker_option == '1' ){ accesspress_mag_ticker(); }
        ?>
            
        <div class="logo-ad-wrapper clearfix">
            <div class="apmag-container">
        		<div class="site-branding">
                    <div class="sitelogo-wrap">  
                        <?php if ( get_header_image() ) { ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <img src="<?php header_image(); ?>" alt="<?php echo esc_attr( $accesspress_mag_logo_alt ); ?>" title="<?php echo esc_attr( $accesspress_mag_logo_title ); ?>" />
                        </a>
                        <?php } ?>
                        <meta itemprop="name" content="<?php bloginfo( 'name' )?>" />
                    </div>
                    <div class="sitetext-wrap">  
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                        <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                        </a>
                    </div>                   
                 </div><!-- .site-branding -->        		
                
                
                <?php if ( is_active_sidebar( 'accesspress-mag-header-ad' ) ) : ?>
                    <div class="header-ad">
                        <?php dynamic_sidebar( 'accesspress-mag-header-ad' ); ?> 
                    </div><!--header ad-->
                <?php endif; ?>                
                
            </div><!-- .apmag-container -->
        </div><!-- .logo-ad-wrapper -->
   
    	
        <nav id="site-navigation" class="main-navigation">
			<div class="apmag-container">
                <div class="nav-wrapper">
                    <div class="nav-toggle hide">
                        <span> </span>
                        <span> </span>
                        <span> </span>
                    </div>
        			<?php 
                        if( has_nav_menu( 'primary' ) ){
                            wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu' ) );    
                        } else {
                            wp_page_menu();
                        }
                    ?>
                </div>                
                <?php get_search_form(); ?>
                <?php if ( $accesspress_mag_random_icon == 1 ) { ?>
                  <?php accesspress_mag_random_post(); ?>
               <?php } ?>
            </div>
		</nav><!-- #site-navigation -->
        
	</header><!-- #masthead -->
    <?php do_action( 'accesspress_mag_after_header' ); ?>
	<?php do_action( 'accesspress_mag_before_main' ); ?>
	<div id="content" class="site-content">