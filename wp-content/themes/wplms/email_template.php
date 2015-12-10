<?php
/**
 * Template Name: Email Page
 */


if(empty($_GET['vars'])){
	$message = __('PAGE CANNOT BE ACCESSED : MISSING EMAIL VARS ','vibe');
    wp_die($message,$message,array('back_link'=>true));
}else{
	get_header();
	$vars = json_decode(stripslashes(urldecode($_GET['vars'])));

	$template = get_option('wplms_email_template');
	if(isset($vars->to) && $vars->subject){
		if(is_object($vars->args)){
			$args = get_object_vars($vars->args);
		}else{
			$args ='';
		}
		$template = bp_course_process_mail($vars->to,$vars->subject,$vars->message,$args);
		echo $template;
	}else{
		wp_redirect(home_url(),'302');	
	}	
}

get_footer();
?>
