<?php

/**
 * In this file you'll want to add filters to the template tag output of your component.
 * You can use any of the built in WordPress filters, and you can even create your
 * own filter functions in this file.
 */

 /**
  * Some WP filters you may want to use:
  *  - wp_filter_kses() VERY IMPORTANT see below.
  *  - wptexturize()
  *  - convert_smilies()
  *  - convert_chars()
  *  - wpautop()
  *  - stripslashes_deep()
  *  - make_clickable()
  */

/**
 * --- NOTE ----
 * It's very very important that you use the wp_filter_kses() function to filter all
 * input AND output in your plugin. This will stop users adding malicious scripts and other
 * bad things onto any page.
 */

/**
 * In all your template tags that output data, you should have an apply_filters() call, you can
 * then use those filters to automatically add the wp_filter_kses() call.
 * The third parameter "1" adds the highest priority to the filter call.
 */


add_action('wp_ajax_course_filter','course_filter');
add_action('wp_ajax_nopriv_course_filter','course_filter');
function course_filter(){
	global $bp;

	$args=array('post_type' => BP_COURSE_CPT);
	if(isset($_POST['filter'])){
		$filter = $_POST['filter'];
		switch($filter){
			case 'popular':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'vibe_students';
			break;
			case 'newest':
				$args['orderby'] = 'date';
			break;
			case 'rated':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'average_rating';
			break;
			case 'alphabetical':
				$args['orderby'] = 'title';
				$args['order'] = 'ASC';
			break;
			case 'start_date':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'vibe_start_date';
				$args['meta_type'] = 'DATE';
				$args['order'] = 'ASC';
			break;
			default:
				$args['orderby'] = '';
			break;
		}
	}

	if(isset($_POST['search_terms']) && $_POST['search_terms'])
		$args['search_terms'] = $_POST['search_terms'];

	if(isset($_POST['page']))
		$args['paged'] = $_POST['page'];

	if(isset($_POST['scope']) && $_POST['scope'] == 'personal'){
		$uid=get_current_user_id();
		$args['meta_query'] = array(
			array(
				'key' => $uid,
				'compare' => 'EXISTS'
				)
			);
	}

	if(isset($_POST['scope']) && $_POST['scope'] == 'instructor'){
		$uid=get_current_user_id();
		$args['instructor'] = $uid;
	}

	if(isset($_POST['extras'])){

		$extras = json_decode(stripslashes($_POST['extras']));
		$course_categories=array();
		$course_levels=array();
		$course_location=array();
		$type=array();
		if(is_array($extras)){
			foreach($extras as $extra){
				switch($extra->type){
					case 'course-cat':
						$course_categories[]=$extra->value;
					break;
					case 'free':
						$type=$extra->value;
					break;
					case 'instructor':
						$instructors[]=$extra->value;
					break;
					case 'level':
						$course_levels[]=$extra->value;
					break;
					case 'location':
						$course_location[]=$extra->value;
					break;
					case 'start_date':
						$start_date = $extra->value;;
					break;
					case 'end_date':
						$end_date = $extra->value;;
					break;
				}
			}
		}
		
		$args['tax_query']=array();
		if(count($course_categories)){
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][]=array(
								'taxonomy' => 'course-cat',
								'terms'    => $course_categories,
								'field'    => 'slug',
							);
		}
		if(count($instructors)){
			$args['author__in']=$instructors;
		}
		if($type){
			switch($type){
				case 'free':
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][]=array(
					'key' => 'vibe_course_free',
					'value' => 'S',
					'compare'=>'='
				);
				break;
				case 'paid':
				$args['meta_query']['relation'] = 'AND';
				$args['meta_query'][]=array(
					'key' => 'vibe_course_free',
					'value' => 'H',
					'compare'=>'='
				);
				break;
			}
		}
		if(!empty($start_date)){
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][]=array(
				'key' => 'vibe_start_date',
				'value' => $start_date,
				'compare'=>'>='
			);
		}
		if(!empty($end_date)){
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][]=array(
				'key' => 'vibe_start_date',
				'value' => $end_date,
				'compare'=>'<='
			);
		}
		if(count($course_levels)){
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][]=array(
									'taxonomy' => 'level',
									'field'    => 'slug',
									'terms'    => $course_levels,
								);
		}
		if(count($course_location)){
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][]=array(
									'taxonomy' => 'location',
									'field'    => 'slug',
									'terms'    => $course_location,
								);
		}

	}


$loop_number=vibe_get_option('loop_number');
isset($loop_number)?$loop_number:$loop_number=5;

$args['per_page'] = $loop_number;

?>

<?php do_action( 'bp_before_course_loop' ); ?>

<?php 
if ( bp_course_has_items( $args ) ) : ?>

	<div id="pag-top" class="pagination ">

		<div class="pag-count" id="course-dir-count-top">

			<?php bp_course_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="course-dir-pag-top">

			<?php bp_course_item_pagination(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_course_list' );

		$cookie=urldecode($_POST['cookie']);
		if(stripos($cookie,'bp-course-list=grid')){
			$class='grid';
		}
	?>
	<ul id="course-list" class="item-list <?php echo apply_filters('wplms_course_directory_list',$class); ?>" role="main">

	<?php while ( bp_course_has_items() ) : bp_course_the_item(); ?>

			<?php 
			global $post;
			$cache_duration = vibe_get_option('cache_duration'); if(!isset($cache_duration)) $cache_duration=86400;
			if($cache_duration){
				$course_key= 'course_'.$post->ID;
				if(is_user_logged_in()){
					$user_id = get_current_user_id();
					$user_meta = get_user_meta($user_id,$post->ID,true);
					if(isset($user_meta)){
						$course_key= 'course_'.$user_id.'_'.get_the_ID();
					}
				}
				$result = wp_cache_get($course_key,'course_loop');
			}else{$result=false;}

			if ( false === $result) {
				ob_start();
				bp_course_item_view();
				$result = ob_get_clean();
			}
			if($cache_duration)
			wp_cache_set( $course_key,$result,'course_loop',$cache_duration);
			echo $result;
			?>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_course_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="course-dir-count-bottom">

			<?php bp_course_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="course-dir-pag-bottom">

			<?php bp_course_item_pagination(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No Courses found.', 'vibe' ); ?></p>
	</div>

<?php endif;  ?>


<?php do_action( 'bp_after_course_loop' ); ?>
<?php

	die();
}


add_action('bp_ajax_querystring','filtering_instructor_custom',20,2);
function filtering_instructor_custom($qs=false,$object=false){
 	//list of users to exclude
 	if($object!='members')//hide for members only
	 return $qs;
	 
	 $args=array('role' => 'Instructor','fields' => 'ID');
	 $users = new WP_User_Query($args);

	 $included_user = implode(',',$users->results);
	 //$included_user='1,2,3';//comma separated ids of users whom you want to exclude
	 
	 $args=wp_parse_args($qs);
	 if(!isset($args['scope']) || $args['scope'] != 'instructors')
	 	return $qs;
	 //check if we are searching  or we are listing friends?, do not exclude in this case
	 if(!empty($args['user_id'])||!empty($args['search_terms']))
	 return $qs;
	 
	 if(!empty($args['include']))
	 $args['include']=$args['include'].','.$included_user;
	 else
	 $args['include']=$included_user;


	 $qs=build_query($args);
	 
	 return $qs;
 
}


add_filter('bp_ajax_querystring','bp_course_ajax_querystring',20,2);
function bp_course_ajax_querystring($string,$object){

	if(function_exists('vibe_get_option'))
		$loop_number=vibe_get_option('loop_number');
	
	if(!isset($loop_number) || !is_numeric($loop_number))
		$loop_number=5;

	
	$appended = '&per_page='.$loop_number;
	if($object == 'activity'){
		$appended = apply_filters('wplms_activity_loop',$appended);
	}

	$string .=$appended;
	global $bp; 
	
	if(is_singular('course')){
		global $post;
		$course_activity .='&primary_id='.$post->ID;
		if($_GET['student'] && is_numeric($_GET['student']))
			$course_activity .= '&user_id='.$_GET['student'];

		$string .=$course_activity;
	}
	

	if($object != BP_COURSE_SLUG)
		return $string;

	$course_filters = $_COOKIE["bp-course-filter"];
	$course_extras=$_COOKIE["bp-course-extras"];
	$course_scope=$_COOKIE["bp-course-scope"];

	if(isset($course_filters)){
		$string.='&filters='.$course_filters;
	}

	if(isset($course_extras)){
		$string.='&extras='.$course_extras;
	}
	if(isset($course_scope)){
		$string.='&scope='.$course_scope;
	}

	return $string;
}

add_filter('wplms_course_product_id','wplms_expired_course_product_id',10,3);
function wplms_expired_course_product_id($pid,$course_id,$status){
	if($status == -1){ // Expired course
		$free = get_post_meta($course_id,'vibe_course_free',true);
		if(vibe_validate($free)){
			$pid = get_permalink($course_id).'?renew';
		}
	}
	return $pid;
}

add_action('wplms_course_before_front_main','wplms_renew_free_course');
function wplms_renew_free_course(){

	global $post; 
	if(!is_user_logged_in())
		return;

	$course_id = get_the_ID();
	$user_id = get_current_user_id();
	$expiry = get_user_meta($user_id,$course_id,true);
	
	if($expiry > time())
		return;


	$free = get_post_meta($course_id,'vibe_course_free',true);
	if(vibe_validate($free) && isset($_REQUEST['renew'])){
		$course_duration = get_post_meta($course_id,'vibe_duration',true);
		$course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
		$new_expiry = time() + $course_duration*$course_duration_parameter;
		update_user_meta($user_id,$course_id,$new_expiry);
		
	    do_action('wplms_renew_course',$course_id,$user_id);
	}
}
/*
add_action('wp_ajax_instructors_filter','instructors_filter');
add_action('wp_ajax_no_priv_instructors_filter','instructors_filter');
function instructors_filter($query){
	global $bp;
	$args=array('role' => 'Instructor','fields' => 'ID');
	$users = new WP_User_Query($args);
	$query_array->query_vars['user_ids'] = $users->results;

	return $query_array;
	die();
}*/
//  bp_course_get_item_pagination
?>