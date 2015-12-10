<?php

	global $wp_customize;
	
	$zerif_subscribe_show = get_theme_mod('zerif_subscribe_show');

	zerif_before_subscribe_trigger();

	if( !empty($zerif_subscribe_show) ):
	
		echo '<section class="newsletter" id="subscribe">';
	
	elseif( isset( $wp_customize ) ):

		echo '<section class="newsletter zerif_hidden_if_not_customizer" id="subscribe">';
		
	endif;

	zerif_top_subscribe_trigger();

	if( !empty($zerif_subscribe_show) || isset( $wp_customize ) ):

		echo '<div class="container">';

			$zerif_subscribe_title = get_theme_mod('zerif_subscribe_title','STAY IN TOUCH');
			$zerif_subscribe_subtitle = get_theme_mod('zerif_subscribe_subtitle','Sign Up for Email Updates on on News & Offers');
			
			if( !empty($zerif_subscribe_title) ):
			
				echo '<h3 class="white-text" data-scrollreveal="enter left after 0s over 1s">'.$zerif_subscribe_title.'</h3>';
			
			elseif ( isset( $wp_customize ) ):
			
				echo '<h3 class="white-text zerif_hidden_if_not_customizer" data-scrollreveal="enter left after 0s over 1s"></h3>';
				
			endif;
			
			if( !empty($zerif_subscribe_subtitle) ):
			
				echo '<div class="sub-heading white-text" data-scrollreveal="enter right after 0s over 1s">'.$zerif_subscribe_subtitle.'</div>';
				
			elseif ( isset( $wp_customize ) ):

				echo '<div class="sub-heading white-text zerif_hidden_if_not_customizer" data-scrollreveal="enter right after 0s over 1s"></div>';
				
			endif;
			
			if(is_active_sidebar( 'sidebar-subscribe' )):
				dynamic_sidebar( 'sidebar-subscribe' );
			endif;

		echo '</div> <!-- / END CONTAINER -->';

		zerif_bottom_subscribe_trigger();
		
	echo '</section> <!-- / END NEWSLETTER SECTION -->';
	
	endif;

	zerif_after_subscribe_trigger();