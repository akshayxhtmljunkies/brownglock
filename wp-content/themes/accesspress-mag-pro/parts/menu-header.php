<?php
/**  
 * The main menu of the theme
 *  
 * @package AccessPress Mag Pro
 */

?>
<!-- header menu -->
<nav id="site-navigation" class="main-navigation" role="navigation">
	<div class="apmag-container">
        <div class="nav-wrapper">
            <div class="nav-toggle hide">
                <span> </span>
                <span> </span>
                <span> </span>
            </div>
			<?php 
                if( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'container_id' =>'apmag-header-menu' ) );
                } else {
                    wp_page_menu();
                }
            ?>
        </div>
            <?php 
                get_search_form();
                if( of_get_option( 'random_icon_option', '1' ) == '1' ) {
                    accesspress_mag_random_post();
                }
            ?> 
    </div>
</nav><!-- #site-navigation -->