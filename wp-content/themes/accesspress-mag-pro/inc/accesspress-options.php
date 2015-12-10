<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 * 
 * @package Accesspress Mag Pro
 */

function optionsframework_options() {
    
    $imagepath =  get_template_directory_uri() . '/inc/option-framework/images/';
    $patternpath = get_template_directory_uri() . '/inc/option-framework/images/patterns/80X80/';
    $preloaderpath = get_template_directory_uri().'/images/preloader/';
    
	// slider transition
	$transitions = array(
		'fade' => __('Fade', 'accesspress-mag' ),
		'horizontal' => __('Slide Horizontal', 'accesspress-mag' ),
		'vertical' => __('Slide Vertical', 'accesspress-mag' )
	);
    
	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );
        
    //Article Type
    $article_type = array(
          'category' => __( 'by category' , 'accesspress-mag' ),
          'tag' => __( 'by tag' , 'accesspress-mag' ),
          );

    
    // text transforms
	$text_transform = array(
		'uppercase' => __('Uppercase', 'accesspress-mag' ),
		'lowercase' => __('Lowercase', 'accesspress-mag' ),
		'capitalize' => __('Captalize', 'accesspress-mag' ),
		'none' => __('Normal', 'accesspress-mag' )
	);
    
     
	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
    $options_categories[]= __( 'Select category', 'accesspress-mag' );
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->slug] = $category->cat_name;
	}
    
    // Pull all the categories into an array
	$popular_category_option = array();
	$popular_categories_obj = get_categories();
    $popular_category_option['all']= __( 'All categories', 'accesspress-mag' );
	foreach ($popular_categories_obj as $category) {
		$popular_category_option[$category->slug] = $category->cat_name;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}
    
    //Pull all menus into an array
    $options_menus = array();
    $options_menus_obj = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
    $options_menus[] = __( 'Select Menu', 'accesspress-mag' );
    foreach($options_menus_obj as $menu) {
        $options_menus[$menu->slug] = $menu->name;
    }
    
    //Slide options for homepage slider
    $options_slides = array();
    for($i=1;$i<=10;$i++)
    {
        $options_slides[$i] = $i ;
    }
    
    //No.of posts for homepage blocks
    $options_block_posts = array();
    $options_block_posts[-1] = __( '-- All posts --', 'accesspress-mag' );
    for( $i=2; $i<=50; $i++ )
    {
        $options_block_posts[$i] = $i ;
    }
    
    
    //Footer Pattern
	$footer_pattern = array(
        'column4' => $imagepath . 'footers/footer-4.png',
        'column3' => $imagepath . 'footers/footer-3.png',
		'column2' => $imagepath . 'footers/footer-2.png', 
        'column1' => $imagepath . 'footers/footer-1.png',		   
		);
  
    // Website Background Options
	$background_options = array(
		'none' => __('-- None --', 'accesspress-mag' ),
		'image' => __('Image', 'accesspress-mag' ),
		'color' => __('Color', 'accesspress-mag' ),
		'pattern' => __('Pattern', 'accesspress-mag' ),
	);
        
    //Post Templates
    $post_template = array(
        'single' => $imagepath.'post_template/post-templates-icons-0.png',
        'single-style1' => $imagepath.'post_template/post-templates-icons-1.png', 
        'single-style2' => $imagepath.'post_template/post-templates-icons-2.png',
        'single-style3' => $imagepath.'post_template/post-templates-icons-3.png',
        'single-style4' => $imagepath.'post_template/post-templates-icons-4.png'
        );
        
    //Archive Templates
    $archive_template = array(
        'archive-default' => $imagepath.'post_template/post-templates-icons-0.png',
        'archive-style1' => $imagepath.'post_template/post-templates-icons-1.png',
        'archive-style2' => $imagepath.'post_template/post-templates-icons-2.png',
        );
    
    //Categories Templates
    $category_template = array(
        'global-archive-default' => $imagepath.'post_template/post-templates-icons-theme.png',
        'archive-default' => $imagepath.'post_template/post-templates-icons-0.png',
        'archive-style1' => $imagepath.'post_template/post-templates-icons-1.png',
        'archive-style2' => $imagepath.'post_template/post-templates-icons-2.png',
        );
        
    //Categories sidebars
    $category_sidebar = array(
        'global-sidebar' => $imagepath.'default-sidebar.png',
        'right-sidebar' => $imagepath.'right-sidebar.png',
        'left-sidebar' => $imagepath.'left-sidebar.png',
        'no-sidebar' => $imagepath.'no-sidebar.png',
        );
    
    // Header Layouts
    $ap_header_layout = array(
        ' ' => $imagepath.'header1.jpg',
        '2' => $imagepath.'header2.jpg',
        '3' => $imagepath.'header3.jpg',
        '4' => $imagepath.'header4.jpg',
    );

    // Homepage Layouts
    $homepage_templates = array(
        'home-default' => $imagepath.'home-default.jpg',
        'home-style1' => $imagepath.'home-style1.jpg',
    );

    // Homepage Slider Layouts
    $homepage_slider_layout = array(
        'slider-default' => $imagepath.'slider-default.jpg',
        'slider-single' => $imagepath.'slider-single.jpg',
        'slider-rev' => $imagepath.'slider-rev.jpg',
    );
    
    //Background Pattern
	$background_pattern = array(
		'pattern1' => $patternpath . 'pattern1.png', 
		'pattern2' => $patternpath . 'pattern2.png', 
		'pattern3' => $patternpath . 'pattern3.png', 
		'pattern4' => $patternpath . 'pattern4.png', 
		'pattern5' => $patternpath . 'pattern5.png', 
		'pattern6' => $patternpath . 'pattern6.png', 
		'pattern7' => $patternpath . 'pattern7.png', 
		'pattern8' => $patternpath . 'pattern8.png', 
		'pattern9' => $patternpath . 'pattern9.png', 
		'pattern10' => $patternpath . 'pattern10.png', 
		'pattern11' => $patternpath . 'pattern11.png', 
		'pattern12' => $patternpath . 'pattern12.png', 
		'pattern13' => $patternpath . 'pattern13.png', 
		'pattern14' => $patternpath . 'pattern14.png', 
		'pattern15' => $patternpath . 'pattern15.png', 
		'pattern16' => $patternpath . 'pattern16.png', 
		'pattern17' => $patternpath . 'pattern17.png', 
		'pattern18' => $patternpath . 'pattern18.png'
		);
  
    //Preloader Options
	$preloaders = array(
		'loader1' => $preloaderpath . 'loader1.gif', 
		'loader2' => $preloaderpath . 'loader2.gif', 
		'loader3' => $preloaderpath . 'loader3.gif', 
		'loader4' => $preloaderpath . 'loader4.gif', 
		'loader5' => $preloaderpath . 'loader5.gif', 
		'loader6' => $preloaderpath . 'loader6.gif', 
		'loader7' => $preloaderpath . 'loader7.gif', 
		'loader8' => $preloaderpath . 'loader8.gif',
		'loader9' => $preloaderpath . 'loader9.gif',
		'loader10' => $preloaderpath . 'loader10.gif',
        'loader11' => $preloaderpath . 'loader11.gif'
		);
    
    //Post sidebar
    $post_sidebar = array(
        'right-sidebar'=>$imagepath.'right-sidebar.png',
        'left-sidebar'=>$imagepath.'left-sidebar.png',
        'no-sidebar'=>$imagepath.'no-sidebar.png',
        );
    
    
	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = __( 'Select a page', 'accesspress-mag' );
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
    
    // Logo settings
    $logo_options = array(
		'image' => __( 'Image', 'accesspress-mag' ),
		'text' => __( 'Text', 'accesspress-mag' ),
		'image_text' => __( 'Image & Text', 'accesspress-mag' ),
		);

    // slider transition
	$transitions = array(
		'fade' => __( 'Fade', 'accesspress-mag' ),
		'horizontal' => __( 'Slide Horizontal', 'accesspress-mag' )
	);
    
    //Traslations Array
    $translation_name = array(
	        __( "You are here", "accesspess-mag" ),__( "Review overview", "accesspess-mag" ),__( "Summary", "accesspess-mag" ),__( "View all posts", "accesspress-mag" ),__( "by", "accesspress-mag" ),
	        __( "Tagged", "accesspess-mag" ),__( "Next article", "accesspess-mag" ),__( "Previous article", "accesspess-mag" ),
            __( "Older Posts", "accesspess-mag" ),__( "Newer Posts", "accesspess-mag" ),__( "Older Comments", "accesspess-mag" ),__( "Newer Comments", "accesspess-mag" ),
            __( "Via", "accesspess-mag" ),__( "Source", "accesspess-mag" ),__( "Similar Articles", "accesspess-mag" ),
            __( "Advertisement", "accesspress-mag" ),__( "Search results for", "accesspess-mag" ),__( "Search", "accesspress-mag" ),
            __( "Search Placeholder", "accesspress-mag" ),__( "Top arrow", "accesspress-mag" )
	        );
    $translation_std = array(
	        __( "You are here", "accesspess-mag" ),__( "Review overview", "accesspess-mag" ),__( "Summary", "accesspess-mag" ),__( "View all posts", "accesspress-mag" ),__( "by", "accesspress-mag" ),
	        __( "Tagged", "accesspess-mag" ),__( "Next article", "accesspess-mag" ),__( "Previous article", "accesspess-mag" ),
            __( "Older Posts", "accesspess-mag" ),__( "Newer Posts", "accesspess-mag" ),__( "Older Comments", "accesspess-mag" ),__( "Newer Comments", "accesspess-mag" ),
            __( "Via", "accesspess-mag" ),__( "Source", "accesspess-mag" ),__( "Similar Articles", "accesspess-mag" ),
            __( "Advertisement", "accesspress-mag" ),__( "Search Results for", "accesspess-mag" ),__( "Search", "accesspress-mag" ),
            __( "Search Content...", "accesspress-mag" ),__( "Top", "accesspress-mag" )
	        );
    $translation_id = array(
            'you_are_here','review_overview','summary','view_all_posts','sep_by','tagged','next_article','previous_article','older_posts','newer_posts','older_comments','newer_comments','via','source','similar_articles',
            'advertisement','search_results_for','search_button','search_placeholder','top_arrow'
            );
            
    $trans_count = count( $translation_id );

	$options = array();

/*----------------------------Welcome----------------------------------------*/
    $options[] = array(
            'name' => __( 'Welcome', 'accesspress-mag' ),
            'type' => 'heading'
            );
    /*-------------------One click demo-----------------------*/
    $options[] = array(
            'name' => __( 'One Click Demo Install', 'accesspress-mag' ),
            'id'   => 'demo_install',
            'type' => 'groupstart'
            );
   	$options[] = array(
		'name' => __( 'Import Demo Content','accesspress-mag' ),
		'type' => 'button',
		'button_name' => __( 'Import Demo','accesspress-mag' ),
		'id' => 'ap_import',
		'desc' => __( 'This button should only be pressed on a <strong>clean WordPress</strong> installation. This will overwrite all existing option values, please proceed with caution!','accesspress-mag' ),
		'html' => '<div class="import_message"></div>'
		);   
    $options[] = array(
            'type' => 'groupend'
            );

/*-----------------------Basic Setting------------------------*/
	$options[] = array(
            'name' => __( 'Basic Settings', 'accesspress-mag' ),
            'type' => 'heading'
            );
   /*---------------------Background Settings----------------------*/
    $options[] = array(
            'name' => __( 'General Settings', 'accesspress-mag' ),
            'id'   => 'general_settings',
            'type' => 'groupstart'
            );    
    $options[] = array( 
    		"name" => __( 'Skin Color', 'accesspress-mag' ),
    		"desc" => __( 'Select from predefined colors', 'accesspress-mag' ),
    		"id" => "skin_color",
    		"std" => "#dc3522",
    		"type" => "radio",
    		"options" => array(
    			'#dc3522' => 'color1', 
    			'#627F9A' => 'color2',
    			'#4DC7EC' => 'color3',
    			'#232B2D' => 'color4',
    			'#1ABC9C' => 'color5', 
    			'#F25454' => 'color6',
    			'#E5C41A' => 'color7',
    			'#3E5372' => 'color8',
    			)
    		);
	$options[] = array( 
    		"name" => __( 'Custom Skin Color', 'accesspress-mag' ),
    		"desc" => __( 'Select from unlimited colors', 'accesspress-mag' ),
    		"id" => "theme_color",
    		"std" => "#E5623B",
    		"type" => "color" 
            );
        
    $options[] = array( 
    		"name" => __( 'Background', 'accesspress-mag' ),
    		"desc" => __( 'Select between Image/Color/Pattern','accesspress-mag' ),
    		"id" => "page_background_option",
    		"std" => "none",
    		"type" => "select",
    		"options" => $background_options 
            );

	$options[] = array( 
    		"name" => __( 'Background Image', 'accesspress-mag' ),
    		"id" => "page_background_image",
    		"class" =>"sub-option",
    		"type" => "background",
    		'std' => array(
    			'image' => '',
    			'repeat' => 'repeat',
    			'position' => 'top center',
    			'attachment'=>'scroll',
    			'size' => 'auto' )
    		);

	$options[] = array( 
    		"name" => __( 'Background Color', 'accesspress-mag' ),
    		"desc" => __( 'Color for the Background', 'accesspress-mag' ),
    		"id" => "page_background_color",
    		"std" => "#FFFFFF",
    		"type" => "color" 
            );

	$options[] = array(
    		'name' => __( 'Background Patterns', 'accesspress-mag' ),
    		'id' => "page_background_pattern",
    		'std' => "pattern1",
    		'type' => "images",
    		'options' => $background_pattern
    	   );
    
    $options[] = array(
    		'name' => __( 'Enable Pre Loader', 'accesspress-mag' ),
    		'id' => 'enable_preloader',
    		'on' => __( 'Yes', 'accesspress-mag' ),
    		'off' => __( 'No', 'accesspress-mag' ),
    		'std' => '1',
    		'type' => 'switch'
            );

	$options[] = array(
    		'name' => __( 'Select Preloader', 'accesspress-mag' ),
    		'id' => "select_preloader",
    		'std' => "loader1",
    		'type' => "images",
    		'options' => $preloaders
    	   );/*
    $options[] = array(
            'name' => __( 'Accessibility Option', 'accesspress-mag' ),
            'id' => 'theme_accessibility_option',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '0',
            'type' => 'switch'
            );*/
    $options[] = array(
            'name' => __( 'Post Overlay Icon', 'accesspress-mag' ),
            'desc'=> __( 'Set the font Awesome icon at overlay of post.', 'accesspress-mag' ),
            'id' => 'apmag_overlay_icon',
            'std' => 'fa-external-link',           
            'type' => 'text'
            );
    $options[] = array(
            'type' => 'groupend'
            );
            
    /*-------------------Website layout------------------------*/
    $options[] = array(
            'name' => __( 'Website Layout', 'accesspress-mag' ),
            'id'   => 'website_layout',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Website layout', 'accesspress-mag' ),
            'id' => 'website_layout_option',            
            'options' => array(
                    ' ' => 'Fullwidth',
                    'boxed' => 'Boxed',
                    ),
            'type' => 'radio',
            'std' => ' ' 
            );
    $options[] = array(
            'type' => 'groupend'
            );
    /*-------------------Primay menu setting------------------------*/
    $options[] = array(
            'name' => __( 'Primary Menu Setting', 'accesspress-mag' ),
            'id'   => 'primary_menu_setting',
            'type' => 'groupstart'
            );
    $options[] = array( 
    		'name' => __( 'Menu Background Color', 'accesspress-mag' ),
            'desc' => __( 'Select primary menu background color', 'accesspress-mag' ),
    		'id' => 'primary_menu_bg_color',
    		'std' => '#ffffff',
    		'type' => 'color' 
            );
    $options[] = array( 
    		'name' => __( 'Sub Menu Background Color', 'accesspress-mag' ),
            'desc' => __( 'Select sub menu background color', 'accesspress-mag' ),
    		'id' => 'sub_menu_bg_color',
    		'std' => '#ffffff',
    		'type' => 'color' 
            );
    $options[] = array(
    		'name' => __( 'Font Family', 'accesspress-mag' ),
    		'id' => 'primarymenu_typography',
    		'size' => '16',   
    		'face' => 'Dosis',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );
    $options[] = array(
    		'name' => __( 'Text Transform', 'accesspress-mag' ),
    		'id' => 'primary_menu_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );
    $options[] = array( 
    		'name' => __( 'Menu Hover Color', 'accesspress-mag' ),
    		'id' => 'primary_menu_hover_color',
    		'std' => '#dc3522',
    		'type' => 'color' 
            );
    $options[] = array(
            'type' => 'groupend'
            );
    /*------------------------Breadcrumbs template------------------------*/ 
    $options[] = array(
            'name' => __( 'Breadcrumbs Settings', 'accesspress-mag' ),
            'id'   => 'breadcrumbs_settings',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Breadcrumb', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide breadcrumbs on site', 'accesspress-mag' ),
            'id' => 'show_hide_breadcrumbs',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
    		'name' => __( 'Breadcrumb in Mobile', 'accesspress-mag' ),
            'desc' => __( 'Show or hide breadcrumbs in mobile', 'accesspress-mag' ),            
    		'id' => 'enable_breadcrumb_mobile',
    		'on' => __( 'Yes', 'accesspress-mag' ),
    		'off' => __( 'No', 'accesspress-mag' ),
    		'std' => '1',
    		'type' => 'switch'
            );
    $options[] = array(
    		'name' => __( 'Breadcrumb Seperator', 'accesspress-mag' ),
            'desc'=> __( 'example: >> , / , - etc ', 'accesspress-mag' ),
    		'id' => 'breadcrumb_seperator',
    		'std' => '>',    		
    		'type' => 'text'
            );
	$options[] = array(
    		'name' => __( 'Home Text', 'accesspress-mag' ),
            'desc' => __( 'Change home text as required', 'accesspress-mag' ),
    		'id' => 'breadcrumb_home_text',
    		'std' => 'Home',
    		'type' => 'text'
            );            
    $options[] = array(
            'name' => __( 'Enable link on Home', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable homepage link at home in breadcrumbs', 'accesspress-mag' ),
            'id' => 'show_home_link_breadcrumbs',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '0',
            'type' => 'switch'
            );    
    $options[] = array(
            'name' => __( 'Enable article title', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide article title on single post', 'accesspress-mag' ),
            'id' => 'show_article_breadcrumbs',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'type' => 'groupend'
            );
    /*------------------------Custom css------------------------*/ 
     $options[] = array(
            'name' => __( 'Custom Css', 'accesspress-mag' ),
            'id'   => 'custom_css_header',
            'type' => 'groupstart'
            );
     $options[] = array(
            'name' => __( 'Custom Css', 'accesspress-mag' ),
            'desc' => __( 'Add your required css', 'accesspress-mag' ),
            'id' => 'custom_css',
            'std' => __('', 'accesspress-mag' ),
            'type' => 'textarea' 
            );
     $options[] = array(
            'type' => 'groupend'
            );
     /*------------------------Custom script------------------------*/ 
     $options[] = array(
            'name' => __( 'Custom Script', 'accesspress-mag' ),
            'id'   => 'custom_script_header',
            'type' => 'groupstart'
            );
     $options[] = array(
            'name' => __( 'Custom Script', 'accesspress-mag' ),
            'desc' => __( 'Add your required script', 'accesspress-mag' ),
            'id' => 'custom_script',
            'std' => __( '', 'accesspress-mag' ),
            'type' => 'apmagtextarea' 
            );
     $options[] = array(
            'type' => 'groupend'
            );
    
/*--------------------------------------------Header Setting----------------------------------------*/

	$options[] = array(
		    'name' => __( 'Header', 'accesspress-mag' ),
            'type' => 'heading'
	        );
    /*----------------Header Style----------------------------*/
    $options[] = array(
            'name' => __( 'Header Style', 'accesspress-mag' ),
            'id'   => 'header_style',
            'type' => 'groupstart'
            );
            
    $options[] = array(
            'name' => __( 'Header Style', 'accesspress-mag' ),
            'desc' => __( 'Select the order in which the header elements will be arranged', 'accesspress-mag' ),
            'id' => 'header_style_option',
            'type' => 'images',
            'std' => ' ',
            'options' => $ap_header_layout,            
            // 'options' => array(
            //         ' ' => __( 'Style 1 (logo + ad) + menu', 'accesspress-mag' ),
            //         '2' => __( 'Style 2 (full width logo) + menu', 'accesspress-mag' ),
            //         '3' => __( 'Style 3 menu + (logo + ad)', 'accesspress-mag' ),
            //         '4' => __( 'Style 4 menu + (full width logo)', 'accesspress-mag' ),
            //         ),
            // 'type' => 'radio',
             
            );
     $options[] = array(
            'name' => __( 'Sticky Menu', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable sticky menu while page scroll', 'accesspress-mag' ),
            'id' => 'sticky_menu',
            'on' => __( 'Enable', 'accesspress-mag' ),
            'off' => __( 'Disable', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Random Post in Menu', 'accesspress-mag' ),                
            'desc' => __( 'Enable or Disable Random Post icon in menu section.', 'accesspress-mag' ),
            'id' => 'random_icon_option',
            'on' => __( 'Enable', 'accesspress-mag' ),
            'off' => __( 'Disable', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );       
    $options[] = array(
            'type' => 'groupend'
            );
    /*---------------Top menu(black one)---------------------------- */
    $options[] = array(
            'name' => __( 'Top Menu', 'accesspress-mag' ),
            'id'   => 'top_menu',
            'type' => 'groupstart'
            );
            
    $options[] = array(
            'name' => __( 'Top Menu Option', 'accesspress-mag' ),                
            'desc' => __( 'Hide or show the top menu', 'accesspress-mag' ),
            'id' => 'top_menu_switch',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Disable Current Date', 'accesspress-mag' ),                
            'desc' => __( 'Checked to disable current date at top menu.', 'accesspress-mag' ),
            'id' => 'header_current_date_option',
            'type' => 'checkbox'
            );
    $options[] = array( 
    		'name' => __( 'Menu Background Color', 'accesspress-mag' ),
    		'id' => 'top_menu_bg_color',
    		'std' => '#36403f',
    		'type' => 'color' 
            );
    $options[] = array(
    		'name' => __('Font Family', 'accesspress-mag' ),
    		'id' => 'topmenu_typography',
    		'size' => '14',   
    		'face' => 'Dosis',
    		'style' => '400',
    		'color' => '#ffffff',
    		'type' => 'typography'
            );
    $options[] = array(
    		'name' => __( 'Text Transform', 'accesspress-mag' ),
    		'id' => 'top_menu_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );
    $options[] = array( 
    		'name' => __( 'Menu Hover Color', 'accesspress-mag' ),
    		'id' => 'top_menu_hover_color',
    		'std' => '#dc3522',
    		'type' => 'color' 
            );
    $options[] = array(
            'type' => 'groupend'
            );
    
    /*------------------------Logo settings-------------------------*/
    $options[] = array(
            'name' => __( 'Logo Settings', 'accesspress-mag' ),
            'id'   => 'logo',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Logo Alt Attribute', 'accesspress-mag' ),
            'desc' => __( 'Write ALT attribute for the logo', 'accesspress-mag' ),
            'id' => 'logo_alt',
            'type' => 'text', 
            );    
    $options[] = array(
            'name' => __( 'Logo Title Attribute', 'accesspress-mag' ),
            'desc' => __( 'Write TITLE attribute for the logo', 'accesspress-mag' ),
            'id' => 'logo_title',
            'type' => 'text', 
            );    
    $options[] = array(
            'type' => 'groupend'
            );
            
    /*---------------------Trending News Ticker---------------------*/
    $options[] = array(
            'name' => __( 'Trending Ticker', 'accesspress-mag' ),
            'id'   => 'trending_ticker',
            'type' => 'groupstart'
            );            
    $options[] = array(
            'name' => __( 'Trending Ticker Option', 'accesspress-mag' ),                
            'desc' => __( 'Show or Hide the trending Ticker', 'accesspress-mag' ),
            'id' => 'news_ticker_option',
            'on' => __( 'Enable', 'accesspress-mag' ),
            'off' => __( 'Disable', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Ticker Title', 'accesspress-mag' ),
            'desc' => __( 'Write TITLE for ticker eg( Trending: )', 'accesspress-mag' ),
            'id' => 'ticker_title',
            'std' => __( 'Trending', 'accesspress-mag' ),            
            'type' => 'text', 
            );            
    $options[] = array(
            'name' => __( 'Number of ticker', 'accesspress-mag' ),
            'desc' => __( 'Choose number of ticker for trending ticker', 'accesspress-mag' ),
            'id' => 'ticker_count', 
            'type' => 'number',
            'std' => '5'
            );
    $options[] = array(
    		'name' => __('Ticker pause speed', 'accesspress-mag' ),
    		'id' => 'ticker_speed',
    		'std' => '2000',
    		"min" 	=> "1000",
    		"step"	=> "100",
    		"max" 	=> "10000",
    		"type" 	=> "sliderui"
            );            
    $options[] = array(
            'name' => __( 'Ticker Order', 'accesspress-mag' ),
            'id' => 'ticker_order',       
            'options' => array(
                    ' ' => 'Descending Order',
                    'asc' => 'Ascending Order',
                    'rand' => 'Random Oroder'
                    ),
            'type' => 'radio',
            'std' => ' ' 
            );            
    $options[] = array(
            'type' => 'groupend'
            );    

/*-----------------------Footer Setting------------------------*/
    $options[] = array(
            'name' => __( 'Footer', 'accesspress-mag' ),
		    'type' => 'heading'
	       );
    
    $options[] = array(
            'name' => __( 'Footer Setting', 'accesspress-mag' ),
            'id'   => 'footer_setting',
            'type' => 'groupstart'
            );            
    $options[] = array(
            'name' => __( 'Footer Widget Option', 'accesspress-mag' ),                
            'desc' => __( 'Hide or show the footer widter area', 'accesspress-mag' ),
            'id' => 'footer_switch',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );    
    $options[] = array( 
    		'name' => __( 'Footer Widget Background', 'accesspress-mag' ),
    		'desc' => __( 'Select background color for widget section', 'accesspress-mag' ),
    		'id' => 'footer_widget_bg',
    		'std' => '#f1f1f1',
    		'type' => 'color' 
            );    
    $options[] = array(
            'name' => __( 'Footer Layout', 'accesspress-mag' ),
            'desc' => __( 'Choose footer widget layout', 'accesspress-mag' ),
            'id' => 'footer_layout',
            'std' => 'column4',
            'type' => 'images',
            'options' => $footer_pattern
            );    
    $options[] = array(
            'type' => 'groupend'
            );
    
    /*---------------------------SubFooter Settings--------------------------------*/            
    $options[] = array(
            'name' => __( 'Sub-footer Setting', 'accesspress-mag' ),
            'id'   => 'sub_footer_setting',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Sub Footer Option', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide copy right and footer menu section', 'accesspress-mag' ),
            'id' => 'sub_footer_switch',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array( 
    		'name' => __( 'Sub Footer Background', 'accesspress-mag' ),
            'desc' => __( 'Select background color for subfooter section', 'accesspress-mag' ),
    		'id' => 'sub_footer_bg',
    		'std' => '#374140',
    		'type' => 'color' 
            ); 
    $options[] = array( 
    		'name' => __( 'Sub Footer Text', 'accesspress-mag' ),
            'desc' => __( 'Select text color for subfooter section', 'accesspress-mag' ),
    		'id' => 'sub_footer_text_color',
    		'std' => '#eeeeee',
    		'type' => 'color' 
            );   
    $options[] = array(
            'name' => __( 'Copyright text', 'accesspress-mag' ),
            'desc' => __( 'Set footer copyright text', 'accesspress-mag' ),
            'id' => 'mag_footer_copyright',
            'std' => __('&copy; 2015 AccessPress Mag Pro', 'accesspress-mag' ),
            'type' => 'text' 
            );
    $options[] = array(
    		'name' => __( 'Footer Text', 'accesspress-mag' ),
    		'id' => 'mag_footer_text',
    		'std' => 'Powered By <a href="http://accesspressthemes.com/">Accesspress Themes</a>',
    		'type' => 'textarea',
            'rows' => 1
            );
    $options[] = array(
            'name' => __( 'Footer Menu Option', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide footer menu', 'accesspress-mag' ),
            'id' => 'footer_menu_switch',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
    		'name' => __( 'Font Family', 'accesspress-mag' ),
    		'id' => 'footermenu_typography',
    		'size' => '14',   
    		'face' => 'Dosis',
    		'style' => '400',
    		'color' => '#e6e6e6',
    		'type' => 'typography'
            );
    $options[] = array(
    		'name' => __( 'Text Transform', 'accesspress-mag' ),
    		'id' => 'footer_menu_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );
    $options[] = array( 
    		'name' => __( 'Menu Hover Color', 'accesspress-mag' ),
    		'id' => 'footer_menu_hover_color',
    		'std' => '#cccccc',
    		'type' => 'color' 
            );
    $options[] = array(
            'type' => 'groupend'
            );
    
/*-----------------------Ads Setting------------------------*/

    $options[] = array(
            'name' => __( 'ADS', 'accesspress-mag' ),
            'type' => 'heading'
            ); 
    
    /*-------------Header Ads------------------------------*/
    $options[] = array(
            'name' => __( 'Heder ad', 'accesspress-mag' ),
            'id'   => 'header_ad',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Your Header Ad', 'accesspress-mag' ),
            'desc' => __( 'Add your google adsense code or use your banner image (728x90) ( &lt; a href="(your image link)"&gt; &lt; img scr="(your image source)" /&gt; &lt;/a&gt; )', 'accesspress-mag' ),
            'id' => 'value_header_ad',
            'std' => __('', 'accesspress-mag' ),
            'type' => 'apmagtextarea' 
            );            
    $options[] = array(
            'type' => 'groupend'
            );    
    /*-------------Article Ads------------------------------*/
    $options[] = array(
            'name' => __( 'Article ad', 'accesspress-mag' ),
            'id'   => 'article_ad',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Your Article Ad', 'accesspress-mag' ),
            'desc' => __( 'Add your google adsense code or use your banner image (728x90) ( &lt; a href="(your image link)"&gt; &lt; img scr="(your image source)" /&gt; &lt;/a&gt; )', 'accesspress-mag' ),
            'id' => 'value_article_ad',
            'std' => __( '', 'accesspress-mag' ),
            'type' => 'apmagtextarea' 
            );            
    $options[] = array(
            'type' => 'groupend'
            );
    /*-------------Homepage Ads------------------------------*/
    $options[] = array(
            'name' => __( 'Homepage ad', 'accesspress-mag' ),
            'id'   => 'homepage_ad',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Your Homepage Inline Ad', 'accesspress-mag' ),
            'desc' => __( 'Add your google adsense code or use your banner image (728x90) ( &lt; a href="(your image link)"&gt; &lt; img scr="(your image source)" /&gt; &lt;/a&gt; )', 'accesspress-mag' ),
            'id' => 'value_homepage_inline_ad',
            'std' => __( '', 'accesspress-mag' ),
            'type' => 'apmagtextarea',
            );
    $options[] = array(
            'name' => __( 'Your Homepage Sidebar Top Ad', 'accesspress-mag' ),
            'desc' => __( 'Add your google adsense code or use your banner image (300x250) ( &lt; a href="(your image link)"&gt; &lt; img scr="(your image source)" /&gt; &lt;/a&gt; )', 'accesspress-mag' ),
            'id' => 'value_sidebar_top_ad',
            'std' => __( '', 'accesspress-mag' ),
            'type' => 'apmagtextarea' 
            );
    $options[] = array(
            'name' => __( 'Your Homepage Sidebar Middle Ad', 'accesspress-mag' ),
            'desc' => __( 'Add your google adsense code or use your banner image (300x250) ( &lt; a href="(your image link)"&gt; &lt; img scr="(your image source)" /&gt; &lt;/a&gt; )', 'accesspress-mag' ),
            'id' => 'value_sidebar_middle_ad',
            'std' => __( '', 'accesspress-mag' ),
            'type' => 'apmagtextarea' 
            );
    $options[] = array(
            'type' => 'groupend'
            );
    
/*-----------------------Layout Setting------------------------*/
    $options[] = array(
            'name' => __( 'Layout Settings', 'accesspress-mag' ),
            'type' => 'heading',
            'static_text' =>'static',
            'id'=>'layout-settings'
	        );

/*-----------------------Homepage Settings------------------------*/
    $options[] = array(
            'name' => __( 'Homepage Settings', 'accesspress-mag' ),
		    'type' => 'heading'
	       );
   /*-------------Slider Settings Tab ----------------*/
    $options[] = array(
            'name' => __( 'Slider Settings', 'accesspress-mag' ),
            'id'   => 'slider_settings',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Slider section Option', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable slider section at homepage', 'accesspress-mag' ),
            'id' => 'slider_option',
            'on' => __( 'Enable', 'accesspress-mag' ),
            'off' => __( 'Disable', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Slider Layouts', 'accesspress-mag' ),
            'desc' => __( "Choose your slider layout as you like", 'accesspress-mag' ),
            'id' => 'slider_layout',
            'class'=>'slider_layout_image',
            'std' => 'slider-default',
            'type' => 'images',
            'options' => $homepage_slider_layout
            );
    $options[] = array(
            'name' => __( 'Slider Effect Option', 'accesspress-mag' ),                
            'desc' => __( 'Checked to disable slider effect for this section.', 'accesspress-mag' ),
            'id' => 'slider_effect_option',
            'type' => 'checkbox'
            );
    $options[] = array(
            'name' => __( 'Slide`s posts option', 'accesspress-mag' ),
            'desc' => __( 'Display the posts from category or featured in slider', 'accesspress-mag' ),
            'id' => 'slide_post_option',            
            'options' => array(
                    'by_latest_post' => __( 'From Latest post', 'accesspress-mag' ),
                    'by_category' => __( 'From Selected Category' , 'accesspress-mag' ),
                    'by_featured_post' => __( 'From Featrued post', 'accesspress-mag' ),                    
                    ),
            'type' => 'radio',
            'class' => 'slider_type',
            'std' => 'by_latest_post' 
            );            
    $options[] = array(
            'name' => __( 'Select Category', 'accesspress-mag' ),
            'desc' => __( 'Select a category for homepage slider', 'accesspress-mag' ),    
            'id' => 'homepage_slider_category',
            'type' => 'select',
            'options' => $options_categories
            );   
    $options[] = array(
            'name' => __( 'Show Pager', 'accesspress-mag' ),                
            'desc' => __( 'Hide or show the slider pager', 'accesspress-mag' ),
            'id' => 'slider_pager',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '0',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Show Controls', 'accesspress-mag' ),                
            'desc' => __( 'Hide or show the slider controls', 'accesspress-mag' ),
            'id' => 'slider_controls',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Auto Transition', 'accesspress-mag' ),                
            'desc' => __( 'On or off the slider auto transition', 'accesspress-mag' ),
            'id' => 'slider_auto_transition',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
    		'name' => __('Slider Transition', 'accesspress-mag' ),
            'desc' => __( 'Select option for slider transition', 'accesspress-mag' ),
    		'id' => 'slider_transition',
    		'std' => 'horizontal',
    		'type' => 'select',
    		'options' => $transitions
            );
    $options[] = array(
            'name' => __( 'Slide Caption', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide slider`s Title/info/caption', 'accesspress-mag' ),
            'id' => 'slider_info',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Big Slide Caption', 'accesspress-mag' ),
            'id' => 'bslidetitle_typography',
            'size' => '24',
            'face' => 'Oswald',
            'style' => '400',
            'color' => '#ffffff',
            'type' => 'typography'
            );
    $options[] = array(
            'name' => __( 'Big Slide Caption Transform', 'accesspress-mag' ),
            'id' => 'bslidetitle_transform',
            'type' => 'select',
            'std' => 'uppercase',
            'options' => $text_transform
            );
    $options[] = array(
            'name' => __( 'Small Slide Caption', 'accesspress-mag' ),
            'id' => 'sslidetitle_typography',
            'size' => '16',
            'face' => 'Oswald',
            'style' => '400',
            'color' => '#ffffff',
            'type' => 'typography'
            );
    $options[] = array(
            'name' => __( 'Small Slide Caption Transform', 'accesspress-mag' ),
            'id' => 'sslidetitle_transform',
            'type' => 'select',
            'std' => 'uppercase',
            'options' => $text_transform
            );
    $options[] = array( 
            'name' => __( 'Caption Box Background Color', 'accesspress-mag' ),
            'id' => 'slider_title_bg_color',
            'std' => '#3d3d3d',
            'type' => 'color' 
            );
    $options[] = array(
            'name' => __( 'Category Box', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide slider`s category box', 'accesspress-mag' ),
            'id' => 'slider_cat_box_option',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
    		'name' => __('Slider Transition Speed', 'accesspress-mag' ),
    		'id' => 'slider_speed',
    		'std' => '5000',
    		"min" 	=> "1000",
    		"step"	=> "100",
    		"max" 	=> "10000",
    		"type" 	=> "sliderui"
            );

	$options[] = array(
    		'name' => __('Slider Pause Duration', 'accesspress-mag' ),
    		'id' => 'slider_pause',
    		'std' => '5000',
    		"min" 	=> "1000",
    		"step"	=> "100",
    		"max" 	=> "8000",
    		"type" 	=> "sliderui"
            );
    $options[] = array(
            'name' => __( 'Number of slides', 'accesspress-mag' ),
            'desc' => __( 'Choose number of slides', 'accesspress-mag' ),
            'id' => 'count_slides', 
            'type' => 'number',
            'std' => '2'
            );
    $options[] = array( 
    		'name' => __( 'Category Box Color', 'accesspress-mag' ),
    		'id' => 'slider_cat_box_color',
    		'std' => '#3d3d3d',
    		'type' => 'color'
            );
    $options[] = array( 
            'name' => __( 'Cateogry Title', 'accesspress-mag' ),
            'id' => 'slider_cat_title_color',
            'std' => '#e5e5e5',
            'type' => 'color' 
            );
    $options[] = array(
            'name' => __( 'Revolution Shortcode', 'accesspress-mag' ),
            'desc' => __( 'Enter the ShortCode of the slider if Slider layout is Revolution Slider', 'accesspress-mag' ),
            'id' => 'apmag_rev_slider_shortcode',
            'type' => 'text',
            'std' => __( '', 'accesspress-mag' ),
            'placeholder' => __( '[rev_slider homepage]', 'accesspress-mag' ),
            );
    $options[] = array(
            'type' => 'groupend'
            );
    /*------------------------Homepage Popular Block Settings ------------------*/
    $options[] = array(
            'name' => __( 'Popular Block Settings', 'accesspress-mag' ),
            'id'   => 'popular_blocks_settings',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Popular Block', 'accesspress-mag' ),
            'desc' => __( 'Show or hide popular ariticle section', 'accesspress-mag' ),
            'id' => 'popular_section_option',
            'on' => __( 'Show', 'accesspress-mag' ),
            'off' => __( 'Hide', 'accesspress-mag' ),
            'std' => '0',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Popular Block Title', 'accesspress-mag' ),
            'desc' => __( 'Add block name as you like (example: Popular Articles)', 'accesspress-mag' ),
            'id' => 'popular_block_name',
            'type' => 'text',
            'std' => __('Popular Articles', 'accesspress-mag' ),
            );
    $options[] = array(
            'name' => __( 'Select Category', 'accesspress-mag' ),
            'desc' => __( 'Select a category for popular block in homepage', 'accesspress-mag' ),
            'id' => 'popular_block_category',
            'type' => 'select',
            'std' => 'all',
            'options' => $popular_category_option
            );
    $options[] = array(
            'name' => __( 'Number of Posts', 'accesspress-mag' ),
            'desc' => __( 'Choose number of posts in popular section at homepage', 'accesspress-mag' ),
            'id' => 'popular_posts_count', 
            'type' => 'number',
            'std' => '5'
            );
    $options[] = array(
            'type' => 'groupend'
            );
    /*------------------------Homepage Block Settings Tab ------------------*/        
    $options[] = array(
            'name' => __( 'Blocks settings', 'accesspress-mag' ),
            'id'   => 'blocks_settings',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Block Title Link', 'accesspress-mag' ),                
            'desc' => __( 'Checked to enable link of Block Title to their category.', 'accesspress-mag' ),
            'id' => 'home_cat_link',
            'type' => 'checkbox'
            );
    $options[] = array(
            'name' => __( 'Featured Block (First)', 'accesspress-mag' ),
            'desc' => __( 'Select a category for first block in homepage', 'accesspress-mag' ),    
            'id' => 'featured_block_1',
            'type' => 'select',
            'options' => $options_categories
            );
    $options[] = array(
            'name' => __( 'Number of posts', 'accesspress-mag' ),
            'desc' => __( 'Choose number of posts for block (first)', 'accesspress-mag' ),
            'id' => 'posts_for_block1', 
            'type' => 'select',
            'options' => $options_block_posts
            );
    $options[] = array(
            'name' => __( 'Featured Block (Second)', 'accesspress-mag' ),
            'desc' => __( 'Select a category for second block in homepage', 'accesspress-mag' ),    
            'id' => 'featured_block_2',
            'type' => 'select',
            'options' => $options_categories
            );
    $options[] = array(
            'name' => __( 'Number of posts', 'accesspress-mag' ),
            'desc' => __( 'Choose number of posts for block (second)', 'accesspress-mag' ),
            'id' => 'posts_for_block2', 
            'type' => 'select',
            'options' => $options_block_posts
            );
    $options[] = array(
            'name' => __( 'Featured Block (Third)', 'accesspress-mag' ),
            'desc' => __( 'Select a category for third block in homepage', 'accesspress-mag' ),    
            'id' => 'featured_block_3',
            'type' => 'select',
            'options' => $options_categories
            );
    $options[] = array(
            'name' => __( 'Number of posts', 'accesspress-mag' ),
            'desc' => __( 'Choose number of posts for block (third)', 'accesspress-mag' ),
            'id' => 'posts_for_block3', 
            'type' => 'select',
            'options' => $options_block_posts
            );
    $options[] = array(
            'name' => __( 'Featured Block (Fourth)', 'accesspress-mag' ),
            'desc' => __( 'Select a category for fourth block in homepage', 'accesspress-mag' ),    
            'id' => 'featured_block_4',
            'type' => 'select',
            'options' => $options_categories
            );
    $options[] = array(
            'name' => __( 'Number of posts', 'accesspress-mag' ),
            'desc' => __( 'Choose number of posts for block (fourth)', 'accesspress-mag' ),
            'id' => 'posts_for_block4', 
            'type' => 'select',
            'options' => $options_block_posts
            );
    $options[] = array(
            'type' => 'groupend'
            );
            
    /*----------------------- Homepage Layout Tab ------------------------*/
    $options[] = array(
            'name' => __( 'Homepage Layouts', 'accesspress-mag' ),
            'id'   => 'homepage_layouts_settings',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Available Layouts', 'accesspress-mag' ),
            'desc' => __( "Choose your hompage layout", 'accesspress-mag' ),
            'id' => 'homepage_layout',
            'class'=>'homepage_layout_image',
            'std' => 'home-default',
            'type' => 'images',
            'options' => $homepage_templates
            );
    $options[] = array(
            'type' => 'groupend'
            );          
/*------------------------Post Settings------------------------*/         
     $options[] = array(
            'name' => __( 'Post Settings', 'accesspress-mag' ),
            'type' => 'heading'
            ); 
            
    $options[] = array(
            'name' => __( 'Additional Settings', 'accesspress-mag' ),
            'id'   => 'post_settings',
            'type' => 'groupstart'
            );
            
    $options[] = array(
            'name' => __( 'Show Date', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable the post date', 'accesspress-mag' ),
            'id' => 'post_show_date',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
            
    $options[] = array(
            'name' => __( 'Show Post Views', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable the post views', 'accesspress-mag' ),
            'id' => 'show_post_views',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Show Comment Count', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable comment number', 'accesspress-mag' ),
            'id' => 'show_comment_count',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Show Author Under Title', 'accesspress-mag' ),                
            'desc' => __( 'Shows or hide the author under the post title', 'accesspress-mag' ),
            'id' => 'show_author_name',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Show Tags on Site', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable the post tags', 'accesspress-mag' ),
            'id' => 'show_tags_post',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Show Author Box', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable the author box', 'accesspress-mag' ),
            'id' => 'show_author_box',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Show Navigation in Posts', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide `next` and `previous` posts', 'accesspress-mag' ),
            'id' => 'show_post_nextprev',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Lightbox Effect', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable lightbox effect for galleries images.', 'accesspress-mag' ),
            'id' => 'show_lightbox_effect',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'type' => 'groupend'
            );
      /*------------------------Default site post template------------------------*/ 
    $options[] = array(
            'name' => __( 'Post Layout', 'accesspress-mag' ),
            'id'   => 'post_layout',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Default Post Template', 'accesspress-mag' ),
            'desc' => __( "Setting this option will make all post pages, that don't have a post template associated to them, to be displayed using this template. This option is OVERWRITTEN by the `Post template` option from the backend - post add / edit page.", 'accesspress-mag' ),
            'id' => 'global_post_template',
            'class'=>'post_template_image',
            'std' => 'single',
            'type' => 'images',
            'options' => $post_template
            );
    $options[] = array(
            'name' => __( 'Default Post Sidebar', 'accesspress-mag' ),
            'desc' => __( "Setting this option will make all post pages, that don't have a post sidebar associated to them, to be displayed using this sidebar. This option is OVERWRITTEN by the `Post sidebar` option from the backend - post add / edit page.", 'accesspress-mag' ),
            'id' => 'global_post_sidebar',
            'class'=>'post_sidebar_image',
            'std' => 'right-sidebar',
            'type' => 'images',
            'options' => $post_sidebar
            );
    $options[] = array(
            'type' => 'groupend'
            );
      
      /*------------------------Featured image settings------------------------*/ 
    $options[] = array(
            'name' => __( 'Featured images', 'accesspress-mag' ),
            'id'   => 'featured_image_section',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Show Featured Image', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide featured image in post`s single page', 'accesspress-mag' ),
            'id' => 'featured_image',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );       
    $options[] = array(
            'name' => __( 'Fallback Image option', 'accesspress-mag' ),                
            'desc' => __( 'Enable or disable featured fallback image in single posts.', 'accesspress-mag' ),
            'id' => 'fallback_image_option',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Fallback Image', 'accesspress-mag' ),
            'desc' => __( "Upload your fallback image for post's featured image.", 'accesspress-mag' ),
            'id' => 'fallback_image',
            'class' =>'sub-option',
            'std' => get_template_directory_uri(). '/images/no-image.jpg',
            'type' => 'upload', 
            );
    $options[] = array(
            'type' => 'groupend'
            );
    /*--------------------Similar article----------------------*/
    $options[] = array(
            'name' => __( 'Similar Aritcle', 'accesspress-mag' ),
            'id'   => 'similar_article',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Show Similar Article', 'accesspress-mag' ),                
            'desc' => __( 'Show or hide similar article section in single post view', 'accesspress-mag' ),
            'id' => 'show_similar_article',
            'on' => __( 'Yes', 'accesspress-mag' ),
            'off' => __( 'No', 'accesspress-mag' ),
            'std' => '1',
            'type' => 'switch'
            );
    $options[] = array(
            'name' => __( 'Similar Article-Type', 'accesspress-mag' ),
            'desc' => __( 'Sort similar aritcle', 'accesspress-mag' ),    
            'id' => 'similar_article_type',
            'std' => 'category',
            'type' => 'select',
            'options' => $article_type
            );
    $options[] = array(
            'name' => __( 'Number of Posts', 'accesspress-mag' ),
            'desc' => __( 'Choose number of posts', 'accesspress-mag' ),    
            'id' => 'similar_article_count',
            'std' => '2',
            'type' => 'radio',
            'options' => array(
                           '2' => __( '2 Posts', 'accesspress-mag' ),
                           '4' => __( '4 Posts', 'accesspress-mag' ),
                           '6' => __( '6 Posts', 'accesspress-mag' ),
                            )
            );
    $options[] = array(
            'type' => 'groupend'
            );
      
/*------------------Archive Page Settings---------------------*/
    $options[] = array(
            'name' => __( 'Archive Settings', 'accesspress-mag' ),
            'type' => 'heading'
            ); 
            
    $options[] = array(
            'name' => __( 'Default Archive Style', 'accesspress-mag' ),
            'id'   => 'archive_style',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Archive page template', 'accesspress-mag' ),
            'desc' => __( "Define - Choose template for all archive pages", 'accesspress-mag' ),
            'id' => 'global_archive_template',
            'class'=>'archive_post_template_image',
            'std' => 'archive-default',
            'type' => 'images',
            'options' => $archive_template
            );
    $options[] = array(
            'name' => __( 'Archive page sidebar', 'accesspress-mag' ),
            'desc' => __( "Define - Choose sidebar for all archive pages", 'accesspress-mag' ),
            'id' => 'global_archive_sidebar',
            'class'=>'archive_page_sidebar_image',
            'std' => 'right-sidebar',
            'type' => 'images',
            'options' => $post_sidebar
            );
    $options[] = array(
            'type' => 'groupend'
            );
            
    $options[] = array(
            'name' => __( 'Custom Category Style', 'accesspress-mag' ),
            'id'   => 'category_style',
            'type' => 'groupstart'
            );
    $cat_args = array(
            	'type'                     => 'post',
                'child_of'                 => 0,
            	'orderby'                  => 'name',
            	'order'                    => 'ASC',
            	'hide_empty'               => 1,
            	'taxonomy'                 => 'category',
                );
    $categories = get_categories( $cat_args );
    foreach( $categories as $cat )
    {
        $category_template_name = __( $cat->name.' Category Template', 'accesspress-mag' );
        $category_sidebar_name = __( $cat->name.' Category Sidebar', 'accesspress-mag' );
        $options[] = array(
            'name' => $category_template_name,
            'desc' => __( "Define - Choose template for selected category pages", 'accesspress-mag' ),
            'id' => $cat->cat_ID.'_cat_template',
            'class'=>'cat_template_image',
            'std' => 'global-archive-default',
            'type' => 'images',
            'options' => $category_template
            );
        $options[] = array(
            'name' => $category_sidebar_name,
            'desc' => __( "Define - Choose sidebar for selected category pages", 'accesspress-mag' ),
            'id' => $cat->cat_ID.'_cat_sidebar',
            'class'=>'cat_sidebar_image',
            'std' => 'global-sidebar',
            'type' => 'images',
            'options' => $category_sidebar
            );
    }
    $options[] = array(
            'type' => 'groupend'
            );

            
/*--------------------------MISC--------------------------*/        
    $options[] = array(
            'name' => __( 'MISC', 'accesspress-mag' ),
            'type' => 'heading',
            'static_text' =>'static',
            'id'=>'misc'
	        );
    
    /* --------------------------TYPOGRAPHY SETTINGS-------------------------- */
    $options[] = array(
    		'name' => __('Typography', 'accesspress-mag' ),
    		'icon' => 'fa fa-font',
    		'type' => 'heading' );
            
    $options[] = array(
            'name' => __( 'Body', 'accesspress-mag' ),
            'id'   => 'settings_typography_body',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('Body Font', 'accesspress-mag' ),
    		'id' => 'body_typography',
    		'size' => '15',
    		'face' => 'Dosis',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography' 
            );
            
    $options[] = array(
            'type' => 'groupend'
            );  

	$options[] = array(
            'name' => __( 'Header 1', 'accesspress-mag' ),
            'id'   => 'settings_typography_h1',
            'type' => 'groupstart'
            );
    
	$options[] = array(
    		'name' => __('H1 Font', 'accesspress-mag' ),
    		'id' => 'h1_typography',
    		'size' => '26',
    		'face' => 'Oswald',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );

	$options[] = array(
    		'name' => __('H1 Text Transform', 'accesspress-mag' ),
    		'id' => 'h1_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );

	$options[] = array(
            'type' => 'groupend'
            );  

	$options[] = array(
            'name' => __( 'Header 2', 'accesspress-mag' ),
            'id'   => 'settings_typography_h2',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('H2 Font', 'accesspress-mag' ),
    		'id' => 'h2_typography',
    		'size' => '22',
    		'face' => 'Oswald',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );

	$options[] = array(
    		'name' => __('H2 Text Transform', 'accesspress-mag' ),
    		'id' => 'h2_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );

	$options[] = array(
            'type' => 'groupend'
            );  

	$options[] = array(
            'name' => __( 'Header 3', 'accesspress-mag' ),
            'id'   => 'settings_typography_h3',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('H3 Font', 'accesspress-mag' ),
    		'id' => 'h3_typography',
    		'size' => '18',
    		'face' => 'Dosis',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );

	$options[] = array(
    		'name' => __('H3 Text Transform', 'accesspress-mag' ),
    		'id' => 'h3_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );

	$options[] = array(
            'type' => 'groupend'
            );  

	$options[] = array(
            'name' => __( 'Header 4', 'accesspress-mag' ),
            'id'   => 'settings_typography_h4',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('H4 Font', 'accesspress-mag' ),
    		'id' => 'h4_typography',
    		'size' => '16',
    		'face' => 'Dosis',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );

	$options[] = array(
    		'name' => __('H4 Text Transform', 'accesspress-mag' ),
    		'id' => 'h4_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );

	$options[] = array(
            'type' => 'groupend'
            );  

	$options[] = array(
            'name' => __( 'Header 5', 'accesspress-mag' ),
            'id'   => 'settings_typography_h5',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('H5 Font', 'accesspress-mag' ),
    		'id' => 'h5_typography',
    		'size' => '14',
    		'face' => 'Oswald',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );

	$options[] = array(
    		'name' => __('H5 Text Transform', 'accesspress-mag' ),
    		'id' => 'h5_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );

	$options[] = array(
            'type' => 'groupend'
            );  

	$options[] = array(
            'name' => __( 'Header 6', 'accesspress-mag' ),
            'id'   => 'settings_typography_h6',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('H6 Font', 'accesspress-mag' ),
    		'id' => 'h6_typography',
    		'size' => '12',
    		'face' => 'Oswald',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );

	$options[] = array(
    		'name' => __('H6 Text Transform', 'accesspress-mag' ),
    		'id' => 'h6_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );

	$options[] = array(
            'type' => 'groupend'
            );  
     /*----------------------Widget Typography---------------------*/   
	$options[] = array(
            'name' => __( 'Widget', 'accesspress-mag' ),
            'id'   => 'settings_typography_widget',
            'type' => 'groupstart'
            );

	$options[] = array(
    		'name' => __('Widget Title Font', 'accesspress-mag' ),
    		'id' => 'widgettitle_typography',
    		'size' => '18',
    		'face' => 'Oswald',
    		'style' => '400',
    		'color' => '#3d3d3d',
    		'type' => 'typography'
            );
	$options[] = array(
    		'name' => __('Widget Text Heading Transform', 'accesspress-mag' ),
    		'id' => 'widget_title_text_transform',
    		'type' => 'select',
    		'std' => 'uppercase',
    		'options' => $text_transform
            );
            
    $options[] = array(
            'type' => 'groupend'
            );
    /*------------------------Excerpts Settings------------------------*/
    $options[] = array(
            'name' => __( 'Excerpts', 'accesspress-mag' ),
            'type' => 'heading'
            );
    $options[] = array(
            'name' => __( 'Archive Excerpts', 'accesspress-mag' ),
            'id'   => 'excerpts',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Notice:', 'accesspress-mag' ),
            'desc' => __( 'Adding a text as excerpt on post edit page (Excerpt box), will overwrite the theme excerpts', 'accesspress-mag' ),
            'id' => 'excerpt_notice',
            'type' => 'info', 
            );
    $options[] = array(
            'name' => __( 'Excerpts Type', 'accesspress-mag' ),
            'desc' => __( 'Define - type of excerpt for archives pages', 'accesspress-mag' ),
            'id' => 'excerpt_type',            
            'options' => array(
                    ' '     => 'On Words',
                    'letters' => 'On Letters',                    
                    ),
            'type' => 'radio',
            'std' => ' ' 
            );    
    $options[] = array(
            'name' => __( 'Excerpt Length', 'accesspress-mag' ),
            'desc' => __( 'Define - Excerpt length of words/letters for archive pages', 'accesspress-mag' ),
            'id' => 'excerpt_lenght',
            'type' => 'number',
            'std' => 50, 
            );
    $options[] = array(
            'type' => 'groupend'
            );
            
    $options[] = array(
            'name' => __( 'Home Excerpts', 'accesspress-mag' ),
            'id'   => 'home_excerpts',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Excerpts Type', 'accesspress-mag' ),
            'desc' => __( 'Define - type of excerpt for homepage posts', 'accesspress-mag' ),
            'id' => 'home_excerpt_type',            
            'options' => array(
                    ' '     => 'On Words',
                    'letters' => 'On Letters',                    
                    ),
            'type' => 'radio',
            'std' => ' ' 
            );    
    $options[] = array(
            'name' => __( 'Excerpt Length', 'accesspress-mag' ),
            'desc' => __( 'Define - Excerpt length of words/letters for homepage posts', 'accesspress-mag' ),
            'id' => 'home_excerpt_lenght',
            'type' => 'number',
            'std' => 30, 
            );
    $options[] = array(
            'type' => 'groupend'
            );

/*------------------------Translations------------------------*/
    $options[] = array(
            'name' => __( 'Translations', 'accesspress-mag' ),
            'type' => 'heading'
            );
    $options[] = array(
            'name' => __( 'Translations', 'accesspress-mag' ),
            'id'   => 'translations',
            'type' => 'groupstart'
            );
    $options[] = array(
            'name' => __( 'Translate Your Theme', 'accesspress-mag' ),
            'desc' => __( 'Translate your frontend easily without any external plugins. While you leave the box empty and the theme will load the default string', 'accesspress-mag' ),
            'id' => 'translate_notice',
            'type' => 'info', 
            );
     for($i=0;$i<$trans_count;$i++)
     {
    $options[] = array(
        'name' => $translation_name[$i],
        'std' => $translation_std[$i],
        'id' => 'trans_'.$translation_id[$i],
        'type' => 'text', 
        );
     }       
    $options[] = array(
            'type' => 'groupend'
            );
            
/*----------------------------------------------------------*/
	return $options;
}