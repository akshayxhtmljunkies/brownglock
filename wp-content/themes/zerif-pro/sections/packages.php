<?php

	global $wp_customize;

	$zerif_packages_show = get_theme_mod('zerif_packages_show');

	zerif_before_packages_trigger();

	if( !empty($zerif_packages_show) ):
	
		echo '<section class="packages" id="pricingtable">';
	
	elseif( isset( $wp_customize ) ):
	
		echo '<section class="packages zerif_hidden_if_not_customizer" id="pricingtable">';
	
	endif;

	zerif_top_packages_trigger();
	
	if( !empty($zerif_packages_show) || isset( $wp_customize ) ):
	
		echo '<div class="container">';
	
		/* SECTION HEADER */
	
		echo '<div class="section-header">';
		
			/* title */
			
			$zerif_packages_title = get_theme_mod('zerif_packages_title','PACKAGES');
			
			if( !empty($zerif_packages_title) ):
			
				echo '<h2 class="white-text">'.$zerif_packages_title.'</h2>';
				
			elseif ( isset( $wp_customize ) ):

				echo '<h2 class="white-text zerif_hidden_if_not_customizer"></h2>';
				
			endif;	
			
			/* subtitle */
			
			$zerif_packages_subtitle = get_theme_mod('zerif_packages_subtitle','We have 4 friendly packages for you. Check all the packages and choose the right one for you.');

			if( !empty($zerif_packages_subtitle) ):

				echo '<h6 class="white-text">'.$zerif_packages_subtitle.'</h6>';
				
			elseif ( isset( $wp_customize ) ):
			
				echo '<h6 class="white-text zerif_hidden_if_not_customizer"></h6>';

			endif;	

		echo '</div>';
		
		/* END SECTION HEADER */
	
		/* PACKAGES */
		
		echo '<div class="row">';

			if ( is_active_sidebar( 'sidebar-packages' ) ) :	

				dynamic_sidebar( 'sidebar-packages' ); 
				
			endif;

		echo '</div> <!-- / END PACKAGES -->';
	
	echo '</div> <!--END CONTAINER  -->';

	zerif_bottom_packages_trigger();
	
echo '</section> <!-- END PACKAGES SECTION -->';

endif;

zerif_after_packages_trigger();