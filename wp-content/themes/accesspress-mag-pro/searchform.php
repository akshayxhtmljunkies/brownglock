<?php
/**
 * Search Form
 * 
 * @package Accesspress Mag Pro
 */
?>
<div class="search-icon">
    <i class="fa fa-search"></i>
    <div class="ak-search">
        <div class="close">&times;</div>
     <form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" method="get" role="search">
        <label>
            <span class="screen-reader-text"><?php _e( 'Search for:', 'accesspress-mag' ) ?></span>
            <input type="search" title="<?php esc_attr_e( 'Search for:', 'accesspress-mag' ); ?>" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search content...', 'accesspress-mag' );?>" class="search-field" />
        </label>
        <input type="submit" value="<?php esc_attr_e( 'Search', 'accesspress-mag' ); ?>" class="search-submit" />
     </form>
     <div class="overlay-search"> </div> 
    </div>
</div>