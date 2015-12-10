<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Accesspress Mag Pro
 */
?>

</div><!-- #content -->

<?php
    $accesspress_mag_theme_option = get_option( 'accesspress-mag-theme' );
    $apmag_footer_switch = of_get_option( 'footer_switch', 1 );
    $apmag_footer_layout = of_get_option( 'footer_layout' );
    $apmag_sub_footer_switch = of_get_option( 'sub_footer_switch', '1' );
    $apmag_copyright_text = of_get_option( 'mag_footer_copyright', __( '&copy; 2015 AccessPress Mag Pro', 'accesspress-mag' ) );
    $apmag_footer_text = of_get_option( 'mag_footer_text', __( 'Powered By <a href="http://accesspressthemes.com/">Accesspress Themes</a>', 'accesspress-mag' ) );
    $apmag_footer_menu = of_get_option( 'footer_menu_select' );
    $apmag_footer_menu_switch = of_get_option( 'footer_menu_switch' ); 
    $trans_top = of_get_option( 'trans_top_arrow', __( 'Top', 'accesspress-mag' ) );
?>
    <!--
    <div class="acc_option">
        <a href="javascript:void(0)" id="apmagincfont">A+</a>
        <a href="javascript:void(0)" id="apmagdecfont">A-</a>
    </div>
    -->
    <footer id="colophon" class="site-footer" role="contentinfo">    
        <?php 
            if( $apmag_footer_switch != '0' ){
            if ( is_active_sidebar( 'footer-1' ) ||  is_active_sidebar( 'footer-2' )  || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' )  ) : ?>
			<div class="top-footer footer-<?php echo esc_attr( $apmag_footer_layout ); ?>">
    			<div class="apmag-container">
                    <div class="footer-block-wrapper clearfix">
        				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                            <div class="footer-block-1 footer-block wow fadeInLeft" data-wow-delay="0.5s">        					
            						<?php dynamic_sidebar( 'footer-1' ); ?>        					
            				</div>
                        <?php endif; ?>
        
        				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                            <div class="footer-block-2 footer-block wow fadeInLeft" data-wow-delay="0.8s" style="display: <?php if( $apmag_footer_layout == 'column1' ){ echo 'none'; } else { echo 'block'; }?>;">        					
            						<?php dynamic_sidebar( 'footer-2' ); ?>        						
            				</div>
                        <?php endif; ?>
        
        				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                            <div class="footer-block-3 footer-block wow fadeInLeft" data-wow-delay="1.2s" style="display: <?php if ( $apmag_footer_layout == 'column1' || $apmag_footer_layout == 'column2' ){ echo 'none'; } else { echo 'block'; } ?>;">
        					   <?php dynamic_sidebar( 'footer-3' ); ?>	
        				    </div>
                        <?php endif; ?>
                        
                        <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                            <div class="footer-block-4 footer-block wow fadeInLeft" data-wow-delay="1.2s" style="display: <?php if ( $apmag_footer_layout != 'column4' ){ echo 'none'; } else { echo 'block'; }?>;">
        					   <?php dynamic_sidebar( 'footer-4' ); ?>
        					</div>
                        <?php endif; ?>
                    </div> <!-- footer-block-wrapper -->
                 </div><!--apmag-container-->
            </div><!--top-footer-->
        <?php endif; } ?>
        	         
        <?php if( $apmag_sub_footer_switch == 1 ){ ?>
        <div class="bottom-footer clearfix">
            <div class="apmag-container">            
        		<div class="site-info">
                    <?php 
                        if( !empty( $apmag_copyright_text ) ){
                            echo $apmag_copyright_text;
                        }
                    ?>         
        		</div><!-- .site-info -->            
                <div class="ak-info">
                    <?php 
                        if( !empty( $apmag_footer_text ) ){
                        echo $apmag_footer_text;
                        }
                    ?>
                </div>
                <div class="subfooter-menu">
                    <?php   if( $apmag_footer_menu_switch == 1 && ( has_nav_menu( 'footer_menu' ) ) ){  ?>
                        <nav id="footer-navigation" class="footer-main-navigation" role="navigation">
                                <button class="menu-toggle hide" aria-controls="menu" aria-expanded="false"><?php _e( 'Footer Menu', 'accesspress-mag' ); ?></button>
                                <?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'container_class' => 'menu', 'container_id' =>'apmag-footer-header-menu' ) ); ?>
                        </nav><!-- #site-navigation -->
                    <?php  } ?>
                </div>             
            </div>
        </div><!-- .bottom-footer -->
        <?php } ?>
	</footer><!-- #colophon -->
    <div id="back-top">
        <a href="#top"><i class="fa fa-arrow-up"></i> <span> <?php echo esc_attr( $trans_top ) ;?> </span></a>
    </div>   
</div><!-- #page -->
<?php if ( of_get_option( 'enable_preloader' ) == '1' ) : ?>
    <div id="page-overlay"></div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>