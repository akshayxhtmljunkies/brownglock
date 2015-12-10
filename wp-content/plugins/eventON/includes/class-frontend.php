<?php
/**
 * evo_frontend class for front and backend.
 *
 * @class 		evo_frontend
 * @version		0.2
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */

class evo_frontend {

	private $content;
	public $evo_options;

	public function __construct(){
		global $eventon;

		// eventon related wp options access on frontend
		$this->evo_options = get_option('evcal_options_evcal_1');

		// hooks for frontend
		add_action( 'init', array( $this, 'register_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_default_evo_styles' ), 10 );
		add_action( 'wp_head', array( $this, 'load_dynamic_evo_styles' ), 50 );

		$this->evopt1 = $eventon->evo_generator->evopt1;

		if(empty($this->evopt1['evcal_header_generator']) || (!empty($this->evopt1['evcal_header_generator']) && $this->evopt1['evcal_header_generator']!='yes')){
			add_action( 'wp_head', array( $this, 'generator' ) );
		}		
	}

	// styles and scripts
		public function register_scripts() {
			global $eventon;
			
			$evo_opt= $this->evo_options;			
			
			// Google gmap API script -- loadded from class-calendar_generator.php	
			wp_register_script('evo_mobile',AJDE_EVCAL_URL.'/assets/js/jquery.mobile.min.js', array('jquery'), $eventon->version, true ); // 2.2.17
			wp_register_script('evcal_easing', AJDE_EVCAL_URL. '/assets/js/jquery.easing.1.3.js', array('jquery'),'1.0',true );//2.2.24
			wp_register_script('evcal_functions', AJDE_EVCAL_URL. '/assets/js/eventon_functions.js', array('jquery'), $eventon->version ,true );// 2.2.22
			wp_register_script('evcal_ajax_handle', AJDE_EVCAL_URL. '/assets/js/eventon_script.js', array('jquery'), $eventon->version ,true );
			wp_localize_script( 
				'evcal_ajax_handle', 
				'the_ajax_script', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ) , 
					'postnonce' => wp_create_nonce( 'eventon_nonce' )
				)
			);

			// google maps	
			wp_register_script('eventon_gmaps', AJDE_EVCAL_URL. '/assets/js/maps/eventon_gen_maps.js', array('jquery'), $eventon->version ,true );	
			wp_register_script('eventon_init_gmaps', AJDE_EVCAL_URL. '/assets/js/maps/eventon_init_gmap.js', array('jquery'),'1.0',true );
			wp_register_script( 'eventon_init_gmaps_blank', AJDE_EVCAL_URL. '/assets/js/maps/eventon_init_gmap_blank.js', array('jquery'), $eventon->version ,true ); // load a blank initiate gmap javascript

			wp_register_script( 'evcal_gmaps', apply_filters('eventon_google_map_url', 'https://maps.googleapis.com/maps/api/js?sensor=false'), array('jquery'),'1.0',true);

			// STYLES
			wp_register_style('evo_font_icons',AJDE_EVCAL_URL.'/assets/fonts/font-awesome.css');		
			
			// Defaults styles and dynamic styles
			wp_register_style('evcal_cal_default',AJDE_EVCAL_URL.'/assets/css/eventon_styles.css');	
			//wp_register_style('evo_dynamic_css', admin_url('admin-ajax.php').'?action=evo_dynamic_css');


			global $is_IE;
			if ( $is_IE ) {
				wp_register_style( 'ieStyle', AJDE_EVCAL_URL.'/assets/css/ie.css', array(), '1.0' );
				wp_enqueue_style( 'ieStyle' );
			}

			// LOAD custom google fonts for skins	
			//$gfonts = (is_ssl())? 'https://fonts.googleapis.com/css?family=Oswald:400,300|Open+Sans:400,300': 'http://fonts.googleapis.com/css?family=Oswald:400,300|Open+Sans:400,300';	
			$gfonts="//fonts.googleapis.com/css?family=Oswald:400,300|Open+Sans:400,300";
			wp_register_style( 'evcal_google_fonts', $gfonts, '', '', 'screen' );
			
			$this->register_evo_dynamic_styles();
		}
		public function register_evo_dynamic_styles(){
			$opt= $this->evo_options;
			if(!empty($opt['evcal_css_head']) && $opt['evcal_css_head'] =='no' || empty($opt['evcal_css_head'])){
				if(is_multisite()) {
					$uploads = wp_upload_dir();
					wp_register_style('eventon_dynamic_styles', $uploads['baseurl'] . '/eventon_dynamic_styles.css', 'style');
				} else {
					wp_register_style('eventon_dynamic_styles', 
						AJDE_EVCAL_URL. '/assets/css/eventon_dynamic_styles.css', 'style');
				}
			}
		}
		
		public function load_dynamic_evo_styles(){
			$opt= $this->evo_options;
			if(!empty($opt['evcal_css_head']) && $opt['evcal_css_head'] =='yes'){
				
				$dynamic_css = get_option('evo_dyn_css');
				if(!empty($dynamic_css)){
					echo '<style type ="text/css">'.$dynamic_css.'</style>';
				}				
			}else{
				wp_enqueue_style( 'eventon_dynamic_styles');
			}
		}
		public function load_default_evo_scripts(){
			//wp_enqueue_script('add_to_cal');
			wp_enqueue_script('evcal_functions');
			wp_enqueue_script('evo_mobile');
			wp_enqueue_script('evcal_ajax_handle');			
			wp_enqueue_script('eventon_gmaps');

			do_action('eventon_enqueue_scripts');
			
		}
		public function load_default_evo_styles(){
			$opt= $this->evo_options;
			if(empty($opt['evo_googlefonts']) || $opt['evo_googlefonts'] =='no')
				wp_enqueue_style( 'evcal_google_fonts' );

			wp_enqueue_style( 'evcal_cal_default');	
			if(empty($opt['evo_fontawesome']) || $opt['evo_fontawesome'] =='no')
				wp_enqueue_style( 'evo_font_icons' );
		}
		public function load_evo_scripts_styles(){
			$this->load_default_evo_scripts();
			$this->load_default_evo_styles();
		}
		public function evo_styles(){
			add_action('wp_head', array($this, 'load_default_evo_scripts'));
		}

	// language
		function lang($evo_options = '', $field, 
			$default_val, 
			$lang = ''
		){
			global $eventon;
				
			// check which language is called for
			$evo_options = (!empty($evo_options))? $evo_options: get_option('evcal_options_evcal_2');
			
			// check for language preference
			if(!empty($lang)){
				$_lang_variation = $lang;
			}else{
				$shortcode_arg = $eventon->evo_generator->shortcode_args;
				$_lang_variation = (!empty($shortcode_arg['lang']))? $shortcode_arg['lang']:'L1';
			}
			
			$new_lang_val = (!empty($evo_options[$_lang_variation][$field]) )?
				stripslashes($evo_options[$_lang_variation][$field]): $default_val;
				
			return $new_lang_val;
		}

	// Event Type Taxonomies
		function get_localized_event_tax_names($lang='', $options='', $options2=''
		){
			$output ='';

			$options = (!empty($options))? $options: get_option('evcal_options_evcal_1');
			$options2 = (!empty($options2))? $options2: get_option('evcal_options_evcal_2');
			$_lang_variation = (!empty($lang))? $lang:'L1';

			
			// foreach event type upto activated event type categories
			for( $x=1; $x< (evo_get_ett_count($options)+1); $x++){
				$ab = ($x==1)? '':$x;

				$_tax_lang_field = 'evcal_lang_et'.$x;

				// check on eventon language values for saved name
				$lang_name = (!empty($options2[$_lang_variation][$_tax_lang_field]))? 
					stripslashes($options2[$_lang_variation][$_tax_lang_field]): null;

				// conditions
				if(!empty($lang_name)){
					$output[$x] = $lang_name;
				}else{
					$output[$x] = (!empty($options['evcal_eventt'.$ab]))? $options['evcal_eventt'.$ab]:'Event Type '.$ab;
				}			
			}
			return $output;
		}
		function get_localized_event_tax_names_by_slug($slug, $lang=''){
			$options = get_option('evcal_options_evcal_1');
			$options2 = get_option('evcal_options_evcal_2');
			$_lang_variation = (!empty($lang))? $lang:'L1';

			// initial values
			$x = ($slug=='event_type')?'1': (substr($slug,-1));
			$ab = ($x==1)? '':$x;
			$_tax_lang_field = 'evcal_lang_et'.$x;

			// check on eventon language values for saved name
			$lang_name = (!empty($options2[$_lang_variation][$_tax_lang_field]))? 
				stripslashes($options2[$_lang_variation][$_tax_lang_field]): null;

			// conditions
			if(!empty($lang_name)){
				return $lang_name;
			}else{
				return (!empty($options['evcal_eventt'.$ab]))? $options['evcal_eventt'.$ab]:'Event Type '.$ab;
			}	

		}

	/*
	// replaced
		// throw popup window
			public function popup_window($arg){
				$defaults = array(
					'content'=>'',
					'class'=>'regular',
					'attr'=>'',
					'title'=>'',
					'subtitle'=>'',
					'type'=>'normal',
					'hidden_content'=>'',
					'width'=>'',
				);
				$args = (!empty($arg) && is_array($arg) && count($arg)>0) ? array_merge($defaults, $arg) : $defaults;
				
				
				
				$_padding_class = (!empty($args['type']) && $args['type']=='padded')? ' padd':null;

				//print_r($args);
				$content='';
				$content .= 
				"<div class='eventon_popup {$args['class']}{$_padding_class}' {$args['attr']} style='display:none; ". ( (!empty($args['width']))? 'width:'.$args['width'].'px;':null )."'>				
						<div class='evoPOP_header'>
							<a class='evopop_backbtn' style='display:none'><i class='fa fa-angle-left'></i></a>
							<p id='evoPOP_title'>{$args['title']}</p>
							". ( (!empty($args['subtitle']))? "<p id='evoPOP_subtitle'>{$args['subtitle']}</p>":null) ."
							<a class='eventon_close_pop_btn'>X</a>
						</div>
								
						<div id='eventon_loading'></div>";

					$content .= (!empty($args['max_height']))? "<div class='evo_lightbox_outter maxbox' style='max-height:{$args['max_height']}px'>":null;
					$content .= "<div class='eventon_popup_text'>{$args['content']}</div>";
					$content .= (!empty($args['max_height']))? "</div>":null;
					$content .= "	<p class='message'></p>
						
					</div>
				";
				
				$this->content .= $content;
				add_action('admin_footer', array($this, 'actual_output_popup'));
			}
			function actual_output_popup($content){			
				echo "<div id='eventon_popup_outter'>";
				echo $this->content;
				echo "</div><div id='evo_popup_bg'></div>";
			}

		// get HTML for legend guide
			public function get_html_guide($content, $position=''){			
				$L = (!empty($position) && $position=='L')? ' L':null;			
				return "<span class='evoGuideCall{$L}'>?<em>{$content}</em></span>";
			}

		// frontend tool tip
			public function tooltip($content, $position='', $echo=false){
				$L = (!empty($position) && $position=='L')? ' L':null;

				$output = "<span class='evoGuideCall{$L}'><em>{$content}</em></span>";

				if(!$echo)
					return $output;			
				
				echo $output;
			}
		*/

	// EMAILING	
		// get email parts
			public function get_email_part($part){
				global $eventon;

				$file_name = 'email_'.$part.'.php';

				$paths = array(
					0=> TEMPLATEPATH.'/'.$eventon->template_url.'templates/email/',
					1=> AJDE_EVCAL_PATH.'/templates/email/',
				);

				foreach($paths as $path){				
					if(file_exists($path.$file_name) ){	
						$template = $path.$file_name;	
						break;
					}//echo($path.$file_name.'<br/>');
				}

				ob_start();

				include($template);

				return ob_get_clean();
			}

		// Get email body parts
		// to pull full email templates
			public function get_email_body($part, $def_location, $args='', $paths=''){
				$file_name = $part.'.php';
				global $eventon;

				if(empty($paths) && !is_array($paths)){
					$paths = array(
						0=> TEMPLATEPATH.'/'.$eventon->template_url.'templates/email/',
						1=> $def_location,
					);
				}

				foreach($paths as $path){	
					// /echo $path.$file_name.'<br/>';			
					if(file_exists($path.$file_name) ){	
						$template = $path.$file_name;	
						break;
					}
				}

				ob_start();

				if($template)
					include($template);

				return ob_get_clean();
			}
	// front-end website
		/** Output generator to aid debugging. */
			public function generator() {
				global $eventon;
				echo "\n\n" . '<!-- EventON Version -->' . "\n" . '<meta name="generator" content="EventON ' . esc_attr( $eventon->version ) . '" />' . "\n\n";
			}
}