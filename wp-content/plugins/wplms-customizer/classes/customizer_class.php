<?php

if(!class_exists('WPLMS_Customizer_Plugin_Class'))
{   
    class WPLMS_Customizer_Plugin_Class  // We'll use this just to avoid function name conflicts 
    {
            
        public function __construct(){   
            
        } // END public function __construct
        public function activate(){
        	// ADD Custom Code which you want to run when the plugin is activated

		add_filter('wplms_product_course_order_filter','wplms_rearrange_courses');
		function wplms_rearrange_courses($courses){

			foreach($courses as $course){
 				 $new_courses[get_post_field('menu_order',$course)]=$course;
			}
			$courses = ksort($new_courses); //Sort courses by Menu order value
			return $courses;
		}
            add_action('bp_init',array($this,'wplms_remove_instructor_button'));  // Removes the Become and Instructor Button
            add_action('wplms_be_instructor_button',array($this,'wplms_be_instructor_button')); // Adds Extra function to run in place of Instructor button
        }
        public function deactivate(){
        	// ADD Custom Code which you want to run when the plugin is de-activated	
        }
        
        // ADD custom Code in clas

        
    } // END class WPLMS_Customizer_Class
} // END if(!class_exists('WPLMS_Customizer_Class'))

?>