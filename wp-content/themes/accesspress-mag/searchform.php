<?php
/**
 * The template for search form.
 *
 * @package AccessPress Mag
 */
?>
<?php 
    $search_button = of_get_option( 'trans_search_button', 'Search' );
    $search_placeholder = of_get_option( 'trans_search_placeholder', 'Search Content...' );
?>
<div class="search-icon">
    <i class="fa fa-search"></i>
    <div class="ak-search">
        <div class="close">&times;</div>
     <form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" method="get">
        <label>
            <span class="screen-reader-text"><?php _e( 'Search for:', 'accesspress-mag' ) ?></span>
            <input type="search" title="<?php esc_attr_e( 'Search for:', 'accesspress-mag' ); ?>" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" class="search-field" />
        </label>
        <input type="submit" value="<?php echo esc_attr( $search_button ); ?>" class="search-submit" />
     </form>
     <div class="overlay-search"> </div> 
    </div>
</div> 
