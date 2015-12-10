<?php

/*-----------------------------------------------------------------------------------*/
/*	Drop Caps
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_dropcaps')) {
	function vibe_dropcaps( $atts, $content = null ) {
            
        $return ='<span class="dropcap">'.$content.'</span>';
        return $return;
	}
	add_shortcode('d', 'vibe_dropcaps');
}
/*-----------------------------------------------------------------------------------*/
/*	Pull Quote
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_pullquote')) {
	function vibe_pullquote( $atts, $content = null ) {
        extract(shortcode_atts(array(
		  'style'   => 'left'
        ), $atts));
        $return ='<div class="pullquote '.$style.'">'.do_shortcode($content).'</div>';
        return $return;
	}
	add_shortcode('pullquote', 'vibe_pullquote');
}

/*-----------------------------------------------------------------------------------*/
/*	SELL CONTENT WOOCOMMERCE SHORTCODE
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_sell_content')) {
	function vibe_sell_content( $atts, $content = null ) {
        extract(shortcode_atts(array(
			'product_id'    	 => '',
	    ), $atts));

        if(is_user_logged_in() && is_numeric($product_id)){
        	$user_id = get_current_user_id();
        	$check = wc_customer_bought_product('',$user_id,$product_id);
        	if($check){
        		echo apply_filters('the_content',$content);
        	}else{  
        		$product = get_product( $product_id );
				if(is_object($product)){
					$link = get_permalink($product_id);

					$check=vibe_get_option('direct_checkout');
        			if(isset($check) && $check)
        				$link.='?redirect';

        			$price_html = str_replace('class="amount"','class="amount" itemprop="price"',$product->get_price_html());

        		echo '<div class="message info">'.
        		sprintf(__('You do not have access to this content. <a href="%s" class="button"> Puchase </a> content for %s','vibe-shortcodes'),$link,$price_html).
        		'</div>';	
        		}else{
        			echo '<div class="message info">'.__('You do not have access to this content','vibe-shortcodes').'</div>';
        		}
        	}
        }else{
        		$product = get_product( $product_id );
				if(is_object($product)){
					$link = get_permalink($product_id);

					$check=vibe_get_option('direct_checkout');
        			if(isset($check) && $check)
        				$link.='?redirect';

        			$price_html = $product->get_price_html();

        		echo '<div class="message info">'.
        		sprintf(__('You do not have access to this content. <a href="%s" class="button"> Puchase </a> content for %s','vibe-shortcodes'),$link,$price_html).
        		'</div>';	
        		}else{
        			echo '<div class="message info">'.__('You do not have access to this content','vibe-shortcodes').'</div>';
        		}
        }

        return $return;
	}
	add_shortcode('sell_content', 'vibe_sell_content');
}

/*-----------------------------------------------------------------------------------*/
/*	Social Buttons
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_social_buttons')) {
	function vibe_social_buttons( $atts, $content = null ) {
           $return = social_sharing();
        return $return;
	}
	add_shortcode('social_buttons', 'vibe_social_buttons');
}

/*-----------------------------------------------------------------------------------*/
/*	Social Sharing Buttons
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_social_sharing_buttons')) {
	function vibe_social_sharing_buttons( $atts, $content = null ) {
           $return = vibe_socialicons();
        return $return;
	}
	add_shortcode('social_icons', 'vibe_social_sharing_buttons');
}


/*-----------------------------------------------------------------------------------*/
/*	Number Counter
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_number_counter')) {
	function vibe_number_counter( $atts, $content = null ) {
           extract(shortcode_atts(array(
		'min'   => 0,
		'max'   => 100,
		'delay' => 0,
		'increment'=>1,
                ), $atts));

        if(strlen($content)>2){
        	$m = do_shortcode($content);
        	if(is_numeric($m))
        		$max = $m;
        }
        wp_enqueue_script( 'counter-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/scroller-counter.js',array('jquery'),'1.0',true);
        $return ='<div class="numscroller" data-max="'.$max.'" data-min="'.$min.'" data-delay="'.$delay.'" data-increment="'.$increment.'">'.$min.'</div>';
        return $return;
	}
	add_shortcode('number_counter', 'vibe_number_counter');
}


/*-----------------------------------------------------------------------------------*/
/*	Vibe Container
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_container')) {
	function vibe_container( $atts, $content = null ) {
            extract(shortcode_atts(array(
		'style'   => ''
                ), $atts));
        $return ='<div class="container '.$style.'">'.do_shortcode($content).'</div>';
        return $return;
	}
	add_shortcode('vibe_container', 'vibe_container');
}

/*-----------------------------------------------------------------------------------*/
/*	IMG
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_img')) {
	function vibe_img( $atts, $content = null ) {
        extract(shortcode_atts(array(
			'id'   => 0,
			'size' => 'thumb'
        ), $atts));
        $id=trim($id,"'");//intval();
    	$image =wp_get_attachment_image_src($id,$size);
    	$alt = get_post_meta($id, '_wp_attachment_image_alt', true);
        $return ='<img src="'.$image[0].'" class="'.$size.'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$alt.'" />';
        return $return;
	}
	add_shortcode('img', 'vibe_img');
}

/*-----------------------------------------------------------------------------------*/
/*	Pull Quote
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_allbages')) {
	function vibe_allbages( $atts, $content = null ) {
            extract(shortcode_atts(array(
				'size'   => '60'
                ), $atts));
            global $wpdb;

            $all_badges = apply_filters('vibe_all_badges', $wpdb->get_results( "
			SELECT post_id,meta_value FROM $wpdb->postmeta
			WHERE 	meta_key 	= 'vibe_course_badge'
			AND meta_value REGEXP '^-?[0-9]+$'
		" ) );

        $user_id = get_current_user_id();
        $return ='<div class="allbadges">';
        if(isset($all_badges) && is_array($all_badges)){
        	$return .='<ul>';
        	foreach($all_badges as $badge){
        		if(is_object($badge)){
        			$badge_title=get_post_meta($badge->post_id,'vibe_course_badge_title',true);
        			$badge_image =wp_get_attachment_image_src( $badge->meta_value, 'full');
        			$check = get_user_meta($user_id,$badge->post_id,true);
        			$return .='<li '.(($check)?'class="finished"':'').'><a class="tip" title="'.$badge_title.'"><img src="'.$badge_image[0].'" alt="'.$badge->post_title.'" width="'.$size.'" />'.(($check)?'<span>'.__('EARNED','vibe-shortcodes').'</span>':'').'</a></li>';
        		}
	        }
	        $return .='</ul>';		
        }
        $return .='</div>';
        return $return;
	}
	add_shortcode('allbadges', 'vibe_allbages');
}

/*-----------------------------------------------------------------------------------*/
/*	Instructor
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_instructor')) {
	function vibe_instructor( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'id'   => '1'
                ), $atts));
        $instructor = $id;
        $return ='<div class="course_instructor_widget">';
	    $return.= bp_course_get_instructor('instructor_id='.$instructor);
	    $return.= '<div class="description">'.bp_course_get_instructor_description('instructor_id='.$instructor).'</div>';
	    $return.= '<a href="'.get_author_posts_url($instructor).'" class="tip" title="'.__('Check all Courses created by ','vibe-shortcodes').bp_core_get_user_displayname($instructor).'"><i class="icon-plus-1"></i></a>';
	    $return.= '<h5>'.__('More Courses by ','vibe-shortcodes').bp_core_get_user_displayname($instructor).'</h5>';
	    $return.= '<ul class="widget_course_list">';
	    $query = new WP_Query( 'post_type=course&author='.$instructor.'&posts_per_page=5');
	    while($query->have_posts()):$query->the_post();
	    global $post;
	    $return.= '<li><a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'<h6>'.get_the_title($post->ID).'<span>by '.bp_core_get_user_displayname($post->post_author).'</span></h6></a>';
	    endwhile;
	    wp_reset_postdata();
	    $return.= '</ul>';
	    $return.= '</div>'; 
        return $return;
	}
	add_shortcode('instructor', 'vibe_instructor');
}


/*-----------------------------------------------------------------------------------*/
/*	Divider
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_divider')) {
	function vibe_divider( $atts, $content = null ) {
            extract(shortcode_atts(array(
				'style'   => ''
                ), $atts));
        $return ='<hr class="divider '.$style.'" />';
        return $return;
	}
	add_shortcode('divider', 'vibe_divider');
}

/*-----------------------------------------------------------------------------------*/
/*	COURSE
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_course')) {
	function vibe_course( $atts, $content = null ) {
            extract(shortcode_atts(array(
					'id'   => '',
                    'featured_block'=>'course'
                ), $atts));
            $course_query = new WP_Query("post_type=course&p=$id");
            
            if($course_query->have_posts()){
            	while($course_query->have_posts()){
            		$course_query->the_post();
            	   			
            		if(function_exists('thumbnail_generator'))
        				$return = thumbnail_generator($course_query->posts[0],$block,'medium',1,1,1);

            	}
            }

            wp_reset_postdata();
        return $return;
	}
	add_shortcode('course', 'vibe_course');
}
/*-----------------------------------------------------------------------------------*/
/*	Icon
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_icon')) {
	function vibe_icon( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'icon'   => 'icon-facebook',
                'size' => '',
                'bg' =>'',
                'hoverbg'=>'',
                'padding' =>'',
                'radius' =>'',
                'color' => '',
                'hovercolor' => ''
	), $atts));
        $rand = 'icon'.rand(1,9999);
        $return ='<style> #'.$rand.'{'.(isset($size)?'font-size:'.$size.';':'').''.((isset($bg))?'background:'.$bg.';':';').''.(isset($padding)?'padding:'.$padding.';':'').''.(isset($radius)?'border-radius:'.$radius.';':'').''.((isset($color))?'color:'.$color.';':'').'}
            #'.$rand.':hover{'.((isset($hovercolor))?'color:'.$hovercolor.';':'').''.((isset($hoverbg))?'background:'.$hoverbg.';':'').'}</style><i class="'.$icon.'" id="'.$rand.'"></i>';
	   return $return;
	}
	add_shortcode('icon', 'vibe_icon');
}

/*-----------------------------------------------------------------------------------*/
/*	Video
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_iframevideo')) {
	function vibe_iframevideo( $atts, $content = null ) {
	$return = '<div class="fitvids">'.html_entity_decode($content).'</div>';		
       return $return;
	}
	add_shortcode('iframevideo', 'vibe_iframevideo');
}

/*-----------------------------------------------------------------------------------*/
/*	Iframe
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_iframe')) {
	function vibe_iframe( $atts, $content = null ) {
		extract(shortcode_atts(array(
		'height'   => '',
		), $atts));
		$return = '<div class="iframecontent" '.((isset($height) && is_numeric($height))?'style="height:'.$height.'px;"':'').'><iframe src="'.html_entity_decode($content).'" width="100%"></iframe></div>';		
       return $return;
	}
	add_shortcode('iframe', 'vibe_iframe');
}

/*-----------------------------------------------------------------------------------*/
/*	Round Progress
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_roundprogress')) {
	function vibe_roundprogress( $atts, $content = null ) {
	extract(shortcode_atts(array(
                'style' => '',
		'percentage'   => '60',
                'radius' => '',
                'thickness' =>'',
                'color' =>'#333',
                'bg_color' =>'#65ABA6',
	), $atts));
        $rand = 'icon'.rand(1,9999);
        
        $return ='<figure class="knob animate zoom" style="width:'.($radius+10).'px;min-height:'.($radius+10).'px;">
                    <input class="dial" data-skin="'.$style.'" data-value="'.$percentage.'" data-fgColor="'.$color.'" data-bgColor="'.$bg_color.'" data-height="'.$radius.'" data-inputColor="'.$color.'" data-width="'.$radius.'" data-thickness="'.($thickness/100).'" value="'.$percentage.'" data-readOnly=true />
                        <div class="knob_content"><h3 style="color:'.$color.';">'.do_shortcode($content).'</h3></div>
                  </figure>';
        return $return;
	}
	add_shortcode('roundprogress', 'vibe_roundprogress');
}




/*-----------------------------------------------------------------------------------*/
/*	WPML Language Selector shortcode
/*-----------------------------------------------------------------------------------*/

//[wpml_lang_selector]
function wpml_shortcode_func(){
do_action('icl_language_selector');
}
add_shortcode( 'wpml_lang_selector', 'wpml_shortcode_func' );


/*-----------------------------------------------------------------------------------*/
/*	Note
/*-----------------------------------------------------------------------------------*/


if (!function_exists('note')) {
	function note( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'style'   => '',
                'bg' =>'',
                'border' =>'',
                'bordercolor' =>'',
                'color' => ''
	), $atts));
	   return '<div class="notification '.$style.'" style="background-color:'.$bg.';border-color:'.$border.';">
			<div class="notepad" style="color:'.$color.';border-color:'.$bordercolor.';">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('note', 'note');
}

/*-----------------------------------------------------------------------------------*/
/*	Column Shortcode
/*-----------------------------------------------------------------------------------*/

if (!function_exists('one_half')) {
	function one_half( $atts, $content = null ) {
	    $clear='';
	    if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	      $clear='clearfix';
	      
            return '<div class="one_half '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('one_half', 'one_half');
}


if (!function_exists('one_third')) {
	function one_third( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
	  
	   return '<div class="one_third '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('one_third', 'one_third');
}


if (!function_exists('one_fourth')) {
	function one_fourth( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
             return '<div class="one_fourth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';	}
	add_shortcode('one_fourth', 'one_fourth');
}


if (!function_exists('three_fourth')) {
	function three_fourth( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
             return '<div class="three_fourth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('three_fourth', 'three_fourth');
}


if (!function_exists('two_third')) {
	function two_third( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
            return '<div class="two_third"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('two_third', 'two_third');
}

if (!function_exists('one_fifth')) {
	function one_fifth( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
            return '<div class="one_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('one_fifth', 'one_fifth');
}
if (!function_exists('two_fifth')) {
	function two_fifth( $atts, $content = null ) {
            return '<div class="two_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('two_fifth', 'two_fifth');
}
if (!function_exists('three_fifth')) {
	function three_fifth( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
            return '<div class="three_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('three_fifth', 'three_fifth');
}
if (!function_exists('four_fifth')) {
	function four_fifth( $atts, $content = null ) {
	$clear='';
	if (isset($atts['first']) && strpos($atts['first'],'first') !== false)
	  $clear='clearfix';
            return '<div class="four_fifth '.$clear.'"><div class="column_content '.(isset($atts['first'])?$atts['first']:'').'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode('four_fifth', 'four_fifth');
}
/*-----------------------------------------------------------------------------------*/
/*	Team
/*-----------------------------------------------------------------------------------*/


if (!function_exists('team_member')) {
	function team_member( $atts, $content = null ) {
            extract(shortcode_atts(array(
                        'style' => '',
                        'pic' => '',
			'name'   => '',
                        'designation' => ''
	    ), $atts));
	    
	    $output  = '<div class="team_member '.$style.'">';
            
            if(isset($pic) && $pic !=''){
                if(preg_match('!(?<=src\=\").+(?=\"(\s|\/\>))!',$pic, $matches )){
                    $output .= '<img src="'.$matches[0].'" class="animate zoom" alt="'.$name.'" />';
                }else{
                    $output .= '<img src="'.$pic.'" class="animate zoom" alt="'.$name.'" />';
                }
            }
            $output .= '<div class="member_info">';
            (isset($name) && $name !='')?$output .= '<h3>'.html_entity_decode($name).''.((isset($designation) && $designation !='')?' <small>[ '.$designation.' ]</small>':'').'</h3>':'';
            
            $output .= '<span class="clear"></span>';
            $output .= '<ul class="team_socialicons">';
            $output .=do_shortcode($content);
            $output .= '</ul></div>
                </div>';
            return $output;
	}
	add_shortcode('team_member', 'team_member');
}

if (!function_exists('team_social')) {
	function team_social( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'icon' => 'icon-facebook',
            'url' => ''
	    ), $atts));
           $class=str_replace('icon-','',$icon);
	   return '<li><a href="'.$url.'" title="'.apply_filters('vibe_shortcodes_team_social',$class).'" class="'.$class.'"><i class="'.$icon.'"></i></a></li>';;
	}
	add_shortcode('team_social', 'team_social');
}

/*-----------------------------------------------------------------------------------*/
/*	Buttons
/*-----------------------------------------------------------------------------------*/

if (!function_exists('button')) {
	function button( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'url' => '#',
			'target' => '_self',
                        'class' => 'base',
			'bg' => '',
			'hover_bg' => '',
			'color' => '',
                        'size' => 0,
                        'width' => 0,
                        'height' => 0,
                        'radius' => 0,
	    ), $atts));
		
             $rand = 'button'.rand(1,9999);
           $return ='<style> #'.$rand.'{'.(($bg)?'background-color:'.$bg.' !important;':'').''.(($color)?'color:'.$color.' !important;':'').''.(($size!= '0px')?'font-size:'.$size.' !important;':'').''.(($width!= '0px')?'width:'.$width.';':'').''.(($height!= '0px')?'padding-top:'.$height.';padding-bottom:'.$height.';':'').''.(($radius!= '0px')?'border-radius:'.$radius.';':'').'} #'.$rand.':hover{'.(($hover_bg)?'background-color:'.$hover_bg.' !important;':'').'}</style><a target="'.$target.'" id="'.$rand.'" class="button '.$class.'" href="'.$url.'">'.do_shortcode($content) . '</a>';
                
                 return $return;
	}
	add_shortcode('button', 'button');
}


/*-----------------------------------------------------------------------------------*/
/*	Alerts
/*-----------------------------------------------------------------------------------*/

if (!function_exists('alert')) {
	function alert( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'style'   => 'block',
                        'bg' => '',
                        'border' =>'',
                        'color' => '',
	    ), $atts));
		
           return '<div class="alert alert-'.$style.'" style="'.(($color)?'color:'.$color.';':'').''.(($bg)?'background-color:'.$bg.';':'').''.(($border)?'border-color:'.$border.';':'').'">'
                     . do_shortcode($content) . '</div>';
	}
	add_shortcode('alert', 'alert');
}

/*-----------------------------------------------------------------------------------*/
/*	Accordion Shortcodes
/*-----------------------------------------------------------------------------------*/

global $random_number;
$random_number = rand(1,999);

if (!function_exists('agroup')) {
	function agroup( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'first'   => '',
	), $atts));
	 global $random_number;   
	   return '<div class="accordion '.(($first)?'load_first':'').'" id="accordion'.$random_number.'">' . 
                   do_shortcode($content) . '</div>';
	}
	add_shortcode('agroup', 'agroup');
}



if (!function_exists('accordion')) {
	function accordion( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'title' => 'Title goes here',
            'id' => ''
	    ), $atts));
         global $random_number;   
        $new_random_number=$random_number+rand(1,99);
        $check_url = strpos($content,'http');
        if($check_url !== false && $check_url < 2){
        	return '<div class="accordion-group panel">
                     <div class="accordion-heading">
                        <a href="'.$content.'" class="accordion-toggle collapsed" target="_blank">
                            <i></i> '. $title .'</a>
                    </div>
                   </div>';
        }else{
        	return '<div class="accordion-group panel">
                     <div class="accordion-heading">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion'.$random_number.'"  href="#collapse'.$new_random_number.'">
                            <i></i> '. $title .'</a>
                    </div>
                    <div id="collapse'.$new_random_number.'" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <p>'. do_shortcode($content) .'</p>
                        </div>
                   </div>
                   </div>';
        }
	   
	}
	add_shortcode('accordion', 'accordion');
}




/*-----------------------------------------------------------------------------------*/
/*	Testimonial Shortcodes
/*-----------------------------------------------------------------------------------*/

if (!function_exists('testimonial')) {
	function testimonial( $atts, $content = null ) {
	global $vibe_options;
	    extract(shortcode_atts(array(
			'id'    	 => '',
            'length'    => 100,
	    ), $atts));
    
    if($id == 'random'){
    	$args=array('post_type'=>'testimonials', 'orderby'=>'rand', 'posts_per_page'=>'1','fields=ids');
		$testimonials=new WP_Query($args);
		while ($testimonials->have_posts()) : $testimonials->the_post();
			$postdata = get_post(get_the_ID());
		endwhile;	
		wp_reset_postdata();
    }else{
    	$postdata=get_post($id);
    }
    
    if(function_Exists('thumbnail_generator')){
    	$return = thumbnail_generator($postdata,'testimonial',3,$length,0,0);
    }

   return $return;
	}
	add_shortcode('testimonial', 'testimonial');
}

/*-----------------------------------------------------------------------------------*/
/*	User Only
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_useronly')) {
	function vibe_useronly( $atts, $content = null ) {
            extract(shortcode_atts(array(
				'id'   => 1
            ), $atts));
            $return = '';
            if(is_user_logged_in()){
            	if(isset($id) ){
	            	if(is_numeric($id)){
	            		if($id == get_current_user_id()){
	            			$return ='<div class="user_only_content">'.do_shortcode($content).'</div>';
	            		}
	            	}else{
	            		$ids = explode(',',$id);
	            		foreach($ids as $id){
	            			if(is_numeric($id) && $id == get_current_user_id()){
		            			$return ='<div class="user_only_content">'.do_shortcode($content).'</div>';
		            		}
	            		}
	            	}
            	}
            }
        return $return;
	}
	add_shortcode('user_only', 'vibe_useronly');
}


/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : Student Name
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_student_name')) {
	function vibe_certificate_student_name( $atts, $content = null ) {
            $id=$_GET['u'];
            if(isset($id) && $id)
        		return bp_core_get_user_displayname($id);
        	else
        		return '[certificate_student_name]';
	}
	add_shortcode('certificate_student_name', 'vibe_certificate_student_name');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : Student Photo
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_student_photo')) {
	function vibe_certificate_student_photo( $atts, $content = null ) {
            $id=$_GET['u'];
            if(isset($id) && $id)
        		return bp_core_fetch_avatar(array('item_id'=>$id,'type'=>'thumb'));
        	else
        		return '[certificate_student_photo]';
	}
	add_shortcode('certificate_student_photo', 'vibe_certificate_student_photo');
}


/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : Student Email
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_student_email')) {
	function vibe_certificate_student_email( $atts, $content = null ) {
            $id=$_GET['u'];
            if(isset($id) && $id)
        		return get_the_author_meta('user_email',$id);
        	else
        		return '[certificate_student_email]';
	}
	add_shortcode('certificate_student_email', 'vibe_certificate_student_email');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : COURSE NAME
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_course')) {
	function vibe_certificate_course( $atts, $content = null ) {
            $id=$_GET['c'];
            if(isset($id) && $id)
        		return get_the_title($id);
        	else
        		return '[certificate_course]';
	}
	add_shortcode('certificate_course', 'vibe_certificate_course');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : COURSE MARKS
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_student_marks')) {
	function vibe_certificate_student_marks( $atts, $content = null ) {
            $uid=$_GET['u'];
             $cid=$_GET['c'];
            if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid)  && get_post_type($cid) == 'course')
        		return get_post_meta($cid,$uid,true);
        	else
        		return '[certificate_student_marks]';
	}
	add_shortcode('certificate_student_marks', 'vibe_certificate_student_marks');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : STUDENT FIELD
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_student_field')) {
	function vibe_certificate_student_field( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'field'    	 => '',
		    ), $atts));
            $uid=$_GET['u'];
            if(isset($uid) && is_numeric($uid) && isset($field) && strlen($field)>3)
        		return bp_get_profile_field_data( 'field='.$field.'&user_id=' .$uid);
        	else
        		return '[certificate_student_field]';
	}
	add_shortcode('certificate_student_field', 'vibe_certificate_student_field');
}

/*-----------------------------------------------------------------------------------*/
/*    CERTIFICATE SHORTCODES  : CERTIFICATE DATE
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_student_date')) {
    function vibe_certificate_student_date( $atts, $content = null ) {
           $uid=$_GET['u'];
           $cid=$_GET['c'];
           global $bp,$wpdb;

           if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
           $course_submission_date = $wpdb->get_var($wpdb->prepare( "
                                SELECT activity.date_recorded FROM {$bp->activity->table_name} AS activity
                                WHERE     activity.component     = 'course'
                                AND     activity.type     = 'student_certificate'
                                AND     user_id = %d
                                AND     item_id = %d
                                ORDER BY date_recorded DESC LIMIT 0,1
                            " ,$uid,$cid));

                  if(isset($course_submission_date)){
                   return date_i18n( get_option( 'date_format' ), strtotime($course_submission_date));                    
                  }else{
                   $date = $wpdb->get_var($wpdb->prepare( "
                                            SELECT activity.date_recorded
                                            FROM {$bp->activity->table_name} AS activity 
                                            LEFT JOIN {$bp->activity->table_name_meta} as meta ON activity.id = meta.activity_id
                                            WHERE     activity.component     = 'course'
                                            AND     activity.type     = 'bulk_action'
                                            AND     meta.meta_key   = 'add_certificate'
                                            AND     meta.meta_value = %d
                                            AND     activity.item_id = %d
                                            ORDER BY date_recorded DESC LIMIT 0,1
                                        " ,$uid,$cid));

                       if(isset($date)){
                        return date_i18n( get_option( 'date_format' ), strtotime($date));                    
                       }
                  }
           }    
       return '[certificate_student_date]';
    }
    add_shortcode('certificate_student_date', 'vibe_certificate_student_date');
}
/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : COURSE COMPLETION DATE
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_course_finish_date')) {
	function vibe_certificate_course_finish_date( $atts, $content = null ) {
            $uid=$_GET['u'];
            $cid=$_GET['c'];
            global $bp,$wpdb;

            if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
            $course_submission_date = $wpdb->get_results($wpdb->prepare( "
								SELECT activity.date_recorded as date FROM {$bp->activity->table_name} AS activity
								WHERE 	activity.component 	= 'course'
								AND 	activity.type 	= 'submit_course'
								AND 	user_id = %d
								AND 	item_id = %d
								ORDER BY date_recorded DESC LIMIT 0,1
							" ,$uid,$cid));

           		if(isset($course_submission_date[0]->date)){
        			return date_i18n(get_option( 'date_format' ), strtotime($course_submission_date[0]->date));        			
           		}
        	}	
    	return '[course_completion_date]';
	}
	add_shortcode('course_completion_date', 'vibe_certificate_course_finish_date');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : CERTIFICATE CODE
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_code')) {
	function vibe_certificate_code( $atts, $content = null ) {
            $uid=$_GET['u'];
            $cid=$_GET['c'];
            if(isset($uid) && is_numeric($uid) && isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
            	$ctemplate=get_post_meta($cid,'vibe_certificate_template',true);
            	if(isset($ctemplate) && $ctemplate){
            		$code = $ctemplate.'-'.$cid.'-'.$uid;
            	}else{
            		$code = get_the_ID().'-'.$cid.'-'.$uid;
            	}
            	return apply_filters('wplms_certificate_code',$code,$cid,$uid);
            }
            else
        		return '[certificate_code]';
	}
	add_shortcode('certificate_code', 'vibe_certificate_code');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : CERTIFICATE COURSE INSTRUCTOR
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_course_instructor')) {
	function vibe_certificate_course_instructor( $atts, $content = null ) {
            $cid=$_GET['c'];
            if(isset($cid) && is_numeric($cid) && get_post_type($cid) == 'course'){
            	$course=get_post($cid);
            	$instructor = apply_filters('wplms_course_instructors',$course->post_author,$course->ID);
            	if(!isset($instructor))
            		return;
            	
            	if(is_array($instructor)){

            	}else{

            	}
            	return get_the_author_meta('display_name',$instructor);
            }
            else
        		return '[course_instructor]';
	}
	add_shortcode('course_instructor', 'vibe_certificate_course_instructor');
}

/*-----------------------------------------------------------------------------------*/
/*	CERTIFICATE SHORTCODES  : CERTIFICATE COURSE FIELD
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_certificate_course_field')) {
	function vibe_certificate_course_field( $atts, $content = null ) {
			extract(shortcode_atts(array(
			'field'   => '',
            'course' =>'',
	    	), $atts));

	    	if(!isset($course) || !is_numeric($course)){
	    		$course_id=$_GET['c'];
	    	}

            if(isset($course_id) && is_numeric($course_id) && get_post_type($course_id) == 'course'){
            	$value = get_post_meta($course_id,$field,true);
            	if(isset($value)){
            		return $value;
            	}else
        			return '[certificate_course_field]';
            }else
        		return '[certificate_course_field]';
	}
	add_shortcode('certificate_course_field', 'vibe_certificate_course_field');
}

/*-----------------------------------------------------------------------------------*/
/*	Tabs Shortcodes
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_tabs')) {
	function vibe_tabs( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'style'   => '',
            'theme'   => ''
	    ), $atts));
            
		$defaults=$tab_icons = array();
                extract( shortcode_atts( $defaults, $atts ) );
		
		// Extract the tab titles for use in the tab widget.
		preg_match_all( '/tab title="([^\"]+)" icon="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
		
		$tab_titles = array();
        
		if(!count($matches[1])){ 
		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

		if( isset($matches[1]) ){ $tab_titles = $matches[1];}
		}else{
		if( isset($matches[1]) ){ $tab_titles = $matches[1]; $tab_icons= $matches[2];}
		}
		
		
		$output = '';
        global $random_number;
		if( count($tab_titles) ){
		    $output .= '<div id="vibe-tabs-'. rand(1, 100) .'" class="tabs tabbable '.$style.' '.$theme.'">';
			$output .= '<ul class="nav nav-tabs clearfix">';

         	foreach( $tab_titles as $i=>$tab ){
                $tabstr= str_replace(' ', '-', $tab[0]);
                $tabstr=preg_replace('/[^A-Za-z0-9\-]/', '', $tabstr);

                $check_url = strpos($tab_icons[$i][0],'http');
                if(isset($tab_icons[$i][0]) && $check_url !== false && $check_url<2){
                	$href = $tab_icons[$i][0];
                }else{
                	$href='#tab-'. $tabstr .'-'.$random_number;
	            }

				$output .= '<li><a href="'.$href.'">';
				
				if(isset($tab_icons[$i][0]))
					$output.='<span><i class="' . $tab_icons[$i][0] . '"></i></span>';

				$output .= $tab[0] . '</a></li>';
			}
		    $output .= '</ul><div class="tab-content">';
		    $output .= do_shortcode( $content );
		    $output .= '</div></div>';
		} else {
			$output .= do_shortcode( $content );
		}
		
		return $output;
	}
	add_shortcode( 'tabs', 'vibe_tabs' );
}

if (!function_exists('vibe_tab')) {
	function vibe_tab( $atts, $content = null ) { 
		$defaults = array( 'title' => 'Tab' );
		extract( shortcode_atts( $defaults, $atts ) );
		global $random_number;
		$tabstr= str_replace(' ', '-', $title);//
		$tabstr=preg_replace('/[^A-Za-z0-9\-]/', '', $tabstr);
		return '<div id="tab-'. $tabstr .'-'.$random_number.'" class="tab-pane"><p>'. do_shortcode( $content ) .'</p></div>';
	}
	add_shortcode( 'tab', 'vibe_tab' );
}


/*-----------------------------------------------------------------------------------*/
/*	Tooltips
/*-----------------------------------------------------------------------------------*/

if (!function_exists('tooltip')) {
	function tooltip( $atts, $content = null ) {
		extract(shortcode_atts(array(
	        'direction'   => 'top',
	        'tip' => 'Tooltip',
	    ), $atts));
		$istyle='';

           return '<a data-rel="tooltip" class="tip" data-placement="'.$direction.'" data-original-title="'.$tip.'">'.do_shortcode($content).'</a>';

	}
	add_shortcode('tooltip', 'tooltip');
}


/*-----------------------------------------------------------------------------------*/
/*	Taglines
/*-----------------------------------------------------------------------------------*/

if (!function_exists('tagline')) {
	function tagline( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'style'   => '',
                        'bg'   => '',
                        'border'   => '',
                        'bordercolor'   => '',
                        'color'   => '',
	    ), $atts));
           return '<div class="tagline '.$style.'" style="background:'.$bg.';border-color:'.$border.';border-left-color:'.$bordercolor.';color:'.$color.';" >'.do_shortcode($content).'</div>';
	}
	add_shortcode('tagline', 'tagline');
}




/*-----------------------------------------------------------------------------------*/
/*	POPUP
/*-----------------------------------------------------------------------------------*/

if (!function_exists('popupajax')) {
	function popupajax( $atts, $content = null ) {
            extract(shortcode_atts(array(
            	'id'   => '',
                'auto' => 0,
                'classes' =>''
            ), $atts));


  
   $return='';
    if($auto){
     $return .='<script>jQuery(window).load(function(){ jQuery("#anchor_popup_'.$id.'").trigger("click");});</script>'; 
    }
        
        $return .= '<a class="popup-with-zoom-anim ajax-popup-link '.$classes.'" href="'.admin_url('admin-ajax.php').'?ajax=true&action=vibe_popup&id='.$id.'" id="anchor_popup_'.$id.'">
                   '.apply_filters('the_content',$content).'</a>';
        return $return;

	}
	add_shortcode('popup', 'popupajax');
}



/*-----------------------------------------------------------------------------------*/
/*	Google Maps shortcode
/*-----------------------------------------------------------------------------------*/

if (!function_exists('gmaps')) {
	function gmaps( $atts, $content = null ) { 
                        $map ='<div class="gmap">'.$content.'</div>';
                        return $map;
	}
	add_shortcode('map', 'gmaps');
}

/*-----------------------------------------------------------------------------------*/
/*	Gallery shortcode
/*-----------------------------------------------------------------------------------*/

if (!function_exists('gallery')) {
	function gallery( $atts, $content = null ) { 
           extract(shortcode_atts(array(
                        'size' => 'normal',
                        'ids' => ''
                            ), $atts));
            $gallery='<div class="gallery '.$size.'">';
            
            
                if(isset($ids) && $ids!=''){
                    $rand='gallery'.rand(1,999);
                    $posts=explode(',',$ids);
                    foreach($posts as $post_id){
                         // IF Ids are not Post Ids
                           if ( wp_attachment_is_image( $post_id ) ) {
                               $attachment_info = wp_get_attachment_info($post_id);
                               
                               $full=wp_get_attachment_image_src( $post_id, 'full' );
                               $thumb=wp_get_attachment_image_src( $post_id, $size );
                               
                               if(is_array($thumb))$thumb=$thumb[0];
                                if(is_array($full))$full=$full[0];
                                
                               $gallery.='<a href="'.$full.'" title="'.$attachment_info['title'].'"><img src="'.$thumb.'" alt="'.$attachment_info['title'].'" /></a>';
                            }
                    }
                }
            $gallery.='</div>';
                        return $gallery;
	}
	add_shortcode('gallery', 'gallery');
}


/*-----------------------------------------------------------------------------------*/
/*	HEADING
/*-----------------------------------------------------------------------------------*/

if (!function_exists('heading')) {
	function heading( $atts, $content = null ) { 
             extract(shortcode_atts(array(
                        'style' => '',
                            ), $atts));
                return '<h3 class="heading '.$style.'"><span>'.do_shortcode($content).'</span></h3>';
	}
	add_shortcode('heading', 'heading');
}




/*-----------------------------------------------------------------------------------*/
/*	PROGRESSBARS
/*-----------------------------------------------------------------------------------*/

if (!function_exists('progressbar')) {
	function progressbar( $atts, $content = null ) { 
			extract(shortcode_atts(array(
			             'color' => '',
                                     'bg' => '',
                                     'textcolor' => '',
			             'percentage' => '20'
			                 ), $atts));
				
           return '<div class="progress" '.(($bg)?'style="background-color:'.$bg.';"':'').'>
             <div class="bar animate stretchRight" style="width: '.$percentage.'%;'.(($color)?'background-color:'.$color.';':'').''.((isset($padding) && $padding != '0px' )?'padding:'.$padding.';':'').''.(($textcolor)?'color:'.$textcolor.';':'').'">'.do_shortcode($content).'<span>'.$percentage.'%</span></div>
           </div>';

	}
	add_shortcode('progressbar', 'progressbar');
}


/*-----------------------------------------------------------------------------------*/
/*	FORMS
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibeform')) {
	function vibeform( $atts, $content = null ) { 
            extract(shortcode_atts(array(
			             'to' => '',
                         'subject' => '',
                         'isocharset' => '',
			             ), $atts));

            global $post;
            $nonce = wp_create_nonce( 'vibeform_security'.$to);
            return apply_filters('vibe_shortcode_form','<div class="form">
           	 <form method="post" data-to="'.$to.'" data-subject="'.$subject.'" '.(($isocharset)?'class="isocharset"':'').'>'.
                    do_shortcode($content)  
           	 .'<div class="response" data-security="'.$nonce.'"></div></form>
           	 </div>');

	}
	add_shortcode('form', 'vibeform');
}


if (!function_exists('form_element')) {
	function form_element( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'type' => 'text',
            'validate' => '',
            'options' => '',
            'placeholder' => 'Name'
	    ), $atts));
            $output='';
            $r =  rand(1,999);
            switch($type){
                case 'text': $output .= '<input type="text" placeholder="'.$placeholder.'" class="form_field text" data-validate="'.$validate.'" />';
                    break;
                case 'textarea': $output .= '<textarea placeholder="'.$placeholder.'" class="form_field  textarea" data-validate="'.$validate.'"></textarea>';
                    break;
                case 'select': $output .= '<select class="form_field  select" placeholder="'.$placeholder.'">';
                                $output .= '<option value="">'.$placeholder.'</option>';
                                $options  = explode(',',$options);
                                foreach($options as $option){
                                    $output .= '<option value="'.$option.'">'.$option.'</option>';
                                }
                                $output .= '</select>';
                    break;
                case 'captcha': $output .='<i class="math_sum"><span id="num'.$r.'-1">'.rand(1,9).'</span><span> + </span><span id="num'.$r.'-2">'.rand(1,9).'</span><span> = </span></i><input id="num'.$r.'" type="text" placeholder="0" class="form_field text small" data-validate="captcha" />';
                	break;    
                case 'submit':
                    $output .= '<input type="submit" class="form_submit button primary" value="'.$placeholder.'" />';
                    break;
            }

	   return $output;
	}
	add_shortcode('form_element', 'form_element');
}

/*-----------------------------------------------------------------------------------*/
/*	QUIZ SHORTCODE : FILLBLANK
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_fillblank')) {
	function vibe_fillblank( $atts, $content = null ) {
        global $post; 
        $user_id=get_current_user_id();
        $answers=get_comments(array(
          'post_id' => $post->ID,
          'status' => 'approve',
          'user_id' => $user_id
          ));

        $content =' ';
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $content = $answer->comment_content;
        }
        if(bp_is_member())
        	return '____________';

    	$return ='<i class="live-edit" data-model="article" data-url="/articles"><span class="vibe_fillblank" data-editable="true" data-name="content" data-max-length="250" data-text-options="true">'.$content.'</span></i>';

    	return $return;
	}
	add_shortcode('fillblank', 'vibe_fillblank');
}


/*-----------------------------------------------------------------------------------*/
/*	QUIZ SHORTCODE : SELECT
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_select')) {
	function vibe_select( $atts, $content = null ) {
        global $post; 
        $user_id=get_current_user_id();
        $answers=get_comments(array(
          'post_id' => $post->ID,
          'status' => 'approve',
          'user_id' => $user_id
          ));

        $content ='';
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $content = $answer->comment_content;
        }

        $options = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_question_options',false));
        

        if(!is_array($options) || !count($options))
        	return '&laquo; ______ &raquo;';

        $return = '<select id="vibe_select_dropdown" class="chosen">';
        foreach($options as $key=>$value){
        	$k=$key+1;
          $return .= '<option value="'.$k.'" '.(($k == $content)?'selected':'').'>'.$value.'</option>';
        }
    	$return .= '</select>';
    	return $return;
	}
	add_shortcode('select', 'vibe_select');
}



/*-----------------------------------------------------------------------------------*/
/*	QUIZ SHORTCODE : MATCH
/*-----------------------------------------------------------------------------------*/
if (!function_exists('vibe_match')) {
	function vibe_match( $atts, $content = null ) {
		global $post; 
        $user_id=get_current_user_id();
        $answers=get_comments(array(
          'post_id' => $post->ID,
          'status' => 'approve',
          'user_id' => $user_id
          ));
        $string ='';
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $option_matches = explode(',',$answer->comment_content);
            foreach($option_matches as $k=>$option_match){
            	$string .= ' data-match'.$k.'="'.$option_match.'"';
            }
        }
		return '<div class="matchgrid_options '.((isset($answers) && is_array($answers) && count($answers))?'saved_answer':'').' "'.$string.'>'.do_shortcode($content).'</div>';
	}
	add_shortcode('match', 'vibe_match');
}


/*-----------------------------------------------------------------------------------*/
/*	Course Product
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_course_product_details')) {
	function vibe_course_product_details( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'id' => '',
			'details' => '',
	    ), $atts));
		
		if(isset($id) && is_numeric($id)){
     		$course_id = $id;	
		}else{
			if(isset($_GET['c']) && is_numeric($_GET['c']))
				$course_id=$_GET['c']; // For certificate use
			else
				return;
		}

		if(get_post_type($course_id) == BP_COURSE_CPT){
			$product_id = get_post_meta($course_id,'vibe_product',true);
			if(isset($product_id) && is_numeric($product_id)){
				switch($details){
					case 'sku':
						$return = get_post_meta($product_id,'_sku',true);
					break;
					case 'price':
						$product = wc_get_product( $product_id );
						$return = $product->get_price_html();
					break;
					case 'sales':
						$return = get_post_meta($product_id,'total_sales',true);
					break;
					case 'note':
						$return = get_post_meta($product_id,'_purchase_note',true);
					break;
				}
			}
		}
		return $return;
	}
	add_shortcode('course_product', 'vibe_course_product_details');
}


/*-----------------------------------------------------------------------------------*/
/*	Vibe site stats [vibe_site_stats total=1 courses=1 instructors=1 ]
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_site_stats')) {
	function vibe_site_stats($atts, $content = null){
		extract(shortcode_atts(array(
		'total'   => 0,
		'courses'   =>0,
		'instructor' => 0,
		'groups' => 0,
		'subscriptions' => 0,
		'sales' => 0,
		'commissions' => 0,
		'posts'=>0,
		'comments'=>0,
		'number'=>0
        ), $atts));
		
		$return = array();
		$users =count_users();
		if($total){
			$return['total'] = $users['total_users'];
			if($number)
				return $return['total'];
		}
		if($instructor){
			$return['instructor'] = $users['avail_roles']['instructor'];
			if($number)
				return $return['instructor'];
		}

		if($courses){
			$count_posts = wp_count_posts('course');
			$return['courses'] = $count_posts->publish;
			if($number)
				return $return['courses']; 
		}
		
		if($groups){
			global $wpdb,$bp;
			$count = $wpdb->get_results("SELECT count(*) as count FROM {$bp->groups->table_name}",ARRAY_A);
			if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
				$return['groups']=$count[0]['count'];
			}else{
				$return['groups']=0;
			}
			if($number)
				return $count[0]['count'];
		}
		if($subscriptions){
			global $wpdb,$bp;
			$count = $wpdb->get_results("SELECT count(*) as count FROM {$wpdb->postmeta} WHERE meta_key REGEXP '^[0-9]+$' AND meta_value REGEXP '^[0-9]+$'",ARRAY_A);
			if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
				$return['subscriptions']=$count[0]['count'];
			}else{
				$return['subscriptions']=0;
			}
			if($number)
				return $count[0]['count'];
		}
		if($sales){
			global $wpdb;
			$count = $wpdb->get_results($wpdb->prepare("SELECT sum(meta_value) as count FROM {$wpdb->postmeta} WHERE meta_key = %s",'_order_total'),ARRAY_A);
			if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
				$return['sales']=$count[0]['count'];
			}else{
				$return['sales']=0;
			}
			if($number)
				return $count[0]['count'];
		}
		if($commissions){
			global $wpdb;
			$table_name = $wpdb->prefix.'woocommerce_order_itemmeta';
			$q=$wpdb->prepare("SELECT sum(meta_value) as count FROM {$table_name} WHERE meta_key LIKE %s",'commission%');
			$count = $wpdb->get_results($q,ARRAY_A);
			if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
				$return['commissions']=$count[0]['count'];
			}else{
				$return['commissions']=0;
			}
			if($number)
				return $count[0]['count'];
		}
		if($posts){
			global $wpdb;
			$count = $wpdb->get_results($wpdb->prepare("SELECT count(*) as count FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s",'post','publish'),ARRAY_A);
			if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
				$return['posts']=$count[0]['count'];
			}else{
				$return['posts']=0;
			}
			if($number)
				return $count[0]['count'];
		}
		if($comments){
			global $wpdb;
			$count = $wpdb->get_results($wpdb->prepare("SELECT count(*) as count FROM {$wpdb->comments} WHERE comment_approved = %d AND comment_post_ID IN (SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s)",1,'post','publish'),ARRAY_A);
			if(is_array($count) && isset($count[0]['count']) && is_numeric($count[0]['count'])){
				$return['comments']=$count[0]['count'];
			}else{
				$return['comments']=0;
			}
			if($number)
				return $count[0]['count'];
		}
		$return_html='';
		if(is_array($return) && count($return)){
			$return_html='<ul class="site_stats">';
			foreach($return as $key=>$value){
				if($value){
					switch($key){
						case 'total':
							$return_html .='<li>'.__('MEMBERS','vibe-shortcodes').'<span>'.$value.'</span></li>';
						break;
						case 'courses':
						$return_html .='<li>'.__('COURSES','vibe-shortcodes').'<span>'.$value.'</span></li>';
						break;
						case 'instructor':
						$return_html .='<li>'.__('INSTRUCTORS','vibe-shortcodes').'<span>'.$value.'</span></li>';
						break;
						case 'groups':
						$return_html .='<li>'.__('GROUPS','vibe-shortcodes').'<span>'.$value.'</span></li>';
						break;
						case 'subscriptions':
						$return_html .='<li>'.__('SUBSCRIPTIONS','vibe-shortcodes').'<span>'.$value.'</span></li>';
						break;
						case 'sales':
						$return_html .='<li>'.__('SALES','vibe-shortcodes').'<span>'.get_woocommerce_currency_symbol("USD").$value.'</span></li>';
						break;
						case 'commissions':
						$return_html .='<li>'.__('EARNINGS','vibe-shortcodes').'<span>'.get_woocommerce_currency_symbol("USD").$value.'</span></li>';
						break;
					}
				}
			}
			$return_html .= '</ul>';
		}
		return $return_html;
	}
	add_shortcode('vibe_site_stats', 'vibe_site_stats');
}


/*-----------------------------------------------------------------------------------*/
/*	Question
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_question')) {
	function vibe_question( $atts, $content = null ) {
            extract(shortcode_atts(array(
			'id'   => '',
            ), $atts));
            
            if(!is_numeric($id) || get_post_type($id) != 'question')
            	return '';

    		$question = get_post($id);
    		$hint = get_post_meta($id,'vibe_question_hint',true);
  			$type = get_post_meta($id,'vibe_question_type',true);
    		$return ='<div class="question '.$type.'">';
    		$return .='<div class="question_content">'.apply_filters('the_content',$question->post_content);
    		if(isset($hint) && strlen($hint)>5){
		        $return .='<a class="show_hint tip" tip="'.__('SHOW HINT','vibe-shortcodes').'"><span></span></a>';
		        $return .='<div class="hint"><i>'.__('HINT','vibe-shortcodes').' : '.apply_filters('the_content',$hint).'</i></div>';
	      	}
    		$return .='</div>';
    		switch($type){
		        case 'truefalse': 
		        case 'single': 
		        case 'multiple': 
		        case 'sort':
		        case 'match':
		           $options = vibe_sanitize(get_post_meta($id,'vibe_question_options',false));

		          if($type == 'truefalse')
		            $options = array( 0 => __('FALSE','vibe-shortcodes'),1 =>__('TRUE','vibe-shortcodes'));

		          if(isset($options) || $options){
		        
		            $return .= '<ul class="question_options '.$type.'">';
		              if($type=='single'){
		                foreach($options as $key=>$value){
		                  $return .= '<li>
		                            <input type="radio" id="'.$question->post_name.$key.'" class="ques'.$id.'" name="'.$id.'" value="'.($key+1).'" />
		                            <label for="'.$question->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
		                        </li>';
		                }
		              }else if($type == 'sort'){
		                foreach($options as $key=>$value){
		                  $return .= '<li id="'.($key+1).'" class="ques'.$question->ID.' sort_option">
		                              <label for="'.$question->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
		                          </li>';
		                }        
		              }else if($type == 'match'){
		                foreach($options as $key=>$value){
		                  $return .= '<li id="'.($key+1).'" class="ques'.$question->ID.' match_option">
		                              <label for="'.$question->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
		                          </li>';
		                }        
		              }else if($type == 'truefalse'){
		                foreach($options as $key=>$value){
		                  $return .= '<li>
		                            <input type="radio" id="'.$question->post_name.$key.'" class="ques'.$question->ID.'" name="'.$question->ID.'" value="'.$key.'" />
		                            <label for="'.$question->post_name.$key.'"><span></span> '.$value.'</label>
		                        </li>';
		                }       
		              }else{
		                foreach($options as $key=>$value){
		                  $return .= '<li>
		                            <input type="checkbox" class="ques'.$question->ID.'" id="'.$question->post_name.$key.'" name="'.$question->ID.$key.'" value="'.($key+1).'" />
		                            <label for="'.$question->post_name.$key.'"><span></span> '.do_shortcode($value).'</label>
		                        </li>';
		                }
		              }  
		            $return .= '</ul>';
		          }
		        break; // End Options
		        case 'fillblank': 
		        break;
		        case 'select': 
		        break;
		        case 'smalltext': 
		          $return .= '<input type="text" name="'.$k.'" class="ques'.$k.' form_field" value="" placeholder="'.__('Type Answer','vibe-shortcodes').'" />';
		        break;
		        case 'largetext': 
		          $return .= '<textarea name="'.$k.'" class="ques'.$k.' form_field" placeholder="'.__('Type Answer','vibe-shortcodes').'"></textarea>';
		        break;
		      }
		      $return .='<ul class="check_options">';
		      
		      
		      $answer = get_post_meta($id,'vibe_question_answer',true);
		      if(isset($answer) && strlen($answer) && in_array($type,array('single','multiple','truefalse','sort','match'))){
		         $return .='<li><a class="check_answer" data-id="'.$id.'">'.__('Check Answer','vibe-shortcodes').'</a></li>';		
		      	 $ans_json = array('type' => $type);
		      	 if(in_array($type,array('multiple'))){
		      	 	$ans_array =  explode(',',$answer);
		      	 	$ans_json['answer'] = $ans_array;
		      	 }else{
		      	 	$ans_json['answer'] = $answer; 
		      	 }
		      	 echo '<script>
		      	 	var ans_json'.$id.'= '.json_encode($ans_json).';
		      	 </script>';
		      }

		      $explaination = get_post_meta($id,'vibe_question_explaination',true);
		      if(isset($explaination) && strlen($explaination)>2){
		      	$return .= '<li><a href="#question_explaination'.$id.'" class="open_popup_link">'.__('Explanation','vibe-shortcodes').'</a></li>';
		      
		      	echo '<div id="question_explaination'.$id.'" class="white-popup mfp-hide">
			      '.do_shortcode($explaination).'
			      </div>';
		      }

    		$return .='</ul></div>';	    	
            
        return $return;
	}
	add_shortcode('question', 'vibe_question');
}


/*-----------------------------------------------------------------------------------*/
/*	Course Search box
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_course_search')) {
	function vibe_course_search( $atts, $content = null ) {
            extract(shortcode_atts(array(
		'style'   => 'left'
                ), $atts));
        
        $html ='<form role="search" method="get" class="'.$style.'" action="'.home_url( '/' ).'">
		     			<input type="hidden" name="post_type" value="'.BP_COURSE_SLUG.'" />
		     			<input type="text" value="'.(isset($_GET['s'])?$_GET['s']:'').'" name="s" id="s" placeholder="'.__('Type Keywords..','vibe-shortcodes').'" />
					    <input type="submit" id="searchsubmit" value="'.__('Search','vibe-shortcodes').'" />
                        </form>';

        return $html;
	}
	add_shortcode('course_search', 'vibe_course_search');
}

/*-----------------------------------------------------------------------------------*/
/*	Pass Fail shortcodes
/*-----------------------------------------------------------------------------------*/

if (!function_exists('vibe_pass_fail')) {
	function vibe_pass_fail( $atts, $content = null ) {
            extract(shortcode_atts(array(
					'id'   => '',
					'key'   => '',
					'passing_score'   => '',
					'pass'=>0,
					'fail'=>0
                ), $atts));
        
        if(!is_numeric($id)){
        	return;
        }
        if(!isset($key) || !$key){
        	$key = get_current_user_id();
        }
        if(!isset($passing_score) || !$passing_score){
        	$post_type=get_post_type($id);
        	if($post_type == 'course'){
        		$passing_score = get_post_meta($id,'vibe_course_passing_percentage',true);
        	}else if($post_type == 'quiz'){
        		$passing_score = get_post_meta($id,'vibe_quiz_passing_score',true);
        	}else
        		return;
        }
        $score = apply_filters('wplms_pass_fail_shortcode',get_post_meta($id,$key,true));

        if($pass && $score >=$passing_score){ 
        	return apply_filters('the_content',$content);
        }

        if($fail && $score < $passing_score){
        	return apply_filters('the_content',$content);
        }
        
        return $return;
	}
	add_shortcode('pass_fail', 'vibe_pass_fail');
}


?>