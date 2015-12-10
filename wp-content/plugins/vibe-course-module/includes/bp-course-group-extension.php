<?php
/**
 * 
 * This file contains a reference user interface for Course groups.
 * 
 */



if( ! class_exists( 'BP_Group_Extension') ) {
	// Groups component is not enabled; don't initialize the extension
	return;
}

if(!function_exists('vibe_get_option') || !vibe_get_option('course_batches')){ // Enable only for Course batches
	return;
}

class BP_Course_Group_Extension extends BP_Group_Extension {
	
	public function __construct() {
		
		global $bp;
		
		if( method_exists( $this, 'init' ) ) {

			$args = array(
				'name'              => __('Course','vibe'),
				'slug'              => BP_COURSE_SLUG,
				'enable_nav_item'   => false,
				'screens'           => array(
					'edit'          => array(
						'submit_text'=> __('Save Course Settings','vibe'),
						'screen_callback'=> array($this,'manage_course_settings'),
						'screen_save_callback'=> array($this,'save_course_settings'),
						),
				)
			);
			
			parent::init( $args );
		}
	}

	function  manage_course_settings($group_id = NULL){
		wp_enqueue_script('jquery-ui-datepicker');
		$args = array('post_type'=>'course','post_per_page'=>-1);
		global $bp;
		$group_id = bp_get_group_id();
		?>
		<h3><?php _e('Connect with Course'); ?></h3>
		<?php
		$course_id = groups_get_groupmeta($group_id,'course_id');
		$the_query = get_posts($args); 
		echo '<select name="group_course" class="chosen"><option value="">'.__('Select a Course','vibe').'</option>';
		foreach($the_query as $result){
			echo '<option value="'.$result->ID.'" '.selected($result->ID,$course_id).'>'.$result->post_title.'</option>';
		}
		echo '</select>';
		?><hr />
		<h4><?php _e('Start Date/Time'); ?></h4>
		<?php		
		$start_date = groups_get_groupmeta($group_id,'start_date');
		echo '<input type="text" name="start_date" class="date_box" value="'.$start_date.'" />';
		?>
		<script>
			jQuery(document).ready(function(){
				    jQuery('.date_box').datepicker({
				      dateFormat: 'yy-mm-dd'
				    });
			});
		</script>
		<hr />
		<?php
	}

	function  save_course_settings(){ 
		$group_id = $_POST['group-id'];
		if(isset($_POST['group_course']) && is_numeric($_POST['group_course'])){ 
			$c=groups_update_groupmeta($group_id,'course_id',$_POST['group_course']);
		}
		if(isset($_POST['start_date'])){
			$c=groups_update_groupmeta($group_id,'start_date',$_POST['start_date']);
		}	
	}


				
}	
bp_register_group_extension( 'BP_Course_Group_Extension' );