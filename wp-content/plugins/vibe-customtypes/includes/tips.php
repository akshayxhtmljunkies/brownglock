<?php
/**
 * TIPS from MUSettings - General section
 *
 * @class       WPLMS_tips
 * @author      VibeThemes
 * @category    Admin
 * @package     Vibe customtypes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WPLMS_tips{

	var $settings;
	var $temp;

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_tips();
        return self::$instance;
    }

	private function __construct(){
		
		$this->lms_settings = get_option('lms_settings');

		if(is_array($this->lms_settings) && isset($this->lms_settings['general'])){
			$this->settings = $this->lms_settings['general'];
			foreach($this->settings as $key=>$setting){
				switch($key){
					case 'instructor_login_redirect': 
						if(!empty($this->settings['instructor_login_redirect'])){
							if($this->settings['instructor_login_redirect'] == 'same'){
								add_filter('wplms_login_widget_action',array($this,'wplms_login_same_page'));	
							}
							add_filter('login_redirect',array($this,'login_redirect'),100,3);
						}
					break;
					case 'student_login_redirect':
						if(!empty($this->settings['instructor_login_redirect'])){
							if($this->settings['student_login_redirect'] == 'same'){
								add_filter('wplms_login_widget_action',array($this,'wplms_login_same_page'));
							}
							add_filter('login_redirect',array($this,'login_redirect'),100,3);
						}
					break;
					case 'hide_course_members':
						add_filter('wplms_course_nav_menu',array($this,'coursenav_remove_members'));
					break;
					case 'course_curriculum_below_description':
						add_filter('wplms_course_nav_menu',array($this,'coursenav_remove_curriculum'));
						add_action('wplms_after_course_description',array($this,'course_curriculum_below_description'));  
					break;
					case 'admin_instructor':
						add_filter('wplms_show_admin_in_instructors',array($this,'hide_admin_in_instructor'));
					break;
					case 'unit_quiz_start_datetime':
						add_filter('wplms_unit_metabox',array($this,'show_unit_date_time_backend'));
						add_filter('wplms_front_end_unit_settings',array($this,'add_date_time_field'));
						add_action('wplms_front_end_unit_settings_form',array($this,'show_date_time_field'),10,1);
						add_action('wplms_front_end_save_unit_settings_extras',array($this,'save_unit_extra_settings'),10,1);
						add_filter('wplms_drip_value',array($this,'apply_unit_date_time_drip_feed'),10,4);
					break;
					case 'one_session_per_user':
						add_filter( 'authenticate',array($this,'one_session_per_user'), 30, 3 );
					break;
					case 'disable_ajax':
						add_action('wplms_course_start_after_timeline',array($this,'disable_ajax'),10,1);
						add_filter('wplms_get_course_unfinished_unit',array($this,'load_unit'));
						add_filter('wplms_get_course_unfinished_unit_key',array($this,'unit_key'),10,3);
					break;
					case 'course_codes':
						add_filter('wplms_course_product_metabox',array($this,'course_codes_setting'));
						add_filter('wplms_frontend_create_course_pricing',array($this,'wplms_front_end_course_codes'));
						add_action('wplms_front_end_pricing_content',array($this,'wplms_front_end_show_course_codes'),10,1);
						add_action('wplms_front_end_save_course_pricing',array($this,'wplms_front_end_save_course_codes'),10,1);
						add_action('bp_before_course_body',array($this,'wplms_course_code_check'));
					break;
					case 'woocommerce_account':
						add_filter('wplms_logged_in_top_menu',array($this,'wplms_woocommerce_orders_link'));  
					break;
					case 'wplms_course_delete':
						add_filter('wplms_front_end_course_delete',array($this,'enable_front_end_course_deletion'));
					break;
					case 'disable_autofree':
						add_filter('wplms_auto_subscribe',array($this,'disable_auto_subscribe'));
						add_filter('wplms_private_course_button',array($this,'manual_subscription'),10,2);
						add_filter('wplms_private_course_button_label',array($this,'free_label'),10,2);
						add_action('bp_before_course_body',array($this,'subscribe_free_course'));
						add_action('wplms_course_product_id',array($this,'return_blank_for_free'));
					break;
					case 'user_progress_course_admin':
						add_action('wplms_user_course_admin_member',array($this,'show_progressbar_user'),10,2);
					break;
					case 'default_order':
						add_filter('wplms_course_drectory_default_order',array($this,'default_order'));
					break;
					case 'in_course_quiz':
						add_filter('wplms_in_course_quiz',array($this,'wplms_enable_incourse_quiz'));
					break;
					case 'in_course_quiz_paged':
						add_filter('wplms_incourse_quiz_per_page',array($this,'wplms_incourse_quiz_per_page'));
						add_action('wplms_unit_header',array($this,'wplms_quiz_check'),10,1);
					break;
					case 'show_message_instructor':
						add_filter('wplms_instructor_meta',array($this,'show_message_icon'),10,2);
					break;
					case 'instructor_signup_ninja_form_id':
						add_filter( 'ninja_forms_sub_table_row_actions',array($this,'wplms_ninja_forms_sub_table_row_actions_convert_to_instructor'), 40, 4 );
						add_action('wplms_ninja_forms_change_to_instructor_sub',array($this,'wplms_ninja_forms_change_to_instructor_sub'),10,3);
					break;
					case 'enable_student_menus': 
  						add_action( 'init',array($this,'register_student_menus'));
  						add_filter('wplms-mobile-menu',array($this,'student_mobile_menu'));
  						add_filter('wplms-main-menu',array($this,'student_main_menu'));
  						add_filter('wplms-top-menu',array($this,'student_top_menu'));
					break;
					case 'enable_instructor_menus':
						add_action( 'init',array($this,'register_instructor_menus'));
						add_filter('wplms-mobile-menu',array($this,'instructor_mobile_menu'),100);
  						add_filter('wplms-main-menu',array($this,'instructor_main_menu'),100);
  						add_filter('wplms-top-menu',array($this,'instructor_top_menu'),100);
					break;
					case 'enable_forum_privacy':
						add_filter('bbp_has_forums',array($this,'bpp_filter_forums_by_permissions'), 10, 2);
						add_action('bbp_template_redirect', array($this,'bpp_enforce_permissions'), 1);
					break;
					case 'course_coming_soon':

						add_filter('wplms_auto_subscribe',array($this,'disable_free_course_allocation'),10,2);

						add_filter('wplms_course_product_metabox',array($this,'wplms_coming_soon_backend'));
						add_action('wplms_front_end_pricing_content',array($this,'wplms_coming_soon_front_end_pricing'),10,1);
						add_action('wplms_front_end_save_course_pricing',array($this,'save_coming_soon'),10,1);

						add_filter('wplms_course_product_id',array($this,'wplms_coming_soon_link'),10,2);
						add_filter('wplms_course_credits',array($this,'coming_soon_display'),10,2);
						add_filter('wplms_private_course_button_label',array($this,'coming_soon_display'),10,2);
						add_filter('wplms_take_this_course_button_label',array($this,'coming_soon_display'),10,2);
					break;
					case 'course_drip_section':
						add_filter('wplms_drip_value',array($this,'section_wise_drip'),10,4);
					break;
					case 'course_unit_drip_section':
						add_filter('wplms_drip_value',array($this,'unit_wise_drip'),9,4);
					break;
					case 'quiz_passing_score':
						add_filter('wplms_next_unit_access',array($this,'wplms_next_access'),10,2);
						add_filter('wplms_quiz_metabox',array($this,'wplms_quiz_passing_score'),10);
						add_filter('wplms_front_end_quiz_settings',array($this,'quiz_passing_score_control'));
						add_action('wplms_front_end_quiz_settings_action',array($this,'quiz_passing_score_setting'),10,1);
					break;
					case 'quiz_correct_answers':
						add_filter('wplms_show_quiz_correct_answer',array($this,'wplms_show_quiz_correct_answer'),10,2);
					break;
					case 'unit_comments':
						add_filter('wplms_unit_classes',array($this,'wplms_check_unit_comments_filter'));
						add_action('wplms_after_every_unit',array($this,'check_unit_comments_enabled'),9,1); 
					break;
					case 'quiz_negative_marking':
						add_filter('wplms_incorrect_quiz_answer',array($this,'negative_marks_per_question'),10,2);
						add_filter('wplms_quiz_metabox',array($this,'wplms_quiz_negative_marking'),10);
						add_filter('wplms_front_end_quiz_settings',array($this,'negative_marking_control'));
						add_action('wplms_front_end_quiz_settings_action',array($this,'negative_marking_setting'),10,1);
						add_action('wplms_front_end_save_quiz_settings_extras',array($this,'save_front_end_marks'),10,1);
					break;
					case 'wplms_course_assignments':
						add_filter('wplms_curriculum_time_filter',array($this,'show_assignments_in_units'),10,3);
					break;
					case 'members_default_order':
						add_filter('bp_ajax_querystring',array($this,'default_members_order'),20,2);
						add_filter('wplms_members_default_order',array($this,'set_default_members_order'));
					break;
					case 'submission_meta':
						add_action('wplms_assignment_submission_meta',array($this,'submission_meta'),10,2);
						add_action('wplms_quiz_submission_meta',array($this,'submission_meta'),10,2);
						add_action('wplms_course_submission_meta',array($this,'submission_meta'),10,2);
					break;
					case 'terms_conditions_in_registration':
						add_action('bp_signup_validate', array($this,'terms_conditions_validation'));
						add_action('bp_before_registration_submit_buttons', array($this,'show_terms_conditions'),1,1);  
					break;
				}
			}
		}
	}
	/*
    * Checks for Terms and conditions check.
    */
	function terms_conditions_validation(){
		global $bp;
		if(empty($this->settings['terms_conditions_in_registration']))
			return;
        $custom_field = $_POST['terms_conditions'];
        
        if (empty($custom_field) || $custom_field == '') {
            $bp->signup->errors['terms_conditions'] = __('Please Check Terms & Conditions','vibe-customtypes');
        }
        return;
	}
	/*
    * Add Terms and Conditions in Registration page
    * select page in Musettings
    * Gets content from the page and displays on registration page
    */
	function show_terms_conditions(){
		if(empty($this->settings['terms_conditions_in_registration']))
			return;

		$page_id = $this->settings['terms_conditions_in_registration'];

        $content = get_post_field('post_content',$page_id);
		echo '<div class="terms_conditions_container">
            <h3><strong>'.get_the_title($page_id).'</strong></h3>
            <div class="terms-and-conditions-container">';
           echo apply_filters('the_content',$content);
        echo '</div>';
        do_action( 'bp_terms_conditions_errors' );
        echo '<input type="checkbox" name="terms_conditions" id="bph_field" value="1" /> <strong>'.__(' I agree to these Terms and Conditions','vibe-customtypes').'</strong>
        </div><style>.terms_conditions_container{clear:both;margin:20px 0}.terms-and-conditions-container{width:100%;margin:15px 0;border:1px solid rgba(0,0,0,0.05);height:120px;padding:10px;overflow-y:scroll;}</style>';    
	}
    /*
    * Disable Unit Comments
    * Per Paragraph notes and discussion
    * Simple Notes and Discussion
    */
    function wplms_check_unit_comments_filter($unit_class){
	  global $post;
	  if($post->comment_status != 'open'){
	    $unit_class .= ' stop_notes';
	  }
	  return $unit_class;
	}

	function check_unit_comments_enabled($unit_id){ 
	  $comment_status = get_post_field('comment_status',$unit_id);
	  if($comment_status != 'open'){
	      remove_action('wplms_after_every_unit','wplms_show_notes_discussion',10,1);
	  }
	}

	/*
    * Disable Ajax in Units
    * Visual Composer compatibility
    */
	function disable_ajax($course_id){
		echo '<form id="no_ajax_submit" method="post">
		<input type="hidden" name="no_ajax_course_id" value="'.$course_id.'" />
		</form>'; ?>
		<script>
		jQuery(document).ready(function($){
			$(".unit").click(function(event){
				event.preventDefault();
				var security = $("#hash").clone();
				$("#no_ajax_submit").append(security);
				var unit_id=$(this).attr('data-unit');
				$("#no_ajax_submit").append('<input type="hidden" name="load_unit" value="'+unit_id+'" />');
				$("#no_ajax_submit").submit();
				event.stopPropagation();
			});
		});
		</script>
		<?php
	}

	function load_unit($unit_id){ 
		if(empty($_POST['load_unit']))
			return $unit_id;

		$uid= $_POST['load_unit'];
		$course_id = $_POST['no_ajax_course_id'];
		if ( !isset($_POST['hash']) || !wp_verify_nonce($_POST['hash'],'security') || !is_numeric($uid) || !is_numeric($course_id)){
	        _e('Security check Failed. Contact Administrator.','vibe-customtypes');
	        die();
   	    }else{
   	    	$units = bp_course_get_curriculum_units($course_id);
  			if(in_array($uid,$units)){ 
   	    		return $uid;
   	    	}
   	    }
		return $unit_id;
	}

	function unit_key($key,$unit_id,$course_id){
		if($unit_id == $_POST['load_unit']){
			$units = bp_course_get_curriculum_units($course_id);
			$key = array_search($unit_id,$units);
			$key++;
		}
		return $key;
	}

	function submission_meta($user_id,$id){
		global $bp,$wpdb;

		$meta = $wpdb->get_var($wpdb->prepare("SELECT date_recorded FROM {$bp->activity->table_name} WHERE user_id = %d AND item_id = %d",$user_id,$id));
		echo human_time_diff(strtotime($meta),current_time('timestamp'));
	}
	function set_default_members_order($order){
		return $this->settings['members_default_order'];
	}
	function default_members_order($string,$object){
		if ( bp_is_active( 'members' ) ){
			$string.='&type='.$this->settings['members_default_order'];
    	}

		return $string;
	}
	function show_assignments_in_units($html,$mins,$unit_id){
		$assignments = vibe_sanitize(get_post_meta($unit_id,'vibe_assignment',false));
		if(isset($assignments) && is_array($assignments) && count($assignments)){
			foreach($assignments as $assignment){
				$html .= '<small><strong>'.__('Assignment :','vibe-customtypes').'</strong>'.get_the_title($assignment).'</small>';
			}
		}
		return $html;
	}
	function wplms_next_access($access,$quiz_id){
		$nextunit_access = vibe_get_option('nextunit_access');
		if(get_post_type($quiz_id) == 'quiz' && $nextunit_access){
			$user_id = get_current_user_id();
			$marks = get_post_meta($quiz_id,$user_id,true);
			$passing_marks = get_post_meta($quiz_id,'vibe_quiz_passing_score',true);
			
			if($marks < $passing_marks)
				return false;
		}
		return $access;
	}
	function wplms_quiz_passing_score($metabox){
		foreach($metabox as $key=>$value){
			if($key == 'vibe_quiz_questions'){
				$newmetabox['vibe_quiz_passing_score'] = array( // Text Input
					'label'	=> __('Quiz passing score','vibe-customtypes'), // <label>
					'desc'	=> __('Passing score for quiz. Combined with Prev.Unit/Quiz lock the user progress can be restricted.','vibe-customtypes'), // description
					'id'	=> 'vibe_quiz_passing_score', // field id and name
					'type'	=> 'text', // type of field
					'std'   => 0
				);
			}
			$newmetabox[$key] = $value;
		}
		return $newmetabox; 
	}

	function quiz_passing_score_setting($setting){
		?>
		<li><label><?php _e('SET QUIZ PASSING SCORE','vibe-customtypes'); ?></label>
            <input type="text" class="small_box vibe_extras" id="vibe_quiz_passing_score" value="<?php echo $setting['vibe_quiz_passing_score']; ?>" />
        </li>
		<?php
	}
	function quiz_passing_score_control($settings){
		$value = get_post_meta(get_the_ID(),'vibe_quiz_passing_score',true);
		 if(is_numeric($value))
		 	$settings['vibe_quiz_passing_score'] = $value;
		 else
		 	$settings['vibe_quiz_passing_score'] = 0;
		return $settings;
	}

	function negative_marks_per_question($marks,$quiz_id){
		if(is_numeric($quiz_id)){
			$nmarks = get_post_meta($quiz_id,'vibe_quiz_negative_marks_per_question',true);
			if(isset($nmarks) && $nmarks)
				$marks = -1*$nmarks;
		}
		return $marks;
	}

	function negative_marking_setting($setting){
		?>
		<li><label><?php _e('NEGATIVE MARKS PER QUESTION','vibe-customtypes'); ?></label>
            <input type="text" class="small_box vibe_extras" id="vibe_quiz_negative_marks_per_question" value="<?php echo $setting['vibe_quiz_negative_marks_per_question']; ?>" />
        </li>
		<?php
	}
	function negative_marking_control($settings){
		$value = get_post_meta(get_the_ID(),'vibe_quiz_negative_marks_per_question',true);
		 if(is_numeric($value))
		 	$settings['vibe_quiz_negative_marks_per_question'] = $value;
		 else
		 	$settings['vibe_quiz_negative_marks_per_question'] = 0;
		return $settings;
	}
	function wplms_quiz_negative_marking($metabox){
		foreach($metabox as $key=>$value){
			if($key == 'vibe_quiz_questions'){
				$newmetabox['vibe_quiz_negative_marks_per_question'] = array( // Text Input
					'label'	=> __('Negative Marks per Question','vibe-customtypes'), // <label>
					'desc'	=> __('Deduct marks for a Wrong answer.','vibe-customtypes'), // description
					'id'	=> 'vibe_quiz_negative_marks_per_question', // field id and name
					'type'	=> 'text', // type of field
					'std'   => 0
				);
			}
			$newmetabox[$key] = $value;
		}
		
		return $newmetabox;
	}
	function wplms_show_quiz_correct_answer($return,$quiz_id){ 
		$course_id = get_post_meta($quiz_id,'vibe_quiz_course',true);
		if(is_numeric($course_id)){
			$user_id = get_current_user_id();
			$course_status = bp_course_get_user_course_status($user_id,$course_id);

			if($course_status >= 3){
				return true;
			}else{
				return false;
			}
		}
		return $return;
	}
	function section_wise_drip($value,$pre_unit_id,$course_id,$unit_id){
		$curriculum = vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));


		$user_id = get_current_user_id();
		$drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);


		if(is_array($curriculum)){
			$key = array_search($unit_id,$curriculum);
			if(!isset($key) || !$key)
				return $value;
			//GET Previous Two Sections
			$i=$key;
			while($i>=0){
				if(!is_numeric($curriculum[$i])){
					if(!isset($k2)){
						$k2 = $i;
					}else if(!isset($k1)){
						$k1 = $i;
					}
				}
				$i--;
			}

			//First section incomplete
			if(!isset($k2) || !isset($k1) || !$k2 || $k1 == $k2 || $k2<$k1)
				return 0;

			//Get first unit in previous section
			for($i=$k1;$i<=$k2;$i++){
				if(is_numeric($curriculum[$i]) && get_post_type($curriculum[$i]) == 'unit') 
					break;
			}

			if($i == $k2){
				return 0; // section drip feed disabled if a section has all quizzes
			}
			$start_section_timestamp = get_post_meta($curriculum[$i],$user_id,true);
			$drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);
            $value = $start_section_timestamp + $drip_duration*$drip_duration_parameter;
			
			
		}
		return $value;
	}

	function unit_wise_drip($value,$pre_unit_id,$course_id,$unit_id){
		$user_id = get_current_user_id();
		$duration = get_post_meta($pre_unit_id,'vibe_duration',true);
		$unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60);
		$preunit_access_timestamp = get_post_meta($pre_unit_id,$user_id,true);
		if(!empty($preunit_access_timestamp)){
			$value = $preunit_access_timestamp+$duration*$unit_duration_parameter;
		}
		return $value;
	}

	function wplms_coming_soon_link($pid,$course_id){
		$coming_soon = get_post_meta($course_id,'vibe_coming_soon',true);
		if(vibe_validate($coming_soon)){
			return '#';
		}
		return $pid;
	}
	function coming_soon_display($credits,$course_id){
		$coming_soon = get_post_meta($course_id,'vibe_coming_soon',true);
		if(vibe_validate($coming_soon)){
			return '<strong><span class="amount">'.__('COMING SOON','vibe-customtypes').'</span></strong>';
		}
		return $credits;
	}
	
	function disable_free_course_allocation($auto,$course_id){
		$coming_soon = get_post_meta($course_id,'vibe_coming_soon',true); 
		if(vibe_validate($coming_soon)){
			return false;
		}
		return $auto;
	}

	function wplms_coming_soon_backend($metabox){
		$metabox[] = array( // Text Input
					'label'	=> __('Coming soon Mode','vibe-customtypes'), // <label>
					'desc'	=> __('Enable Coming soon mode','vibe-customtypes'), // description
					'id'	=> 'vibe_coming_soon', // field id and name
					'type'	=> 'yesno', // type of field
			        'options' => array(
			          array('value' => 'H',
			                'label' =>'Hide'),
			          array('value' => 'S',
			                'label' =>'Show'),
			        ),
			        'std'   => 'H'
				);
		return $metabox;
	}
	function save_coming_soon($course_id){
		if(isset($_POST['vibe_coming_soon']) && in_array($_POST['vibe_coming_soon'],array('H','S')) && is_numeric($course_id)){
			update_post_meta($course_id,'vibe_coming_soon',$_POST['vibe_coming_soon']);
		}
	}

	function wplms_coming_soon_front_end_pricing($course_id){

		if(isset($course_id) && $course_id){
			$vibe_coming_soon = get_post_meta($course_id,'vibe_coming_soon',true);	
		}else{
			$vibe_coming_soon = 'H';
		}
		
		echo '<li>
                <h3>'.__('Coming Soon mode','vibe-customtypes').'<span>
                    <div class="switch coming_soon">
                            <input type="radio" class="switch-input vibe_coming_soon" name="vibe_coming_soon" value="H" id="disable_coming_soon" '; checked($vibe_coming_soon,'H'); echo '>
                            <label for="disable_coming_soon" class="switch-label switch-label-off">'.__('Disable','vibe-customtypes').'</label>
                            <input type="radio" class="switch-input vibe_coming_soon" name="vibe_coming_soon" value="S" id="enable_coming_soon" '; checked($vibe_coming_soon,'S'); echo '>
                            <label for="enable_coming_soon" class="switch-label switch-label-on">'.__('Enable','vibe-customtypes').'</label>
                            <span class="switch-selection"></span>
                          </div>
                </span></h3>
            </li>
            ';
	}

	function bpp_filter_forums_by_permissions($args = ''){
		

		$bbp = bbpress();
	    // Setup possible post__not_in array
	    $post_stati[] = bbp_get_public_status_id();

	    // Check if user can read private forums
	    if (current_user_can('read_private_forums'))
	        $post_stati[] = bbp_get_private_status_id();

	    // Check if user can read hidden forums
	    if (current_user_can('read_hidden_forums'))
	        $post_stati[] = bbp_get_hidden_status_id();

	    // The default forum query for most circumstances
	    $meta_query = array(
	        'post_type' => bbp_get_forum_post_type(),
	        'post_parent' => bbp_is_forum_archive() ? 0 : bbp_get_forum_id(),
	        'post_status' => implode(',', $post_stati),
	        'posts_per_page' => get_option('_bbp_forums_per_page', 50),
	        'orderby' => 'menu_order',
	        'order' => 'ASC'
	    );

	    //Get an array of IDs which the current user has permissions to view
	    $allowed_forums = $this->bpp_get_permitted_forum_ids();

	    // The default forum query with allowed forum ids array added
	    $meta_query['post__in'] = $allowed_forums;

	    $bbp_f = bbp_parse_args($args, $meta_query, 'has_forums');

	    // Run the query
	    $bbp->forum_query = new WP_Query($bbp_f);

	    return apply_filters('bpp_filter_forums_by_permissions', $bbp->forum_query->have_posts(), $bbp->forum_query);
	}

	function bpp_get_permitted_forum_ids(){
		$user_id = get_current_user_id();
		global $wpdb;
		$forum_ids = array();
		$course_ids_array = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %d AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = '%s')",$user_id,BP_COURSE_CPT,'publish'),ARRAY_A);
		if(is_array($course_ids_array)){
			foreach($course_ids_array as $course_id){
				if(isset($course_id['post_id'])){
					$forum_id = get_post_meta($course_id['post_id'],'vibe_forum',true);
					if(is_numeric($forum_id)){
						$forum_ids[] = $forum_id;
					}
				}
			}
			array_unique($forum_ids);
			return $forum_ids;
		}else{
			return false;
		}

	}

	function bpp_get_permitted_subforums($forum_list){
			$filtered_forums = array();
			foreach ($forum_list as $forum) {
				$forum_id = $forum->ID;
				$permitted_forums = $this->bpp_get_permitted_forum_ids();
				if(in_array($forum_id,$permitted_forums))
				{
					array_push($filtered_forums,$forum);
				}
			}
			
			return (array) $filtered_forums;
	}
	function bpp_enforce_permissions(){
		// Bail if not viewing a bbPress item
	    if (!is_bbpress())
	        return;

	    // Bail if not viewing a single item or if user has caps
	    if (!is_singular() || bbp_is_user_keymaster() || current_user_can('read_hidden_forums') || bbp_is_forum_archive())
	        return;

	    global $post;

	    
	    if (!$this->bpp_can_user_view_post($post->ID)) {
	        if (!is_user_logged_in()) { 
	        	if(is_numeric($this->temp)){
	        		$link =get_permalink($this->temp).'?error=not-accessible';
	        		wp_redirect($link,'302');
	        		exit();
	        	}else{
	        		auth_redirect();
	        	}
	        }else {
	        	if(is_numeric($this->temp)){
	        		wp_safe_redirect(get_permalink($this->temp).'?error=not-accessible','302');
	        	}else{
	        		bbp_set_404();
	        	}
	        }
	    }

	}
	
	function bpp_can_user_view_post($post_id){
		global $wpdb;
		$user_id = get_current_user_id();
		$parents = get_post_ancestors( $post_id );
		$id = ($parents) ? $parents[count($parents)-1]: $post_id;
		
		$course_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %d",'vibe_forum',$id));
		$this->temp = $course_id;
		return wplms_user_course_check($user_id,$course_id);
	}
	
	function student_mobile_menu($args){
		if(is_user_logged_in()){
			$args['theme_location'] = 'student-mobile-menu';
		}
		return $args;
	}
	function student_main_menu($args){
		if(is_user_logged_in()){
			$args['theme_location'] = 'student-main-menu';
		}
		return $args;
	}
	function student_top_menu($args){
		if(is_user_logged_in()){
			$args['theme_location'] = 'student-top-menu';
		}
		return $args;
	}
	function instructor_mobile_menu($args){
		if(is_user_logged_in() && current_user_can('edit_posts')){
			$args['theme_location'] = 'instructor-mobile-menu';
		}
		return $args;
	}
	function instructor_main_menu($args){
		if(is_user_logged_in() && current_user_can('edit_posts')){
			$args['theme_location'] = 'instructor-main-menu';
		}
		return $args;
	}
	function instructor_top_menu($args){
		if(is_user_logged_in() && current_user_can('edit_posts')){
			$args['theme_location'] = 'instructor-top-menu';
		}
		return $args;
	}
    function register_student_menus() {
	    register_nav_menus(
    	    array(
	            'student-top-menu' => __( 'Top Menu for Students','vibe-customtypes' ),
	            'student-main-menu' => __( 'Main Menu for Students','vibe-customtypes' ),
	            'student-mobile-menu' => __( 'Mobile Menu for Students','vibe-customtypes' ),
	            )
        );
    }
    function register_instructor_menus() {
	    register_nav_menus(
    	    array(
	            'instructor-top-menu' => __( 'Top Menu for Instructor','vibe-customtypes' ),
	            'instructor-main-menu' => __( 'Main Menu for Instructor','vibe-customtypes' ),
	            'instructor-mobile-menu' => __( 'Mobile Menu for Instructor','vibe-customtypes' ),
	            )
        );
    }
	/* ===== NINJA FORMS FOR INSTRUCTOR SIGNUP ====== */

	function wplms_ninja_forms_sub_table_row_actions_convert_to_instructor( $row_actions, $data, $sub_id, $form_id ) {
		if ( !in_array( 'ninja-forms/ninja-forms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
			return $row_actions;

		if(!isset($this->settings['instructor_signup_ninja_form_id']) || !is_numeric($this->settings['instructor_signup_ninja_form_id']))
			return $row_actions;
		
	    $ninja_instructor_form_id = $this->settings['instructor_signup_ninja_form_id'];
	    if(isset($ninja_instructor_form_id) && $ninja_instructor_form_id == $form_id){
	    	$row_actions['instructor'] = apply_filters('wplms_ninja_forms_change_to_instructor_sub','<span><a href="?post_status=all&post_type=nf_sub&action=-1&form_id='.$form_id.'&make_instructor" id="'.$sub_id.'" class="wplms_ninja_forms_convert_to_instructor_sub">'. __( 'Make Instructor', 'vibe-customtypes' ).'</a></span>',$sub_id,$form_id);
	    }
	  return $row_actions;

	}
	
	function wplms_ninja_forms_change_to_instructor_sub($link,$sub_id,$form_id){
		if(!isset($_GET['make_instructor']) && !isset($_GET['make_student'])){
	    	return	$link;	
    	}
		if ( !in_array( 'ninja-forms/ninja-forms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
			return $link;
		if($this->settings['instructor_signup_ninja_form_id'] != $form_id){
			return $link;
		}

		if(isset($_GET['make_instructor'])){
	    	$role = 'instructor';
    	}
    	if(isset($_GET['make_student'])){
	    	$role = 'student';
    	}
	    $submission = Ninja_Forms()->sub( $sub_id );
	    
	    if(isset($submission) && is_array($submission->fields)){

		    foreach($submission->fields as $value){
		      $email=$value;
		       if(filter_var($email, FILTER_VALIDATE_EMAIL)){ 
		            $args = array(
		                  'search'         => $email,
		                  'search_columns' => array('user_email' ),
		                );
		            $user_query = new WP_User_Query( $args );
		            // User Loop
		            if ( ! empty( $user_query->results ) && count($user_query->results) == 1) { 
		              $user = $user_query->results[0];
		              $user_id = $user->ID;

		              if(!user_can($user->ID,'edit_posts')){
		                $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => 'instructor' ) );
		                  if ( is_wp_error( $user_id ) ) {
		                    return '<span>'.__('There was some error','vibe-customtypes').'</span>';
		                  } else {
		                    return '<span><a href="?post_status=all&post_type=nf_sub&action=-1&form_id='.$form_id.'&make_student" id="'.$sub_id.'" class="wplms_ninja_forms_convert_to_instructor_sub">'. __( 'Make Student', 'vibe-customtypes' ).'</a></span>';
		                  }
		              }else{
		              	return '<span>'.__('Instructor','vibe-customtypes').'</span>';
		              }
		            }
		       }
		    }
		  }
	}
	/* Show Message and Mail icon below Instructor name */
	function show_message_icon($meta,$instructor_id){
		if(is_numeric($instructor_id) && is_singular('course') && is_user_logged_in()){
			$meta .= '<ul class="instructor_meta">';
			if(is_user_logged_in()){
				$user_id = get_current_user_id();
				if($user_id != $instructor_id && function_exists('bp_get_messages_slug')){
					$link = wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $instructor_id ) );				
					$meta .= '<li><a href="'.$link.'" class="button tip" title="'.__('Send Message','vibe-customtypes').'"><i class="icon-email"></i></a></li>';
				}
			}
			$user_info = get_userdata($instructor_id);
			$meta .= '<li><a href="mailto:'.$user_info->user_email.'" class="button tip" title="'.__('Send Email','vibe-customtypes').'"><i class="icon-at-email"></i></a></li>';
			$meta .= '</ul>';
		}
		return $meta;
	}
	function wplms_enable_incourse_quiz($quiz_class){
		$quiz_class .=' start_quiz';
		return $quiz_class;
	}
	function wplms_quiz_check($unit_id){
		if(get_post_type($unit_id) == 'quiz'){
			echo '<input type="hidden" id="results_link" value="'.bp_loggedin_user_domain().BP_COURSE_SLUG.'/'.BP_COURSE_RESULTS_SLUG.'/?action='.$unit_id.'" />';
		}
	}
	function wplms_incourse_quiz_per_page($num){
		if(isset($this->settings['in_course_quiz_paged']) && is_numeric($this->settings['in_course_quiz_paged'])){
			return $this->settings['in_course_quiz_paged'];
		}
		return $num;
	}
	function show_progressbar_user($user_id,$course_id){
		$progress = get_user_meta($user_id,'progress'.$course_id,true);
		if(isset($progress) && is_numeric($progress)){
			echo '<div class="progress">
             <div class="bar animate stretchRight" style="width: '.$progress.'%"><span>'.$progress.'%</span></div>
           </div>';
		}
	}

	function default_order($order){
		if(!isset($order['orderby']))
			return;

		if(empty($order['orderby'])){
			switch($this->settings['default_order']){
				case 'date':
					$order['orderby']=array('menu_order' => 'DESC', 'date' => 'DESC');
				break;
				case 'title':
					$order['orderby']=array('menu_order' => 'DESC', 'title' => 'ASC');
				break;
				case 'popular':
					$order['orderby']=array('menu_order' => 'DESC', 'meta_value' => 'DESC');
					$order['meta_key']='vibe_students';
				break;
				case 'rated':
					$order['orderby']=array('menu_order' => 'DESC', 'meta_value' => 'DESC');
					$order['meta_key']='average_rating';
				break;
			}
		}
		return $order;
	}
	function subscribe_free_course(){
		global $post;
		if(isset($_GET['subscribe'])){
			$free = get_post_meta($post->ID,'vibe_course_free',true);
			if(vibe_validate($free)){
				$user_id = get_current_user_id();
				bp_course_add_user_to_course($user_id,$post->ID);
			}
		}
	}
	function return_blank_for_free($pid){
		global $post;
		$free = get_post_meta($post->ID,'vibe_course_free',true);
		if(vibe_validate($free))
			return '';
		return $pid;
	}
	function manual_subscription($link,$course_id){
		$free = get_post_meta($course_id,'vibe_course_free',true);
		if(vibe_validate($free)){
			$link = get_permalink($course_id).'?subscribe';
		}
		return $link;
	}
	function free_label($label,$course_id){
		$free = get_post_meta($course_id,'vibe_course_free',true);
		if(vibe_validate($free)){
			$label = __('Take this Course','vibe-customtypes');	
		}
		return $label;
	}
	function disable_auto_subscribe($flag){
		return 0;
	}
	function enable_front_end_course_deletion($flag){
		return 1;
	}
	function wplms_woocommerce_orders_link($loggedin_menu){
            $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
            if ( isset($myaccount_page_id) && is_numeric($myaccount_page_id) ) {
              $loggedin_menu['orders']=array(
                          'icon' => 'icon-list',
                          'label' => __('My Orders','vibe-customtypes'),
                          'link' =>get_permalink( $myaccount_page_id )
                          );
            }
             if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_user_logged_in()) {
            $pmpro_account_page_id = get_option('pmpro_account_page_id');
            if ( isset($pmpro_account_page_id ) && is_numeric($pmpro_account_page_id ) ) {
              $loggedin_menu['membership']=array(
                          'icon' => 'icon-archive',
                          'label' => __('My Membership','vibe-customtypes'),
                          'link' =>get_permalink( $pmpro_account_page_id )
                          );
           		 }
           	}
		return  $loggedin_menu;
    }

	function wplms_course_code_check(){
		$user_id=get_current_user_id();
    	$course_id =get_the_ID();
    	$course_codes = get_post_meta($course_id,'vibe_course_codes',true);
		if($_POST['submit_course_codes']){
      		if ( !isset($_POST['security_code']) || !wp_verify_nonce($_POST['security_code'],'security'.$user_id) ){
			    echo '<p class="message">'.__('Security check Failed. Contact Administrator.','vibe-customtypes').'</p>';
		    }else{
		    	$code = $_POST['course_code'];
		    	
		    	if(isset($code) && strlen($code)<2){
		    		echo '<p class="message">'.__('Code does not exist. Please check the code.','vibe-customtypes').'</p>';
		    		return;
		    	}

	    		$x=preg_match("/(^|,)$code(\|([0-9]+)|(,|$))/", $course_codes, $matches);
	    		if(!$x){
	    			echo '<p class="message">'.__('Code does not exist. Please check the code.','vibe-customtypes').'</p>';
	    			return;
	    		}else{	
	    			global $wpdb,$bp;
					if(isset($matches[3]) && is_numeric($matches[3])){
						$total_count = $matches[3];
						
						$count = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM {$bp->activity->table_name} WHERE component = %s AND type = %s AND content = %s AND item_id = %d",'course','course_code',$code,$course_id));
		    			if($count <= $total_count){
		    				if(!wplms_user_course_check($user_id,$course_id)){
				    			bp_course_record_activity(array(
						          'action' => __('Course code applied','vibe-customtypes'),
						          'content' => $code,
						          'type' => 'course_code',
						          'item_id' => $course_id,
						          'primary_link'=>get_permalink($course_id),
						          'secondary_item_id'=>$user_id
						        )); 
						        do_action('wplms_course_code',$code,$course_id,$user_id);
						        bp_course_add_user_to_course($user_id,$course_id);
						        echo '<p class="message success">'.__('Congratulations! You are now added to the course.','vibe-customtypes').'</p>';
				    		}else{
				    			echo '<p class="message">'.__('User already in course.','vibe-customtypes').'</p>';
				    		}
		    			}else{
		    				echo '<p class="message">'.__('Maximum number of usage for course code exhausted','vibe-customtypes').'</p>';
		    			}
					}else{
						if(!wplms_user_course_check($user_id,$course_id)){
		    				 
					        do_action('wplms_course_code',$code,$course_id,$user_id);
			    			bp_course_add_user_to_course($user_id,$course_id);
			    			echo '<p class="message success">'.__('Congratulations! You are now added to the course.','vibe-customtypes').'</p>';
			    		}else{
			    			echo '<p class="message">'.__('User already in course.','vibe-customtypes').'</p>';
			    		}
					}
	    		}
		    }
      	}
	}
	function wplms_front_end_save_course_codes($course_id){
		if($_POST['extras']){ 
			$extras = json_decode(stripslashes($_POST['extras']));
	        if(is_array($extras) && isset($extras))
	        foreach($extras as $c){
	           update_post_meta($course_id,$c->element,$c->value);
	        }
		}
	}
	function wplms_front_end_show_course_codes($course_id){
		$course_codes='';
		if(isset($_GET['action']) && is_numeric($_GET['action'])){
            $course_id = $_GET['action'];
            $course_codes = get_post_meta($course_id,'vibe_course_codes',true);
        }
		echo '<li class="course_membership"><h3>'.__('Course Codes','vibe-customtypes').'<span>
                  <textarea id="vibe_course_codes" class="vibe_extras" placeholder="'.__('Enter Course codes (XXX|2,YYY|4)','vibe-customtypes').'" >'.$course_codes.'</textarea>
              </span>
              </h3>
          </li>';
	}
	function wplms_front_end_course_codes($settings){
		$settings['vibe_course_codes']='';
		if(isset($_GET['action']) && is_numeric($_GET['action'])){
            $course_id = $_GET['action'];
            $settings['vibe_course_codes'] = get_post_meta($course_id,'vibe_course_codes',true);
        }
		return $settings;
	}
	function course_codes_setting($setting){
		$setting[]=array( // Text Input
					'label'	=> __('Set Course purchase codes','vibe-customtypes'), // <label>
					'desc'	=> __('Student can gain access to Course using course codes (multiple codes comma saperated, usage count pipe saperate eg : xxx|2,yyy|4)','vibe-customtypes'), // description
					'id'	=> 'vibe_course_codes', // field id and name
					'type'	=> 'textarea', // type of field
				);
		return $setting;
	}
	function show_unit_date_time_backend($settings){
		$prefix='vibe_';
		$settings[]= array( // Text Input
					'label'	=> __('Access Date','vibe-customtypes'), // <label>
					'desc'	=> __('Date on which unit is accessible','vibe-customtypes'), // description
					'id'	=> $prefix.'access_date', // field id and name
					'type'	=> 'date', // type of field
				);
		$settings[]=array( // Text Input
					'label'	=> __('Access Time','vibe-customtypes'), // <label>
					'desc'	=> __('Time after which unit is accessible','vibe-customtypes'), // description
					'id'	=> $prefix.'access_time', // field id and name
					'type'	=> 'time', // type of field
				);
		return $settings;
	}
	function add_date_time_field($unit_settings){
		$unit_settings['vibe_access_date']='';
		$unit_settings['vibe_access_time']='';
		$vibe_access_date= get_post_meta(get_the_ID(),'vibe_access_date',true);
		$vibe_access_time= get_post_meta(get_the_ID(),'vibe_access_time',true);
		if(isset($vibe_access_date) && isset($vibe_access_time) && $vibe_access_date && $vibe_access_time){
			$unit_settings['vibe_access_date']=$vibe_access_date;
			$unit_settings['vibe_access_time']=$vibe_access_time;
		}
		return $unit_settings;
	}
	function show_date_time_field($unit_settings){
		wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ) );
		wp_enqueue_script( 'timepicker_box', VIBE_PLUGIN_URL . '/vibe-customtypes/metaboxes/js/jquery.timePicker.min.js', array( 'jquery' ) );
		echo '<script>
		jQuery(document).ready(function(){
				jQuery( ".datepicker" ).datepicker({
                    dateFormat: "yy-mm-dd",
                    numberOfMonths: 1,
                    showButtonPanel: true,
                });
                 jQuery( ".timepicker" ).each(function(){
                 jQuery(this).timePicker({
                      show24Hours: false,
                      separator:":",
                      step: 15
                  });
                });});</script>
		     <li><label>'.__('Unit access date','vibe-customtypes').'</label>
                <h3>'.__('Access date','vibe-customtypes').'<span>
                <input type="text" class="datepicker vibe_extras" id="vibe_access_date" value="'.$unit_settings['vibe_access_date'].'" /> 
            </li><li><label>'.__('Unit access time','vibe-customtypes').'</label>
                <h3>'.__('Access time','vibe-customtypes').'<span>
                <input type="text" class="timepicker vibe_extras" id="vibe_access_time" value="'.$unit_settings['vibe_access_time'].'" /> 
            </li>';
	}
	function save_unit_extra_settings($unit_id){
		if($_POST['extras']){
			$extras = json_decode(stripslashes($_POST['extras']));
	        if(is_array($extras) && isset($extras))
	        foreach($extras as $c){
	           update_post_meta($unit_id,$c->element,$c->value);
	        }
		}
	} 

	function apply_unit_date_time_drip_feed($value,$pre_unit_id,$course_id,$unit_id){
		$vibe_access_date= get_post_meta($unit_id,'vibe_access_date',true);
		$vibe_access_time= get_post_meta($unit_id,'vibe_access_time',true);
		if(isset($vibe_access_date) && isset($vibe_access_time) && $vibe_access_date && $vibe_access_time){
			$value=strtotime($vibe_access_date.' '.$vibe_access_time);
			$value = $value -1*(current_time('timestamp') - time()); // Adjustment to UTC timestamp
		}
		return $value;
	}
	function custom_wplms_login_widget_action($url){
        return wp_login_url( get_permalink() );
	}  
	function login_redirect($redirect_url,$request_url,$user){
		global $bp;
		global $user;
		if(is_a($user,'WP_User')){
			$redirect_array = apply_filters('wplms_redirect_location',array(
					'home' => home_url(),
					'profile' => bp_core_get_user_domain($user->ID),
					'mycourses' => bp_core_get_user_domain($user->ID).'/'.BP_COURSE_SLUG,
					'instructing_courses' => bp_core_get_user_domain($user->ID).'/'.BP_COURSE_SLUG.'/'.BP_COURSE_INSTRUCTOR_SLUG,
					'dashboard' => bp_core_get_user_domain($user->ID).'/'.WPLMS_DASHBOARD_SLUG,
					'same' => '',
					));
			
			$flag=0;
			if (isset($user->allcaps['edit_posts'])) {
				$redirect_url=$redirect_array[$this->settings['instructor_login_redirect']];
				if($this->settings['instructor_login_redirect'] == 'same')
					$redirect_url=$_REQUEST['redirect_to'];
			}else{
				$redirect_url=$redirect_array[$this->settings['student_login_redirect']];
				if($this->settings['student_login_redirect'] == 'same')
					$redirect_url=$_REQUEST['redirect_to'];
			}
		}
		if(empty($redirect_url))
			$redirect_url=home_url();

		return $redirect_url;
	}

	function wplms_login_same_page($url){
		return $url .='?redirect_to='.urlencode($this->getCurrentUrl());
	}
	function getCurrentUrl() {
        $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
        $url .= '://' . $_SERVER['SERVER_NAME'];
        $url .= in_array( $_SERVER['SERVER_PORT'], array('80', '443') ) ? '' : ':' . $_SERVER['SERVER_PORT'];
        $url .= $_SERVER['REQUEST_URI'];
        return $url;
    }
	function hide_admin_in_instructor($flag){ 
		return 0;
	}

	function coursenav_remove_members($menu_array){
		unset($menu_array['members']);
        return $menu_array;
	}

	function coursenav_remove_curriculum($menu_array){
		unset($menu_array['curriculum']);
        return $menu_array;
	}
	function course_curriculum_below_description(){
		global $post;
		$id= get_the_ID();
		$class='';
		if(isset($this->settings['curriculum_accordion']))
			$class="accordion";
		?>
			<div class="course_curriculum <?php echo $class; ?>">
				<?php
					$file = get_stylesheet_directory() . '/course/single/curriculum.php';
					if(!file_exists($file)){
						$file = VIBE_PATH.'/course/single/curriculum.php';
					}
					include $file;
				?>
			</div>
		<?php
	}

	function one_session_per_user( $user, $username, $password ) { 
		
		if(isset($user->allcaps['edit_posts']) && $user->allcaps['edit_posts']){
			return $user;
		}
		$sessions = WP_Session_Tokens::get_instance( $user->ID );

    	$all_sessions = $sessions->get_all();
		if ( count($all_sessions) ) {
			$flag=0;
			$previous_login = get_user_meta($user->ID,'last_activity',true);
			if(isset($previous_login) && $previous_login){
				$threshold = apply_filters('wplms_login_threshold',1800);
				$difference = time()-strtotime($previous_login) - $threshold;
				if($difference <= 0){ // If the user Logged in within 30 Minutes
					$flag=1;
				}else{
					$token = wp_get_session_token();
					$sessions->destroy_others( $token );
				} 
			}else{
				$flag = 1;
			}
			if($flag)
				$user = new WP_Error('already_signed_in', __('<strong>ERROR</strong>: User already logged in.','vibe-customtypes'));
		}
	    return $user;
	}
}

add_action('plugins_loaded','wplms_tips_init');
function wplms_tips_init(){
	WPLMS_tips::init();
}


add_action( 'widgets_init', 'wplms_course_code_widget');
function wplms_course_code_widget(){
	register_widget('wplms_course_codes');
}

class wplms_course_codes extends WP_Widget {
 
	function wplms_course_codes() {
	    $widget_ops = array( 'classname' => 'wplms_course_codes', 'description' => __('WPLMS Course codes widget', 'vibe-customtypes') );
	    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wplms_course_codes' );
	    $this->WP_Widget( 'wplms_course_codes', __('WPLMS Course Codes', 'vibe-customtypes'), $widget_ops, $control_ops );
  	}
        
    function widget( $args, $instance ) {
    	if(!is_singular(BP_COURSE_CPT) || !defined('BP_COURSE_CPT') || !is_user_logged_in())
    		return;

    	$user_id=get_current_user_id();
    	$course_id =get_the_ID();
    	$course_codes = get_post_meta($course_id,'vibe_course_codes',true);
    	if(!isset($course_codes) || strlen($course_codes)<2)
    		return;

    	extract( $args );
    	$title = apply_filters('widget_title', $instance['title'] );

    	echo $before_widget;
    	// Display the widget title 
    	if ( $title )
      		echo $before_title . $title . $after_title;

      	echo '<form method="post">
      			<input type="text" name="course_code" class="form_field" placeholder="'.$placeholder.'"/>';
      			wp_nonce_field('security'.$user_id,'security_code');
      	echo '<input type="submit" name="submit_course_codes" value="'.__('Submit','vibe-customtypes').'"/></form>';
    	echo $after_widget;
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
	    $instance = $old_instance;
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['placeholder'] = $new_instance['placeholder'];
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {  
    $defaults = array( 
        'title'  => __('Enter Course code','vibe-customtypes'),
        'placeholder'  => __('Place holder text','vibe-customtypes'),
    );
    $instance = wp_parse_args( (array) $instance, $defaults );                 
    ?>
    <p> <?php _e('Title','vibe-customtypes'); ?> <input type="text" class="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" /></p>
    <p> <?php _e('Course Codes input box text','vibe-customtypes'); ?> <input type="text" class="text" name="<?php echo $this->get_field_name('placeholder'); ?>" value="<?php echo $instance['placeholder']; ?>" /></p>
	<?php
    }

}
