<?php

/**
 * Check to see if a high five is being given, and if so, save it.
 *
 * Hooked to bp_actions, this function will fire before the screen function. We use our function
 * bp_is_course_component(), along with the bp_is_current_action() and bp_is_action_variable()
 * functions, to detect (based on the requested URL) whether the user has clicked on "send high
 * five". If so, we do a bit of simple logic to see what should happen next.
 *
 * @package BuddyPress_Course_Component
 * @since 1.6
 */


add_action('bp_activity_register_activity_actions','bp_course_register_actions');
function bp_course_register_actions(){
	global $bp; 
	$bp_course_action_desc=array(
		'remove_from_course' => __( 'Removed a student from Course', 'vibe' ),
		'submit_course' => __( 'Student submitted a Course', 'vibe' ),
		'start_course' => __( 'Student started a Course', 'vibe' ),
		'submit_quiz' => __( 'Student submitted a Quiz', 'vibe' ),
		'start_quiz' => __( 'Student started a Course', 'vibe' ),
		'unit_complete' => __( 'Student submitted a Course', 'vibe' ),
		'reset_course' => __( 'Course reset for Student', 'vibe' ),
		'bulk_action' => __( 'Bulk action by instructor', 'vibe' ),
		'course_evaluated' => __( 'Course Evaluated for student', 'vibe' ),
		'student_badge'=> __( 'Student got a Badge', 'vibe' ),
		'student_certificate' => __( 'Student got a certificate', 'vibe' ),
		'quiz_evaluated' => __( 'Quiz Evaluated for student', 'vibe' ),
		'subscribe_course' => __( 'Student subscribed for course', 'vibe' ),
		);
	foreach($bp_course_action_desc as $key => $value){
		bp_activity_set_action($bp->activity->id,$key,$value);	
	}
}

add_filter( 'woocommerce_get_price_html', 'course_subscription_filter',100,2 );
function course_subscription_filter($price,$product){

	$subscription=get_post_meta($product->id,'vibe_subscription',true);

		if(vibe_validate($subscription)){
			$duration = intval(get_post_meta($product->id,'vibe_duration',true));

			$product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400);
			$t=$duration * $product_duration_parameter;
			if($duration == 1){
				$price = $price .'<span class="subs"> '.__('per','vibe').' '.tofriendlytime($t,1).'</span>';
			}else{
				$price = $price .'<span class="subs"> '.__('per','vibe').' '.tofriendlytime($t,1).'</span>';
			}
		}
		return $price;
}




add_action('woocommerce_after_add_to_cart_button','bp_course_subscription_product');
function bp_course_subscription_product(){
	global $product;
	$check_susbscription=get_post_meta($product->id,'vibe_subscription',true);
	if(vibe_validate($check_susbscription)){
		$duration=intval(get_post_meta($product->id,'vibe_duration',true));	
		
		$product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400);
		$t=tofriendlytime($duration*$product_duration_parameter);
		echo '<div id="duration"><strong>'.__('SUBSCRIPTION FOR','vibe').' '.$t.'</strong></div>';
	}
}
//woocommerce_order_status_completed

add_action( 'woocommerce_order_status_completed', 'bp_course_convert_customer_to_student' );
function bp_course_convert_customer_to_student( $order_id ) {
    $order = new WC_Order( $order_id );
    if ( $order->user_id > 0 ) {
        $user = new WP_User( $order->user_id );
        $user->remove_role( 'customer' ); 
        $user->add_role( 'student' );
    }
}


add_action('woocommerce_order_status_completed','bp_course_enable_access');
function bp_course_enable_access($order_id){

	$order = new WC_Order( $order_id );

	$items = $order->get_items();
	$user_id=$order->user_id;
	$order_total = $order->get_total();
	$commission_array=array();

	foreach($items as $item_id=>$item){

	$instructors=array();
	
	$courses=get_post_meta($item['product_id'],'vibe_courses',true);
	$product_id = apply_filters('bp_course_product_id',$item['product_id'],$item);
	$subscribed=get_post_meta($product_id,'vibe_subscription',true);

	if(isset($courses) && is_array($courses)){

		if(vibe_validate($subscribed) ){
			$duration=get_post_meta($product_id,'vibe_duration',true);
			foreach($courses as $course){
				bp_course_add_user_to_course($user_id,$course,$duration,1);
		        $instructors[$course]=apply_filters('wplms_course_instructors',get_post_field('post_author',$course),$course);
		        do_action('wplms_course_product_puchased',$course,$user_id,$t,1);
			}
		}else{	
			if(isset($courses) && is_array($courses)){
			foreach($courses as $course){
					bp_course_add_user_to_course($user_id,$course,'',1);
	        		$instructors[$course]=apply_filters('wplms_course_instructors',get_post_field('post_author',$course,'raw'),$course);
		        	do_action('wplms_course_product_puchased',$course,$user_id,$t,0);
				}
			}
		}//End Else

			$line_total=$item['line_total'];

		//Commission Calculation
		$commission_array[$item_id]=array(
			'instructor'=>$instructors,
			'course'=>$courses,
			'total'=>$line_total,
		);

	  }//End If courses
	}// End Item for loop
	
	if(function_exists('vibe_get_option'))
      $instructor_commission = vibe_get_option('instructor_commission');
    
    if($instructor_commission == 0)
      		return;
      	
    if(!isset($instructor_commission) || !$instructor_commission)
      $instructor_commission = 70;

    $commissions = get_option('instructor_commissions');

	foreach($commission_array as $item_id=>$commission_item){

			foreach($commission_item['course'] as $course_id){ 
			
			if(count($commission_item['instructor'][$course_id]) > 1){     // Multiple instructors
				
				$calculated_commission_base=round(($commission_item['total']*($instructor_commission/100)/count($commission_item['instructor'][$course_id])),0); // Default Slit equal propertion

				foreach($commission_item['instructor'][$course_id] as $instructor){
					if(empty($commissions[$course_id][$instructor])){
						$calculated_commission_base = round(($commission_item['total']*$instructor_commission/100),2);
					}else{
						$calculated_commission_base = round(($commission_item['total']*$commissions[$course_id][$instructor]/100),2);
					}
					$calculated_commission_base = apply_filters('wplms_calculated_commission_base',$calculated_commission_base,$instructor);
					woocommerce_update_order_item_meta( $item_id, 'commission'.$instructor,$calculated_commission_base);
				}
			}else{
				if(is_array($commission_item['instructor'][$course_id]))                                    // Single Instructor
					$instructor=$commission_item['instructor'][$course_id][0];
				else
					$instructor=$commission_item['instructor'][$course_id]; 
				
				if(isset($commissions[$course_id][$instructor]) && is_numeric($commissions[$course_id][$instructor]))
					$calculated_commission_base = round(($commission_item['total']*$commissions[$course_id][$instructor]/100),2);
				else
					$calculated_commission_base = round(($commission_item['total']*$instructor_commission/100),2);

				$calculated_commission_base = apply_filters('wplms_calculated_commission_base',$calculated_commission_base,$instructor);
				woocommerce_update_order_item_meta( $item_id, 'commission'.$instructor,$calculated_commission_base);
			}   
		}

	} // End Commissions_array  
}

add_action('woocommerce_order_status_cancelled','bp_course_disable_access',10,1);
add_action('woocommerce_order_status_refunded','bp_course_disable_access',10,1);

function bp_course_disable_access($order_id){
	$order = new WC_Order( $order_id );

	$items = $order->get_items();
	$user_id=$order->user_id;
	foreach($items as $item){
		$product_id = $item['product_id'];
		$subscribed=get_post_meta($product_id,'vibe_subscription',true);
		$courses=vibe_sanitize(get_post_meta($product_id,'vibe_courses',false));

			if(isset($courses) && is_array($courses)){
			foreach($courses as $course){
				delete_post_meta($course,$user_id);
				delete_user_meta($user_id,$course);
				$group_id=get_post_meta($course,'vibe_group',true);

				if(isset($group_id) && function_exists('groups_remove_member'))
					groups_remove_member($user_id,$group_id);
				else
					$group_id = '';

				$instructors = apply_filters('wplms_course_instructors',get_post_field('post_author',$course,'raw'),$course);
				if(is_array($instructors)){
					foreach($instructors as $instructor){
						woocommerce_update_order_item_meta( $item_id, 'commission'.$instructor,0);//Nulls the commission
					}
				}
			    	do_action('wplms_course_unsubscribe',$course_id,$user_id,$group_id);
				}
			}
		} 
}

add_action('bp_members_directory_member_types','bp_course_instructor_member_types');

function bp_course_instructor_member_types(){
	?>
		<li id="members-instructors"><a href="#"><?php printf( __( 'All Instructors <span>%s</span>', 'vibe' ), bp_get_total_instructor_count() ); ?></a></li>
	<?php
}


add_filter('bp_course_admin_before_course_students_list','bp_course_admin_search_course_students',10,2);
function bp_course_admin_search_course_students($students,$course_id){

	$course_statuses = apply_filters('wplms_course_status_filters',array(
		0 => __('Expired Course','vibe'),
		1 => __('Start Course','vibe'),
		2 => __('Continue Course','vibe'),
		3 => __('Under Evaluation','vibe'),
		4 => __('Course Finished','vibe')
		));
	echo '<form method="get" action="">
			<input type="hidden" value="admin" name="action">
			'.(isset($_GET['item_page'])?'<input type="hidden" name="item_page" value="'.$_GET['item_page'].'">':'').'
			<select name="status"><option value="">'.__('Filter by Status','vibe').'</option>';
			foreach($course_statuses as $key =>$value){
				echo '<option value="'.$key.'" '.selected($_GET['status'],$key).'>'.$value.'</option>';
			}
	echo  '</select>';
	do_action('wplms_course_admin_form',$students,$course_id);
	echo '<input type="text" name="search" value="'.$_GET['search'].'" placeholder="'.__('Enter student name/email','vibe').'" class="input" />
			<input type="submit" value="'.__('Search','vibe').'" />
		 </form>';
    if(isset($_GET['search']) && $_GET['search']){
    	$args = array(
			'search'         => $_GET['search'],
			'search_columns' => array( 'login', 'email','nicename'),
			'fields' => array('ID'),
			'meta_query' => array(
				array(
					'key' => $course_id,
					'compare' => 'EXISTS'
					)
				),
		);
    	$user_query = new WP_User_Query( $args );
    	$users = $user_query->get_results();

		if(count($users)){
			$students=array();
			foreach($users as $user){
				if(is_object($user) && isset($user->ID))
					$students[]=$user->ID;
			}
		}
    }
	return $students;
}

if(function_exists('wplms_show_course_student_status')){
	add_filter('wplms_course_credits','wplms_show_new_course_student_status',20,2);
	function wplms_show_new_course_student_status($credits,$course_id){

	  if(is_user_logged_in() && !is_singular('course')){
	    $user_id=get_current_user_id();
	    $check=get_user_meta($user_id,$course_id,true);
	    if(isset($check) && $check){
	      if($check < time()){
	        return '<strong>'.__('EXPIRED','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong>';
	      }

	      $check_course= bp_course_get_user_course_status($user_id,$course_id);
	      $new_check_course = get_user_meta($user_id,'course_status'.$course_id,true);
	      if(isset($new_check_course) && is_numeric($new_check_course) && $new_check_course){
	  	      switch($check_course){
		        case 1:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('START','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
		        break;
		        case 2:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('CONTINUE','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
		        break;
		        case 3:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('UNDER','vibe').'<span class="subs">'.__('EVALUATION','vibe').'</span></strong></a>';
		        break;
		        case 4:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('FINISHED','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
		        break;
		        default:
		        $credits =apply_filters('wplms_course_status_display','<a href="'.get_permalink($course_id).'"><strong>'.__('COURSE','vibe').'<span class="subs">'.__('ENABLED','vibe').'</span></strong></a>',$course_id);
		        break;
		      }
	      }else{
	      		switch($check_course){
		        case 0:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('START','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
		        break;
		        case 1:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('CONTINUE','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
		        break;
		        case 2:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('UNDER','vibe').'<span class="subs">'.__('EVALUATION','vibe').'</span></strong></a>';
		        break;
		        default:
		        $credits ='<a href="'.get_permalink($course_id).'"><strong>'.__('FINISHED','vibe').'<span class="subs">'.__('COURSE','vibe').'</span></strong></a>';
		        break;
		      }	
	      }
	    }
	  }

	  return $credits;
	}
}

add_action('wplms_before_start_course','wplms_before_start_course_status');
function wplms_before_start_course_status(){
  $user_id = get_current_user_id();  
  
  if ( isset($_POST['start_course']) && wp_verify_nonce($_POST['start_course'],'start_course'.$user_id) ){
      $course_id=$_POST['course_id'];
      $coursetaken=1;
      $cflag=0;
      $precourse=get_post_meta($course_id,'vibe_pre_course',true);

      if(!empty($precourse)){
          if(is_numeric($precourse)){
          	$preid=bp_course_get_user_course_status($user_id,$precourse);
          	if(!empty($preid) && $preid > 2){ 
	            // COURSE STATUSES : Since version 1.8.4
	            // 1 : START COURSE
	            // 2 : CONTINUE COURSE
	            // 3 : FINISH COURSE : COURSE UNDER EVALUATION
	            // 4 : COURSE EVALUATED
	              $cflag=1;
	          }
          }else if(is_array($precourse)){
          	foreach($precourse as $pc){
          		$preid=bp_course_get_user_course_status($user_id,$pc);
	          	if(!empty($preid) && $preid > 2){ 
		              $cflag=1;
		        }else{
		        	//Break from loop
		        	break;
		        }
          	}
          }
      }else{
          $cflag=1;
      }

      if($cflag){
          
          $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
          $expire=time()+$course_duration_parameter; // One Unit logged in Limit for the course
          setcookie('course',$course_id,$expire,'/');
          bp_course_update_user_course_status($user_id,$course_id,1);//Since version 1.8.4
          do_action('wplms_start_course',$course_id,$user_id);
      }else{
          
          header('Location: ' . get_permalink($course_id) . '?error=precourse');
          
      }

    

  }else if ( isset($_POST['continue_course']) && wp_verify_nonce($_POST['continue_course'],'continue_course'.$user_id) ){
    $course_id=$_POST['course_id'];
    $coursetaken=get_user_meta($user_id,$course_id,true);
      setcookie('course',$course_id,$expire,'/');
  }else{
    if(isset($_COOKIE['course'])){
      $course_id=$_COOKIE['course'];
      $coursetaken=1;
    }else
      wp_die( __('This Course can not be taken. Contact Administrator.','vibe'), 'Contact Admin', array(500,true) );
  }

}

add_action('wplms_user_course_stats','add_course_review_button',10,2);
function add_course_review_button($user_id,$course_id){
	global $wpdb;
	$check = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_post_ID=%d AND user_id=%d",$course_id,$user_id));
	if(empty($check))
	echo '<form action="'.get_permalink($course_id).'" method="post">
 	<button type="submit" style="float: right;padding: 5px 7px 0 7px;" name="review_course" class="review_course tip" title="'.__('REVIEW COURSE ','vibe').'"/><i class="icon-comment tip" style="color:#666;font-size: 24px;"></i></button>'.wp_nonce_field($course_id,'review').'</form>';
}


add_action('bp_course_header_actions','bp_course_schema');
function bp_course_schema(){
	global $post;
	$key = 'course_microdata'.$post->ID;

	if(!isset($_GET['clear'])){
		$meta = get_transient( $key );
	}

	if(empty($meta) || false === $meta){
		ob_start();
		?> 
		<div itemscope itemtype="http://schema.org/Product">
	    	<meta itemprop="brand" content="<?php echo get_bloginfo( 'name', 'display' ); ?>" /> 
			<meta itemprop="name" content="<?php echo $post->post_title; ?>"/> 
			<meta itemprop="description" content="<?php echo strip_tags(get_the_excerpt()); ?>"/> 
			<?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID,'full') );?>
			<meta itemprop="image" content="<?php echo $url; ?>"/> 
			<?php
				$rating = get_post_meta(get_the_ID(),'average_rating',true);
				$count = get_post_meta(get_the_ID(),'rating_count',true);
				if(!empty($rating)){
			?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> 
				<meta itemprop="ratingValue" content="<?php echo $rating; ?>"/> <meta itemprop="reviewCount" content="<?php echo $count; ?>"/> 
			</span>
			<?php
				}
			if(function_exists('get_woocommerce_currency')){
				$product_id = get_post_meta(get_the_ID(),'vibe_product',true);
				if(!empty($product_id)){
					$sale = get_post_meta( $product_id, '_sale_price', true);
					$price = get_post_meta( $product_id, '_regular_price', true);
					$currency = get_woocommerce_currency();
				?>
				<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<meta itemprop="price" content="<?php echo (empty($sale)?$price:$sale); ?>" />
					<meta itemprop="priceCurrency" content="<?php echo (empty($currency)?'USD':$currency); ?>" />
				</span>
				<?php
				}
			}
			?>
		</div>
		<?php
		$meta = ob_get_clean();
		set_transient( $key , $meta, 24 * HOUR_IN_SECONDS );
	}
	echo $meta;
}