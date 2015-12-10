<?php
/**
 * Sticky Menu
 * 
 * Display sticky menu while admin enable option  
 * 
 * @package Accesspress Mag Pro
 * 
 */
 
   
$apmag_logo = of_get_option( 'logo_upload' );
$apmag_logo_setting = of_get_option( 'logo_setting' );
$apmag_logo_alt = of_get_option( 'logo_alt' );
$apmag_logo_title = of_get_option( 'logo_title' );
$branding_class = '';
switch($apmag_logo_setting){
    case 'image':
    $branding_class = 'logo_only';
    break;
    
    case 'text':
    $branding_class = 'text_only';
    break;
    
    case 'image_text':
    $branding_class = "logo_with_text";
    break;
}
?>

<div id="header-sticky" class="sticky-menu-wrapper" style="display: none;">
<div class="apmag-container">		
         <!-- header menu -->
<nav id="site-navigation" class="main-navigation" role="navigation">
<div class="site-branding <?php echo $branding_class ;?>">
                    <?php 
                        if( $apmag_logo_setting == 'image' || $apmag_logo_setting == 'image_text') :
                        if (!empty($apmag_logo)): ?>
                          <div class="sitelogo-wrap">  
                            <a itemprop="url" href="<?php echo home_url(); ?>"><img src="<?php echo $apmag_logo?>" alt="<?php echo $apmag_logo_alt ?>" title="<?php echo $apmag_logo_title ?>" /></a>
                            <meta itemprop="name" content="<?php bloginfo( 'name' )?>" />
                          </div>
                    <?php endif; endif;
                        if( $apmag_logo_setting == 'text' || $apmag_logo_setting == 'image_text' ):
                    ?> 
                         <div class="sitetext-wrap">  
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                			<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                            </a>
                        </div>
                    <?php endif;?>
                    <?php 
                        $apmag_theme_option = get_option( 'accesspress-mag-theme' );
                        if( empty( $apmag_theme_option )){
                    ?>
                        <div class="sitetext-wrap">  
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                			<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
                			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                            </a>
                        </div>
                    <?php } ?>
         </div><!-- .site-branding -->
	<div class="apmag-container">
        <div class="nav-wrapper">
            <div class="nav-toggle hide">
                <span> </span>
                <span> </span>
                <span> </span>
            </div>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'container_id' =>'apmag-header-menu-sticky' ) ); ?>
        </div>

        <div class="search-icon">
            <i class="fa fa-search"></i>
            <div class="ak-search">
                <div class="close">&times;</div>
             <form action="<?php echo site_url(); ?>" class="search-form" method="get" role="search">
                <label>
                    <span class="screen-reader-text">Search for:</span>
                    <input type="search" title="Search for:" name="s" value="" placeholder="Search content..." class="search-field">
                </label>
                <input type="submit" value="Search" class="search-submit" />
             </form>
             <div class="overlay-search"> </div> 
            </div>
        </div> 
    </div>
</nav><!-- #site-navigation --> 
</div>        
</div>              