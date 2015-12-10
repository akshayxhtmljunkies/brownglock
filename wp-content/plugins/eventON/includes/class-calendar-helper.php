<?php
/**
 * helper fnctions for calendar
 *
 * @class 		evo_cal_help
 * @version		2.2.28
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */

class evo_cal_help {
	
	function get_eventinclasses($atts){
		 
		$classnames[] = (!empty($atts['img_thumb_src']) && !empty($atts['show_et_ft_img']) && $atts['show_et_ft_img']=='yes')? 'hasFtIMG':'';

		$classnames[] = ($atts['event_type']!='nr')? 'event_repeat':null;		
		$classnames[] = $atts['event_description_trigger'];
		$classnames[] = (!empty($atts['existing_classes']['__featured']) && $atts['existing_classes']['__featured'])? 'featured_event':null;
		$classnames[] = (!empty($atts['existing_classes']['_cancel']) && $atts['existing_classes']['_cancel'])? 'cancel_event':null;

		$classnames = array_merge($classnames, $atts['existing_classes']);
		$classnames = array_filter($classnames);

		return implode(' ',  $classnames);
	}

	function implode($array=''){
		if(empty($array))
			return '';

		return implode(' ', $array);
	}

	function get_attrs($array){
		if(empty($array)) return;

		$output = '';
		$array = array_filter($array);

		foreach($array as $key=>$value){
			if($key=='style' && !empty($value)){
				$output .= 'style="'. implode("", $value).'" ';
			}elseif($key=='rest'){
				$output .= implode(" ", $value);
			}else{
				$output .= $key.'="'.$value.'" ';
			}
		}

		return $output;
	}

	function evo_meta($field, $array, $type=''){
		switch($type){
			case 'tf':
				return (!empty($array[$field]) && $array[$field][0]=='yes')? true: false;
			break;
			case 'yn':
				return (!empty($array[$field]) && $array[$field][0]=='yes')? 'yes': 'no';
			break;
			case 'null':
				return (!empty($array[$field]) )? $array[$field][0]: null;
			break;
			default;
				return (!empty($array[$field]))? true: false;
			break;
		}		
	}

	// sort eventcard fields 
	function eventcard_sort($array, $opt){

		$evoCard_order = $opt['evoCard_order'];
		
		$new_array = array();
		
		// create an array
		$correct_order = (!empty($evoCard_order))? 
			explode(',',$evoCard_order): null;
		
		if(!empty($correct_order)){
			$evoCard_hide = (!empty($opt['evoCard_hide']))? 
				explode(',',$opt['evoCard_hide']): null;

			// each saved order item
			foreach($correct_order as $box){
				if(array_key_exists($box, $array) 
					&& (!empty($evoCard_hide) && !in_array($box, $evoCard_hide) || empty($evoCard_hide)) 
				){
					$new_array[$box]=$array[$box];
				}
			}
		}else{
			$new_array = $array;
		}	
		return $new_array;
	}

}