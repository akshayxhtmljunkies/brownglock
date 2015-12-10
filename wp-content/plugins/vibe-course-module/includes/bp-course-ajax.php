<?php

/***
 * You can hook in ajax functions in WordPress/BuddyPress by using the 'wp_ajax' action.
 * 
 * When you post your ajax call from javascript using jQuery, you can define the action
 * which will determin which function to run in your PHP component code.
 *
 * Here's an course:
 *
 * In Javascript we can post an action with some parameters via jQuery:
 * 
 * 			jQuery.post( ajaxurl, {
 *				action: 'my_course_action',
 *				'cookie': encodeURIComponent(document.cookie),
 *				'parameter_1': 'some_value'
 *			}, function(response) { ... } );
 *
 * Notice the action 'my_course_action', this is the part that will hook into the wp_ajax action.
 * 
 * You will need to add an add_action( 'wp_ajax_my_course_action', 'the_function_to_run' ); so that
 * your function will run when this action is fired.
 * 
 * You'll be able to access any of the parameters passed using the $_POST variable.
 *
 * Below is an course of the addremove_friend AJAX action in the friends component.
 */



add_action('wp_ajax_complete_unit', 'wplms_complete_unit');

function wplms_complete_unit(){
  $unit_id = $_POST['id'];
  $course_id = $_POST['course_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') ){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }

  // Check if user has taken the course
  $user_id = get_current_user_id();
  $coursetaken=get_user_meta($user_id,$course_id,true);
  
  if(isset($coursetaken) && $coursetaken){
    $nextunit_access = vibe_get_option('nextunit_access');

    if(isset($nextunit_access) && $nextunit_access){ // Enable Next unit access
      if(update_user_meta($user_id,$unit_id,time())){
         $curriculum=bp_course_get_curriculum_units($course_id);
         $key = array_search($unit_id,$curriculum);
         if($key <=(count($curriculum)-1) ){  // Check if not the last unit
          $key++;
          echo $curriculum[$key];
         }
      }
    }else{
      $curriculum=bp_course_get_curriculum_units($course_id);
      $key = array_search($unit_id,$curriculum);
      $key++;
      update_user_meta($user_id,$unit_id,time());
    }
    
    $c=(count($curriculum)?count($curriculum):1);
    $course_progress = $key/$c;
    do_action('wplms_unit_complete',$unit_id,$course_progress,$course_id );
  }
  die();
}


add_action('wp_ajax_reset_question_answer', 'reset_question_answer');
function reset_question_answer(){
  global $wpdb;
  $ques_id = $_POST['ques_id'];
  if(isset($ques_id) && $_POST['security'] && wp_verify_nonce($_POST['security'],'security'.$ques_id)){
    $user_id = get_current_user_id();
    $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$ques_id,$user_id));
    echo '<p>'.__('Answer Reset','vibe').'</p>';
  }else
    echo '<p>'.__('Unable to Reset','vibe').'</p>';

  die();
}


add_action( 'wp_ajax_calculate_stats_course', 'calculate_stats_course' ); // RESETS QUIZ FOR USER
function calculate_stats_course(){
	$course_id=$_POST['id'];
	$flag=0;
	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($course_id) || !$course_id){
    	echo '<p>'.__('Incorrect Course selected.','vibe').'</p>';
        die();
    }
    $badge=$pass=$total_qmarks=$gross_qmarks=0;
    $users=array();
	global $wpdb;

	$badge_val=get_post_meta($course_id,'vibe_course_badge_percentage',true);
	$pass_val=get_post_meta($course_id,'vibe_course_passing_percentage',true);

	$members_course_grade = $wpdb->get_results( $wpdb->prepare("SELECT meta_value,meta_key FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key IN (SELECT DISTINCT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s)",$course_id,'course_status'.$course_id), ARRAY_A);
  
	if(count($members_course_grade)){
    $cmarks=$i=0;
		foreach($members_course_grade as $meta){
			if(is_numeric($meta['meta_key']) && $meta['meta_value'] > 2){
       
						if($meta['meta_value'] > $badge_val)
							$badge++;

						if($meta['meta_value'] > $pass_val)
							$pass++;

						$users[]=$meta['meta_key'];

            if(isset($meta['meta_value']) && is_numeric($meta['meta_value']) && $meta['meta_value'] > 2 && $meta['meta_value']<101){
              $cmarks += $meta['meta_value'];
              $i++;
            }
					}
			}  // META KEY is NUMERIC ONLY FOR USERIDS
	}

	if($pass)
		update_post_meta($course_id,'pass',$pass);


	if($badge)
		update_post_meta($course_id,'badge',$badge);

	if($i==0)$i=1;
    $avg = round(($cmarks/$i));

update_post_meta($course_id,'average',$avg);

if($flag !=1){
	$curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
		foreach($curriculum as $c){
			if(is_numeric($c)){

				if(get_post_type($c) == 'quiz'){
          $i=$qmarks=0;

					foreach($users as $user){
						$k=get_post_meta($c,$user,true);
            if(is_numeric($k)){
  						$qmarks +=$k;
              $i++;
  						$gross_qmarks +=$k;
            }
					}
          if($i==0)$i=1;
					
          $qavg=round(($qmarks/$i),1);

					if($qavg)
						update_post_meta($c,'average',$qavg);
					else{
						$flag=1;
						break;
					}
				}
			}
	}
}

if(function_exists('assignment_comment_handle')){ // Assignment is active
  $assignments_query = $wpdb->get_results( $wpdb->prepare("select post_id from {$wpdb->postmeta} where meta_value = %d AND meta_key = 'vibe_assignment_course'",$course_id), ARRAY_A);
  foreach($assignments_query as $assignment_query){
    $assignments[]=$assignment_query['post_id'];
  }

  if(count($assignments)){ // If any connected assignments
    $assignments_string = implode(',',$assignments);
    $assignments_marks_query = $wpdb->get_results("select post_id,meta_value from {$wpdb->postmeta} where post_id IN ($assignments_string) AND meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$'", ARRAY_A);
    
    foreach($assignments_marks_query as $marks){
      $user_assignments[$marks['post_id']]['total'] += $marks['meta_value'];
      $user_assignments[$marks['post_id']]['number']++;
    }

    foreach($user_assignments as $key=>$user_assignment){
      if(isset($user_assignment['number']) && $user_assignment['number']){
        $avg = $user_assignment['total']/$user_assignment['number'];  
        update_post_meta($key,'average',$avg);
      }
    }
  }
}
	if(!$flag){
		echo '<p>'.__('Statistics successfully calculated. Reloading...','vibe').'</p>';
	}else{
		echo '<p>'.__('Unable to calculate Average.','vibe').'</p>';
	}

	die();
}

add_action( 'wp_ajax_course_stats_user', 'course_stats_user' ); // RESETS QUIZ FOR USER
function course_stats_user(){
	$course_id = $_POST['id'];
    $user_id = $_POST['user'];

    echo '<a class="show_side link right" data-side=".course_stats_user">'.__('SHOW STATS','vibe').'</a><div class="course_stats_user"><a class="hide_parent link right">'.__('HIDE','vibe').'</a>';

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<div id="message" class="info notice"><p>'.__('Security check failed !','vibe').'</p></div>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
    	echo '<div id="message" class="info notice"><p>'.__('Incorrect User selected.','vibe').'</p></div>';
        die();
    }


   global $wpdb,$bp;
    $start=$wpdb->get_var($wpdb->prepare("SELECT date_recorded FROM {$bp->activity->table_name} WHERE type ='start_course' AND item_id=%d AND component='course' AND user_id=%d ORDER BY id DESC LIMIT 1", $course_id,$user_id));
	
	$being=get_post_meta($course_id,$user_id,true);

	if(isset($being) && $being !=''){
		if(!$being){
			echo '<p>'.__('This User has not started the course.','vibe').'</p>';
		}else if($being > 2 && $being < 100){
			echo '<p>'.__('This User has completed the course.','vibe').'</p>';
			echo '<h4>'.__('Student Score for Course ','vibe').' : <strong>'.$being.__(' out of 100','vibe').'</strong></h4>';

      $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
      $complete=$total=count($course_curriculum);

		}else{
			$total=0;
			$complete=0;

			echo '<h6>';
			_e('Course Started : ','vibe');
			 echo '<span>'.human_time_diff(strtotime($start),time()).'</span></h6>';

			$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));

			$curriculum = '<div class="curriculum_check"><h6>'.__('Curriculum :','vibe').'</h6><ul>';
			$quiz ='<h5>'.__('Quizes','vibe').'</h5>';
			foreach($course_curriculum as $c){
				if(is_numeric($c)){
					$total++;
					$check=get_user_meta($user_id,$c,true);
					if(isset($check) && $check !=''){
						$complete++;
						if(get_post_type($c) == 'quiz'){
							$marks = get_post_meta($c,$user_id,true);

							$curriculum .= '<li class="check_user_quiz_results" data-quiz="'.$c.'" data-user="'.$user_id.'"><span class="done"></span> '.get_the_title($c).' <strong>'.(($marks)?__('Marks Obtained : ','vibe').$marks:__('Under Evaluation','vibe')).'</strong></li>';
						}else
							$curriculum .= '<li><span class="done"></span> '.get_the_title($c).'</li>';

					}else{
						$curriculum .= '<li><span></span> '.get_the_title($c).'</li>';
					}
				}else{
					$curriculum .= '<li><h5>'.$c.'</h5></li>';
				}
			}
			$curriculum .= '</ul></div>';
		}
	}

	echo '<strong>'.__('Units Completed ','vibe').$complete.__(' out of ','vibe').$total.'</strong>';
	echo '<div class="complete_course"><input type="text" class="dial" data-max="'.$total.'" value="'.$complete.'"></div>';
	echo $curriculum;
    echo '</div>';
	die();
}


add_action('wp_ajax_check_user_quiz_results','wplms_check_user_quiz_results');
function wplms_check_user_quiz_results(){

  $quiz_id = $_REQUEST['quiz'];
  $user_id = $_REQUEST['user'];

  if ( !isset($_REQUEST['security']) || !wp_verify_nonce($_REQUEST['security'],'vibe_security') ){
      echo '<p>'.__('Security check failed !','vibe').'</p>';
      die();
  }


      
$questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
  $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));

$sum=$total_sum=0;
echo '<div class="quiz_result"><h3 class="heading">'.get_the_title($quiz_id).'</h3>';
if(count($questions)){

  echo '<ul class="quiz_questions">';

  foreach($questions['ques'] as $key=>$question){
    if(isset($question) && is_numeric($question)){
    $q=get_post($question);
    echo '<li>
        <div class="q">'.apply_filters('the_content',$q->post_content).'</div>';
    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query( array('post_id'=> $question,'user_id'=>$user_id,'number'=>1,'status'=>'approve') );   

    echo '<strong>';
    _e('Marked Answer :','vibe');
    echo '</strong>';

    $correct_answer=get_post_meta($question,'vibe_question_answer',true);
    $marks=0;
    foreach($comments as $comment){ // This loop runs only once
      $type = get_post_meta($question,'vibe_question_type',true);

        switch($type){
          case 'truefalse': 
            $options = array( 0 => __('FALSE','vibe'),1 =>__('TRUE','vibe'));
            
            echo $options[(intval($comment->comment_content))]; // Reseting for the array
            if(isset($correct_answer) && $correct_answer !=''){
              $ans=$options[(intval($correct_answer))];
            }
          break;    
          case 'single':
          case 'select':
            $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
            
            echo do_shortcode($options[(intval($comment->comment_content)-1)]); // Reseting for the array
            if(isset($correct_answer) && $correct_answer !=''){
              $ans=$options[(intval($correct_answer)-1)];
            }
          break;  
          case 'sort': 
          case 'match': 
          case 'multiple': 
              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
              $ans=explode(',',$comment->comment_content);

              foreach($ans as $an){
                echo $options[intval($an)-1].' ';
              }

              $cans = explode(',',$correct_answer);
              $ans='';
              foreach($cans as $can){
                $ans .= $options[intval($can)-1].', ';
              }
            break;
          case 'fillblank':
          case 'smalltext': 
              echo $comment->comment_content;
              $ans = $correct_answer;
          break;
          case 'largetext': 
              echo apply_filters('the_content',$comment->comment_content);
              $ans = $correct_answer;
          break;
      }

      $marks=get_comment_meta( $comment->comment_ID, 'marks', true );
    }// END- COMMENTS-FOR
    
    $flag = apply_filters('wplms_show_quiz_correct_answer',true,$quiz_id);
    
    if(isset($correct_answer) && $correct_answer !='' && isset($marks) && $marks !='' && $flag){
      $explaination = get_post_meta($question,'vibe_question_explaination',true);
      echo '<strong>';
      _e('Correct Answer :','vibe');
      echo '<span>'.do_shortcode($ans).' '.((isset($explaination) && $explaination && strlen($explaination) > 5)?'<a class="show_explaination tip" title="'.__('View answer explanation','vibe').'"></a>':'').'</span></strong>';
    }
      
    
    $total_sum=$total_sum+intval($questions['marks'][$key]);
    echo '<span> '.__('Total Marks :','vibe').' '.$questions['marks'][$key].'</span>';

    if(isset($marks) && $marks !=''){
      if($marks > 0){
        echo '<span>'.__('MARKS OBTAINED','vibe').' <i class="icon-check"></i> '.$marks.'</span>';
      }else{
        echo '<span>'.__('MARKS OBTAINED','vibe').' <i class="icon-x"></i> '.$marks.'</span>';
      }
      $sum = $sum+intval($marks);
    }else{
      echo '<span>'.__('Marks Obtained','vibe').' <i class="icon-alarm"></i></span>';
    }

    if(isset($explaination) && $explaination && strlen($explaination) > 5 && $flag){
      echo '<div class="explaination">'.do_shortcode($explaination).'</div>';
    }
    
    echo '</li>';
    } // IF question check
  } // END FOR LOOP

  echo '</ul>';
  echo '<div id="total_marks">'.__('Total Marks','vibe').' <strong><span>'.$sum.'</span> / '.$total_sum.'</strong> </div></div>';
  do_action('wplms_quiz_results_extras');
  }
  die();
}


add_action( 'wp_ajax_remove_user_course', 'remove_user_course' ); // RESETS QUIZ FOR USER
function remove_user_course(){
	  $course_id = $_POST['id'];
    $user_id = $_POST['user'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p>'.__(' Incorrect User selected.','vibe').'</p>';
        die();
    }

      delete_post_meta($course_id,$user_id);
			delete_user_meta($user_id,$course_id);
      delete_user_meta($user_id,'course_status'.$course_id);

      $students=get_post_meta($course_id,'vibe_students',true);
      if($students > 1){
        $students--;
        update_post_meta($course_id,'vibe_students',$students);
      }
			echo '<p>'.__('User removed from the Course','vibe').'</p>';

      $group_id=get_post_meta($course_id,'vibe_group',true);
      if(isset($group_id) && is_numeric($group_id) && bp_is_active('groups')){
        groups_remove_member($user_id,$group_id);
      }else{
        $group_id ='';
      }
      
      do_action('wplms_course_unsubscribe',$course_id,$user_id,$group_id);

	die();
}


add_action( 'wp_ajax_reset_course_user', 'reset_course_user' ); // RESETS COURSE FOR USER
function reset_course_user(){
	  $course_id = $_POST['id'];
    $user_id = $_POST['user'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($user_id) || !is_numeric($user_id) || !$user_id){
        echo '<p>'.__(' Incorrect User selected.','vibe').'</p>';
        die();
    }
      
      //delete_user_meta($user_id,$course_id) // DELETE ONLY IF USER SUBSCRIPTION EXPIRED
    $status = bp_course_get_user_course_status($user_id,$course_id);
    
    if(isset($status) && is_numeric($status)){  // Necessary for continue course
      
      bp_course_update_user_course_status($user_id,$course_id,0); // New function
      
			$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
      update_user_meta($user_id,'progress'.$course_id,0);
			foreach($course_curriculum as $c){
				if(is_numeric($c)){
					delete_user_meta($user_id,$c);
					delete_post_meta($c,$user_id);
					if(get_post_type($c) == 'quiz'){

            $questions = vibe_sanitize(get_post_meta($c,'quiz_questions'.$user_id,false));
            
            if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
              $questions = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
            else
              delete_post_meta($c,'quiz_questions'.$user_id); // Re-capture new questions in quiz begining

            if(isset($questions) && is_array($questions) && is_Array($questions['ques']))
				      	foreach($questions['ques'] as $question){
				        	global $wpdb;
                  if(isset($question) && $question !='' && is_numeric($question))
				        	$wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$question,$user_id));
				      	}
					}
				}
			}
      /*=== Fix in 1.5 : Reset  Badges and CErtificates on Course Reset === */
      $user_badges=vibe_sanitize(get_user_meta($user_id,'badges',false));
      $user_certifications=vibe_sanitize(get_user_meta($user_id,'certificates',false));

      if(isset($user_badges) && is_Array($user_badges) && in_array($course_id,$user_badges)){
          $key=array_search($course_id,$user_badges);
          unset($user_badges[$key]);
          $user_badges = array_values($user_badges);
          update_user_meta($user_id,'badges',$user_badges);
      }
      if(isset($user_certifications) && is_Array($user_certifications) && in_array($course_id,$user_certifications)){
          $key=array_search($course_id,$user_certifications);
          unset($user_certifications[$key]);
          $user_certifications = array_values($user_certifications);
          update_user_meta($user_id,'certificates',$user_certifications);
      }
      /*==== End Fix ======*/

			echo '<p>'.__('Course Reset for User','vibe').'</p>';
      
      do_action('wplms_course_reset',$course_id,$user_id);

	}else{
		echo '<p>'.__('There was issue in resetting this course for the user. Please contact admin.','vibe').'</p>';
	}
	die();
}

add_action( 'wp_ajax_reset_quiz', 'reset_quiz' ); // RESETS QUIZ FOR USER
function reset_quiz(){

    $quiz_id = $_POST['id'];
    $user_id = $_POST['user'];

     if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_quiz') ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p>'.__(' Incorrect User selected.','vibe').'</p>';
        die();
    }

    if(delete_user_meta($user_id,$quiz_id)){

      delete_post_meta($quiz_id,$user_id); // Optional validates that user can retake the quiz

      $questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
      if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
        $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
      else
        delete_post_meta($quiz_id,'quiz_questions'.$user_id); // Re-capture new questions in quiz begining

      foreach($questions['ques'] as $question){
        global $wpdb;
        $wpdb->query($wpdb->prepare("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=%d AND user_id=%d",$question,$user_id));
      }
      echo '<p>'.__('Quiz Reset for Selected User','vibe').'</p>';
    }else{
      echo '<p>'.__('Could not find Quiz results for User. Contact Admin.','vibe').'</p>';
    }
	
    do_action('wplms_quiz_reset',$quiz_id,$user_id);
    die();
}


add_action( 'wp_ajax_give_marks', 'give_marks' ); // RESETS QUIZ FOR USER
function give_marks(){
    $answer_id=intval($_POST['aid']);
    $value=intval($_POST['aval']);
    
    if(is_numeric($answer_id) && is_numeric($value))
      update_comment_meta( $answer_id, 'marks',$value);

    die();
}

add_action( 'wp_ajax_complete_course_marks', 'complete_course_marks' ); // COURSE MARKS FOR USER
function complete_course_marks(){
    $user_id=intval($_POST['user']);
    $course_id=intval($_POST['course']);
    $marks=intval($_POST['marks']);

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],$course_id) || !is_numeric($user_id) || !is_numeric($course_id) ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }


    $badge_per = get_post_meta($course_id,'vibe_course_badge_percentage',true);
    $passing_per =get_post_meta($course_id,'vibe_course_passing_percentage',true);

    


    $badge_filter = 0;
    if(isset($badge_per) && $badge_per && $marks >= $badge_per)
      $badge_filter = 1;

    $badge_filter = apply_filters('wplms_course_student_badge_check',$badge_filter,$course_id,$user_id,$marks,$badge_per);
    
    if($badge_filter){  
        $badges= vibe_sanitize(get_user_meta($user_id,'badges',false));

        if(is_array($badges)){
          if(!in_array($course_id,$badges))
            $badges[]=$course_id;
        }else{
          $badges = array();
          $badges[]=$course_id;
        }
        update_user_meta($user_id,'badges',$badges);
        do_action('wplms_badge_earned',$course_id,$badges,$user_id,$badge_filter);
    }

    $passing_filter = 0;
    if(isset($passing_per) && $passing_per && $marks >= $passing_per)
      $passing_filter = 1;

    $passing_filter = apply_filters('wplms_course_student_certificate_check',$passing_filter,$course_id,$user_id,$marks,$passing_per);
      
    if($passing_filter){
        $pass=vibe_sanitize(get_user_meta($user_id,'certificates',false));
        if(is_array($pass)){
          if(!in_array($course_id,$pass))
            $pass[]=$course_id; 
        }else{
          $pass = array();
          $pass[]=$course_id; 
        }
        update_user_meta($user_id,'certificates',$pass);
        do_action('wplms_certificate_earned',$course_id,$pass,$user_id,$passing_filter);
    }
    update_post_meta( $course_id,$user_id,$marks);    
    $course_end_status = apply_filters('wplms_course_status',4);  
    update_user_meta( $user_id,'course_status'.$course_id,$course_end_status);//EXCEPTION
    echo __('COURSE MARKED COMPLETE','vibe');

    do_action('wplms_evaluate_course',$course_id,$marks,$user_id);
    
    die();
}



add_action( 'wp_ajax_save_quiz_marks', 'save_quiz_marks' ); // RESETS QUIZ FOR USER
function save_quiz_marks(){
    $quiz_id=intval($_POST['quiz_id']);
    $user_id=intval($_POST['user_id']);
    $marks=intval($_POST['marks']);

    $questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
      if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
        $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));

    $max= array_sum($questions['marks']);
    
    update_post_meta( $quiz_id, $user_id,$marks);

    do_action('wplms_evaluate_quiz',$quiz_id,$marks,$user_id,$max);

    die();
}

add_action( 'wp_ajax_evaluate_course', 'wplms_evaluate_course' ); // RESETS QUIZ FOR USER
function wplms_evaluate_course(){
    
    $course_id=intval($_POST['id']);
    $user_id=intval($_POST['user']);

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],$course_id) ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id || !is_numeric($user_id)){
        echo '<p>'.__(' Incorrect User selected.','vibe').'</p>';
        die();
    }
    $sum=$max_sum=0;
    $curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
     echo '<ul class="course_curriculum">';
    foreach($curriculum as $c){
      if(is_numeric($c)){
        if(get_post_type($c) == 'quiz'){
            $status = get_user_meta($user_id,$c,true);
            $marks=get_post_meta($c,$user_id,true);
            $sum += intval($marks);

            $qmax = vibe_sanitize(get_post_meta($c,'quiz_questions'.$user_id,false));
            if(!isset($questions) || !is_array($questions))
              $qmax=vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));

            $max=array_sum($qmax['marks']);
            $max_sum +=$max;
            echo '<li>
                  <strong>'.get_the_title($c).' <span>'.((isset($status) && $status !='')?__('MARKS: ','vibe').$marks.__(' out of ','vibe').$max:__(' PENDING','vibe')).'</span></strong>
                  </li>';
        }else{
            $status = get_user_meta($user_id,$c,true);
            echo '<li>
                  <strong>'.get_the_title($c).' <span>'.((isset($status) && $status !='')?'<i class="icon-check"></i> '.__('DONE','vibe'):'<i class="icon-alarm-1"></i>'.__(' PENDING','vibe')).'</span></strong>
                  </li>';
        } 
      }else{

      }
    }     
    do_action('wplms_course_manual_evaluation',$course_id,$user_id);
    echo '</ul>';
    echo '<div id="total_marks">'.__('Total','vibe').' <strong><span>'.apply_filters('wplms_course_student_marks',$sum,$course_id,$user_id).'</span> / '.apply_filters('wplms_course_maximum_marks',$max_sum,$course_id,$user_id).'</strong> </div>';
    echo '<div id="course_marks">'.__('Course Percentage (Out of 100)','vibe').' <strong><span><input type="number" name="course_marks" id="course_marks_field" class="form_field" value="0" placegolder="'.__('Course Percentage out of 100','vibe').'" /></span></div>';
    echo '<a href="#" id="course_complete" class="button full" data-course="'.$course_id.'" data-user="'.$user_id.'">'.__('Mark Course Complete','vibe').'</a>';
    
    wp_nonce_field($course_id,'security');
  die();
}


add_action( 'wp_ajax_evaluate_quiz', 'evaluate_quiz' ); // EVALAUTES QUIZ FOR USER : MANUAL EVALUATION
function evaluate_quiz(){

    $quiz_id=intval($_POST['id']);
    $user_id=intval($_POST['user']);

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_quiz') ){
       echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
         echo '<p>'.__(' Incorrect User selected.','vibe').'</p>';
        die();
    }

    if(get_post_type($quiz_id) != 'quiz'){
      echo '<p>'.__(' Incorrect Quiz Id.','vibe').'</p>';
        die();
    }

  $questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
  if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
    $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
  echo '<h3 class="evaluate_heading">'.get_the_title($quiz_id).'</h3>';
  if(count($questions)):

    echo '<ul class="quiz_questions">';
    $sum=$max_sum=0;
    foreach($questions['ques'] as $key=>$question){
      if(isset($question) && $question){
      $q=get_post($question);
      echo '<li>
          <div class="q">'.apply_filters('the_content',$q->post_content).'</div>';
      $comments_query = new WP_Comment_Query;
      $comments = $comments_query->query( array('post_id'=> $question,'user_id'=>$user_id,'number'=>1,'status'=>'approve') );   
      echo '<strong>';
      _e('Marked Answer :','vibe');
      echo '</strong>';

      $correct_answer=get_post_meta($question,'vibe_question_answer',true);
      foreach($comments as $comment){ // This loop runs only once
        $type = get_post_meta($question,'vibe_question_type',true);

          switch($type){
            case 'select':
            case 'single': 
              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
              
              echo $options[(intval($comment->comment_content)-1)]; // Reseting for the array
              if(isset($correct_answer) && $correct_answer !=''){
                $ans=$options[(intval($correct_answer)-1)];

              }
            break;  
            case 'multiple': 
              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
              $ans=explode(',',$comment->comment_content);

              foreach($ans as $an){
                echo $options[intval($an)-1].' ';
              }

              $cans = explode(',',$correct_answer);
              $ans='';
              foreach($cans as $can){
                $ans .= $options[intval($can)-1].', ';
              }
            break;
            case 'match': 
            case 'sort': 
              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
              $ans=explode(',',$comment->comment_content);

              foreach($ans as $an){
                echo $an.'. '.$options[intval($an)-1].' ';
              }

              $cans = explode(',',$correct_answer);
              $ans='';
              foreach($cans as $can){
                $ans .= $can.'. '.$options[intval($can)-1].', ';
              }
            break;
            case 'fillblank':
            case 'smalltext': 
                echo $comment->comment_content;
                $ans = $correct_answer;
            break;
            default: 
                echo apply_filters('the_content',$comment->comment_content);
                $ans = $correct_answer;
            break;
        }
        $cid=$comment->comment_ID;
        $marks=get_comment_meta( $comment->comment_ID, 'marks', true );
      }

      if(isset($correct_answer) && $correct_answer !=''){
        echo '<strong>';
        _e('Correct Answer :','vibe');
        echo '<span>'.$ans.'</span></strong>';


      }
      

    

      if(isset($marks) && $marks !=''){
          echo '<span class="marking">'.__('Marks Obtained','vibe').' <input type="text" id="'.$cid.'" class="form_field question_marks" value="'.$marks.'" placeholder="'.__('Give marks','vibe').'" />
                <a href="#" class="give_marks button" data-ans-id="'.$cid.'">'.__('Update Marks','vibe').'</a>';

          $sum = $sum+$marks;
      }else{
        echo '<span class="marking">'.__('Marks Obtained','vibe').' <input type="text" id="'.$cid.'" class="form_field question_marks" value="" placeholder="'.__('Give marks','vibe').'" />
        <a href="#" class="give_marks button" data-ans-id="'.$cid.'">'.__('Give Marks','vibe').'</a>';
      }
      $max_sum=$max_sum+intval($questions['marks'][$key]);
      echo '<span> '.__('Total Marks','vibe').' : '.$questions['marks'][$key].'</span>';
      echo '</li>';

      } // IF question check
    } 
    echo '</ul>';
    echo '<div id="total_marks">'.__('Total','vibe').' <strong><span>'.$sum.'</span> / '.$max_sum.'</strong> </div>';
    echo '<a href="#" id="mark_complete" class="button full" data-quiz="'.$quiz_id.'" data-user="'.$user_id.'">'.__('Mark Quiz as Checked','vibe').'</a>';
    endif;

    die();
}



add_action( 'wp_ajax_send_bulk_message', 'send_bulk_message' );
function send_bulk_message(){

    $course_id=$_POST['course'];
    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security'.$course_id) ){
        echo 'Security check failed !';
        die();
    }
    $members = json_decode(stripslashes($_POST['members']));

    $sender = $_POST['sender'];
    $subject=stripslashes($_POST['subject']);
    if(!isset($subject)){
      _e('Set a Subject for the message','vibe');
      die();  
    }
    $message=stripslashes($_POST['message']);
    if(!isset($message)){
      _e('Set a Subject for the message','vibe');
      die();  
    }
    $sent=0;
    if(count($members) > 0){
      foreach($members as $member){
          if(bp_is_active('messages'))
          if( messages_new_message( array('sender_id' => $sender, 'subject' => $subject, 'content' => $message,   'recipients' => $member ) ) ){
            $sent++;
          }
      }
      echo __('Messages Sent to ','vibe').$sent.__(' members','vibe');
    }else{
      echo __('Please select members','vibe');
    }

    do_action('wplms_bulk_action','bulk_message',$course_id,$members);

    die();
}


add_action( 'wp_ajax_add_bulk_students', 'add_bulk_students' );
function add_bulk_students(){
    
    $course_id=$_POST['course'];
    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security'.$course_id) ){
        echo 'Security check failed !';
        die();
    }
    $member_ids = array();
    $members = stripslashes($_POST['members']);
    $member_ids = array();

    if(strpos($members,',')){
      $members=explode(',',$members);
    }else{
      $members = array($members);
    }    
    $html = '';
    foreach($members as $member){
      if(is_numeric($member)){
        $user_id = $member;
      }else{
        if(filter_var($member, FILTER_VALIDATE_EMAIL)) {
          $user_id = email_exists($member);
        }else {
          $user_id = bp_core_get_userid_from_nicename($member);
        }  
      }
      
      if(!empty($user_id)){
        $force_flag = apply_filters('wplms_force_flag_bulk_add_students',1,$course_id,$user_id);
        $check = bp_course_add_user_to_course($user_id,$course_id,'',$force_flag);
        if($check){
            $field = vibe_get_option('student_field');
            if(!isset($field) || !$field) $field = 'Location';
            $html .= '<li id="s'.$user_id.'">
            <input type="checkbox" class="member" value="'.$user_id.'">
            '.bp_core_fetch_avatar ( array( 'item_id' => $user_id, 'type' => 'full' ) ).'
            <h6>'.bp_core_get_userlink( $user_id ).'</h6><span>'.(function_exists('xprofile_get_field_data')?xprofile_get_field_data( $field, $user_id ):'').'</span><ul> 
            <li><a class="tip reset_course_user" data-course="'.$course_id.'" data-user="'.$user_id.'" title="" data-original-title="'.__('Reset Course for User','vibe').'"><i class="icon-reload"></i></a></li>
            <li><a class="tip course_stats_user" data-course="'.$course_id.'" data-user="'.$user_id.'" title="" data-original-title="'.__('See Course stats for User','vibe').'"><i class="icon-bars"></i></a></li>
            <li><a class="tip remove_user_course" data-course="'.$course_id.'" data-user="'.$user_id.'" title="" data-original-title="'.__('Remove User from this Course','vibe').'"><i class="icon-x"></i></a></li>
            </ul></li>'; 
            $member_ids[]=$user_id;
        }
      }
    }
    echo $html;
    if(!empty($member_ids)){
        foreach($member_ids as $member_id){
          do_action('wplms_course_subscribed',$course_id, $user_id);
        }
        do_action('wplms_bulk_action','added_students',$course_id,$member_ids);
    }
    die();
}



/*=== ASSIGN CERTIFICATES & BADGES to STUDENTS FROM FRONT END v 1.5.4 =====*/
add_action( 'wp_ajax_assign_badge_certificates', 'assign_badge_certificates' );
function assign_badge_certificates(){

    $course_id=$_POST['course'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security'.$course_id) ){
        echo 'Security check failed !';
        die();
    }
    $members = json_decode(stripslashes($_POST['members']));

    $assign_action = $_POST['assign_action'];
    if(!isset($assign_action) && !$assign_action){
      _e('Select Assign Value','vibe');
      die();  
    }

    $assigned=0;
    if(count($members) > 0){
      foreach($members as $mkey=>$member){ 
          if(is_numeric($member) && get_post_type($course_id) == 'course'){

            switch($assign_action){
              case 'add_badge':
                $badges = vibe_sanitize(get_user_meta($member,'badges',false));
                if(isset($badges) && is_array($badges)){
                  $badges[]=$course_id;
                }else{
                  $badges = array($course_id);
                }
                update_user_meta($member,'badges',$badges);
              break;
              case 'add_certificate':
                $certificates = vibe_sanitize(get_user_meta($member,'certificates',false));
                if(isset($certificates) && is_array($certificates)){
                  $certificates[]=$course_id;
                }else{
                    $certificates = array($course_id);
                }
                update_user_meta($member,'certificates',$certificates);
              break;
              case 'remove_badge': 
                $badges = vibe_sanitize(get_user_meta($member,'badges',false));
                if(isset($badges) && is_array($badges)){
                  $k=array_search($course_id,$badges);
                  if(isset($k)){
                    unset($badges[$k]);
                  }
                  $badges = array_values($badges);
                  update_user_meta($member,'badges',$badges);
                }
              break;
              case 'remove_certificate':
                $certificates = vibe_sanitize(get_user_meta($member,'certificates',false));
                $k=array_search($course_id,$certificates);
                if(isset($k))
                  unset($certificates[$k]);
                $certificates = array_values($certificates);
                update_user_meta($member,'certificates',$certificates);
              break;
            }
            
            
            $flag=1;
            $assigned++;
          }else{
            $flag=0;
            break;
          }
      }


      if($flag){
        echo __('Action assigned to ','vibe').$assigned.__(' members','vibe');
        
        do_action('wplms_bulk_action',$assign_action,$course_id,$members);

      }else
        echo __('Could not assign action to members','vibe');

    }else{
      echo __('Please select members','vibe');
    }

    die();
}

/*=== EXTEND SUBSCRIPTION =====*/
add_action( 'wp_ajax_extend_course_subscription', 'wplms_extend_course_subscription' );
function wplms_extend_course_subscription(){

    $course_id=$_POST['course'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security'.$course_id) || get_post_type($course_id) != 'course' ){
        echo 'Security check failed !';
        die();
    }
    $members = json_decode(stripslashes($_POST['members']));
    $extend_amount = $_POST['extend_amount'];
    if(!isset($extend_amount) || !$extend_amount){
      echo __('Please enter extension amount','vibe');
      die();
    }

    if(!count($members)){
      echo __('Please select members','vibe');
      die();
    }
    $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
    $extend_amount_seconds = $extend_amount*$course_duration_parameter;
    $count=0;$neg=0;
    foreach($members as $member){
        if(is_numeric($member)){

          $expiry = get_user_meta($member,$course_id,true);
          if(isset($expiry) && $expiry){
            $expiry = $expiry + $extend_amount_seconds;
            update_user_meta($member,$course_id,$expiry);
            $count++;
          }else{
            $neg++;
          }
        }
    }
    if($neg){
      echo sprintf(__('Subscription extended for %d students, unable to extend for %d students','vibe'),$count,$neg);
    }else
      echo sprintf(__('Subscription extended for %d students','vibe'),$count);

    do_action('wplms_bulk_action','extend_course_subscription',$course_id,$members);

  die();  
}

/*=== Manage Course Status v 1.9.5 =====*/

add_action( 'wp_ajax_change_course_status', 'wplms_change_course_status' );
function wplms_change_course_status(){

    $course_id=$_POST['course'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security'.$course_id) ){
        echo 'Security check failed !';
        die();
    }
    $members = json_decode(stripslashes($_POST['members']));

    $status_action = $_POST['status_action'];
    if(!isset($status_action) && !$status_action){
      _e('Select Course Status','vibe');
      die();  
    }

    $assigned=0;
    if(count($members) > 0){
      foreach($members as $mkey=>$member){ 
          if(is_numeric($member) && get_post_type($course_id) == 'course'){

            switch($status_action){
              case 'start_course':
                $status=0;
              break;
              case 'continue_course':
                $status=1;
              break;
              case 'under_evaluation': 
                $status=2;
              break;
              case 'finish_course':
                $status=3;
              break;
            }
            $status = apply_filters('wplms_course_status',$status,$status_action);
            if(is_numeric($status)){
              bp_course_update_user_course_status($member,$course_id,$status);  
              if($status == 3 && isset($_POST['data']) && is_numeric($_POST['data'])){
                update_post_meta($course_id,$member,$_POST['data']);
              }
            }
            
            $flag=1;
            $assigned++;
          }else{
            $flag=0;
            break;
          }
      }


      if($flag){
        echo __('Course status changed for ','vibe').$assigned.__(' members','vibe');
        do_action('wplms_bulk_action','change_course_status',$course_id,$members);
      }else
        echo __('Could not assign action to members','vibe');

    }else{
      echo __('Please select members','vibe');
    }

    die();
}


/*=== DOWNLOAD STATS =====*/
add_action('wp_ajax_download_stats','wplms_course_download_stats');
function wplms_course_download_stats(){
  $course_id=$_POST['course'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') ){
      echo __('Security check failed !','vibe');
      die();
  } 
  if (!current_user_can('edit_posts') || !is_numeric($course_id)){
      echo __('User does not have capability to download stats !','vibe');
      die();
  }
  
  $fields = json_decode(stripslashes($_POST['fields']));
  $type=stripslashes($_POST['type']);
  if(!isset($type))
    die();

  $users = array();
  $csv = array();$csv_title=array();
  global $wpdb,$bp;

  switch($type){
    case 'all_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key  = %s ",'course_status'.$course_id),ARRAY_A);
    break;
    case 'finished_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key  = %s AND meta_value = %d",'course_status'.$course_id,4),ARRAY_A);
    break;
    case 'pursuing_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value < %d",'course_status'.$course_id,4),ARRAY_A);
    break;
    case 'badge_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value LIKE %s",'badges',"%$course_id%"),ARRAY_A);
    break;
    case 'certificate_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value LIKE %s",'certificates',"%$course_id%"),ARRAY_A);
    break;
  }
  if(count($users)){ 
    foreach($users as $user){
      $user_id = $user['user_id'];
      $i=0;
      foreach($fields as $k=>$field){
        switch($field){
          case 'stats_student_start_date':
            $title=__('START DATE','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

            $date = $wpdb->get_results($wpdb->prepare("SELECT date_recorded FROM {$bp->activity->table_name} WHERE type=%s AND user_id = %d and item_id = %d",'start_course',$user_id,$course_id));
            if(is_array($date) && is_object($date[0]) && isset($date[0]->date_recorded))
              $csv[$i][]=$date[0]->date_recorded;
            else
              $csv[$i][]=__('N.A','vibe');

          break;
          case 'stats_student_completion_date':
            $title=__('COMPLETION DATE','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

            $date = $wpdb->get_results($wpdb->prepare("SELECT date_recorded FROM {$bp->activity->table_name} WHERE type=%s AND user_id = %d and item_id = %d",'submit_course',$user_id,$course_id));
            if(is_array($date) && is_object($date[0]) && isset($date[0]->date_recorded))
              $csv[$i][]=$date[0]->date_recorded;
            else
              $csv[$i][]=__('N.A','vibe');
          break;
          case 'stats_student_id':
            $title=__('ID','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

            $csv[$i][] = $user_id;
          break;
          case 'stats_student_name':
            $title=__('NAME','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

            $csv[$i][] = bp_core_get_username($user_id);
          break;
          case 'stats_student_unit_status':
            $units=bp_course_get_curriculum_units($course_id);
            foreach($units as $unit_id){
              if(get_post_type($unit_id) == 'unit'){

              $title=get_the_title($unit_id);
              if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

              if(bp_course_check_unit_complete($unit_id,$user_id)){
                $csv[$i][] = 1;
              }else{
                $csv[$i][] = 0;
              }
              $i++;
              }
            }
            
          break;
          case 'stats_student_quiz_score':
            $units=bp_course_get_curriculum_units($course_id);
            

            foreach($units as $unit_id){
              if(get_post_type($unit_id) == 'quiz'){

                $title=get_the_title($unit_id);
                if(!in_array($title,$csv_title))
                  $csv_title[$i]=$title;

                $score = get_post_meta($unit_id,$user_id,true);
                if(!isset($score) || !$score)
                  $csv[$i][] = __('N.A','vibe');
                else
                  $csv[$i][] = $score;

                $i++;
              }
            }
          break;
          case 'stats_student_badge':
            $title=__('BADGE','vibe');
              if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;
            $check = $wpdb->get_results($wpdb->prepare("SELECT COUNT(meta_key) as count FROM {$wpdb->usermeta} WHERE meta_key = %s AND user_id = %d AND meta_value LIKE %s",'badges',$user_id,"%$course_id%"),ARRAY_A);
            if(isset($check) && is_array($check)){
               if($check[0]['count'])
                $csv[$i][]= 1;
                else
                  $csv[$i][]= 0;
            }
          break;
          case 'stats_student_certificate':
            $title=__('CERTIFICATE','vibe');
            if(!in_array($title,$csv_title))
            $csv_title[$i]=$title;
            $check = $wpdb->get_results($wpdb->prepare("SELECT COUNT(meta_key) as count FROM {$wpdb->usermeta} WHERE meta_key = %s AND user_id = %d AND meta_value LIKE %s",'certificates',$user_id,"%$course_id%"),ARRAY_A);
            if(isset($check) && is_array($check)){
               if($check[0]['count'])
                $csv[$i][]= 1;
                else
                  $csv[$i][]= 0;
            }
          break;
          case 'stats_student_marks':
          $title=__('SCORE','vibe');
          if(!in_array($title,$csv_title))
            $csv_title[$i]=$title;

          $score = get_post_meta($course_id,$user_id,true);
          $csv[$i][]=$score;

          break;
          default;
          do_action_ref_array('wplms_course_stats_process', array( &$csv_title, &$csv,&$i,&$course_id,&$user_id,&$field));
          break;
        }
        $i++;
      }
    }
  }  

  if(!count($csv) || !is_array($csv[0])){
    echo '#';
    die();
  }

  $dir = wp_upload_dir();
  $user_id = get_current_user_id();
  $file_name = 'download_'.$course_id.'_'.$user_id.'.csv';
  $filepath = $dir['basedir'] . '/stats/';
  if(!file_exists($filepath))
    mkdir($filepath,0755);

  $file = $filepath.$file_name;
  if(file_exists($file))
  unlink($file);
  
  if (($handle = fopen($file, "w")) !== FALSE) {
    fputcsv($handle,$csv_title);
      
      $rows = count($csv[0]);

      for($i=0;$i<$rows;$i++){
        $arr=array(); 
          foreach ($csv as $key=>$f) {
            $arr[]=$f[$i];
          }
        fputcsv($handle, $arr);  
      }
    }
    fclose($handle);

  $file_url = $dir['baseurl']. '/stats/'.$file_name;

  echo $file_url;
  
  die();
}

add_action('wp_ajax_download_mod_stats','wplms_download_mod_stats');
function wplms_download_mod_stats(){

  $id=$_POST['id'];
  $post_type=$_POST['type'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') ){
      echo __('Security check failed !','vibe');
      die();
  } 
  if (!current_user_can('edit_posts') || !is_numeric($id)){
      echo __('User does not have capability to download stats !','vibe');
      die();
  }
  
  $fields = json_decode(stripslashes($_POST['fields']));
  $type=stripslashes($_POST['select']);
  if(!isset($type))
    die();

  $users = array();
  $csv = array();$csv_title=array();
  global $wpdb,$bp;
  if(in_array($post_type,array('quiz','wplms-assignment'))){
  switch($type){
    case 'all_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT meta_key as user_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$'",$id),ARRAY_A);
    break;
    case 'finished_students':
      $users = $wpdb->get_results($wpdb->prepare("SELECT meta_key as user_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value REGEXP '^[0-9]+$'  AND meta_key IN (SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %d AND meta_value < %d)",$id,0,$id,time()),ARRAY_A);
    break;
    }
  }
  if($post_type == 'question'){
    $users = $wpdb->get_results($wpdb->prepare("SELECT user_id as user_id FROM {$wpdb->comments} WHERE comment_post_ID = %d AND comment_approved = %d",$id,1),ARRAY_A);
  }
  if(count($users)){
    foreach($users as $user){
      if(is_numeric($user['user_id']) && $user['user_id']){
      $user_id = $user['user_id'];
      $i=0;
      foreach($fields as $k=>$field){
        switch($field){
          case 'stats_student_start_date':
            $title=__('START DATE','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

           if(in_array($post_type,array('quiz','wplms-assignment'))){
            $dtype='start_'.$post_type;
            if($post_type == 'wplms-assignment')
              $dtype='assignment_started';
            
            $date = $wpdb->get_results($wpdb->prepare("SELECT date_recorded FROM {$bp->activity->table_name} WHERE type=%s AND user_id = %d and item_id = %d",$dtype,$user_id,$id));
            }else if($post_type == 'question'){
              $date = $wpdb->get_results($wpdb->prepare("SELECT comment_date as date_recorded FROM {$wpdb->comments} WHERE comment_approved= %d AND user_id = %d and comment_post_ID = %d",1,$user_id,$id));
            }
            if(is_array($date) && is_object($date[0]) && isset($date[0]->date_recorded))
              $csv[$i][]=$date[0]->date_recorded;
            else
              $csv[$i][]=__('N.A','vibe');
          break;
          case 'stats_student_finish_date':
            $title=__('COMPLETION DATE','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;
            if(in_array($post_type,array('quiz','wplms-assignment'))){
               $dtype='submit_'.$post_type;
               if($post_type == 'wplms-assignment')
              $dtype='assignment_submitted';
            $date = $wpdb->get_results($wpdb->prepare("SELECT date_recorded FROM {$bp->activity->table_name} WHERE type=%s AND user_id = %d and item_id = %d",$dtype,$user_id,$id));
            }else if($post_type == 'question'){
              $date = $wpdb->get_results($wpdb->prepare("SELECT comment_date as date_recorded FROM {$wpdb->comments} WHERE comment_approved= %d AND user_id = %d and comment_post_ID = %d",1,$user_id,$id));
            }
            if(is_array($date) && is_object($date[0]) && isset($date[0]->date_recorded))
              $csv[$i][]=$date[0]->date_recorded;
            else
              $csv[$i][]=__('N.A','vibe');
            
          break;
          case 'stats_student_id':
            $title=__('ID','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

            $csv[$i][] = $user_id;
          break;
          case 'stats_student_name':
            $title=__('NAME','vibe');
            if(!in_array($title,$csv_title))
              $csv_title[$i]=$title;

            $csv[$i][] = bp_core_get_username($user_id);
          break;
          case 'stats_question_scores':
            $quiz_dynamic = get_post_meta($id,'vibe_quiz_dynamic',true);
            if(!vibe_validate($quiz_dynamic)){
                $questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',true));
                $i_bkup = $i;
                if(is_array($questions) && is_array($questions['ques'])){
                  foreach($questions['ques'] as $m=>$question){
                    $title = get_the_title($question).' ('.$questions['marks'][$m].') ';
                    if(!in_array($title,$csv_title)){
                      $csv_title[$i_bkup]=$title;  
                      $i_bkup++;
                    }
                  }
                  foreach($questions['ques'] as $m=>$question){
                    $marks = $wpdb->get_results($wpdb->prepare("SELECT meta_value as score FROM {$wpdb->commentmeta} WHERE meta_key = %s AND comment_id IN ( SELECT comment_ID FROM {$wpdb->comments} WHERE comment_approved= %d AND user_id = %d and comment_post_ID = %d )",'marks',1,$user_id,$id));
                    if(isset($marks) && is_array($marks) && is_object($marks[0]) && isset($marks[0]->score))  
                      $csv[$i][]=$marks[0]->score;
                    else
                      $csv[$i][]=0;
                  }
                }
            }
          break;
          case 'stats_student_marks':
          $title=__('SCORE','vibe');
          if(!in_array($title,$csv_title))
            $csv_title[$i]=$title;
          if(in_array($post_type,array('quiz','wplms-assignment'))){
            $score = get_post_meta($id,$user_id,true);
            $csv[$i][]=$score;
          }else if($post_type == 'question'){
            $marks = $wpdb->get_results($wpdb->prepare("SELECT meta_value as score FROM {$wpdb->commentmeta} WHERE meta_key = %s AND comment_id IN ( SELECT comment_ID FROM {$wpdb->comments} WHERE comment_approved= %d AND user_id = %d and comment_post_ID = %d )",'marks',1,$user_id,$id));
            if(isset($marks) && is_array($marks) && is_object($marks[0]) && isset($marks[0]->score))  
              $csv[$i][]=$marks[0]->score;
            else
              $csv[$i][]=0;
          }
          break;
          default:
            do_action_ref_array('wplms_mod_stats_process', array( &$csv_title, &$csv,&$i,&$id,&$user_id,&$field,&$post_type));
          break;
        }
        $i++;
        }
      }
    }
  }  

   $dir = wp_upload_dir();
  $user_id = get_current_user_id();
  $file_name = 'download_'.$id.'_'.$user_id.'.csv';
  $filepath = $dir['basedir'] . '/stats/';
  if(!file_exists($filepath))
    mkdir($filepath,0755);

  $file = $filepath.$file_name;
  if(file_exists($file))
  unlink($file);
  

  if (($handle = fopen($file, "w")) !== FALSE) {
    fputcsv($handle,$csv_title);
    $rows = count($csv[0]);
      for($i=0;$i<$rows;$i++){
        $arr=array();
          foreach ($csv as $key=>$f) {
            $arr[]=$f[$i];
          }

        fputcsv($handle, $arr);  
      }
    }
    fclose($handle);
  //$query=$wpdb->prepare("SELECT * INTO OUTFILE %s FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$'",$file,$course_id);
  //$check = $wpdb->get_results($query);

  //print_r($check);

  $file_url = $dir['baseurl']. '/stats/'.$file_name;

  echo $file_url;


  die();
}
/* == Display and download stats for Quiz/Question/Assignment == */

add_action('wp_ajax_load_stats','wplms_load_cpt_stats');
add_action('wp_ajax_nopriv_load_stats','wplms_load_cpt_stats');
function wplms_load_cpt_stats(){
  $type=$_POST['cpttype'];
  $id=$_POST['id'];
  $check = vibe_get_option('stats_visibility');
  $flag=0;
  if(isset($check)){
    switch($check){
      case '1':
      if(!is_user_logged_in())
        $flag= 1;
      break;
      case '2':
         if(!current_user_can('edit_posts'))
          $flag=1;
      break;
      case '3':
      $user_id = get_current_user_id();
        $instructors = apply_filters('wplms_course_instructors',get_post_field('post_author',$id,'raw'),$id);
        if((!is_array($instructors) || !in_array($user_id,$instructors)) && !current_user_can( 'manage_options' ))
          $flag=1;
      break;
    }
  }
  
  if($flag){
    echo '<div class="message stats_content"><p>'.__('User not allowed to access stats','vibe').'</p></div>';
    die();
  }


  global $wpdb;
  $count=apply_filters('wplms_starts_leader_board',10);
  switch($type){
    case 'quiz':
    case 'wplms-assignment':
      $results = $wpdb->get_results($wpdb->prepare("SELECT meta_key as user_id, meta_value as marks FROM {$wpdb->postmeta} WHERE meta_key REGEXP '^[0-9]+$' AND post_id = %d ORDER BY meta_value DESC LIMIT 0,%d",$id,$count));
    break;
    case 'question':
      $results = $wpdb->get_results($wpdb->prepare("
        SELECT n.user_id as user_id,m.meta_value as marks
        FROM {$wpdb->comments} AS n
        LEFT JOIN {$wpdb->commentmeta} AS m ON n.comment_ID = m.comment_id
        WHERE  n.comment_post_ID = %d
        AND  n.comment_approved   = %d
        ORDER BY m.meta_value DESC
        LIMIT 0,%d
         ",$id,1,$count));
    break;
  }

  if(!is_array($results)){
    echo '<div class="message stats_content"><p>'.__('No data available','vibe').'</p></div>';
    die();
  }

  foreach($results as $result){
    if(is_numeric($result->marks) && is_numeric($result->user_id) && $result->user_id)
    $user_marks[$result->user_id] = $result->marks;
  }
  echo '<div class="stats_content">
        <h2>'.__('Stats','vibe').'</h2><hr />';
  if(is_array($user_marks)){
    $cnt=count($user_marks);if(!$cnt)$cnt=1;
    $average = round(array_sum($user_marks)/$cnt,2);
    $max = max($user_marks);
    $min = min($user_marks);  
    asort($user_marks); 
    echo '<h4>'.__('Average','vibe').'<span class="right">'.$average.'</span></h4>
        <h4>'.__('Max','vibe').'<span class="right">'.$max.'</span></h4>
        <h4>'.__('Min','vibe').'<span class="right">'.$min.'</span></h4>';
  }else{
    echo '<div class="message">'.__('N.A','vibe').'</div>';
  }
  
  
  echo '<h3 class="heading">'.__('Leaderboard','vibe').'</h3>';

  //arsort($user_marks);    

  if(is_array($user_marks)){
    echo '<ol class="marks">';
    
    foreach($user_marks as $userid=>$marks){
      if($count){
        echo '<li>'.bp_core_get_user_displayname($userid).'<span class="right">'.$marks.'</span></li>';
      }else{
        break;
      }
    }
    echo '</ol>';
  }
  if(is_user_logged_in()){
    $user_id = get_current_user_id();
    $instructors = apply_filters('wplms_course_instructors',get_post_field('post_author',$id,'raw'),$id);
    if((is_array($instructors) && in_array($user_id,$instructors)) || current_user_can( 'manage_options' )){
      echo '<h3 class="heading" id="download_stats_options">'.__('Download Stats','vibe').'<i class="icon-download-3 right"></i></h3>';

      $stats_array=apply_filters('wplms_download_mod_stats_fields',array(
        'stats_student_start_date'=>__('Start Date/Time','vibe'),
        'stats_student_finish_date'=>__('End Date/Time','vibe'),
        'stats_student_id'=>__('ID','vibe'),
        'stats_student_name'=>__('Student Name','vibe'),
        'stats_student_marks'=>__('Score','vibe'),
        ),$type);
      if($type == 'quiz'){
        $stats_array['stats_question_scores'] = __('Question scores (* for static quizzes only)','vibe');
      }

      echo '<div class="select_download_options">
      <ul>';
      foreach($stats_array as $key=>$stat){
        echo '<li><input type="checkbox" id="'.$key.'" class="field" value="1" /><label for="'.$key.'">'.$stat.'</label></li>';
      }
      echo '</ul>';
      echo '<br class="clear" /><select id="stats_students">
      <option value="all_students">'.__('All students','vibe').'</option>
      <option value="finished_students">'.__('Students who finished','vibe').'</option>
      </select>';
      wp_nonce_field('security','stats_security');
      echo '<a class="button full" id="download_mod_stats" data-type="'.$type.'" data-id="'.$id.'"><i class="icon-download-3"></i> '.__('Process Stats','vibe').'</a>
      </div>';
    } 
  }
  echo '</div>';         
  die();
}

/*=== UNIT TRAVERSE =====*/
add_action('wp_ajax_unit_traverse', 'unit_traverse');
add_action( 'wp_ajax_nopriv_unit_traverse', 'unit_traverse' );

function unit_traverse(){
  $unit_id= $_POST['id'];
  $course_id = $_POST['course_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }

  //verify unit in course
  $units = bp_course_get_curriculum_units($course_id);
  if(!in_array($unit_id,$units)){
    _e('Unit not in Course','vibe');
    die();
  }

  // Check if user has taken the course
  $user_id = get_current_user_id();
  $coursetaken=get_user_meta($user_id,$course_id,true);

  //if(!isset($_COOKIE['course'])) {
    if($coursetaken>time()){
      setcookie('course',$course_id,$expire,'/');
      $_COOKIE['course'] = $course_id;
    }else{
      $pid=get_post_meta($course_id,'vibe_product',true);
      $pid=apply_filters('wplms_course_product_id',$pid,$course_id,-1); // $id checks for Single Course page or Course page in the my courses section
      if(is_numeric($pid))
        $pid=get_permalink($pid);

      echo '<div class="message"><p>'.__('Course Expired.','vibe').'<a href="'.$pid.'" class="link alignright">'.__('Click to renew','vibe').'</a></p></div>';
      die();
    }
  //}
  
  if(isset($coursetaken) && $coursetaken){
      
      // Drip Feed Check    
      $drip_enable=get_post_meta($course_id,'vibe_course_drip',true);

      
      if(vibe_validate($drip_enable)){

          $drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);
          $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);



          $unitkey = array_search($unit_id,$units);

          for($i=($unitkey-1);$i>=0;$i--){
                if(get_post_type($units[$i]) == 'unit' ){
                  $pre_unit_key = $i;
                  break;
                }
          }

          if($unitkey == 0){
            $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);

            
            if(!isset($pre_unit_time) || $pre_unit_time ==''){
              update_post_meta($units[$unitkey],$user_id,time());
              if(is_numeric($units[1]))
                //Parmas : Next Unit, Next timestamp, course_id, userid
                do_action('wplms_start_unit',$units[$unitkey],$course_id,$user_id,$units[1],(time()+$drip_duration*$drip_duration_parameter));
            }
          }else{

             $pre_unit_time=get_post_meta($units[$pre_unit_key],$user_id,true); 
             if(isset($pre_unit_time) && $pre_unit_time){
                
                $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);

                $value = $pre_unit_time + $drip_duration*$drip_duration_parameter;
                $value = apply_filters('wplms_drip_value',$value,$units[$pre_unit_key],$course_id,$units[$unitkey]);

                //print_r(date('l jS \of F Y h:i:s A',$value).' > '.date('l jS \of F Y h:i:s A',time()));

               if($value > time()){
                      echo '<div class="message"><p>'.__('Unit will be available in ','vibe').tofriendlytime($value-time()).'</p></div>';
                      die();
                  }else{
                      $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);
                      if(!isset($pre_unit_time) || $pre_unit_time ==''){
                        update_post_meta($units[$unitkey],$user_id,time());
                        //Parmas : Next Unit, Next timestamp, course_id, userid
                        do_action('wplms_start_unit',$units[$unitkey],$course_id,$user_id,$units[$unitkey+1],(time()+$drip_duration*$drip_duration_parameter));
                      }
                  } 
              }else{
                  echo '<div class="message"><p>'.__('Unit can not be accessed.','vibe').'</p></div>';
                  die();
              }    
            }
          }  

      // END Drip Feed Check  
      
      echo '<div id="unit" class="'.get_post_type($unit_id).'_title" data-unit="'.$unit_id.'">';
        do_action('wplms_unit_header',$unit_id,$course_id);

        $minutes=0;
        $mins = get_post_meta($unit_id,'vibe_duration',true);
        $unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60);
        if($mins){
          if($mins > $unit_duration_parameter){
            $hours = floor($mins/$unit_duration_parameter);
            $minutes = $mins - $hours*$unit_duration_parameter;
          }else{
            $minutes = $mins;
          }
        
          do_action('wplms_course_unit_meta',$unit_id);
          if($mins < 9999){ 
            if($unit_duration_parameter == 1)
              echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Minutes','vibe'):'').' '.$minutes.__(' seconds','vibe').'</span>';
            else if($unit_duration_parameter == 60)
              echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.__(' minutes','vibe').'</span>';
            else if($unit_duration_parameter == 3600)
              echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Days','vibe'):'').' '.$minutes.__(' hours','vibe').'</span>';
          } 

        }
      echo '<br /><h1>'.get_the_title($unit_id).'</h1>';
          the_sub_title($unit_id);
      echo '<div class="clear"></div>';
      echo '</div>';
        the_unit($unit_id); 
      
              $unit_class='unit_button';
              $hide_unit=0;
              $nextunit_access = vibe_get_option('nextunit_access');
              

              $k=array_search($unit_id,$units);
              $done_flag=get_user_meta($user_id,$unit_id,true);

              $next=$k+1;
              $prev=$k-1;
              $max=count($units)-1;

              echo  '<div class="unit_prevnext"><div class="col-md-3">';
              if($prev >=0){

                if(get_post_type($units[$prev]) == 'quiz'){
                  echo '<a href="#" data-unit="'.$units[$prev].'" class="unit '.$unit_class.'">'.__('Previous Quiz','vibe').'</a>';
                }else    
                  echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="unit unit_button">'.__('Previous Unit','vibe').'</a>';
              }
              echo '</div>';
              $quiz_passing_flag = true;
              echo  '<div class="col-md-6">';
              if(get_post_type($units[($k)]) == 'quiz'){
                $quiz_status = get_user_meta($user_id,$units[($k)],true);
                if(is_numeric($quiz_status)){
                   $quiz_passing_flag = apply_filters('wplms_next_unit_access',true,$units[($k)]);
                  if($quiz_status < time()){
                    echo '<a href="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$units[($k)].'" class="quiz_results_popup">'.__('Check Results','vibe').'</a>';
                  }else{
                      $quiz_class = apply_filters('wplms_in_course_quiz','');
                      echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.' continue">'.__('Continue Quiz','vibe').'</a>';
                  }
                }else{
                    $quiz_class = apply_filters('wplms_in_course_quiz','');
                    echo '<a href="'.get_permalink($units[($k)]).'" class=" unit_button '.$quiz_class.'">'.__('Start Quiz','vibe').'</a>';
                }
              }else  
                  echo ((isset($done_flag) && $done_flag)?'': apply_filters('wplms_unit_mark_complete','<a href="#" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button">'.__('Mark this Unit Complete','vibe').'</a>',$unit_id,$course_id));

              echo '</div>';

              echo  '<div class="col-md-3">';

              if($next <= $max){

                if(isset($nextunit_access) && $nextunit_access){
                    $hide_unit=1;

                    if(isset($done_flag) && $done_flag){
                      $unit_class .=' ';
                      $hide_unit=0;
                    }else{
                      $unit_class .=' hide';
                      $hide_unit=1;
                    }
                }

                if(get_post_type($units[$next]) == 'quiz'){
                  if($quiz_passing_flag)
                      echo '<a href="#" id="next_quiz" data-unit="'.$units[$next].'" class="unit '.$unit_class.'">'.__('Proceed to Quiz','vibe').'</a>';
                }else {
                if($quiz_passing_flag){
                    echo '<a href="#" id="next_unit" '.(($hide_unit)?'':'data-unit="'.$units[$next].'"').' class="unit '.$unit_class.'">'.__('Next Unit','vibe').'</a>';
                }
                
                }
              }
              echo '</div></div>';
          
        }
        die();
}  

/* ===== In Course Quiz ===== */


add_action('wp_ajax_in_submit_quiz','incourse_submit_quiz');

function incourse_submit_quiz(){
  $quiz_id= $_POST['quiz_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($quiz_id)){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }
    $user_id = get_current_user_id();
    $access = get_user_meta($user_id,$quiz_id,true);
    if(!isset($access) || !is_numeric($access)){
      _e('Invalid submission time.','vibe');
       die();
    }
    $get_questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
    if(!is_array($get_questions) || !is_array($get_questions['ques']) || !is_array($get_questions['marks'])){
      _e('Questions not set.','vibe');
      die();
    }
    $answers = json_decode(stripslashes($_POST['answers']));
    $flag = apply_filters('bp_course_quiz_insert_query',1,$quiz_id,$answers);
    if($flag && !empty($answers)){
      foreach($answers as $answer){
        $values .= "(".$answer->id.",'".$answer->value."',".$user_id.",1),";
      }
      $finalvalues= rtrim($values,',');
      global $wpdb;
      $wpdb->query("INSERT INTO {$wpdb->comments}(comment_post_ID,comment_content,user_id,comment_approved) VALUES $finalvalues");
    }    

    update_user_meta($user_id,$quiz_id,time());
    update_post_meta($quiz_id,$user_id,0);

    do_action('wplms_submit_quiz',$quiz_id,$user_id);

    bp_course_quiz_auto_submit($quiz_id,$user_id);
    
    $get_message = trim(get_post_meta($quiz_id,'vibe_quiz_message',true));

    $course_id = get_post_meta($quiz_id,'vibe_quiz_course',true);
    $nextunit_access = vibe_get_option('nextunit_access');
    $flag = apply_filters('wplms_next_unit_access',true,$quiz_id);
    if(is_numeric($course_id) && $nextunit_access && $flag){
      $curriculum=bp_course_get_curriculum_units($course_id);
      $key = array_search($quiz_id,$curriculum);
      if($key <=(count($curriculum)-1) ){  // Check if not the last unit
        $key++;
        echo $curriculum[$key].'##';
      }
    }else{
        echo '##';
      }
    
    echo ' ';
    echo apply_filters('the_content',$get_message);
    die();
  }



// Single Quiz


/*==================================================================

      SINGLE QUIZ REVAMP

*/


add_action('wp_ajax_submit_quiz', 'submit_quiz');
if(!function_exists('submit_quiz')){
  function submit_quiz(){
    $quiz_id= $_POST['quiz_id'];
    $user_id = get_current_user_id();
    $access = get_user_meta($user_id,$quiz_id,true);
    if(!isset($access) || !is_numeric($access)){
      _e('Invalid submission time.','vibe');
       die();
    }
    $get_questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
    if(!is_array($get_questions) || !is_array($get_questions['ques']) || !is_array($get_questions['marks'])){
      _e('Questions not set.','vibe');
      die();
    }

    $answers = json_decode(stripslashes($_POST['answers']));
    $flag = apply_filters('bp_course_quiz_insert_query',1,$quiz_id,$answers);
    
    if($flag && !empty($answers)){
      $values = '';
      foreach($answers as $answer){
        $values .= "(".$answer->id.",'".$answer->value."',".$user_id.",1),";
      }
      $finalvalues= rtrim($values,',');
      global $wpdb;
      $wpdb->query("INSERT INTO {$wpdb->comments}(comment_post_ID,comment_content,user_id,comment_approved) VALUES $finalvalues");
    }

    update_user_meta($user_id,$quiz_id,time());
    update_post_meta($quiz_id,$user_id,0);

    do_action('wplms_submit_quiz',$quiz_id,$user_id);

    $course_id = get_post_meta($quiz_id,'vibe_quiz_course',true);

    if(!empty($course_id)){ // Course progressbar fix for single quiz
      
      $curriculum = bp_course_get_curriculum_units($course_id);
      $per = round((100/count($curriculum)),2);
      $progress = get_user_meta($user_id,'progress'.$course_id,true);
      if(empty($progress))
        $progress = 0;

      $new_progress = $progress+$per;
     
      if($new_progress > 100){
        $new_progress = 100;
      }
      update_user_meta($user_id,'progress'.$course_id,$new_progress);
      echo '<script>jQuery(document).ready(function(){jQuery.cookie("course_progress'.$course_id.'","'.$new_progress.'", { expires: 1 ,path: "/"});});</script>';
    }

    bp_course_quiz_auto_submit($quiz_id,$user_id);
    die();
  }
}

//BEGIN QUIZ
add_action('wp_ajax_begin_quiz', 'begin_quiz'); // Only for LoggedIn Users
if(!function_exists('begin_quiz')){
  function begin_quiz(){
      $id= $_POST['id'];
      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){

        $user_id = get_current_user_id();
        $quiztaken=get_user_meta($user_id,$id,true);
        

         if(!isset($quiztaken) || !$quiztaken){
            
            $quiz_duration_parameter = apply_filters('vibe_quiz_duration_parameter',60);
            $quiz_duration = get_post_meta($id,'vibe_duration',true) * $quiz_duration_parameter; // Quiz duration in seconds
            $expire=time()+$quiz_duration;
            add_user_meta($user_id,$id,$expire);
            
            $quiz_questions = vibe_sanitize(get_post_meta($id,'quiz_questions'.$user_id,false));
            if(!isset($quiz_questions) || !is_array($quiz_questions)){
                $quiz_questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',false));
                update_post_meta($id,'quiz_questions'.$user_id,$quiz_questions);
            } // Fallback for Older versions
              


            the_quiz('quiz_id='.$id.'&ques_id='.$quiz_questions['ques'][0]);

            echo '<script>var all_questions_json = '.json_encode($quiz_questions['ques']).'</script>';

            do_action('wplms_start_quiz',$id,$user_id);
         }else{

          if($quiztaken > time()){
            
            $quiz_questions = vibe_sanitize(get_post_meta($id,'quiz_questions'.$user_id,false));
            if(!isset($quiz_questions) || !is_array($quiz_questions)) // Fallback for Older versions
              $quiz_questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',false));
            
            the_quiz('quiz_id='.$id.'&ques_id='.$quiz_questions['ques'][0]);
            echo '<script>var all_questions_json = '.json_encode($quiz_questions['ques']).'</script>';
          }else{
            echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
            echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
          }
          
         }

     }else{
        echo '<h3>'.__('Quiz Already Attempted.','vibe').'</h3>'; 
        echo '<p>'.__('Security Check Failed. Contact Site Admin.','vibe').'</p>'; 
     }
     die();
  }  
}

// After begin quiz, get unmarked quesitons

add_action('wp_ajax_check_unanswered_questions','bp_course_check_unanswered_questions');
function bp_course_check_unanswered_questions(){
    $questions = json_decode(stripslashes($_POST['questions']));
    $user_id = get_current_user_id();
    $answers = array();
    foreach($questions as $question){
        global $wpdb; $question->id = intval($question->id);
        $val = $wpdb->get_var($wpdb->prepare("SELECT comment_content FROM {$wpdb->comments} WHERE comment_post_ID = %d AND user_id = %d AND comment_approved = %d",$question->id,$user_id,1));
        if(!empty($val))
          $answers[]=array('question_id'=>$question->id,'value'=>$val);
    }
    if(count($answers)){
       print_r(json_encode($answers));
    }
    die();
}

//BEGIN QUIZ
add_action('wp_ajax_quiz_question', 'quiz_question'); // Only for LoggedIn Users
if(!function_exists('quiz_question')){
  function quiz_question(){
      
      $quiz_id= $_POST['quiz_id'];
      $ques_id= $_POST['ques_id'];

      

      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){ // Same NONCE just for validation

        $user_id = get_current_user_id();
        $quiztaken=get_user_meta($user_id,$quiz_id,true);
        

         if(isset($quiztaken) && $quiztaken){
            if($quiztaken > time()){
                the_quiz('quiz_id='.$quiz_id.'&ques_id='.$ques_id);  
            }else{
              echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
        echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
            }
            
         }else{
            echo '<div class="message info"><h3>'.__('Start Quiz to begin quiz.','vibe').'</h3>'; 
            echo '<p>'.__('Click "Start Quiz" button to start the Quiz.','vibe').'</p></div>';
         }

     }else{
                echo '<div class="message error"><h3>'.__('Quiz not active.','vibe').'</h3>'; 
                echo '<p>'.__('Contact your instructor or site admin.','vibe').'</p></div>';
     }
     die();
  }  
}

add_action('wp_ajax_continue_quiz', 'continue_quiz'); // Only for LoggedIn Users

if(!function_exists('continue_quiz')){
  function continue_quiz(){
      $user_id = get_current_user_id();
      $quiztaken=get_user_meta($user_id,get_the_ID(),true);
      
      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){ // Same NONCE just for validation
       if(isset($quiztaken) && $quiztaken && $quiztaken > time()){
          $questions = vibe_sanitize(get_post_meta($id,'quiz_questions'.$user_id,false));
            if(!isset($questions) || !is_array($questions))  
            $questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',false));

          the_quiz('quiz_id='.get_the_ID().'&ques_id='.$questions['ques'][0]);  
          
       }else{
         echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
        echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
       }
     }else{
          echo '<div class="message error"><h3>'.__('Quiz Already Attempted .','vibe').'</h3>'; 
          echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>'; 
     }
      die();
  }  
}

// In course Quiz

add_action('wp_ajax_in_start_quiz', 'incourse_start_quiz');

function incourse_start_quiz(){
  $quiz_id= $_POST['quiz_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($quiz_id)){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }

  $user_id = get_current_user_id();
  
  do_action('wplms_before_quiz_begining',$quiz_id);

  $get_questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));

  if(!isset($get_questions) || !is_array($get_questions)){
    $get_questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
    update_post_meta($quiz_id,'quiz_questions'.$user_id,$get_questions);
  } // Fallback for Older versions
    


  if(!is_array($get_questions) || !is_array($get_questions['ques']) || !is_array($get_questions['marks'])){
    _e('Questions not set.','vibe');
    die();
  }


  $questions=$get_questions['ques'];
  $marks=$get_questions['marks'];
  $posts_per_page = apply_filters('wplms_incourse_quiz_per_page',10);
  $page = $_POST['page'];


  if(!isset($page) || !is_numeric($page) || !$page){
    $page = 1;
   // Add user to quiz : Quiz attempted by user
    update_post_meta($quiz_id,$user_id,0);
    $quiz_duration_parameter = apply_filters('vibe_quiz_duration_parameter',60);
    $quiz_duration = get_post_meta($quiz_id,'vibe_duration',true) * $quiz_duration_parameter; // Quiz duration in seconds
    $expire=time()+$quiz_duration;
    update_user_meta($user_id,$quiz_id,$expire);
    do_action('wplms_start_quiz',$quiz_id,$user_id);
    // Start Quiz Notifications
  }

  $args = apply_filters('wplms_in_course_quiz_args',array('post__in' => $questions,'post_type'=>'question','posts_per_page'=>$posts_per_page,'paged'=>$page,'orderby'=>'post__in'));

  $the_query = new WP_Query($args);

  $quiz_questions = array();

  if ( $the_query->have_posts() ) {
    echo '<script>var all_questions_json = '.json_encode($questions).'</script>';
    while ( $the_query->have_posts() ) {
      $the_query->the_post();
      global $post;
      $loaded_questions[]=get_the_ID();
      $key = array_search(get_the_ID(),$questions);
      $hint = get_post_meta(get_the_ID(),'vibe_question_hint',true);
      $type = get_post_meta(get_the_ID(),'vibe_question_type',true);

      echo '<div class="in_question " data-ques="'.$post->ID.'">';
      echo '<i class="marks">'.(isset($marks[$key])?'<i class="icon-check-5"></i>'.$marks[$key]:'').'</i>';
      echo '<div class="question '.$type.'">';
      the_content();
      if(isset($hint) && strlen($hint)>5){
        echo '<a class="show_hint tip" tip="'.__('SHOW HINT','vibe').'"><span></span></a>';
        echo '<div class="hint"><i>'.__('HINT','vibe').' : '.apply_filters('the_content',$hint).'</i></div>';
      }
      echo '</div>';
      switch($type){
        case 'truefalse': 
        case 'single': 
        case 'multiple': 
        case 'sort':
        case 'match':
           $options = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_question_options',false));

          if($type == 'truefalse')
            $options = array( 0 => __('FALSE','vibe'),1 =>__('TRUE','vibe'));

          if(isset($options) || $options){

            $answers=get_comments(array(
              'post_id' => $post->ID,
              'status' => 'approve',
              'user_id' => $user_id
              ));


            if(isset($answers) && is_array($answers) && count($answers)){
                $answer = reset($answers);
                $content = explode(',',$answer->comment_content);
            }else{
                $content=array();
            }
        
            echo '<ul class="question_options '.$type.'">';
              if($type=='single'){
                foreach($options as $key=>$value){

                  echo '<li>
                            <input type="radio" id="'.$post->post_name.$key.'" class="ques'.$post->ID.'" name="'.$post->ID.'" value="'.($key+1).'" '.(in_array(($key+1),$content)?'checked':'').'/>
                            <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                        </li>';
                }
              }else if($type == 'sort'){
                foreach($options as $key=>$value){
                  echo '<li id="'.($key+1).'" class="ques'.$post->ID.' sort_option">
                              <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                          </li>';
                }        
              }else if($type == 'match'){
                foreach($options as $key=>$value){
                  echo '<li id="'.($key+1).'" class="ques'.$post->ID.' match_option">
                              <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                          </li>';
                }        
              }else if($type == 'truefalse'){
                foreach($options as $key=>$value){
                  echo '<li>
                            <input type="radio" id="'.$post->post_name.$key.'" class="ques'.$post->ID.'" name="'.$post->ID.'" value="'.$key.'" '.(in_array($key,$content)?'checked':'').'/>
                            <label for="'.$post->post_name.$key.'"><span></span> '.$value.'</label>
                        </li>';
                }       
              }else{
                foreach($options as $key=>$value){
                  echo '<li>
                            <input type="checkbox" class="ques'.$post->ID.'" id="'.$post->post_name.$key.'" name="'.$post->ID.$key.'" value="'.($key+1).'" '.(in_array(($key+1),$content)?'checked':'').'/>
                            <label for="'.$post->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
                        </li>';
                }
              }  
            echo '</ul>';
          }
        break; // End Options
        case 'fillblank': 
        break;
        case 'select': 
        break;
        case 'smalltext': 
          echo '<input type="text" name="'.$k.'" class="ques'.$k.' form_field" value="'.($content?$content:'').'" placeholder="'.__('Type Answer','vibe').'" />';
        break;
        case 'largetext': 
          echo '<textarea name="'.$k.'" class="ques'.$k.' form_field" placeholder="'.__('Type Answer','vibe').'">'.($content?$content:'').'</textarea>';
        break;
      }
      echo '</div>';
    }
     $count = count($questions);
      if($posts_per_page < $count){
          echo '<div class="pagination"><label>'.__('PAGES','vibe').'</label>
          <ul>';
          $max =  $count/$posts_per_page;
          if(($count%$posts_per_page)){
            $max++;
          }
          for($i=1;$i<=$max;$i++){
             if($page == $i){
              echo '<li><span>'.$i.'</span></li>';
             }else{
              echo '<li><a class="quiz_page">'.$i.'</a><li>';
             }
          }
          echo '</ul>
          </div>';
      } 
    echo '<script>var questions_json = '.json_encode($loaded_questions).'</script>';
  }
  wp_reset_postdata();  
  die();
}

add_action('wp_ajax_retake_inquiz','wplms_retake_inquiz');
function wplms_retake_inquiz(){
   $quiz_id= $_POST['quiz_id'];
    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($quiz_id)){
       _e('Security check Failed. Contact Administrator.','vibe');
       die();
    }
    student_quiz_retake(array('quiz_id'=>$quiz_id));
    die();
}

// Notes & Discussion Unit comments :

add_action('wp_ajax_add_unit_comment','wplms_add_unit_comment');
function wplms_add_unit_comment(){
  $unit_id= $_POST['unit_id'];
  $content = $_POST['text'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($unit_id)){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }
  if(strlen($content) < 2){
    echo '<div class="message">'.__('Unable to add message','vibe').'</div>';
    die();
  }

  $time = current_time('mysql');
  $user_id = get_current_user_id();
  $args = array(
    'comment_post_ID' => $unit_id,
    'user_id' => $user_id,
    'comment_type' => 'public',
    'comment_content' => $content,
    'comment_date' => $time,
    'comment_approved' => 1,
    );
  if(isset($_POST['parent']) && is_numeric($_POST['parent'])){
    $args['comment_parent']=$_POST['parent'];
  }
  $comment_id = wp_new_comment($args);
  global $wpdb;
  $approved_status = $wpdb->get_var("SELECT comment_approved FROM {$wpdb->comments} WHERE comment_ID = $comment_id");
  if(is_numeric($comment_id)){

      echo '<li class="note byuser zoom load" id="comment-'.$comment_id.'">
            <div id="div-comment-'.$comment_id.'" class="comment-body '.(empty($approved_status)?'unapproved':'approved').'">
            <div class="comment-author vcard">
              '.bp_core_fetch_avatar( array(
              'item_id' => $user_id,
              'type' => 'thumb',
              )).'
              <div class="comment-meta commentmetadata">
              <a href="'.get_permalink($unit_id).'">'.__('JUST NOW','vibe').'</a>  </div>
              <cite class="fn">'.bp_core_get_user_displayname($user_id).'</cite>  </div>
              <p>'.do_shortcode($content).'</p>
            </div>
          </li>';   
      
      do_action('wplms_course_unit_comment',$unit_id,$user_id,$comment_id);     
      
  }else{
    echo '<div class="message">'.__('Unable to add message','vibe').'</div>';
  }
  die();
}

add_action('wp_ajax_load_unit_comments','wplms_load_unit_comments');
function wplms_load_unit_comments(){
  $unit_id= $_POST['unit_id'];
  $page= $_POST['page'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !is_numeric($unit_id) || !is_numeric($page)){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }    
  $per_page = get_option('comments_per_page');
  if(!is_numeric($per_page))
    $per_page = 5;
  
  $offset = $per_page*$page;

  //Gather comments for a specific page/post 
  $comments = get_comments(array(
      'post_id' => $unit_id,
      'status' => 'approve',
      'offset' => $offset,
      //'number' => $per_page,
  ));

  //Display the list of comments
  wp_list_comments(array(
      'page' => ($page+1),
      'per_page' => $per_page, //Allow comment pagination
      'avatar_size' => 120,
      'callback' => 'wplms_unit_comment' //Show the latest comments at the top of the list
  ), $comments);
          

  die();
}

// BuddyPress Star Fix 

add_action('wp_ajax_messages_star','bp_course_messages_star');
function bp_course_messages_star(){
  if(function_exists('bp_messages_star_set_action') && is_numeric($_POST['message_id']) && in_array($_POST['star_status'],array('star','unstar'))){
    echo bp_messages_star_set_action(array(
        'action'     => $_POST['star_status'],
        'message_id' => $_POST['message_id'],
        'bulk'       => false
    ));
  }
  die();
}
?>
