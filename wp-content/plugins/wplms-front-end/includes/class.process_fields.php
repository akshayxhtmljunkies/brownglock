<?php

class WPLMS_Process_Fields {
	var $course_id;

	var $course_settings;
	var $course_product;

	var $prefix = 'vibe_';
	var $status;

	public static $instance;
    
    public static function init(){
        if ( is_null( self::$instance ) )
            self::$instance = new WPLMS_Process_Fields;
        return self::$instance;
    }

	private function __construct(){


        add_action('wp_ajax_get_select_cpt',array($this,'get_select_cpt'));
        add_action('wp_ajax_get_groups',array($this,'get_groups'));
        add_action('wp_ajax_get_forums',array($this,'get_forums'));
        add_action('wp_ajax_create_group',array($this,'create_group'));
        add_action('wp_ajax_create_forum',array($this,'create_forum'));

        add_action('wp_ajax_get_permalink',array($this,'get_permalink'));
        /* ===== Save Course ===*/
        add_action('wp_ajax_new_create_course',array($this,'create_course'));
        add_action('wp_ajax_new_save_course',array($this,'save_course'));
        add_action('wp_ajax_new_save_course_settings',array($this,'save_course_settings'));
        add_action('wp_ajax_new_save_course_components',array($this,'save_course_components'));
        add_action('wp_ajax_preview_element',array($this,'preview_element'));
        add_action('wp_ajax_get_element',array($this,'get_element'));
        add_Action('wp_ajax_save_element',array($this,'save_element'));
        add_action('wp_ajax_delete_element',array($this,'delete_element'));
        add_action('wp_ajax_preview_question_element',array($this,'preview_sub_element'));
        add_action('wp_ajax_get_question_element',array($this,'get_sub_element'));
        add_action('wp_ajax_create_new_question',array($this,'create_new_question'));
        add_action('wp_ajax_create_new_curriculum',array($this,'create_new_curriculum'));
        add_action('wp_ajax_save_course_curriculum',array($this,'save_course_curriculum'));

        add_action('wp_ajax_preview_sub_element',array($this,'preview_sub_element'));
        add_action('wp_ajax_get_sub_element',array($this,'get_sub_element'));
        add_action('wp_ajax_create_new_assignment',array($this,'create_new_curriculum'));
        add_action('wp_ajax_create_new_product',array($this,'create_new_product'));
        add_action('wp_ajax_set_product',array($this,'set_product'));
        add_action('wp_ajax_new_save_pricing',array($this,'new_save_pricing'));
	}


    
	function get_select_cpt(){
        $user_id = get_current_user_id();

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        } 
        $q = $_POST['q'];
        $args = array(
            'post_type'=>$_POST['cpt'],
            'posts_per_page'=>99,
            's'=>$q['term']
            );
        if(!empty($_POST['status'])){
            $args['post_status'] = explode(',',$_POST['status']);
        }

        $args = apply_filters('wplms_fontend_cpt_query',$args);
        $query = new WP_Query($args);
        $return = array();
        if($query->have_posts()){
            while($query->have_posts()){
                $query->the_post();
                $return[] = array('id'=>get_the_ID(),'text'=>get_the_title());
            }
        }
        wp_reset_postdata();
        print_r(json_encode($return));
        die();
    }

    function get_groups(){
        $user_id = get_current_user_id();

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        } 
        $q = $_POST['q'];

        if(class_exists('BP_Groups_Group')){
            $vgroups =  BP_Groups_Group::get(array(
            'type'=>'alphabetical',
            'per_page'=>999,
            's'=>$q['term']
            ));
            $return = array();
            foreach($vgroups['groups'] as $vgroup){
                $return[] = array('id'=>$vgroup->id,'text'=>$vgroup->name);
            }
        }
        print_r(json_encode($return));
        die();
    }

    function get_forums(){
        $user_id = get_current_user_id();

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        } 
        $q = $_POST['q'];

        $args = array(
            'post_type' => 'forum',
            'posts_per_page'=> 99,
            's'=>$q['term'],
            'orderby'=>'alphabetical',
            'order'=>ASC
            );
        $query = new WP_Query($args);
        $return = array();
        while($query->have_posts()):$query->the_post();
            $return[] = array('id'=>get_the_ID(),'text'=>get_the_title());
        endwhile;
        wp_reset_postdata();
        print_r(json_encode($return));
        die();
    }    

    function create_group(){
        $user_id = get_current_user_id();

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        } 

        $course_id = $_POST['course_id'];
        $group_settings = array(
            'creator_id' => $user_id,
            'name' => $_POST['title'],
            'description' => $_POST['description'],
            'status' => $_POST['privacy'],
            'date_created' => current_time('mysql')
        );

        $group_settings = apply_filters('wplms_front_end_group_vars',$group_settings);
        if($course_setting['vibe_forum'] == 'add_group_forum'){
            $group_settings['enable_forum'] = 1;
        }
        
        
        global $bp;

        $new_group_id = groups_create_group( $group_settings);

        if(is_numeric($new_group_id)){
            groups_update_groupmeta( $new_group_id, 'total_member_count', 1 );
            groups_update_groupmeta( $new_group_id, 'last_activity', gmdate( "Y-m-d H:i:s" ) );
            update_post_meta($course_id,'vibe_group',$new_group_id);
            groups_update_groupmeta( $new_group_id, 'course', $course_id );
            echo $new_group_id;    
        }else{
            print_r($new_group_id);
        }
        die();
    }

    function create_forum(){

        $user_id = get_current_user_id();

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') || !current_user_can('edit_posts') || empty($_POST['title'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        } 
        $course_id = $_POST['course_id'];
        $forum_settings = array(
            'post_title' => $_POST['title'],
            'post_type'=>'forum',
            'post_content' => $_POST['description'],
            'post_status' => $_POST['privacy'],
        );

        $forum_settings = apply_filters('wplms_front_end_forum_vars',$forum_settings);

        
        global $bp;
        $new_forum_id = wp_insert_post($forum_settings);

        if(is_numeric($new_forum_id)){
            update_post_meta($course_id,'vibe_forum',$new_forum_id);
            update_post_meta($new_forum_id,'vibe_forum',$course_id);
            echo $new_forum_id;    
        }else{
            print_r($new_forum_id);
        }
        die();
    }


    /*=== Get Permalink : Helper function for Create Group/Forums ====*/
    function get_permalink(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        } 
        $html ='';
        switch($_POST['type']){
            case 'select_group_form':
            case 'group':
                $html = self::get_group_name($_POST['id']).'<span><a href="'.self::get_group_permalink($_POST['id']).'" target="_blank">'.__('edit','wplms-front-end').'</a>&nbsp;<i>'.__('change','wplms-front-end').'</i></span>';
            break;
            case 'select_forum_form':
            case 'forum':
                $html = get_the_title($_POST['id']).'<span><a href="'.get_permalink($_POST['id']).'" target="_blank">'.__('edit','wplms-front-end').'</a>&nbsp;<i>'.__('change','wplms-front-end').'</i></span>';
            break;
        }
        echo $html;
        die();
    }

    /*======== Create Course =====*/
    function create_course(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }
        $settings = json_decode(stripslashes($_POST['settings']));
        $post_settings = array();
        foreach($settings as $setting){
            switch($setting->type){
                case 'title':
                    $post_settings['post_title'] = $setting->value;
                break;
                case 'taxonomy':
                    if(empty($post_settings['tax_input'])){
                        $post_settings['tax_input'] = array();
                    }
                    if($setting->value != 'new' && is_numeric($setting->value)){
                        $post_settings['tax_input'][$setting->id] = $setting->value;    
                    }
                break;
                case 'taxonomy_new':
                    if(empty($post_settings['tax_input'])){
                        $post_settings['tax_input'] = array();
                    }
                    //$setting->id is taxonomy
                    if(!empty($setting->value)){
                        $term = term_exists($setting->value, $setting->id);
                        if ($term !== 0 && $term !== null) {
                           
                        }else{
                            $new_term = wp_insert_term($setting->value, $setting->id);
                            $setting->value = $new_term['term_id'];
                        }
                        $post_settings['tax_input'][$setting->id] = $setting->value;    
                    }
                break;
                case 'featured_image':
                    $featured_thumb = $setting->value;
                break;
                default:
                    $post_settings[$setting->id] = $setting->value;
                break;
            }
        }

        if(empty($post_settings['post_content']) && !empty($post_settings['post_excerpt'])){
            $post_settings['post_content'] = $post_settings['post_excerpt'];
        }
        if(empty($post_settings['post_content']) && empty($post_settings['post_excerpt'])){
            $post_settings['post_content'] = $post_settings['post_title'];
        }
        $post_settings['post_type'] = 'course';
        $post_settings['post_status'] = 'draft';
        $post_settings = apply_filters('wplms_new_course_settings',$post_settings);
        $post_id = wp_insert_post($post_settings);
        
        if(is_numeric($post_id) && $post_id){
            if(isset($featured_thumb) && is_numeric($featured_thumb))
                set_post_thumbnail($post_id,$featured_thumb);

            echo $post_id;    
        }else{
            _e('Unable to create course, contact admin !','wplms-front-end');
        }
        die();
    }
    /* ===== Save Course ===*/
    function save_course(){

    	$course_id = $_POST['course_id'];

    	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }


        if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }

        $post_author = get_post_field('post_author',$course_id);
        $user_id = get_current_user_id();
        
        if($post_author != $user_id && !current_user_can('manage_options')){ // Instructor and Admin check
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }
        $settings = json_decode(stripslashes($_POST['settings']));
        $post_settings = array( 'ID' => $course_id );
        foreach($settings as $setting){
        	switch($setting->type){
        		case 'title':
        			$post_settings['post_title'] = $setting->value;
        		break;
        		case 'taxonomy':
	        		if(empty($post_settings['tax_input'])){
	        			$post_settings['tax_input'] = array();
	        		}
	        		if($setting->value != 'new' && is_numeric($setting->value)){
	        			$post_settings['tax_input'][$setting->id] = $setting->value;	
	        		}
        		break;
        		case 'taxonomy_new':
	        		if(empty($post_settings['tax_input'])){
	        			$post_settings['tax_input'] = array();
	        		}
	        		//$setting->id is taxonomy
	        		if(!empty($setting->value)){
	        			$term = term_exists($setting->value, $setting->id);
			            if ($term !== 0 && $term !== null) {
			               
			            }else{
        					$new_term = wp_insert_term($setting->value, $setting->id);
        					$setting->value = $new_term['term_id'];
        				}
        				$post_settings['tax_input'][$setting->id] = $setting->value;	
	        		}
        		break;
        		case 'featured_image':
        			$featured_thumb = $setting->value;
        		break;
        		default:
        			$post_settings[$setting->id] = $setting->value;
        		break;
        	}
        }

        $post_id = wp_update_post($post_settings);


        if(is_numeric($post_id) && $post_id){
            if(isset($featured_thumb) && is_numeric($featured_thumb))
                set_post_thumbnail($post_id,$featured_thumb);

            echo $post_id;    
        }else{
            _e('Unable to create course, contact admin !','wplms-front-end');
        }
        
    	die();
    }
    /* ===== END Save Course ===*/
    function save_course_settings(){
    	$course_id = $_POST['course_id'];

    	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }


        if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }

        $post_author = get_post_field('post_author',$course_id);
        $user_id = get_current_user_id();
        if($post_author != $user_id && !current_user_can('manage_options')){ // Instructor and Admin check
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }
        $settings = json_decode(stripslashes($_POST['settings']));

        foreach($settings as $setting){
        	if(!empty($setting->id) && !empty($setting->value)){
                if(is_object($setting->value)){
                    $array = array();
                    foreach($setting->value as $k => $v){
                        if(is_object($v)){
                            foreach($v as $k1 => $v1){
                                $new_v[$k1]=$v1;
                            }
                            $v = $new_v;
                        }
                        $array[$k]=$v;
                    }
                    $setting->value = $array;
                }
        		update_post_meta($course_id,$setting->id,$setting->value);
        	}
        	if(!empty($setting->id) && empty($setting->value)){
        		delete_post_meta($course_id,$setting->id);
        	}
        }
        echo $course_id;
        do_action('wplms_front_end_save_course_settings',$course_id,$settings);
    	die();
    }
    function save_course_components(){
    	$course_id = $_POST['course_id'];

    	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }


        if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }

        $post_author = get_post_field('post_author',$course_id);
        $user_id = get_current_user_id();
        if($post_author != $user_id && !current_user_can('manage_options')){ // Instructor and Admin check
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }
        $settings = json_decode(stripslashes($_POST['settings']));

        foreach($settings as $setting){
        	if(!empty($setting->id) && !empty($setting->value)){
        		$flag = apply_filters('wplms_front_end_process_compoenents',1,$setting);
        		if($flag)
        			update_post_meta($course_id,$setting->id,$setting->value);
        	}
        	if(!empty($setting->id) && empty($setting->value)){
        		delete_post_meta($course_id,$setting->id);
        	}
        }
        echo $course_id;
        do_action('wplms_front_end_save_course_components',$course_id,$settings);
    	die();
    }

    function preview_element(){
        $course_id = $_POST['course_id'];

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['element_id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }

        $post_type = get_post_type($_POST['element_id']);
        ?>
        <div class="element_overlay">
            <span class="close-pop dashicons dashicons-no-alt"></span>
            <?php
                if($post_type == 'unit'){
                    the_unit($_POST['element_id']);
                }else if($post_type == 'quiz'){ 
                    the_quiz(array('quiz_id'=>$_POST['element_id']));
                }else {
                    echo apply_filters('the_content',get_post_field('post_content',$_POST['element_id']));
                }
                $buttons = array(
                array(
                        'label'=>__('FULL PREVIEW & STATS','vibe'),
                        'id'=>'preview_element_button',
                        'href'=>get_permalink($_POST['element_id']),
                        'type'=>'small_button'
                        ),
                array(
                        'label'=>__('Close','vibe'),
                        'id'=>'close_element_button',
                        'type'=>'small_button'
                        ),
                );
                foreach($buttons as $button){
                    WPLMS_Front_End_Fields::generate_fields($button,$_POST['element_id']);
                }
            ?>
        </div>    
        <?php 
        die();   
    }
    function get_element(){
    	$course_id = $_POST['course_id'];

    	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['element_id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }

        $post_type = get_post_type($_POST['element_id']);

        ?>
        <div class="element_overlay">
        	<span class="close-pop dashicons dashicons-no-alt"></span>
	        <div class="list-group list-group-sm open">
	        	<div class="list-group-item accordion_trigger">
	        		<h3><?php _e('CONTENT','wplms-front-end'); ?><span></span></h3>
	        	</div>
	            <div class="list-group-item">
	              <label><?php _e('Title','wplms-front-end'); ?></label>  
	              <input type="text" class="post_field megatext" data-type="post_title" id="post_title" placeholder="<?php _e('Enter title','wplms-front-end'); ?>" value="<?php echo get_the_title($_POST['element_id']); ?>" class="form-control no-border" required="" tabindex="0" aria-required="true" aria-invalid="true">
	            </div>
                <div class="list-group-item">
                <?php
                    if($post_type == 'quiz'){
                        $taxonomy = 'quiz-type';
                    }else{
                        $taxonomy = 'module-tag';
                    }
                    $field = array(
                            'label'=> __('Category','vibe'),
                            'type'=> 'taxonomy',
                            'taxonomy'=> $taxonomy,
                            'from'=>'taxonomy',
                            'value_type'=>'single',
                            'style'=>'',
                            'id' => $taxonomy.'_id',
                            'default'=> __('Select Category','vibe'),
                        );
                    WPLMS_Front_End_Fields::generate_fields($field);
                                        
                ?>
                </div>
	            <div class="list-group-item">
	               <label><?php _e('Content','wplms-front-end'); ?></label> 
	               <?php
	                $content = get_post_field('post_content',$_POST['element_id']);
                    $settings =   array(
                        'wpautop' => true,
                        'media_buttons' => true, 
                        'editor_class'=>'post_field',
                        'textarea_name' => 'post_content_'.$post_type, 
                        'textarea_rows' => 10,
                        'tabindex' => '',
                        'editor_css' => '', 
                        'teeny' => true, 
                        'dfw' => true,
                        'tinymce' => true, 
                        'quicktags' => true 
                    );
                    $id_string = $post_type.'__'.rand(0,999);
	               	wp_editor($content,'post_content_'.$id_string,$settings);
	               ?>
                   <script>
                        tinyMCE.execCommand('mceAddEditor', false, 'post_content_<?php echo $id_string; ?>'); 
                        quicktags({id : 'post_content_<?php echo $id_string; ?>'});
                        tinyMCE.triggerSave();
                </script>
	            </div>
	        </div>    
	        <div class="list-group list-group-sm">
	        	<div class="list-group-item accordion_trigger">
	        		<h3><?php _e('SETTINGS','wplms-front-end'); ?><span></span></h3>
	        	</div>
	            <?php
            	$settings = vibe_meta_box_arrays($post_type);
            	if($post_type == 'quiz'){
            		$settings['vibe_quiz_dynamic']=array( // Text Input
						'label'	=> __('Dynamic Quiz','vibe-customtypes'), // <label>
						'desc'	=> __('Dynamic quiz automatically selects questions.','vibe-customtypes'), // description
						'id'	=> 'vibe_quiz_dynamic', // field id and name
						'type'	=> 'conditionalswitch', // type of field
						'hide_nodes'=> array('vibe_quiz_tags','vibe_quiz_number_questions','vibe_quiz_marks_per_question'),
						'options'  => array('H'=>__('DISABLE','vibe'),'S'=>__('ENABLE','vibe')),
						'style'=>'',
						'from'=> 'meta',
						'default'=>'H',
					);
					unset($settings['vibe_quiz_questions']);
            	}
                if($post_type == 'unit'){
                    if(!empty($settings['vibe_assignment'])){
                        unset($settings['vibe_assignment']);
                    }
                }
                if($post_type == 'wplms-assignment'){
                    
                        $assignment_duration_parameter = apply_filters('vibe_assignment_duration_parameter',86400);
                        $settings[3]['extra'] = calculate_duration_time($assignment_duration_parameter);
                    
                }
            	foreach($settings as $setting){
            		if($setting['type'] != 'small_button'){
            			echo '<div class="list-group-item vibe_'.$setting['id'].'">';
            			WPLMS_Front_End_Fields::generate_fields($setting,$_POST['element_id']);
            		echo '</div>';
            		}else{
            			WPLMS_Front_End_Fields::generate_fields($setting,$_POST['element_id']);
            		}
            	}
	            ?>
	        </div>
	        <?php
            if(get_post_type($_POST['element_id']) == 'unit' && class_exists('WPLMS_Assignments')){
            ?>
            <div class="list-group list-group-sm list-group-assignments post_field" data-id="vibe_assignment">
                <div class="list-group-item accordion_trigger">
                    <h3><?php _e('Assignments','wplms-front-end'); ?><span></span></h3>
                </div>  
                <?php
                    $setting = apply_filters('wplms_front_end_unit_assignments',array(
                        'label' => __('Unit assignments','vibe-customtypes'), // <label>
                        'desc'  => __('Select assignment for Unit','vibe-customtypes'), // description
                        'id'    => 'vibe_assignment', // field id and name
                        'type'  => 'assignment', // type of field
                        'cpt'=> 'assignment',
                        'from'=> 'meta',
                        'buttons' => array('add_assignment'=>__('ADD ASSIGNMENT','vibe'))
                        ));
                    if(!empty($_POST['element_id'])){
                        $setting['value'] = get_post_meta($_POST['element_id'],'vibe_assignment',true);
                    }
                    
                    WPLMS_Front_End_Fields::generate_fields($setting,$_POST['element_id']);
                ?>
            </div>              
            <?php
                }
            if(get_post_type($_POST['element_id']) == 'quiz'){
	        ?>
	        <div class="list-group list-group-sm list-group-questions post_field" data-id="vibe_quiz_questions">
	        	<div class="list-group-item accordion_trigger">
	        		<h3><?php _e('QUESTIONS','wplms-front-end'); ?><span></span></h3>
	        	</div>	
        		<?php
        			$setting = apply_filters('wplms_front_end_quiz_questions',array(
        				'label'	=> __('Quiz Questions','vibe-customtypes'), // <label>
						'desc'	=> __('Static Quiz questions','vibe-customtypes'), // description
						'id'	=> 'vibe_quiz_questions', // field id and name
						'type'	=> 'quiz_questions', // type of field
						'cpt'=> 'question',
						'from'=> 'meta',
                        'buttons' => array('add_quiz_question'=>__('ADD QUESTION','vibe'))
        				));
        			if(!empty($_POST['element_id'])){
        				$setting['value'] = get_post_meta($_POST['element_id'],'vibe_quiz_questions',true);
        			}
        			
        			WPLMS_Front_End_Fields::generate_fields($setting,$_POST['element_id']);
        		?>
        	</div>	        	
	        <?php
                }

		        $buttons = array(
                    array(
							'label'=>__('SAVE','vibe'),
							'id'=>'save_element_button',
							'data-id'=>$_POST['element_id'],
							'type'=>'small_button'
							),
	    	        array(
							'label'=>__('EDIT IN ADMIN','vibe'),
							'id'=>'edit_element_button',
							'href'=>get_edit_post_link($_POST['element_id']),
							'type'=>'small_button'
							),
		    	    array(
							'label'=>__('PREVIEW','vibe'),
							'id'=>'preview_element_button',
							'href'=>get_permalink($_POST['element_id']),
							'type'=>'small_button'
							),
                    array(
                        'label'=>__('Close','vibe'),
                        'id'=>'close_element_button',
                        'type'=>'small_button'
                        ),
                    );
                foreach($buttons as $button){
                    WPLMS_Front_End_Fields::generate_fields($button,$_POST['element_id']);
                }
	        ?>	
            </div>
	    </div>    
        <?php
    	die();
    }

    function save_element(){

    	$course_id = $_POST['course_id'];

    	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }

        $post_type = get_post_type($_POST['id']);
        $settings = json_decode(stripslashes($_POST['settings']));
        $post_fields = array();
        $meta_fields = array();
        $new_tax_flag = 0;
        foreach($settings as $setting){

            if(strpos($setting->id,"__")){
                $arr = explode("__", $setting->id, 2);
                $setting->id = $arr[0];
            }

            if( $setting->id == 'post_content' || $setting->id == 'post_content_unit' || $setting->id == 'post_content_quiz' || $setting->id == 'post_content_wplms-assignment' || $setting->id == 'post_content_question' )
                $setting->id = 'post_content';
            
        	if(in_array($setting->id,array('post_title','post_content','post_excerpt','post_author'))){
        		$post_fields[$setting->id] = $setting->value;
        	} if(in_array($setting->type,array('post_title','post_content','post_excerpt','post_author'))){
                $post_fields[$setting->type] = $setting->value;
            } if(in_array($setting->id,array('module-tag_id','quiz-type_id'))){
                $tax = str_replace('_id','',$setting->id);
                if($setting->value == 'new'){
                    $new_tax_flag = 1;
                }else{
                    $val = intval($setting->value);
                    $post_fields['tax_input'] = array($tax => array($val));    
                }
                
            }else{
    			if(is_object($setting->value)){
    				$array = array();
    				foreach($setting->value as $k => $v){
                        if(is_object($v)){
                            foreach($v as $k1 => $v1){
                                $new_v[$k1]=$v1;
                            }
                            $v = $new_v;
                        }
    					$array[$k]=$v;
    				}
    				$setting->value = $array;
    			}
                if(in_array($setting->id,array('module-tag','quiz-type')) && $new_tax_flag){
                    $term = term_exists($setting->value, $setting->id);
                    if ($term !== 0 && $term !== null) {
                       $term_id = $term;
                    }else{
                        $new_term = wp_insert_term($setting->value, $setting->id);
                        $term_id = $new_term['term_id'];
                    }
                    $post_fields['tax_input'] = array($setting->id => $term_id);    
                }else{
                    $meta_fields[$setting->id] = $setting->value;   
                }
        	}
        }
        
        
        if(!empty($post_fields)){
        	$post_fields['ID'] = $_POST['id'];
        	wp_update_post($post_fields);
        }
        if(!empty($meta_fields)){
        	foreach($meta_fields as $meta_key => $meta_value){
    			update_post_meta($_POST['id'],$meta_key,$meta_value);	
        	}
        }
        _e('Settings Saved','wplms-front-end');
        die();
    }

    function delete_element(){

        $element_id = $_POST['id'];

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }

        $author = get_post_field('post_author',$element_id);
        $user_id = get_current_user_id();
        if(current_user_can('manage_options') || $author == $user_id ){
            wp_delete_post($element_id);
            global $wpdb;
            $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->postmeta} WHERE post_id = %d",$element_id));
            echo 1;
        }else{
            _e('Can not delete element','wplms-front-end');
        }
        die();
    }
    function get_sub_element(){

        $question_id = $_POST['id'];

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }
        $post_type = get_post_type($question_id);
        $settings = vibe_meta_box_arrays($post_type);
        ?>
        <div class="<?php echo $post_type; ?>_edit_settings_content">
            <div class="list-group-item">
              <label><?php _e('Title','wplms-front-end'); ?></label>  
              <input type="text" class="post_field megatext" data-type="post_title" data-id="post_title" placeholder="<?php _e('Enter title','wplms-front-end'); ?>" value="<?php echo get_the_title($question_id); ?>" class="form-control no-border" required="" tabindex="0" aria-required="true" aria-invalid="true">
            </div>
            <div class="list-group-item">
               <label><?php _e('Content','wplms-front-end'); ?></label>  
               <?php
                $content = get_post_field('post_content',$question_id);
                $id_string = $post_type.'__'.rand(0,999);
                wp_editor($content,'post_content_'.$id_string,array('editor_class'=>'post_field'));
               ?>
               <script>
                    tinyMCE.execCommand("mceRemoveEditor", true, "<?php echo 'post_content_'.$id_string; ?>");
                    tinyMCE.execCommand("mceAddEditor", false, "<?php echo 'post_content_'.$id_string; ?>"); 
                    quicktags({id : "<?php echo 'post_content_'.$id_string; ?>"});
                    tinyMCE.triggerSave();
                </script>
            </div>
        
        <?php
        foreach($settings as $setting){
            ?><div class="list-group-item"><?php
                WPLMS_Front_End_Fields::generate_fields($setting,$question_id);
            ?></div><?php
        }
        $buttons = apply_filters('wplms_front_end_element_buttons',array(
                array(
                        'label'=>__('SAVE','vibe'),
                        'id'=>'save_element_button',
                        'data-id'=>$question_id,
                        'type'=>'small_button'
                        ),
                array(
                        'label'=>__('EDIT IN ADMIN','vibe'),
                        'id'=>'edit_element_button',
                        'href'=>get_edit_post_link($question_id),
                        'type'=>'small_button'
                        ),
                array(
                        'label'=>__('PREVIEW & STATS','vibe'),
                        'id'=>'preview_element_button',
                        'href'=>get_permalink($question_id),
                        'type'=>'small_button'
                        ),
                array(
                        'label'=>__('Close','vibe'),
                        'id'=>'close_element_button',
                        'type'=>'small_button'
                        ),
                ));
            foreach($buttons as $button){
                WPLMS_Front_End_Fields::generate_fields($button,$question_id);
            }
        ?>
        </div> 
        <?php
        die();
    }
    function preview_sub_element(){
        $question_id = $_POST['id'];

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }
        ?>
        <?php
        $post_type = get_post_type($question_id);
        $flag = apply_filters('wplms_front_end_get_question',1,$question_id); // For future purpose
        if($flag){
            ?>
            <div class="question_display" id="<?php echo $question_id; ?>">
            <?php
                $args = apply_filters('preview_question_element',array('p'=>$question_id,'post_type'=>$post_type));
                $the_query = new WP_Query($args);
                if ( $the_query->have_posts() ) {
                    while ( $the_query->have_posts() ) { $the_query->the_post();
                        global $post;
                        the_question();
                    }
                }
                wp_reset_postdata();
                $buttons = array(
                array(
                        'label'=>__('FULL PREVIEW & STATS','vibe'),
                        'id'=>'preview_element_button',
                        'href'=>get_permalink($question_id),
                        'type'=>'small_button'
                        ),
                array(
                        'label'=>__('Close','vibe'),
                        'id'=>'close_element_button',
                        'type'=>'small_button'
                        ),
                );
            foreach($buttons as $button){
                WPLMS_Front_End_Fields::generate_fields($button,$question_id);
            }
            ?>
            </div>
        <?php

        }
        die();
    }


    function create_new_question(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }

        if(empty($_POST['title'])){
            _e('Empty title.','wplms-front-end');
            die();
        }
        $template = $_POST['template'];
        include_once 'class.templates.php';
        $settings = WPLMS_Content_Templates::get_template('question',$_POST['template']);
        if($_POST['question_tag'] == 'new'){
            $term = term_exists($_POST['new_question_tag'], 'question-tag');
            if ($term !== 0 && $term !== null) {
               $term_id = $term;
            }else{
                $new_term = wp_insert_term($_POST['new_question_tag'], 'question-tag');
                $term_id = $new_term['term_id'];
            }
        }
        $args = apply_filters('wplms_front_end_create_question',array(
            'post_type' => 'question',
            'post_title' => stripslashes($_POST['title']),
            'post_content' => $settings['post_content'],
            'post_status'=>'publish',
            'tax_input'=> array('question-tag'=> array($term_id))
            ));
        $id = wp_insert_post($args);
        foreach($settings['meta_fields'] as $key => $value){
            update_post_meta($id,$key,$value);
        }
        echo $id;
        die();
    }

    function create_new_curriculum(){
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }

        if(empty($_POST['title'])){
            _e('Empty title.','wplms-front-end');
            die();
        }

        $args = apply_filters('wplms_front_end_create_curriculum',array(
            'post_type' => $_POST['cpt'],
            'post_title' => stripslashes($_POST['title']),
            'post_content' => stripslashes($_POST['title']),
            'post_status'=>'publish',
        ));
        $id = wp_insert_post($args);
        echo $id;
        die();
    }

    function save_course_curriculum(){
        
        $user_id= get_current_user_id();
        $course_id =$_POST['course_id'];

        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }
         if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }

        $course_author = get_post_field('post_author',$course_id);
        if($course_author != $user_id && !current_user_can('manage_options')){
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }

        $objcurriculum = json_decode(stripslashes($_POST['curriculum']));
        if(is_array($objcurriculum) && isset($objcurriculum))
        foreach($objcurriculum as $c){
            $curriculum[]=$c->id;
        }
        
       // $curriculum=array(serialize($curriculum)); // Backend Compatiblity
        update_post_meta($course_id,'vibe_course_curriculum',$curriculum);
        echo $course_id;
        do_action('wplms_course_curriculum_updated',$course_id,$curriculum);
        
        die();
    }

    function set_product(){
        $course_id =$_POST['course_id'];
        $user_id= get_current_user_id();
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts') || !is_numeric($_POST['product_id'])){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }
         if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }
        $course_author = get_post_field('post_author',$course_id);
        if($course_author != $user_id && !current_user_can('manage_options')){
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }
        $product_id = $_POST['product_id'];

        $courses = array($course_id);
        update_post_meta($product_id,'vibe_courses',$courses);
        update_post_meta($course_id,'vibe_product',$product_id);
        if(is_numeric($product_id)){
            $product = wc_get_product($product_id);
            echo $post_fields['post_title'].'<span class="edit_product">'.__('Edit','wplms-front-end').'</span><strong class="price">'.$product->get_price_html().'<strong>
            <input type="hidden" class="post_field" data-id="vibe_product" value="'.$product_id.'" />';
            do_action('wplms_front_end_save_course_pricing',$course_id);
        }
        die();
    }

    function create_new_product(){

        $course_id =$_POST['course_id'];
        $user_id= get_current_user_id();
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }
         if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }
        $course_author = get_post_field('post_author',$course_id);
        if($course_author != $user_id && !current_user_can('manage_options')){
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }
        $settings = json_decode(stripslashes($_POST['settings']));

        $post_fields=array('post_type' => 'product','post_status'=>'publish');
        foreach($settings as $setting){
            if(in_array($setting->id,array('ID','post_title','post_content'))){
                $post_fields[$setting->id] = $setting->value;
            }else{
                if($setting->id == 'vibe_subscription1'){
                    $setting->id = 'vibe_subscription';
                }
                $meta_fields[$setting->id] = $setting->value;
            }
        }

        if(empty($post_fields['post_title'])){
            $post_fields['post_title'] = get_post_field('post_title',$course_id);
        }
        if(empty($post_fields['post_content'])){
            $post_fields['post_content'] = get_post_field('post_content',$course_id);
        }

        if(empty($post_fields['post_id'])){
            $product_id = wp_insert_post($post_fields);    
        }else{
            $product_id = wp_update_post($post_fields);    
        }
        
        foreach($meta_fields as $key => $value){
            update_post_meta($product_id,$key,$value);
        }
        wp_set_object_terms($product_id, 'simple', 'product_type');
        if(empty($meta_fields['_sale_price'])){
            update_post_meta($product_id,'_price',$meta_fields['_regular_price']);
        }else{
            update_post_meta($product_id,'_price',$meta_fields['_sale_price']);
        }

        update_post_meta($product_id,'_visibility','visible');
        update_post_meta($product_id,'_virtual','yes');
        update_post_meta($product_id,'_downloadable','yes');
        update_post_meta($product_id,'_sold_individually','yes');

        $courses = array($course_id);
        update_post_meta($product_id,'vibe_courses',$courses);
        
        update_post_meta($course_id,'vibe_product',$product_id);
        $thumbnail_id = get_post_thumbnail_id($course_id);
        set_post_thumbnail($product_id,$thumbnail_id);

        if(is_numeric($product_id)){
            $product = wc_get_product($product_id);
            echo $post_fields['post_title'].'<span class="change_product">'.__('Change','wplms-front-end').'</span><span class="edit_product">'.__('Edit','wplms-front-end').'</span><strong class="price">'.$product->get_price_html().'<strong>
            <input type="hidden" class="post_field" data-id="vibe_product" value="'.$product_id.'" />';
            do_action('wplms_front_end_save_course_pricing',$course_id);
        }
        die();
    }

    function new_save_pricing(){

        $course_id = $_POST['course_id'];
        $user_id = get_current_user_id();
        if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security')  || !current_user_can('edit_posts')){
             _e('Security check Failed. Contact Administrator.','wplms-front-end');
             die();
        }


        if(!is_numeric($course_id) || get_post_type($course_id) != 'course'){
            _e('Invalid Course id, please edit a course','wplms-front-end');
             die();
        }

        $post_author = get_post_field('post_author',$course_id);
        if($post_author != $user_id && !current_user_can('manage_options')){ // Instructor and Admin check
            _e('Invalid Course Instructor','wplms-front-end');
             die();
        }
        $settings = json_decode(stripslashes($_POST['settings']));

        foreach($settings as $setting){
            if(!empty($setting->id) && !empty($setting->value)){
                if(is_object($setting->value)){
                    $array = array();
                    foreach($setting->value as $k => $v){
                        if(is_object($v)){
                            foreach($v as $k1 => $v1){
                                $new_v[$k1]=$v1;
                            }
                            $v = $new_v;
                        }
                        $array[$k]=$v;
                    }
                    $setting->value = $array;
                }
                update_post_meta($course_id,$setting->id,$setting->value);
            }
            if(!empty($setting->id) && empty($setting->value)){
                delete_post_meta($course_id,$setting->id);
            }
        }
        echo $course_id;
        do_action('wplms_front_end_save_course_pricing',$course_id,$settings);
        die();
    }

    function get_group_name($group_id){
        global $wpdb,$bp;
        $name = $wpdb->get_var($wpdb->prepare("SELECT name from {$bp->groups->table_name} WHERE id = %d",$group_id));
        return $name;
    }
    function get_group_permalink($group_id){
        global $wpdb,$bp;
        $pages = get_option('bp-pages');
        $link = get_permalink($pages['groups']);
        $slug = $wpdb->get_var($wpdb->prepare("SELECT slug from {$bp->groups->table_name} WHERE id = %d",$group_id));
        return $link.$slug;
    }   
}

WPLMS_Process_Fields::init();
