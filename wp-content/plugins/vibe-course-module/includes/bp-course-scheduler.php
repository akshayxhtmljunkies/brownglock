<?php

/*
*	BP COURSE SCHEDULER
*   OBJECTIVE : SCHEDULER EMAILS OR CUSTOM TASKS
*   USE CASES :
*   SEND EMAIL TO STUDENT BEFORE HER COURSE EXPIRES
*	SEND EMAIL TO STUDENT WHEN HER UNIT IS AVAILABLE in DRIP FEED COURSE
*	SEND EMAIL FOR EVENT ** to be used with Advanced Events plugin
*	SEND A WEEKLY COURSE PROGRESS REPORT TO INSTRUCTOR, PROCESS COURSE REPORTS AND EMAIL ** ADVANCED SCHEDULER
*/
class bp_course_scheduler{

	var $schedule;

	function __construct(){
		$this->get();
		add_action('wplms_bulk_action',array($this,'reset_course_expire_schedule'),10,3);
		add_action('wplms_start_unit',array($this,'schedule_drip_mail'),10,5);
		add_action('wplms_course_subscribed',array($this,'schedule_expire_mail'),10,3);
		add_action('wplms_send_drip_mail',array($this,'wplms_send_drip_mail'),10,3);
      	add_action('wplms_send_course_expiry_mail',array($this,'wplms_send_course_expiry_mail'),10,3);
	}
   
	function get(){
		$settings = get_option('lms_settings');
		$this->schedule = $settings['schedule'];
	}

	function reset_course_expire_schedule($action,$course_id,$members){ 
		if($action != 'extend_course_subscription')
			return;

		if(isset($this->schedule) && is_array($this->schedule)){
			if($this->schedule['expire'] === 'yes'){
				foreach($members as $user_id){
					$group_id = get_post_meta($course_id,'vibe_group',true);
					if(!is_numeric($group_id))
						$group_id ='';

					$args = array($course_id, $user_id,$group_id);
					wp_clear_scheduled_hook('wplms_send_course_expiry_mail',array($course_id, $user_id,$group_id));

					$timestamp = get_user_meta($user_id,$course_id,true);
					$expire_schedule = $timestamp - $expire_schedule*3600;
					if($expire_schedule > time()){
					
					if(!wp_next_scheduled('wplms_send_course_expiry_mail',$args))
						wp_schedule_single_event($expire_schedule,'wplms_send_course_expiry_mail',$args);
					}
				}
			}
		}

	}
	function schedule_expire_mail($course_id, $user_id,$group_id = null){
		
		if(empty($group_id))
			$group_id = '';
		if(isset($this->schedule) && is_array($this->schedule)){
			if($this->schedule['expire'] === 'yes'){
				$expire_schedule = $this->schedule['expire_schedule'];
				$timestamp = get_user_meta($user_id,$course_id,true);
				$expire_schedule = $timestamp - $expire_schedule*3600;
				if($expire_schedule > time()){
					$args = array($course_id, $user_id,$group_id);
					wp_clear_scheduled_hook('wplms_send_course_expiry_mail',array($course_id, $user_id,$group_id));
					if(!wp_next_scheduled('wplms_send_course_expiry_mail',$args))
						wp_schedule_single_event($expire_schedule,'wplms_send_course_expiry_mail',$args);
				}
			}
		}
	}

	function schedule_drip_mail($prev_unit_id,$course_id,$user_id,$next_unit_id,$timestamp){
		if(isset($this->schedule) && is_array($this->schedule)){
			if($this->schedule['drip'] === 'yes'){
				$drip_schedule = $this->schedule['drip_schedule'];
				$drip_schedule = $timestamp - $drip_schedule*3600;
				$args = array($next_unit_id,$course_id,$user_id);
				wp_clear_scheduled_hook('wplms_send_drip_mail',array($unit_id,$course_id,$user_id));
				if(!wp_next_scheduled('wplms_send_drip_mail',$args))
					wp_schedule_single_event($drip_schedule,'wplms_send_drip_mail',$args);
			}
		}
	}

	function wplms_send_drip_mail($unit_id,$course_id,$user_id){
		    if(isset($this->schedule) && is_array($this->schedule)){
		      if($this->schedule['drip'] === 'yes'){
		        $subject = $this->schedule['drip_subject'];
		        $message = $this->schedule['drip_message'];

		        $subject = str_replace('{{unit}}',get_the_title($unit_id),$subject);
		        $message = str_replace('{{unit}}',get_the_title($unit_id),$message);
		        $subject = str_replace('{{course}}',get_the_title($course_id),$subject);
		        $message = str_replace('{{course}}',get_the_title($course_id),$message);
		        $subject = str_replace('{{user}}',bp_core_get_user_displayname($user_id),$subject);
		        $message = str_replace('{{user}}',bp_core_get_user_displayname($user_id),$message);

		        $user = get_user_by('id',$user_id);        
		        bp_course_wp_mail($user->user_email,$subject,$message);

		        wp_clear_scheduled_hook('wplms_send_drip_mail',array($unit_id,$course_id,$user_id));
		    }
	    }
	}

	function wplms_send_course_expiry_mail($course_id, $user_id,$group_id = null){
		
		if(empty($group_id))
			$group_id = '';
		
	  if(isset($this->schedule) && is_array($this->schedule)){
	      if($this->schedule['expire'] === 'yes'){
	        $subject = $this->schedule['expire_subject'];
	        $message = $this->schedule['expire_message'];

	        $subject = str_replace('{{course}}',get_the_title($course_id),$subject);
	        $message = str_replace('{{course}}',get_the_title($course_id),$message);
	        $subject = str_replace('{{user}}',bp_core_get_user_displayname($user_id),$subject);
	        $message = str_replace('{{user}}',bp_core_get_user_displayname($user_id),$message);
	         $user = get_user_by('id',$user_id);        
	        bp_course_wp_mail($user->user_email,$subject,$message);
	        wp_clear_scheduled_hook('wplms_send_course_expiry_mail',array($unit_id,$course_id,$user_id));
	      }
	    }
	}

}

new bp_course_scheduler;