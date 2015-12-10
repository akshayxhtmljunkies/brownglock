<?php 

get_header(); 


if ( get_option( 'show_on_front' ) == 'page' ) {

	echo '</header>';

	echo '<div id="content" class="site-content">';
	
		echo '<div class="container">';

			echo '<div class="content-left-wrap col-md-12">';

				echo '<div id="primary" class="content-area">';

					echo '<main id="main" class="site-main" role="main">';

						while (have_posts()) : the_post();
							get_template_part('content', 'page');
						endwhile;
						
					echo '</main>';					
				
				echo '</div>';
				
			echo '</div>';	
			
		echo '</div>';		

	echo '</div>';
	
}
else {


	if(isset($_POST['submitted']) && !defined('PIRATE_FORMS_VERSION') && !shortcode_exists( 'pirate_forms' ) ) :    


			/* recaptcha */

			$zerif_contactus_sitekey = get_theme_mod('zerif_contactus_sitekey');
	
			$zerif_contactus_secretkey = get_theme_mod('zerif_contactus_secretkey');
	
			$zerif_contactus_recaptcha_show = get_theme_mod('zerif_contactus_recaptcha_show');

			if( isset($zerif_contactus_recaptcha_show) && $zerif_contactus_recaptcha_show != 1 && !empty($zerif_contactus_sitekey) && !empty($zerif_contactus_secretkey) ) :

		        $captcha;

		        if( isset($_POST['g-recaptcha-response']) ){

		          $captcha=$_POST['g-recaptcha-response'];

		        }

		        if( !$captcha ){

		          $hasError = true;    
		          
		        }

		        $response = wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret=".$zerif_contactus_secretkey."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'] );

		        if($response['body'].success==false) {

		        	$hasError = true;

		        }

	        endif;

			/* name */


			if(trim($_POST['myname']) === ''):               


				$nameError = __('* Please enter your name.','zerif');               


				$hasError = true;        


			else:               


				$name = trim($_POST['myname']);        


			endif; 


			/* email */	


			if(trim($_POST['myemail']) === ''):               


				$emailError = __('* Please enter your email address.','zerif');               


				$hasError = true;        


			elseif (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['myemail']))) :               


				$emailError = __('* You entered an invalid email address.','zerif');               


				$hasError = true;        


			else:               


				$email = trim($_POST['myemail']);        


			endif;  


			/* subject */


			if(trim($_POST['mysubject']) === ''):               


				$subjectError = __('* Please enter a subject.','zerif');               


				$hasError = true;        


			else:               


				$subject = trim($_POST['mysubject']);        


			endif; 


			/* message */


			if(trim($_POST['mymessage']) === ''):               


				$messageError = __('* Please enter a message.','zerif');               


				$hasError = true;        


			else:                                     


				$message = stripslashes(trim($_POST['mymessage']));               


			endif; 		


			


			/* send the email */


			if(!isset($hasError)):               

				$zerif_contactus_email = get_theme_mod('zerif_contactus_email');
				
				if( empty($zerif_contactus_email) ):
				
					$emailTo = get_theme_mod('zerif_email');
				
				else:
					
					$emailTo = $zerif_contactus_email;
				
				endif;


				if(isset($emailTo) && $emailTo != ""):

					if( empty($subject) ):
						$subject = 'From '.$name;
					endif;


					$body = "Name: $name \n\nEmail: $email \n\n Subject: $subject \n\n Message: $message";               


					/* FIXED HEADERS FOR EMAIL NOT GOING TO SPAM */
					$zerif_admin_email = get_option( 'admin_email' );
					$zerif_sitename = strtolower( $_SERVER['SERVER_NAME'] );

					function zerif_is_localhost() {
						$zerif_server_name = strtolower( $_SERVER['SERVER_NAME'] );
						return in_array( $zerif_server_name, array( 'localhost', '127.0.0.1' ) );
					}
					
					if ( zerif_is_localhost() ) {
					
						$headers = 'From: '.$name.' <'.$zerif_admin_email.'>' . "\r\n" . 'Reply-To: ' . $email;
						
					} else {
					
						if ( substr( $zerif_sitename, 0, 4 ) == 'www.' ) {
							$zerif_sitename = substr( $zerif_sitename, 4 );
						}
						
						$headers = 'From: '.$name.' <wordpress@'.$zerif_sitename.'>' . "\r\n" . 'Reply-To: ' . $email;
						
					}				               


					wp_mail($emailTo, $subject, $body, $headers);               


					$emailSent = true;        


				else:


					$emailSent = false;


				endif;


			endif;	


		endif;	
		
	/* BIG TITLE SECTION */



	get_template_part( 'sections/big_title' );


?>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

<?php

	$section1 = get_theme_mod('section1','our_focus');
	$section2 = get_theme_mod('section2','bottom_ribbon');
	$section3 = get_theme_mod('section3','portofolio');
	$section4 = get_theme_mod('section4','about_us');
	$section5 = get_theme_mod('section5','our_team');
	$section6 = get_theme_mod('section6','testimonials');
	$section7 = get_theme_mod('section7','right_ribbon');
	$section8 = get_theme_mod('section8','contact_us');
	$section9 = get_theme_mod('section9','map');
	$section10 = get_theme_mod('section10','packages');
	$section11 = get_theme_mod('section11','subscribe');
	$section12 = get_theme_mod('section12','latest_news');
	
	$sections[0] = $section1;
	$sections[1] = $section2;
	$sections[2] = $section3;
	$sections[3] = $section4;
	$sections[4] = $section5;
	$sections[5] = $section6;
	$sections[6] = $section7;
	$sections[7] = $section8;
	$sections[8] = $section9;
	$sections[9] = $section10;
	$sections[10] = $section11;
	$sections[11] = $section12;
	
	for ($i = 0; $i < 12; $i++):
	
	if( !empty($sections[$i]) ):
		
		switch ( $sections[$i] ) {
		
			case "our_focus":
				
				/* OUR FOCUS SECTION */

				get_template_part( 'sections/our_focus' );
				
				break;
				
			case "portofolio":
				
				/* PORTFOLIO */

				get_template_part( 'sections/portfolio' );
				
				break;

			case "about_us":
				
				/* ABOUT US */

				get_template_part( 'sections/about_us' );
				
				break;

			case "our_team":
				
				/* OUR TEAM */

				get_template_part( 'sections/our_team' );
				
				break;

			case "latest_news":
				
				/* LATEST NEWS */

				get_template_part( 'sections/latest_news' );
				
				break;	
				
			case "testimonials":
				
				/* TESTIMONIALS */

				get_template_part( 'sections/testimonials' );
				
				break;

			case "bottom_ribbon":
				
				/* RIBBON WITH BOTTOM BUTTON */

				get_template_part( 'sections/ribbon_with_bottom_button' );
				
				break;

			case "right_ribbon":
				
				/* RIBBON WITH RIGHT SIDE BUTTON */

				get_template_part( 'sections/ribbon_with_right_button' );
				
				break;

			case "packages":
				
				/* PACKAGES */

				get_template_part( 'sections/packages' );
				
				break;

			case "map":
				
				/* GOOGLE MAP */

				$zerif_googlemap_show = get_theme_mod('zerif_googlemap_show');

				if( !empty($zerif_googlemap_show) ):

					get_template_part( 'sections/google_map' );

				endif;	
				
				break;

			case "subscribe":
				
				/* SUBSCRIBE */

				get_template_part( 'sections/subscribe' );
				
				break;

			case "contact_us":
				/* CONTACT US */
				global $wp_customize;
				
				$zerif_contactus_show = get_theme_mod('zerif_contactus_show');
				
				if( isset($zerif_contactus_show) && $zerif_contactus_show != 1 ):
					?>
					<section class="contact-us" id="contact">
						<div class="container">
							<!-- SECTION HEADER -->
							<div class="section-header">
								<?php
									$zerif_contactus_title = get_theme_mod('zerif_contactus_title','Get in touch');
									
									if ( !empty($zerif_contactus_title) ):
									
										echo '<h2 class="white-text">'.$zerif_contactus_title.'</h2>';
									
									elseif ( isset( $wp_customize ) ):
									
										echo '<h2 class="white-text zerif_hidden_if_not_customizer"></h2>';
									
									endif;
								
									$zerif_contactus_subtitle = get_theme_mod('zerif_contactus_subtitle');
									
									if( !empty($zerif_contactus_subtitle) ):
									
										echo '<h6 class="white-text">'.$zerif_contactus_subtitle.'</h6>';
										
									elseif ( isset( $wp_customize ) ):

										echo '<h6 class="white-text zerif_hidden_if_not_customizer">'.$zerif_contactus_subtitle.'</h6>';
										
									endif;
								?>
							</div>
							<!-- / END SECTION HEADER -->
							
							<?php
							if ( defined('PIRATE_FORMS_VERSION') && shortcode_exists( 'pirate_forms' ) ):
								echo '<div class="row">';
									echo do_shortcode('[pirate_forms]');
								echo '</div>';
							else:
							?>
							
								<!-- CONTACT FORM-->
								<div class="row">

									<?php 

										if(isset($emailSent) && $emailSent == true) :

											echo '<p class="error white-text error_thanks">'.__('Thanks, your email was sent successfully!','zerif').'</p>';                            

										elseif(isset($_POST['submitted'])):                                    

											echo '<p class="error white-text error_sorry">'.__('Sorry, an error occured. The email could not be sent.','zerif').'</p>';

										endif;

									

										if(isset($nameError) && $nameError != '') :																		 

											echo '<p class="error white-text">'.$nameError.'</p>';																 

										endif;	

										if(isset($emailError) && $emailError != '') :																		 

											echo '<p class="error white-text">'.$emailError.'</p>';																 

										endif;	

										if(isset($subjectError) && $subjectError != '') :																		 

											echo '<p class="error white-text">'.$subjectError.'</p>';																 

										endif;	

										if(isset($messageError) && $messageError != '') :																		 

											echo '<p class="error white-text">'.$messageError.'</p>';																 

										endif;	

									?>

									<form role="form" method="POST" action="" onSubmit="this.scrollPosition.value=(document.body.scrollTop || document.documentElement.scrollTop)" class="contact-form">

										<input type="hidden" name="scrollPosition">

										<input type="hidden" name="submitted" id="submitted" value="true" />

										<div class="col-lg-4 col-sm-4 zerif-rtl-contact-name" data-scrollreveal="enter left after 0s over 1s">

											<?php $zerif_contactus_name_placeholder = get_theme_mod('zerif_contactus_name_placeholder','Your Name'); ?>
											
											<input type="text" name="myname" placeholder="<?php if(!empty($zerif_contactus_name_placeholder)) echo $zerif_contactus_name_placeholder; ?>" class="form-control input-box" value="<?php if(isset($_POST['myname'])) echo esc_attr($_POST['myname']);?>">

										</div>

										<div class="col-lg-4 col-sm-4 zerif-rtl-contact-email" data-scrollreveal="enter left after 0s over 1s">
										
											<?php $zerif_contactus_email_placeholder = get_theme_mod('zerif_contactus_email_placeholder','Your Email'); ?>
											
											<input type="email" name="myemail" placeholder="<?php if(!empty($zerif_contactus_email_placeholder)) echo $zerif_contactus_email_placeholder; ?>" class="form-control input-box" value="<?php if(isset($_POST['myemail'])) echo is_email($_POST['myemail']) ? $_POST['myemail'] : ""; ?>">

										</div>

										<div class="col-lg-4 col-sm-4 zerif-rtl-contact-subject" data-scrollreveal="enter left after 0s over 1s">
										
											<?php $zerif_contactus_subject_placeholder = get_theme_mod('zerif_contactus_subject_placeholder','Subject'); ?>
											
											<input type="text" name="mysubject" placeholder="<?php if(!empty($zerif_contactus_subject_placeholder)) echo $zerif_contactus_subject_placeholder; ?>" class="form-control input-box" value="<?php if(isset($_POST['mysubject'])) echo esc_attr($_POST['mysubject']);?>">

										</div>
										
										<div class="col-lg-12 col-sm-12" data-scrollreveal="enter right after 0s over 1s">

											<?php $zerif_contactus_message_placeholder = get_theme_mod('zerif_contactus_message_placeholder','Your Message'); ?>
											
											<textarea name="mymessage" class="form-control textarea-box" placeholder="<?php if(!empty($zerif_contactus_message_placeholder)) echo $zerif_contactus_message_placeholder; ?>"><?php if(isset($_POST['mymessage'])) { echo stripslashes($_POST['mymessage']); } ?></textarea>

										</div>
										
										<?php
											$zerif_contactus_button_label = get_theme_mod('zerif_contactus_button_label','Send Message');
											
											if( !empty($zerif_contactus_button_label) ):
												
												echo '<button class="btn btn-primary custom-button red-btn" type="submit" data-scrollreveal="enter left after 0s over 1s">'.$zerif_contactus_button_label.'</button>';
												
											elseif ( isset( $wp_customize ) ):
											
												echo '<button class="btn btn-primary custom-button red-btn zerif_hidden_if_not_customizer" type="submit" data-scrollreveal="enter left after 0s over 1s"></button>';
												
											endif;
										?>

										<?php 

											$zerif_contactus_sitekey = get_theme_mod('zerif_contactus_sitekey');
											$zerif_contactus_secretkey = get_theme_mod('zerif_contactus_secretkey');
											$zerif_contactus_recaptcha_show = get_theme_mod('zerif_contactus_recaptcha_show');

											if( isset($zerif_contactus_recaptcha_show) && $zerif_contactus_recaptcha_show != 1 && !empty($zerif_contactus_sitekey) && !empty($zerif_contactus_secretkey) ) :

												echo '<div class="g-recaptcha zerif-g-recaptcha" data-sitekey="' . $zerif_contactus_sitekey . '"></div>';

											endif;

										?>

									</form>

								</div>

								<!-- / END CONTACT FORM-->
							<?php
							endif;
							?>

						</div> <!-- / END CONTAINER -->

					</section> <!-- / END CONTACT US SECTION-->
					<?php
				endif;
				break;		
		}
	endif;
	endfor;
	
	echo '</div><!-- .site-content -->';
}

get_footer(); ?>
