<?php


function thumbnail_generator($custom_post,$featured_style,$cols='medium',$n=100,$link=0,$zoom=0){
    $return=$read_more=$class='';    
    global $post;
    $more = __('Read more','vibe-customtypes');
    
    if(strlen($custom_post->post_content) > $n)
        $read_more= '<a href="'.get_permalink($custom_post->ID).'" class="link">'.$more.'</a>';

    $cache_duration = vibe_get_option('cache_duration'); if(!isset($cache_duration)) $cache_duration=0;
    if($cache_duration){
        $key= $featured_style.'_'.$custom_post->post_type.'_'.$custom_post->ID;
        if(is_user_logged_in()){
            $user_id = get_current_user_id();
            $user_meta = get_user_meta($user_id,$custom_post->ID,true);
            if(isset($user_meta)){
                $key .= '_'.$user_id;
            }
        }
        $result = wp_cache_get($key,'featured_block');
    }else{$result=false;}

    if ( false === $result ) {
                    
    switch($featured_style){
            case 'course':
            
                    $return .='<div class="block courseitem">';
                    $return .='<div class="block_media">';
                    $return .= apply_filters('vibe_thumb_featured_image','<a href="'.get_permalink($custom_post->ID).'">'.featured_component($custom_post->ID,$cols).'</a>',$featured_style);
                    $return .='</div>';
                    
                    $return .='<div class="block_content">';
                    
                    
                    $return .= apply_filters('vibe_thumb_heading','<h4 class="block_title"><a href="'.get_permalink($custom_post->ID).'" title="'.$custom_post->post_title.'">'.$custom_post->post_title.'</a></h4>',$featured_style);
                    
                    $category='';
                    if(get_post_type($custom_post->ID) == 'course'){
                        $rating=get_post_meta($custom_post->ID,'average_rating',true);
                        $rating_count=get_post_meta($custom_post->ID,'rating_count',true);
                        $meta = '<div class="star-rating">';
                        for($i=1;$i<=5;$i++){

                            if(isset($rating)){
                                if($rating >= 1){
                                    $meta .='<span class="fill"></span>';
                                }elseif(($rating < 1 ) && ($rating > 0.4 ) ){
                                    $meta .= '<span class="half"></span>';
                                }else{
                                    $meta .='<span></span>';
                                }
                                $rating--;
                            }else{
                                $meta .='<span></span>';
                            }
                        }
                        $meta =  apply_filters('vibe_thumb_rating',$meta,$featured_style,$rating);
                        $meta .= apply_filters('vibe_thumb_reviews','(&nbsp;'.(isset($rating_count)?$rating_count:'0').'&nbsp;'.__('REVIEWS','vibe-customtypes').'&nbsp;)',$featured_style).'</div>';

                        $free_course = get_post_meta($custom_post->ID,'vibe_course_free',true);

                        $meta .=bp_course_get_course_credits(array('id'=>$custom_post->ID));
                        
                        $meta .='<span class="clear"></span>';

                        
                        $instructor_meta='';
                        if(function_exists('bp_course_get_instructor'))
                            $instructor_meta .= bp_course_get_instructor();
                        
                        $meta .= apply_filters('vibe_thumb_instructor_meta',$instructor_meta,$featured_style);

                        $st = get_post_meta($custom_post->ID,'vibe_students',true);
                        if(isset($st) && $st !='')
                            $meta .= apply_filters('vibe_thumb_student_count','<strong>'.$st.' '.__('Students','vibe-customtypes').'</strong>');

                        
                        $return .= $meta;
                    }
                    $return .=apply_filters('wplms_course_thumb_extras',''); // Use this filter to add Extra HTML to the course featured block
                    $return .='</div>';
                    $return .='</div>';


                break;
           case 'course2':
            
                    $return .='<div class="block courseitem course2">';
                    $return .='<div class="block_media">';
                    $return .= apply_filters('vibe_thumb_featured_image','<a href="'.get_permalink($custom_post->ID).'">'.featured_component($custom_post->ID,$cols).'</a>',$featured_style);
                    $return .='</div>';
                    
                    $return .='<div class="block_content">';
                    
                    
                    $return .= apply_filters('vibe_thumb_heading','<h4 class="block_title"><a href="'.get_permalink($custom_post->ID).'" title="'.$custom_post->post_title.'">'.$custom_post->post_title.'</a></h4>',$featured_style);
                    
                    $category='';
                    if(get_post_type($custom_post->ID) == 'course'){
                        $rating=get_post_meta($custom_post->ID,'average_rating',true);
                        $rating_count=get_post_meta($custom_post->ID,'rating_count',true);
                        $meta = '<div class="star-rating">';
                        for($i=1;$i<=5;$i++){

                            if(isset($rating)){
                                if($rating >= 1){
                                    $meta .='<span class="fill"></span>';
                                }elseif(($rating < 1 ) && ($rating > 0.4 ) ){
                                    $meta .= '<span class="half"></span>';
                                }else{
                                    $meta .='<span></span>';
                                }
                                $rating--;
                            }else{
                                $meta .='<span></span>';
                            }
                        }
                        $meta =  apply_filters('vibe_thumb_rating',$meta,$featured_style,$rating);
                        $meta .= apply_filters('vibe_thumb_reviews','(&nbsp;'.(isset($rating_count)?$rating_count:'0').'&nbsp;'.__('REVIEWS','vibe-customtypes').'&nbsp;)',$featured_style).'</div>';

                        $free_course = get_post_meta($custom_post->ID,'vibe_course_free',true);

                        
                        
                        $st = get_post_meta($custom_post->ID,'vibe_students',true);
                        if(isset($st) && $st !='')
                            $meta .= apply_filters('vibe_thumb_student_count','<strong><i class="icon-users"></i>&nbsp;'.$st.'</strong>');

                        $meta .='<span class="clear"></span>';

                        
                        $instructor_meta='';
                        if(function_exists('bp_course_get_instructor'))
                            $instructor_meta .= bp_course_get_instructor();
                        
                        $meta .= apply_filters('vibe_thumb_instructor_meta',$instructor_meta,$featured_style);

                        $meta .=bp_course_get_course_credits(array('id'=>$custom_post->ID));

                        $return .= $meta;
                    }
                    $return .=apply_filters('wplms_course_thumb_extras',''); // Use this filter to add Extra HTML to the course featured block
                    $return .='</div>';
                    $return .='</div>';


                break;     
           case 'side':
                   $return .='<div class="block side">';
                    $return .='<div class="block_media">';
                    if(isset($link) && $link)
                        $return .='<span class="overlay"></span>';
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($custom_post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                    $featured= getPostMeta($custom_post->ID, 'vibe_select_featured');
                    if(isset($zoom) && $zoom && has_post_thumbnail($custom_post->ID) )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($custom_post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    
                    
                    $return .= apply_filters('vibe_thumb_featured_image',featured_component($custom_post->ID,$cols),$featured_style);
                    
                    $category='';
                    if(get_post_type($custom_post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    $return .='</div>';
                    
                    $category='';
                    if(get_post_type($custom_post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    
                    
                    $return .='<div class="block_content">';
                    $return .= apply_filters('vibe_thumb_heading','<h4 class="block_title"><a href="'.get_permalink($custom_post->ID).'" title="'.$custom_post->post_title.'">'.$custom_post->post_title.'</a></h4>',$featured_style);
                    $return .= apply_filters('vibe_thumb_date','<div class="date"><small>'. get_the_time('F d,Y').''.((strlen($category)>2)? ' / '.$category:'').' / '.get_comments_number( '0', '1', '%' ).' '.__(' Comments','vibe-customtypes').'</small></div>',$featured_style);
                    $return .= apply_filters('vibe_thumb_desc','<p class="block_desc">'.custom_excerpt($n,$custom_post->ID).'</p>',$featured_style);
                    $return .='</div>';
                    $return .='</div>';
                break;    
            case 'images_only':
                    $return .='<div class="block">';
                    $return .='<div class="block_media images_only">';
                    if(isset($link) && $link)
                        $return .='<span class="overlay"></span>';
                    
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($custom_post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                    
                    if(isset($zoom) && has_post_thumbnail($custom_post->ID) && $zoom )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($custom_post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    $return .= apply_filters('vibe_thumb_featured_image',featured_component($custom_post->ID,$cols),$featured_style);
                    $return .='</div>';
                    $return .='</div>';
                break;
            case 'testimonial': 
                    $return .='<div class="block testimonials">';
                
                    $author=  getPostMeta($custom_post->ID,'vibe_testimonial_author_name'); 
                    $designation=getPostMeta($custom_post->ID,'vibe_testimonial_author_designation');
                    if(has_post_thumbnail($custom_post->ID)){
                        $image=get_the_post_thumbnail($custom_post->ID,'full'); 
                    }else{
                        $image= get_avatar( 'email@example.com', 96 );
                    } 
                    
                    $return .= '<div class="testimonial_item style2 clearfix">
                                    <div class="testimonial-content">    
                                        <p>'.custom_excerpt($n,$custom_post->ID).(($n < strlen($custom_post->post_content))?$read_more:'').'</p>
                                       <div class="author">
                                          '.$image.'  
                                          <h4>'.html_entity_decode($author).'</h4>
                                          <small>'.html_entity_decode($designation).'</small>
                                        </div>     
                                    </div>        
                                    
                                </div>';
                    $return .='</div>';
                break;
             case 'blogpost':
                    $return .='<div class="block blogpost">';
                    $return .= '<div class="blog-item">
                                '.apply_filters('vibe_thumb_date','<div class="blog-item-date">
                                    <span class="day">'.get_the_time('d').'</span>
                                    <p class="month">'.get_the_time('M').'\''.get_the_time('y').'</p>
                                </div>',$featured_style).'
                                '.apply_filters('vibe_thumb_heading','<h4><a href="'.get_permalink($custom_post->ID).'" title="'.$custom_post->post_title.'">'.$custom_post->post_title.'</a></h4>',$featured_style).'
                                <p>'.apply_filters('vibe_thumb_desc',custom_excerpt($n,$custom_post->ID),$featured_style).'</p>
                                </div>';
                    $return .='</div>';
                break; 
            case 'event_card':
                $return .= '<div class="event_card">';
                $icon_class=get_post_meta($custom_post->ID,'vibe_icon',true);
                $color=get_post_meta($custom_post->ID,'vibe_color',true);
                $start_date=get_post_meta($custom_post->ID,'vibe_start_date',true);
                $end_date=get_post_meta($custom_post->ID,'vibe_end_date',true);
                $start_time=get_post_meta($custom_post->ID,'vibe_start_time',true);
                $end_time=get_post_meta($custom_post->ID,'vibe_end_time',true);
                $show_location=get_post_meta($custom_post->ID,'vibe_show_location',true);
                $all_day=get_post_meta($custom_post->ID,'vibe_all_day',true);
                $location=vibe_sanitize(get_post_meta($custom_post->ID,'vibe_location',false));
                $repeatable = get_post_meta($custom_post->ID,'vibe_repeatable',true);
                $repeat_value = get_post_meta($custom_post->ID,'vibe_repeat_value',true);
                $repeat_unit = get_post_meta($custom_post->ID,'vibe_repeat_unit',true);
                $repeat_count = get_post_meta($custom_post->ID,'vibe_repeat_count',true);
                $return .= ' <span class="event_icon" style="color:'.$color.'"><i class="'.$icon_class.'"></i></span>
                        <h4 style="background:'.$color.'"><i class="'.$icon_class.'"></i> '.__('Event ','vibe-customtypes').'</label><span><a href="'.get_permalink($custom_post->ID).'">'.get_the_title($custom_post->ID).'</a></span></h4>
                        <ul>
                        ';
                        
                        if(isset($start_date) && $start_date !=''){
                           $return .= '<li><label><i class="icon-calendar"></i> '.__('Start Date ','vibe-customtypes').'</label><span>'.date('F j Y',strtotime($start_date)).'</span></li>';
                        } 
                        if(isset($end_date) && $end_date !=''){
                            $return .= '<li><label><i class="icon-calendar"></i> '.__('End Date ','vibe-customtypes').'</label><span>'.date('F j Y',strtotime($end_date)).'</span>';
                        }
                        if(isset($start_time) && $start_time !=''){
                             
                            $return .= '<li><label><i class="icon-clock"></i> '.__('Start Time ','vibe-customtypes').'</label><span>'.$start_time.'</span>';
                        } 
                        if(isset($end_time) && $end_time !=''){
                            $return .= '<li><label><i class="icon-clock"></i> '.__('End Time ','vibe-customtypes').'</label><span>'.$end_time.'</span>';
                        }
                        if(vibe_validate($all_day)){
                            $return .= '<li><label><i class="icon-circle-full"></i> '.__('All Day ','vibe-customtypes').'</label><span>'.__('Yes','vibe-customtypes').'</span>';
                        }
                        if(vibe_validate($repeatable)){
                            $return .= '<li><label><i class="icon-flash"></i> '.__('Frequency ','vibe-customtypes').'</label><span>'.__('Every ','vibe-customtypes').((isset($repeat_value) && $repeat_value > 1)?$repeat_value:'').' '.$repeat_unit.' '.__('for ','vibe-customtypes').$repeat_count.' '.$repeat_unit.'</span>';
                        }
                        if(vibe_validate($show_location)){
                            $return .= '<li><label><i class="icon-pin-alt"></i> '.__('Venue ','vibe-customtypes').'</label><span>'.(isset($location['staddress'])?$location['staddress']:'').(isset($location['city'])?', '.$location['city']:'').(isset($location['state'])?', '.$location['state']:'').(isset($location['country'])?', '.$location['country']:'').(isset($location['pincode'])?' - '.$location['pincode']:'').'</span>';
                        }
                        $return .= '</ul>
                        <a href="'.get_permalink($custom_post->ID).'" class="event_full_details tip" title="'.__('View full details','vibe-customtypes').'" style="background:'.$color.'"><i class="icon-plus-1"></i></a>
                    </div>';
                
                break;
                         
             default:
                   $return .='<div class="block">';
                    $return .='<div class="block_media">';
                    
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($custom_post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                    $featured= getPostMeta($custom_post->ID, 'vibe_select_featured');
                    if(isset($zoom) && $zoom && has_post_thumbnail($custom_post->ID) )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($custom_post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    
                    
                    $return .= featured_component($custom_post->ID,$cols);
                    
                    $category='';
                    if(get_post_type($custom_post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    $return .='</div>';
                    
                    $category='';
                    if(get_post_type($custom_post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    if(get_post_type($custom_post->ID) == 'portfolio'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                             $category .= '<div class="categories">';
                             if (!is_wp_error( get_the_term_list( $custom_post->ID, 'portfolio-type', ' ', ' ', '' ) ) ) {
                             $category .= get_the_term_list( $custom_post->ID, 'portfolio-type', ' ', ' ', '' );
                             }
                             $category .= '</div>';
                        }
                    }
                    
                    
                    $return .='<div class="block_content">';
                    $return .= apply_filters('vibe_thumb_heading','<h4 class="block_title"><a href="'.get_permalink($custom_post->ID).'" title="'.$custom_post->post_title.'">'.$custom_post->post_title.'</a></h4>',$featured_style);
                    $return .= apply_filters('vibe_thumb_date','<div class="date"><small>'. get_the_time('F d,Y').''.((strlen($category)>2)? ' / '.$category:'').' / '.get_comments_number( '0', '1', '%' ).' '.__('Comments','vibe-customtypes').'</small></div>',$featured_style);
                    $return .= apply_filters('vibe_thumb_desc','<p class="block_desc">'.custom_excerpt($n,$custom_post->ID).'</p>',$featured_style);
                    $return .='</div>';
                    $return .='</div>';

                break;
            }
            if($cache_duration)
            wp_cache_set( $course_key,$result,'featured_block',$cache_duration);
        }//end If
        return apply_filters('vibe_featured_thumbnail_style',$return,$custom_post,$featured_style);
}


//*=== Featured Component ===*//

function featured_component($custom_post_id,$cols='',$style=''){

    $default_image = vibe_get_option('default_course_avatar');
    if(!in_array($cols,array('big','small','medium','mini','full'))){
        switch($cols){
          case '2':{ $cols = 'big';
          break;}
          case '3':{ $cols = 'medium';
          break;}
          case '4':{ $cols = 'medium';
          break;}
          case '5':{ $cols = 'small';
          break;}
          case '6':{ $cols = 'small';
          break;}  
          default:{ $cols = 'full';
          break;}
        }
    }
    $custom_post_thumbnail='';
    
    if(has_post_thumbnail($custom_post_id)){
        $custom_post_thumbnail=  '<a href="'.get_permalink().'">'.get_the_post_thumbnail($custom_post_id,$cols).'</a>';
    }else if(isset($default_image) && $default_image)
            $custom_post_thumbnail='<img src="'.$default_image.'" />';
                    
    return $custom_post_thumbnail;   
}        


?>