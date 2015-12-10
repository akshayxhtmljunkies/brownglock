<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package AccessPress Mag
 */
?>

	</div><!-- #content -->
    
	<?php
        $accesspress_mag_show_footer_switch = of_get_option( 'footer_switch', '1' );
        $accesspress_mag_footer_layout = of_get_option( 'footer_layout' );
        $accesspress_mag_sub_footer_switch = of_get_option( 'sub_footer_switch', '1' );
        $accesspress_mag_copyright_text = of_get_option( 'mag_footer_copyright' );
        $accesspress_mag_copyright_symbol = of_get_option( 'copyright_symbol', '1' );
        $trans_top = of_get_option( 'top_arrow', 'Top' );
        if( empty( $trans_top ) ) { $trans_top = __( 'Top', 'accesspress-mag' ); }
    ?>
    
    <?php do_action( 'accesspress_mag_before_footer' ); ?>
    
    <footer id="colophon" class="site-footer" role="contentinfo">
    
        <?php 
            if( $accesspress_mag_show_footer_switch != '0' ){
            if ( is_active_sidebar( 'accesspress-mag-footer-1' ) ||  is_active_sidebar( 'accesspress-mag-footer-2' )  || is_active_sidebar( 'accesspress-mag-footer-3' ) || is_active_sidebar( 'accesspress-mag-footer-4' )  ) : ?>
			<div class="top-footer footer-<?php echo esc_attr($accesspress_mag_footer_layout); ?>">
    			<div class="apmag-container">
                    <div class="footer-block-wrapper clearfix">
        				<div class="footer-block-1 footer-block wow fadeInLeft" data-wow-delay="0.5s">
        					<?php if ( is_active_sidebar( 'accesspress-mag-footer-1' ) ) : ?>
        						<?php dynamic_sidebar( 'accesspress-mag-footer-1' ); ?>
        					<?php endif; ?>
        				</div>
        
        				<div class="footer-block-2 footer-block wow fadeInLeft" data-wow-delay="0.8s" style="display: <?php if( $accesspress_mag_footer_layout == 'column1' ){ echo 'none'; } else { echo 'block'; }?>;">
        					<?php if ( is_active_sidebar( 'accesspress-mag-footer-2' ) ) : ?>
        						<?php dynamic_sidebar( 'accesspress-mag-footer-2' ); ?>
        					<?php endif; ?>	
        				</div>
        
        				<div class="footer-block-3 footer-block wow fadeInLeft" data-wow-delay="1.2s" style="display: <?php if ( $accesspress_mag_footer_layout == 'column1' || $accesspress_mag_footer_layout == 'column2' ){ echo 'none'; } else { echo 'block'; } ?>;">
        					<?php if ( is_active_sidebar( 'accesspress-mag-footer-3' ) ) : ?>
        						<?php dynamic_sidebar( 'accesspress-mag-footer-3' ); ?>
        					<?php endif; ?>	
        				</div>
                        <div class="footer-block-4 footer-block wow fadeInLeft" data-wow-delay="1.2s" style="display: <?php if ( $accesspress_mag_footer_layout != 'column4' ){ echo 'none'; } else { echo 'block'; }?>;">
        					<?php if ( is_active_sidebar( 'accesspress-mag-footer-4' ) ) : ?>
        						<?php dynamic_sidebar( 'accesspress-mag-footer-4' ); ?>
        					<?php endif; ?>	
        				</div>
                    </div> <!-- footer-block-wrapper -->
                 </div><!--apmag-container-->
            </div><!--top-footer-->
        <?php endif; } ?>
        	         
        <div class="bottom-footer clearfix">
            <div class="apmag-container">
            <?php if( $accesspress_mag_sub_footer_switch == 1 ){ ?>
        		<div class="site-info">
                    <?php if( $accesspress_mag_copyright_symbol == 1 ){ ?>
                        <span class="copyright-symbol"> &copy; <?php echo date( 'Y' ) ?></span>
                    <?php } ?> 
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php
                        if( !empty( $accesspress_mag_copyright_text ) ){ 
                            echo '<span class="copyright-text">'.esc_html( $accesspress_mag_copyright_text ).'</span>'; 
                        }
                    ?> 
                    </a>           
        		</div><!-- .site-info -->
            <?php } ?>
                <div class="ak-info">
                    <?php _e( 'Powered by ', 'accesspress-mag' );  ?><a href="<?php echo esc_url( __( 'http://wordpress.org/', 'accesspress-mag' ) ); ?>"><?php _e( 'WordPress', 'accesspress-mag' ); ?> </a>
                    <?php _e( '| Theme: ', 'accesspress-mag' );?>
                    <a title="AccessPress Themes" href="<?php echo esc_url( 'http://accesspressthemes.com', 'accesspress-mag' ); ?>"><?php _e( 'AccessPress Mag', 'accesspress-mag' ); ?></a>
                </div>
             <?php if ( ( has_nav_menu( 'footer_menu' ) ) && ( $accesspress_mag_sub_footer_switch == 1 ) ) { ?>      
                <div class="subfooter-menu">
                    <nav id="footer-navigation" class="footer-main-navigation" role="navigation">
                        <button class="menu-toggle hide" aria-controls="menu" aria-expanded="false"><?php _e( 'Footer Menu', 'accesspress-mag' ); ?></button>
                        <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'container_class' => 'footer_menu' ) ); ?>
                    </nav><!-- #site-navigation -->
                </div>
             <?php } ?>
            </div>
        </div>
	</footer><!-- #colophon -->
    <div id="back-top">
        <a href="#top"><i class="fa fa-arrow-up"></i> <span> <?php echo esc_attr( $trans_top ) ;?> </span></a>
    </div>   
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>