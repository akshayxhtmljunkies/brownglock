<?php
/**
 * Initialise EventOn with WPLMS
 *
 * @author 		VibeThemes
 * @category 	Admin
 * @package 	WPLMS-eventon/Includes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Wplms_EventOn_Init{

	public static $instance;
    
    public static function init(){
    	
        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_EventOn_Init();
        return self::$instance;
    }

	private function __construct(){ 
		add_filter('wplms_course_nav_menu',array($this,'wplms_eventon_link'));
		add_action('wplms_load_templates',array($this,'wplms_eventon_page'));
		add_filter('eventon_event_metaboxs',array($this,'wplms_fields'));
		add_filter('eventon_event_metafields',array($this,'save_wplms_fields'));
		add_filter('eventon_wp_query_args',array($this,'wplms_events'),10,3);
		add_action('eventon_calendar_header_content',array($this,'wplms_hidden_course_element'),10,2);
		add_filter('eventon_wp_query_args',array($this,'wplms_ajax_events'),10,2);
	}


	function wplms_eventon_link($nav_menu){
    	$nav_menu['events'] = array(
                    'id' => 'events',
                    'label'=>__('Events ','wplms-eventon'),
                    'action' => 'events',
                    'link'=>bp_get_course_permalink(),
                );
    	return $nav_menu;
    }

    function wplms_eventon_page(){
    	if(isset($_GET['action']) && $_GET['action'] == 'events'){
    		echo do_shortcode('[add_eventon_dv cal_id="1" today="no" show_et_ft_img="no" ft_event_priority="no" day_incre="1" month_incre="1" wplms_course="1"]');
    	}
    }

    function wplms_events($args,$filters,$ecv){
    	global $post;
    	
    	if(!empty($ecv['wplms_course'])){
    		if($ecv['wplms_course'] == 1){
    			$args['meta_query'][]=array(
	    			'key' => 'wplms_ev_course',
	    			'value'=> $post->ID,
	    			'compare'=> '=',
	    			);
    		}else{
    			if(strpos($ecv['wplms_course'],',')){
    				$courses = explode(',',$ecv['wplms_course']);
    				$args['meta_query'][]=array(
			    			'key' => 'wplms_ev_course',
			    			'value'=> $courses,
			    			'compare'=> 'IN',
		    			);
    			}else{
    				if(is_numeric($ecv['wplms_course']))
	    				$args['meta_query'][]=array(
			    			'key' => 'wplms_ev_course',
			    			'value'=> $ecv['wplms_course'],
			    			'compare'=> '=',
		    			);
    			}
    		}
    	}
    	return $args;
    }

    function wplms_ajax_events($args,$filters){
    	$evodata = $_POST['evodata'];
    	if(!empty($evodata['course'])){
    		if(strpos($evodata['course'],',')){
				$courses = explode(',',$evodata['course']);
				$args['meta_query'][]=array(
		    			'key' => 'wplms_ev_course',
		    			'value'=> $courses,
		    			'compare'=> 'IN',
	    			);
			}else{
				if(is_numeric($evodata['course']))
    				$args['meta_query'][]=array(
		    			'key' => 'wplms_ev_course',
		    			'value'=> $evodata['course'],
		    			'compare'=> '=',
	    			);
			}
    	}

    	return $args;
    }

    function wplms_hidden_course_element($content,$ecv){
    	if(!empty($ecv['wplms_course'])){
    		if($ecv['wplms_course'] == 1){
    			echo '<span class="evo-data" data-course="'.get_the_ID().'"></span>';
    		}else{
    			echo '<span class="evo-data" data-course="'.$ecv['wplms_course'].'"></span>';
    		}
    	}
    	//
    }
	function wplms_fields($metabox){

		$metabox[]=array(
				'id'=>'wplms_ev_course',
				'name'=>__('WPLMS Courses','wplms-eventon'),
				'variation'=>'customfield',		
				'iconURL'=>'fa-book',
				'iconPOS'=>'',
				'type'=>'code',
				'content'=> self::get_course_list(),
				'slug'=>'ev_subtitle'
			);
		return $metabox;
	}

	function save_wplms_fields($fields){
		$fields[] = 'wplms_ev_course';
		return $fields;
	}

	function get_course_list(){
		// HTML - User Interaction
		ob_start();
		?>
			<div class='evcal_data_block_style1'>
				<p class='edb_icon evcal_edb_map'></p>
				<div class='evcal_db_data'>			
					<p>
					<?php
						// organier terms for event post
						$args = apply_filters('wplms_backend_cpt_query',array(
							'post_type' => 'course',
							'posts_per_page'=>-1,
							'orderby'=>'alphabetical',
							'order'=>'ASC',
							));
						unset($args['tax_query']); // remove linkage if any

						$wplms_courses = get_posts($args);
						global $post;
						$wplms_ev_course=get_post_meta($post->ID,'wplms_ev_course',true);
						if(count($wplms_courses) > 0){
							echo "<select id='wplms_ev_course' name='wplms_ev_course' class='chosen'>
								<option value=''>".__('Select a WPLMS Course','wplms-eventon')."</option>";
						    foreach ( $wplms_courses as $wplms_course ) {
						       	echo "<option value='". $wplms_course->ID ."' ".( ($wplms_ev_course == $wplms_course->ID )?"selected='selected'":"" ).">" . $wplms_course->post_title . "</option>";						        
						    }
						    echo "</select> <label for='evcal_organizer_field'>".__('Choose from published courses','eventon')."</label><style>.chosen-container .chosen-results li.active-result{color:#444;}</style>";
						}

					
					?>
					<p style='clear:both'></p>
				</div>
			</div>
		<?php
		$_html = ob_get_clean();
		return $_html; 
	}
}

Wplms_EventOn_Init::init();