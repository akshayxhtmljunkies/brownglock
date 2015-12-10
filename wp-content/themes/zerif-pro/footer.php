<?php

/**

 * The template for displaying the footer.

 *

 * Contains the closing of the #content div and all content after

 *

 * @package zerif

 */

?>

<?php zerif_before_footer_trigger(); ?>

<footer id="footer" role="contentinfo" itemscope="itemscope" itemtype="http://schema.org/WPFooter">

<div class="container">
	<?php zerif_top_footer_trigger(); ?>
	

	<?php
		
		$footer_sections = 0;
	
		$zerif_address = get_theme_mod('zerif_address',__('Company address','zerif'));
		$zerif_address_icon = get_theme_mod('zerif_address_icon',get_template_directory_uri().'/images/map25-redish.png');
		
		$zerif_email = get_theme_mod('zerif_email','<a href="mailto:contact@site.com">contact@site.com</a>');
		$zerif_email_icon = get_theme_mod('zerif_email_icon',get_template_directory_uri().'/images/envelope4-green.png');
		
		$zerif_phone = get_theme_mod('zerif_phone','<a href="tel:0 332 548 954">0 332 548 954</a>');
		$zerif_phone_icon = get_theme_mod('zerif_phone_icon',get_template_directory_uri().'/images/telephone65-blue.png');
		
		$zerif_socials_facebook = get_theme_mod('zerif_socials_facebook','#');

		$zerif_socials_twitter = get_theme_mod('zerif_socials_twitter','#');

		$zerif_socials_linkedin = get_theme_mod('zerif_socials_linkedin','#');

		$zerif_socials_behance = get_theme_mod('zerif_socials_behance','#');

		$zerif_socials_dribbble = get_theme_mod('zerif_socials_dribbble','#');
		
		$zerif_socials_reddit = get_theme_mod('zerif_socials_reddit');
		
		$zerif_socials_tumblr = get_theme_mod('zerif_socials_tumblr');
		
		$zerif_socials_pinterest = get_theme_mod('zerif_socials_pinterest');
		
		$zerif_socials_googleplus = get_theme_mod('zerif_socials_googleplus');
		
		$zerif_socials_youtube = get_theme_mod('zerif_socials_youtube');
			
		$zerif_copyright = get_theme_mod('zerif_copyright');
		
		
		if(!empty($zerif_address) || !empty($zerif_address_icon)):
			$footer_sections++;
		endif;
		
		if(!empty($zerif_email) || !empty($zerif_email_icon)):
			$footer_sections++;
		endif;
		
		if(!empty($zerif_phone) || !empty($zerif_phone_icon)):
			$footer_sections++;
		endif;

		if(!empty($zerif_socials_facebook) || !empty($zerif_socials_twitter) || !empty($zerif_socials_linkedin) || !empty($zerif_socials_behance) || !empty($zerif_socials_youtube) || !empty($zerif_socials_dribbble) || !empty($zerif_socials_reddit) || !empty($zerif_socials_tumblr) || !empty($zerif_socials_pinterest) || !empty($zerif_socials_googleplus) || !empty($zerif_copyright)):
			$footer_sections++;
		endif;
		
		if( $footer_sections == 1 ):
			$footer_class = 'col-md-3 footer-box one-cell';
		elseif( $footer_sections == 2 ):
			$footer_class = 'col-md-3 footer-box two-cell';
		elseif( $footer_sections == 3 ):
			$footer_class = 'col-md-3 footer-box three-cell';
		elseif( $footer_sections == 4 ):
			$footer_class = 'col-md-3 footer-box four-cell';
		else:
			$footer_class = 'col-md-3 footer-box four-cell';
		endif;
		
		

		/* COMPANY ADDRESS */

		echo '<div class="footer-box-wrap">';

		if( !empty($zerif_address) ):

			echo '<div class="'.$footer_class.' company-details">';

				echo '<div class="icon-top red-text">';

					if( !empty($zerif_address_icon) ) echo '<img src="'.$zerif_address_icon.'">';

				echo '</div>';

				echo $zerif_address;

			echo '</div>';

		endif;

		

		/* COMPANY EMAIL */

		
		

		if( !empty($zerif_email) ):

			echo '<div class="'.$footer_class.' company-details">';

				echo '<div class="icon-top green-text">';
					
					if( !empty($zerif_email_icon) ) echo '<img src="'.$zerif_email_icon.'">';

				echo '</div>';

				echo $zerif_email;

			echo '</div>';

		endif;

		

		/* COMPANY PHONE NUMBER */

		
		

		if( !empty($zerif_phone) ):

			echo '<div class="'.$footer_class.' company-details">';

				echo '<div class="icon-top blue-text">';

					if( !empty($zerif_phone_icon) ) echo '<img src="'.$zerif_phone_icon.'">';

				echo '</div>';

				echo $zerif_phone;

			echo '</div>';

		endif;


			


			if( !empty($zerif_socials_facebook) || !empty($zerif_socials_twitter) || !empty($zerif_socials_linkedin) || !empty($zerif_socials_behance) || !empty($zerif_socials_dribbble) || !empty($zerif_socials_reddit) || !empty($zerif_socials_tumblr) || !empty($zerif_socials_pinterest) || !empty($zerif_socials_googleplus) || !empty($zerif_copyright) || !empty($zerif_socials_youtube) ):
			
					echo '<div class="'.$footer_class.' copyright">';

					if( !empty($zerif_socials_facebook) || !empty($zerif_socials_twitter) || !empty($zerif_socials_linkedin) || !empty($zerif_socials_behance) || !empty($zerif_socials_dribbble) || !empty($zerif_socials_reddit) || !empty($zerif_socials_tumblr) || !empty($zerif_socials_pinterest) || !empty($zerif_socials_googleplus) || !empty($zerif_socials_youtube) ):

						echo '<ul class="social">';
	

						/* facebook */

						if( !empty($zerif_socials_facebook) ):

							echo '<li><a target="_blank" title="'. __( 'Facebook', 'zerif' ) .'" href="'.$zerif_socials_facebook.'"><i class="fa fa-facebook"></i></a></li>';

						endif;

						/* twitter */

						if( !empty($zerif_socials_twitter) ):

							echo '<li><a target="_blank" title="'. __( 'Twitter', 'zerif' ) .'" href="'.$zerif_socials_twitter.'"><i class="fa fa-twitter"></i></a></li>';

						endif;

						/* linkedin */

						if( !empty($zerif_socials_linkedin) ):

							echo '<li><a target="_blank" title="'. __( 'LinkedIn', 'zerif' ) .'" href="'.$zerif_socials_linkedin.'"><i class="fa fa-linkedin"></i></a></li>';

						endif;

						/* behance */

						if( !empty($zerif_socials_behance) ):

							echo '<li><a target="_blank" title="'. __( 'Behance', 'zerif' ) .'" href="'.$zerif_socials_behance.'"><i class="fa fa-behance"></i></a></li>';

						endif;

						/* dribbble */

						if( !empty($zerif_socials_dribbble) ):

							echo '<li><a target="_blank" title="'. __( 'Dribbble', 'zerif' ) .'" href="'.$zerif_socials_dribbble.'"><i class="fa fa-dribbble"></i></a></li>';

						endif;
						
						/* googleplus */
						
						if( !empty($zerif_socials_googleplus) ):

							echo '<li><a target="_blank" title="'. __( 'Google Plus', 'zerif' ) .'" href="'.$zerif_socials_googleplus.'"><i class="fa fa-google"></i></a></li>';

						endif;
						
						/* pinterest */
						
						if( !empty($zerif_socials_pinterest) ):

							echo '<li><a target="_blank" title="'. __( 'Pinterest', 'zerif' ) .'" href="'.$zerif_socials_pinterest.'"><i class="fa fa-pinterest"></i></a></li>';
							
							
						endif;
						
						/* tumblr */
						
						if( !empty($zerif_socials_tumblr) ):

							echo '<li><a target="_blank" title="'. __( 'Tumblr', 'zerif' ) .'" href="'.$zerif_socials_tumblr.'"><i class="fa fa-tumblr"></i></a></li>';

						endif;
						
						/* reddit */
						
						if( !empty($zerif_socials_reddit) ):

							echo '<li><a target="_blank" title="'. __( 'Reddit', 'zerif' ) .'" href="'.$zerif_socials_reddit.'"><i class="fa fa-reddit"></i></a></li>';

						endif;
						
						/* youtube */
						
						if( !empty($zerif_socials_youtube) ):

							echo '<li><a target="_blank" title="'. __( 'YouTube', 'zerif' ) .'" href="'.$zerif_socials_youtube.'"><i class="fa fa-youtube"></i></a></li>';

						endif;

						echo '</ul>';

					endif;	


					if( !empty($zerif_copyright) ):

						echo '<p id="zerif-copyright">'.$zerif_copyright.'</p>';
						
					elseif( isset( $wp_customize ) ):

						echo '<p id="zerif-copyright" class="zerif_hidden_if_not_customizer"></p>';

					endif;
					
					echo '</div>';
			
			endif;

			echo '</div>';

			?>
	<?php zerif_bottom_footer_trigger(); ?>
</div> <!-- / END CONTAINER -->

</footer> <!-- / END FOOOTER  -->

<?php zerif_after_footer_trigger(); ?>

<?php if ( wp_is_mobile() ) : ?>

	<!-- reduce heigt of the google maps on mobile -->
	<style type="text/css">
		.zerif_google_map {
			height: 300px !important;
		}
	</style>

<?php endif; 

	$zerif_slides_array = array();

	for ($i=1; $i<=3; $i++){
		$zerif_bgslider = get_theme_mod('zerif_bgslider_'.$i);
		array_push($zerif_slides_array, $zerif_bgslider);
	}

	$count_slides = 0;
	
	if( !empty($zerif_slides_array) && is_home() ):
	
			echo '</div><!-- .zerif_full_site -->';
	
		echo '</div><!-- .zerif_full_site_wrap -->';
		
	endif;

	wp_footer(); ?>

	<?php zerif_bottom_body_trigger(); ?>
	
<?php 

	if( (!empty($zerif_background_settings) && $zerif_background_settings != 'zerif-background-slider' &&  $zerif_background_settings != 'zerif-background-video' ) || empty($zerif_background_settings) ):

?>


<?php if( is_home() ): ?>
	
	</div><!-- .zerif-mobile-bg-helper-content -->
</div><!-- .zerif-mobile-bg-helper-wrap-all -->

<?php endif; ?>


<?php

	endif;

?>


</body>

</html>
