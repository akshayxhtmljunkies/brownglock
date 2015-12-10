<?php
/**
 * Template Loader
 *
 * @class 		EVO_Template_Loader
 * @version		2.2.9
 * @package		Eventon/Classes
 * @category	Class
 * @author 		AJDE
 */
class EVO_Template_Loader {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_loader' ) , 99);
	}


		/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. eventon looks for theme
	 * overrides in /theme/eventon/ by default
	 *
	 * For beginners, it also looks for a eventon.php template first. If the user adds
	 * this to the theme (containing a eventon() inside) this will be used for all
	 * eventon templates.
	 *
	 * @access public
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		global $eventon_sin_event, $eventon;
		
		$file='';
		$sure_path = AJDE_EVCAL_PATH . '/templates/';		
		
		// Paths to check
		$paths = apply_filters('eventon_template_paths', array(
			0=>TEMPLATEPATH.'/',
			1=>TEMPLATEPATH.'/'.$eventon->template_url,
		));
		
		$evOpt = evo_get_options('1');
		$events_page_id = evo_get_event_page_id($evOpt);
		
		// single and archive events page
		if( is_single() && get_post_type() == 'ajde_events' ) {
			$file 	= 'single-ajde_events.php';

		// if this page is event archive page
		}elseif ( is_post_type_archive( 'ajde_events' )  ) {
			$file__ = evo_get_event_template($evOpt);
			$file 	= $file__;
			$paths[] 	= ($file__ == 'archive-ajde_events.php')?
				AJDE_EVCAL_PATH . '/templates/': get_template_directory();
		}
		// Event type taxonomy archive page
		elseif( is_tax(array('event_type', 'event_type_2', 'event_type_3', 'event_type_4','event_type_5'))){
			$file 	= 'taxonomy-event_type.php';
			$paths[] 	= AJDE_EVCAL_PATH . '/templates/';
		}
		// Event location taxonomy
		elseif( is_tax(array('event_location'))){
			$file 	= 'taxonomy-event_location.php';
			$paths[] 	= AJDE_EVCAL_PATH . '/templates/';
		}



		// FILE Exist
		if ( $file ) {			
			// each path
			foreach($paths as $path){				
				if(file_exists($path.$file) ){	
					$template = $path.$file;	
					break;
				}
			}		
							
			if ( ! $template ) { 
				$template = AJDE_EVCAL_PATH . '/templates/' . $file;
			}
		}
		
		//print_r($template);
		
		return $template;
	}
}
new EVO_Template_Loader();