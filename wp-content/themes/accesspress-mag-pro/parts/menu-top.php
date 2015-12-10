<?php 
/**
 * Define top heder menu
 * 
 * @package Accesspress Mag Pro
 */
 
if ( has_nav_menu( 'top_menu' ) ) {
    
?>
<div class="top-menu-wrapper clearfix">
    <div class="apmag-container">
            <?php 
                $apmag_date_option = of_get_option( 'header_current_date_option', '' );
                if( empty( $apmag_date_option ) && $apmag_date_option != '1' ) {
            ?>
            <div class="current_date"><?php echo date('l, F j, Y'); ?></div>
            <?php } ?>
            <nav id="top-navigation" class="top-main-navigation" role="navigation">
                <button class="menu-toggle hide" aria-controls="menu" aria-expanded="false"><?php _e( 'Top Menu', 'accesspress-mag' ); ?></button>
                <?php wp_nav_menu( array( 'theme_location' => 'top_menu', 'container_class' => 'menu', 'container_id' =>'apmag-top-header-menu' ) ); ?>
            </nav><!-- #site-navigation -->
            
        <?php if ( has_nav_menu( 'top_menu_right' ) ) { ?>
            <nav id="top-right-navigation" class="top-right-main-navigation" role="navigation">
                <button class="menu-toggle hide" aria-controls="menu" aria-expanded="false"><?php _e( 'Top Menu Right', 'accesspress-mag' ); ?></button>
                <?php wp_nav_menu( array( 'theme_location' => 'top_menu_right', 'container_class' => 'menu', 'container_id' =>'apmag-top-right-header-menu' ) ); ?>
            </nav><!-- #site-navigation -->
        <?php } ?> 
    </div>
</div>
<?php } else { ?>
<div class="top-header-menu"> 
    <div class="apmag-container">
        <?php 
            $apmag_date_option = of_get_option( 'header_current_date_option', '' );
            if( empty( $apmag_date_option ) && $apmag_date_option != '1' ) {
        ?>
        <div class="current_date"><?php echo date('l, F j, Y'); ?></div>
        <?php } ?>
        <ul class="">
            <li class="menu-item-first">
                <?php printf(__( '<a href="%s" target="_blank">Click here - to select or create a menu</a> ', 'accesspress-mag' ), esc_url(admin_url('/nav-menus.php?action=locations'))); ?>
            </li>
        </ul>
    </div>
    </div>    
<?php } ?>