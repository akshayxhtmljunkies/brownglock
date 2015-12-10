<?php

/**
 * bp_course_record_activity()
 *
 * If the activity stream component is installed, this function will record activity items for your
 * component.
 *
 * You must pass the function an associated array of arguments:
 *
 *     $args = array(
 *	 	 REQUIRED PARAMS
 *		 'action' => For course: "Andy high-fived John", "Andy posted a new update".
 *       'type' => The type of action being carried out, for course 'new_friendship', 'joined_group'. This should be unique within your component.
 *
 *		 OPTIONAL PARAMS
 *		 'id' => The ID of an existing activity item that you want to update.
 * 		 'content' => The content of your activity, if it has any, for course a photo, update content or blog post excerpt.
 *       'component' => The slug of the component.
 *		 'primary_link' => The link for the title of the item when appearing in RSS feeds (defaults to the activity permalink)
 *       'item_id' => The ID of the main piece of data being recorded, for course a group_id, user_id, forum_post_id - useful for filtering and deleting later on.
 *		 'user_id' => The ID of the user that this activity is being recorded for. Pass false if it's not for a user.
 *		 'recorded_time' => (optional) The time you want to set as when the activity was carried out (defaults to now)
 *		 'hide_sitewide' => Should this activity item appear on the site wide stream?
 *		 'secondary_item_id' => (optional) If the activity is more complex you may need a second ID. For course a group forum post may need the group_id AND the forum_post_id.
 *     )
 *
 * course usage would be:
 *
 *   bp_course_record_activity( array( 'type' => 'new_highfive', 'action' => 'Andy high-fived John', 'user_id' => $bp->loggedin_user->id, 'item_id' => $bp->displayed_user->id ) );
 *
 */
function bp_course_record_activity( $args = '' ) {
	global $bp;

	if ( !function_exists( 'bp_activity_add' ) )
		return false;

	$defaults = array(
		'id' => false,
		'user_id' => $bp->loggedin_user->id,
		'action' => 'course',
		'content' => '',
		'primary_link' => '',
		'component' => 'course',
		'type' => false,
		'item_id' => false,
		'secondary_item_id' => false,
		'recorded_time' => gmdate( "Y-m-d H:i:s" ),
		'hide_sitewide' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );
	return bp_activity_add( array( 'id' => $id, 'user_id' => $user_id, 'action' => $action, 'content' => $content, 'primary_link' => $primary_link, 'component' => $component, 'type' => $type, 'item_id' => $item_id, 'secondary_item_id' => $secondary_item_id, 'recorded_time' => $recorded_time, 'hide_sitewide' => $hide_sitewide ) );
}

function bp_course_record_activity_meta($args=''){
	if ( !function_exists( 'bp_activity_update_meta' ) )
		return false;

	$defaults = array(
		'id' => false,
		'meta_key' => '',
		'meta_value' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return bp_activity_update_meta($id,$meta_key,$meta_value);
}

function bp_course_add_notification($args =''){
	global $bp;
	if ( ! bp_is_active( 'notifications' ) || !function_exists('bp_notifications_add_notification')) 
		return;
	$defaults = array(
		'user_id' => $bp->loggedin_user->id,
		'item_id' => false,
		'secondary_item_id' => false,
		'component_name' => 'course',
		'component_action'  => '',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return  bp_notifications_add_notification( array(
		'user_id'           => $user_id,
		'item_id'           => $item_id,
		'secondary_item_id' => $secondary_item_id,
		'component_name'    => $component_name,
		'component_action'  => $component_action,
		'date_notified'     => $date_notified,
		'is_new'            => $is_new,
	) );
	
}
function bp_course_add_notification_meta($args=''){
	if ( !function_exists( 'bp_activity_update_meta' ) )
		return false;

	$defaults = array(
		'id' => false,
		'meta_key' => '',
		'meta_value' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return bp_notifications_update_meta($id,$meta_key,$meta_value);
}


class bp_course_activity{

	public static $instance;
    
    public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new bp_course_activity();
        return self::$instance;
    }

	private function __construct(){

		// Course Activities

		add_action('wplms_dashboard_course_announcement',array($this,'wplms_dashboard_course_announcement'),10,4);
		add_action('publish_post',array($this,'wplms_course_news'),10,2);

		add_action('wplms_course_subscribed',array($this,'course_subscribed'),10,3);
		add_action('wplms_start_course',array($this,'start_course'),10,2);
		add_action('wplms_submit_course',array($this,'submit_course'),10,2);
		add_action('wplms_evaluate_course',array($this,'evaluate_course'),10,3);
		add_action('wplms_course_reset',array($this,'course_reset'),10,2);
		add_action('wplms_course_retake',array($this,'course_retake'),10,2);
 		add_action('wplms_badge_earned',array($this,'badge_earned'),10,4);
 		add_action('wplms_certificate_earned',array($this,'certificate_earned'),10,4);
 		add_action('wplms_course_review',array($this,'course_review'),10,3);
		add_action('wplms_course_unsubscribe',array($this,'course_unsubscribe'),10,3);
		add_action('wplms_course_code',array($this,'course_codes'),10,3);
		add_action('wplms_renew_course',array($this,'renew_course'),10,2);
		add_action('wplms_start_unit',array($this,'start_unit'),10,3);
		add_action('wplms_unit_complete',array($this,'unit_complete'),10,3);
		add_action('wplms_course_unit_comment',array($this,'unit_comment'),10,3);

		add_action('wplms_start_quiz',array($this,'start_quiz'),10,2);
		add_action('wplms_submit_quiz',array($this,'submit_quiz'),10,3);
		add_action('wplms_evaluate_quiz',array($this,'evaluate_quiz'),10,4);
		add_action('wplms_quiz_retake',array($this,'quiz_retake'),10,2);
		add_action('wplms_quiz_reset',array($this,'quiz_reset'),10,2);

		// Student Activities
		add_action('wplms_start_assignment',array($this,'start_assignment'),10,2);
		add_action('wplms_submit_assignment',array($this,'submit_assignment'),10,2);
		add_action('wplms_evaluate_assignment',array($this,'evaluate_assignment'),10,5);
		add_action('wplms_assignment_reset',array($this,'reset_assignment'),10,2);
		add_action('wplms_bulk_action',array($this,'bulk_action'),10,3);
		
		add_filter ( 'bp_blogs_record_post_post_types', array($this,'activity_publish_custom_post_types'),1,1 );
		add_filter ( 'bp_blogs_record_comment_post_types',array($this, 'activity_publish_custom_post_types'),1,1 );	
		add_filter('bp_blogs_activity_new_post_action', array($this,'record_cpt_activity_action'), 1, 3);
		add_filter('bp_blogs_activity_new_comment_action', array($this,'record_cpt_comment_activity_action'), 10, 3);	
	}

	function wplms_dashboard_course_announcement($course_id,$student_type,$email,$announcement){
		bp_course_record_activity(array(
		      'action' => sprintf(__('Announcement for Course %s ','vibe'),get_the_title($course_id)),
		      'content' => $announcement,
		      'type' => 'course_announcement',
		      'item_id' => $course_id,
		      'primary_link'=>get_permalink($course_id),
		      'secondary_item_id'=>$user_id
        ));
	}
	function wplms_course_news($news_id,$post){
		$course_id = get_post_meta($news_id,'vibe_course',true);
		if(!is_numeric($course_id))
			return;

		bp_course_record_activity(array(
		      'action' => sprintf(__('News published for Course %s ','vibe'),get_the_title($course_id)),
		      'content' => $post->post_content,
		      'type' => 'course_news',
		      'item_id' => $course_id,
		      'primary_link'=>get_permalink($course_id),
        ));
	}
	function course_subscribed($course_id,$user_id,$group_id = null){
		
		if(empty($group_id))
			$group_id = '';
		bp_course_record_activity(array(
		      'action' => sprintf(__('Student subscribed for course %s','vibe'),get_the_title($course_id)),
		      'content' => sprintf(__('Student %s subscribed for course %s','vibe'),bp_core_get_userlink( $user_id ),get_the_title($course_id)),
		      'type' => 'subscribe_course',
		      'item_id' => $course_id,
		      'primary_link'=>get_permalink($course),
		      'secondary_item_id'=>$user_id
        ));
	}
	function start_course($course_id,$user_id = NULL){

		if(!is_numeric($user_id))
			$user_id = get_current_user_id();

		$activity_id=bp_course_record_activity(array(
            'action' => __('Student started course ','vibe').get_the_title($course_id),
            'content' => __('Student ','vibe').bp_core_get_userlink( $user_id ).__(' started the course ','vibe').get_the_title($course_id),
            'type' => 'start_course',
            'item_id' => $course_id,
            'primary_link'=>get_permalink($course_id),
            'secondary_item_id'=>$user_id
        ));

        bp_course_record_activity_meta(array(
              'id' => $activity_id,
              'meta_key' => 'instructor',
              'meta_value' => get_post_field( 'post_author', $course_id )
        ));
		do_action('badgeos_wplms_start_course',$course_id);
	}
	function submit_course($course_id,$user_id){

		$message = sprintf(__('Student %s finished and submitted the course %s','vibe'),bp_core_get_userlink($user_id), get_the_title($course_id));
		bp_course_record_activity(array(
	          'action' => __('Student Submitted the course ','vibe'),
	          'content' => $message,
	          'type' => 'submit_course',
	          'item_id' => $course_id,
	          'primary_link'=>get_permalink($course_id),
	          'secondary_item_id'=>$user_id
	        ));
		do_action('badgeos_wplms_submit_course',$course_id);
	}
	function evaluate_course($course_id,$marks,$user_id){

		$activity_id=bp_course_record_activity(array(
	      'action' => __('Instructor evaluated Course for Student','vibe'),
	      'content' => sprintf(__('Student %s got %s in course %s','vibe'),bp_core_get_userlink( $user_id ),apply_filters('wplms_course_marks',$marks.'/100',$course_id),get_the_title($course_id)),
	      'primary_link' => get_permalink($course_id),
	      'type' => 'course_evaluated',
	      'item_id' => $course_id,
	      ));
	    
	    bp_course_record_activity_meta(array(
	      'id' => $activity_id,
	      'meta_key' => 'instructor',
	      'meta_value' => get_post_field( 'post_author', $course_id )
	    ));

		do_action('badgeos_wplms_evaluate_course',$course_id,$marks,$user_id); // BadgeOS integration 

		
	}
	function course_reset($course_id,$user_id){
		bp_course_record_activity(array(
	      'action' => __('Course reset for student ','vibe'),
	      'content' => __('Course ','vibe').get_the_title($course_id).__(' reset for student ','vibe').bp_core_get_userlink($user_id),
	      'type' => 'reset_course',
	      'primary_link' => get_permalink($course_id),
	      'item_id' => $course_id,
	      'secondary_item_id' => $user_id
	    ));
	}
	function course_retake($course_id,$user_id){
		      bp_course_record_activity(array(
		      'action' => __('Student retake Course ','vibe'),
		      'content' => __('Course ','vibe').get_the_title($course_id).__(' retake by student ','vibe').bp_core_get_userlink($user_id),
		      'type' => 'retake_course',
		      'primary_link' => get_permalink($course_id),
		      'item_id' => $course_id,
		      'secondary_item_id' => $user_id
		    ));
	}
	function badge_earned($course_id,$badges,$user_id,$badge_filter){
		bp_course_record_activity(array(
          'action' => __('Student got a Badge in the course ','vibe'),
          'content' => __('Student ','vibe').bp_core_get_userlink($user_id).__(' got a badge in the course ','vibe').get_the_title($course_id),
          'type' => 'student_badge',
          'item_id' => $course_id,
          'primary_link'=>get_permalink($course_id),
        )); 
	}
	function certificate_earned($course_id,$pass,$user_id,$passing_filter){
		 bp_course_record_activity(array(
            'action' => __('Student got a Certificate in course','vibe'),
            'content' => __('Student ','vibe').bp_core_get_userlink($user_id).__(' got a certificate in the course ','vibe').get_the_title($course_id),
            'type' => 'student_certificate',
            'item_id' => $course_id,
            'primary_link'=>get_permalink($course_id),
          )); 
	}
	function course_review($course_id,$rating,$title){
		$user_id = get_current_user_id();
		bp_course_record_activity(array(
	      'action' => __('Student reviewed Course %s','vibe'),
	      'content' => sprintf(__('Student %s reviewd the Course %s','vibe'),bp_core_get_userlink($user_id),get_the_title($course_id)),
	      'type' => 'review_course',
	      'primary_link' => get_permalink($course_id),
	      'item_id' => $course_id,
	      'secondary_item_id' => $user_id
	    ));
	}
	function course_unsubscribe($course_id,$user_id,$group_id = null){
		
		if(empty($group_id))
			$group_id = '';
		bp_course_record_activity(array(
	      'action' => __('Student unsubsribed from course ','vibe'),
	      'content' => __('Student ','vibe').bp_core_get_userlink($user_id).__(' removed from the course ','vibe').get_the_title($course_id),
	      'type' => 'remove_from_course',
	      'primary_link' => get_permalink($course_id),
	      'item_id' => $course_id,
	      'secondary_item_id' => $user_id
	    ));
	}
	function course_codes($code,$course_id,$user_id){
		bp_course_record_activity(array(
          'action' => __('Course code applied','vibe'),
          'content' => $code,
          'type' => 'course_code',
          'item_id' => $course_id,
          'primary_link'=>get_permalink($course_id),
          'secondary_item_id'=>$user_id
        ));
	}
	function renew_course($course_id,$user_id){
		bp_course_record_activity(array(
            'action' => __('Student renewed a course','vibe'),
            'content' => sprintf(__('Student %s renewd the course %s','vibe'),bp_core_get_userlink($user_id),get_the_title($course_id)),
            'type' => 'renew_course',
            'item_id' => $course_id,
            'primary_link'=>get_permalink($course_id),
	    )); 
	}
	function start_unit($unit_id,$course_id,$user_id){
		bp_course_record_activity(array(
          'action' => __('Student started a unit','vibe'),
          'content' => __('Student started the unit ','vibe').get_the_title($unit_id).__(' in course ','vibe').get_the_title($course_id),
          'type' => 'unit',
          'primary_link' => get_permalink($unit_id),
          'item_id' => $unit_id,
          'secondary_item_id' => $user_id
        ));
	}
	function unit_complete($unit_id,$course_progress,$course_id){
		$activity_id=bp_course_record_activity(array(
	      'action' => __('Student finished unit ','vibe'),
	      'content' => sprintf(__('Student %s finished the unit %s in course %s','vibe'),bp_core_get_user_displayname($user_id),get_the_title($unit_id),get_the_title($course_id)),
	      'type' => 'unit_complete',
	      'primary_link' => get_permalink($unit_id),
	      'item_id' => $unit_id,
	      'secondary_item_id' => $course_id
	    ));
	    bp_course_record_activity_meta(array(
	      'id' => $activity_id,
	      'meta_key' => 'instructor',
	      'meta_value' => get_post_field( 'post_author', $unit_id )
        ));
	}
	function unit_comment($unit_id,$user_id,$comment_id){
		bp_course_record_activity(array(
          'action' => __('Student posted comment on unit ','vibe'),
          'content' => sprintf(__('Student %s posted comment on unit %s','vibe'),bp_core_get_user_displayname($user_id),get_the_title($unit_id)),
          'type' => 'course_code',
          'item_id' => $course_id,
          'primary_link'=>get_permalink($course_id),
          'secondary_item_id'=>$user_id
        ));
	}
	function start_quiz($quiz_id,$user_id){
		bp_course_record_activity(array(
          'action' => __('Student started a quiz','vibe'),
          'content' => sprintf(__('Student %s started the quiz %s','vibe'),bp_core_get_userlink($user_id),get_the_title($quiz_id)),
          'type' => 'start_quiz',
          'primary_link' => get_permalink($quiz_id),
          'item_id' => $quiz_id,
          'secondary_item_id' => $user_id
        ));
	}
	function submit_quiz($quiz_id,$user_id){
		bp_course_record_activity(array(
	      'action' => __('Student submitted the Quiz','vibe'),
	      'content' => sprintf(__('Quiz %s was submitted by student','vibe'),get_the_title($quiz_id),bp_core_get_userlink( $user_id )),
	      'type' => 'submit_quiz',
	      'primary_link' => get_permalink($quiz_id),
	      'item_id' => $quiz_id,
	      'secondary_item_id' => $user_id
	      ));
		do_action('badgeos_wplms_submit_quiz',$quiz_id);
	}
	function evaluate_quiz($quiz_id,$marks,$user_id,$max){

	    $activity_id=bp_course_record_activity(array(
	      'action' => __('Instructor evaluated Quiz for student ','vibe'),
	      'type' => 'quiz_evaluated',
	      'content' => sprintf(__('Student %s got %s out of %s in Quiz %s','vibe'),bp_core_get_userlink( $user_id ),$marks,$max,get_the_title($quiz_id)),
	      'primary_link' => trailingslashit( bp_core_get_user_domain( $user_id ) . bp_get_course_slug()) . 'course-results/?action='.$quiz_id ,
	      'item_id' => $quiz_id,
	      ));

	    bp_course_record_activity_meta(array(
	      'id' => $activity_id,
	      'meta_key' => 'instructor',
	      'meta_value' => get_post_field( 'post_author', $quiz_id )
	    ));
	    do_action('badgeos_wplms_evaluate_quiz',$quiz_id,$marks,$user_id); 
	}
	function quiz_retake($quiz_id,$user_id){
		bp_course_record_activity(array(
	        'action' => __('Quiz retake by Student','vibe'),
	        'content' => sprintf(__('Student %s  initiated retake for quiz %s','vibe'),bp_core_get_userlink( $user_id ),get_the_title($quiz_id)),
	        'type' => 'retake_quiz',
	        'primary_link' => get_permalink($quiz_id),
	        'item_id' => $quiz_id,
	        'secondary_item_id' => $user_id
	      ));
	}
	function quiz_reset($quiz_id,$user_id){
		bp_course_record_activity(array(
	      'action' => __('Instructor Reseted the Quiz for User','vibe'),
	      'content' => sprintf(__('Quiz %s  was reset by the Instructor for user %s','vibe'),get_the_title($quiz_id),bp_core_get_userlink( $user_id )),
	      'type' => 'reset_quiz',
	      'primary_link' => get_permalink($quiz_id),
	      'item_id' => $quiz_id,
	      'secondary_item_id' => $user_id
	      ));
	}
	function bulk_action($action,$course_id,$members){
		switch($action){
			case 'added_students':
				$activity_id =bp_course_record_activity(array(
			      'action' => __('Instructor added students in course  ','vibe'),
			      'content' => sprintf(__('Instructor added %s students in course ','vibe'),count($members)),
			      'type' => 'bulk_action',
			      'item_id' => $course_id,
			      ));
			break;
			case 'change_course_status':
				$activity_id =bp_course_record_activity(array(
		        'action' => __('Instructor changed course status  ','vibe'),
		        'content' => sprintf(__('Instructor changed Course Status for %s students in course ','vibe'),count($members)),
		        'type' => 'bulk_action',
		        'item_id' => $course_id,
		        ));
			break;
			case 'bulk_message':
				$activity_id =bp_course_record_activity(array(
			      'action' => __('Instructor sent Bulk message to students','vibe'),
			      'content' => sprintf(__('Bulk Message sent to %s students ','vibe'),count($members)),
			      'type' => 'bulk_action',
			      'item_id' => $course_id,
			      ));
			break;
			case 'extend_course_subscription':
				$activity_id =bp_course_record_activity(array(
			      'action' => __('Instructor extended Course subscription','vibe'),
			      'content' => sprintf(__('Course subscription extended for %s students ','vibe'),count($members)),
			      'type' => 'bulk_action',
			      'item_id' => $course_id,
			      ));
			break;
			case 'add_badge':
			case 'add_certificate':
			case 'remove_badge':
			case 'remove_certificate':
				$activity_id = bp_course_record_activity(array(
			        'action' => __('Instructor assigned/removed Certificate/Badges','vibe'),
			        'content' => sprintf(__('Instructor added/removed Badges/Certificates from %s students in course','vibe'),count($members)),
			        'type' => 'bulk_action',
			        'item_id' => $course_id,
			        ));
			break;
		}
		if(is_array($members) && count($members) && is_numeric($activity_id)){
          foreach($members as $member){
          	bp_course_record_activity_meta(array(
	        'id' => $activity_id,
	        'meta_key' => $action,
	        'meta_value' => $member
	        ));
          }
        }
        die();
	}

	// Assignments
	function start_assignment($assignment_id,$user_id){
		$activity_id=bp_course_record_activity(array(
          'action' => sprintf(__('Student started assignment %s','vibe'),get_the_title($assignment_id)),
          'content' => sprintf(__('Student %s started the assignment %s','vibe'),bp_core_get_userlink($user_id),get_the_title($assignment_id)),
          'type' => 'assignment_started',
          'primary_link' => get_permalink($assignment_id),
          'item_id' => $assignment_id,
          'secondary_item_id' => $user_id
        ));
        $instructor_id = get_post_field('post_author',$assignment_id);
        bp_course_record_activity_meta(array(
          'id' => $activity_id,
          'meta_key' => 'instructor',
          'meta_value' => $instructor_id
          ));
        do_action('badgeos_wplms_start_assignment',$assignment_id);
	}
	function submit_assignment($assignment_id,$user_id){

		$instructor_id=get_post_field('post_author', $assignment_id);
		$activity_id=bp_course_record_activity(array(
          'action' => __('Student submitted assignment ','vibe'),
          'content' => sprintf(__('Student %s submitted the assignment %s','vibe'),bp_core_get_userlink($user_id),get_the_title($assignment_id)),
          'type' => 'assignment_submitted',
          'primary_link' => get_permalink($assignment_id),
          'item_id' => $assignment_id,
          'secondary_item_id' => $user_id
        ));
        bp_course_record_activity_meta(array(
          'id' => $activity_id,
          'meta_key' => 'instructor',
          'meta_value' => $instructor_id
          ));
        do_action('badgeos_wplms_submit_assignment',$assignment_id);
	}

	function evaluate_assignment($assignment_id,$marks,$user_id,$max,$message_id){
		$activity_id=bp_course_record_activity(array(
	      'action' => sprintf(__('Results available for assignment %s','vibe'),get_the_title($assignment_id)),
	      'content' => sprintf(__('Student %s got marks %s out of %s in assignment %s ','vibe'),bp_core_get_userlink( $user_id ),$marks,$max,get_the_title($assignment_id)),
	      'type' => 'evaluate_assignment',
	      'primary_link' => get_permalink($assignment_id),
	      'item_id' => $assignment_id,
	      'secondary_item_id' => $user_id
	      ));
	      bp_course_record_activity_meta(array(
	        'id' => $activity_id,
	        'meta_key' => 'remarks',
	        'meta_value' => $message_id
	        ));

	      do_action('badgeos_wplms_evaluate_assignment',$comment->comment_post_ID,$value, $comment->user_id);
	}

	function reset_assignment($assignment_id,$user_id){
		bp_course_record_activity(array(
	      'action' => __('Instructor Reseted the Assignment for User','vibe'),
	      'content' => sprintf(__('Assignment %s was reset by the Instructor for user %s','vibe'),get_the_title($assignment_id),bp_core_get_userlink( $user_id )),
	      'type' => 'reset_assignment',
	      'primary_link' => get_permalink($assignment_id),
	      'item_id' => $assignment_id,
	      'secondary_item_id' => $user_id
	      ));
	}

	function activity_publish_custom_post_types( $post_types ) {
		$post_types[] = 'course';
		$post_types[] = 'question';
		return $post_types;
	}

	function record_cpt_activity_action( $activity_action,  $post, $post_permalink ) {
		global $bp;
		if( $post->post_type != 'post' ) {
			if ( is_multisite() )
			$activity_action  = sprintf( __( '%1$s wrote a new %2$s, %3$s, on the site %4$s', 'vibe' ), bp_core_get_userlink( (int) $post->post_author ), $post->post_type, '<a href="' . $post_permalink . '">' . $post->post_title . '</a>', get_blog_option( $blog_id, 'blogname' ));
			else
			$activity_action  = sprintf( __( '%1$s wrote a new %2$s, %3$s', 'vibe' ), bp_core_get_userlink( (int) $post->post_author ),$post->post_type, '<a href="' . $post_permalink . '">' . $post->post_title . '</a>' );
		} 
		return $activity_action;
	}

	function record_cpt_comment_activity_action( $activity_action,  $recorded_comment, $comment_link ) {
		global $bp;
		$recorded_comment = get_comment( $comment_id ); 
		if( $recorded_comment->post->post_type == 'course' ) {
				if ( is_multisite() )
					$activity_action = sprintf( __( '%1$s reviewed the %2$s, %3$s, on the site %4$s', 'vibe' ), bp_core_get_userlink( $user_id ), $recorded_comment->post->post_type, '<a href="' . $post_permalink . '">' . apply_filters( 'the_title', $recorded_comment->post->post_title ) . '</a>', '<a href="' . get_blog_option( $blog_id, 'home' ) . '">' . get_blog_option( $blog_id, 'blogname' ) . '</a>' );
				else
					$activity_action = sprintf( __( '%1$s reviewed the %2$s, %3$s', 'vibe' ), bp_core_get_userlink( $user_id ),$recorded_comment->post->post_type, '<a href="' . $post_permalink . '">' . apply_filters( 'the_title', $recorded_comment->post->post_title ) . '</a>' );
		} 
		if( $recorded_comment->post->post_type == 'question' ) {
				if ( is_multisite() )
					$activity_action = sprintf( __( '%1$s answered the %2$s, %3$s, on the site %4$s', 'vibe' ), bp_core_get_userlink( $user_id ), $recorded_comment->post->post_type, '<a href="' . $post_permalink . '">' . apply_filters( 'the_title', $recorded_comment->post->post_title ) . '</a>', '<a href="' . get_blog_option( $blog_id, 'home' ) . '">' . get_blog_option( $blog_id, 'blogname' ) . '</a>' );
				else
					$activity_action = sprintf( __( '%1$s answered the %2$s, %3$s', 'vibe' ), bp_core_get_userlink( $user_id ),$recorded_comment->post->post_type, '<a href="' . $post_permalink . '">' . apply_filters( 'the_title', $recorded_comment->post->post_title ) . '</a>' );
		} 
		return $activity_action;
	}
}


bp_course_activity::init();


