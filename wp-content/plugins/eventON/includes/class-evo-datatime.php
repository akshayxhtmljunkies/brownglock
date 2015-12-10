<?php
/**
 * Eventon date time class.
 *
 * @class 		EVO_generator
 * @version		2.3.8
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */

class evo_datetime{		
	/**	Construction function	 */
		public function __construct(){}

	/*
		input: event post meta, repeat interval, start or end time(var)
		ouput: interval corrected time
	*/
	public function get_int_correct_event_time($post_meta, $repeat_interval, $time='start'){
		if(!empty($post_meta['repeat_intervals']) && $repeat_interval>0){
			$intervals = unserialize($post_meta['repeat_intervals'][0]);
			return ($time=='start')? $intervals[$repeat_interval][0]:$intervals[$repeat_interval][1];
		}else{
			return ($time=='start')? $post_meta['evcal_srow'][0]:$post_meta['evcal_erow'][0];
		}
	}

	/*
	 * Return: array(start, end)
	 * Returns WP proper formatted corrected event time based on repeat interval provided
	 */
	public function get_correct_formatted_event_repeat_time($post_meta, $repeat_interval='', $format=''){
		$format = (!empty($format)? $format: get_option('date_format'));
		if(!empty($repeat_interval) && !empty($post_meta['repeat_intervals']) && $repeat_interval!='0'){
			$intervals = unserialize($post_meta['repeat_intervals'][0]);

			return array(
				'start'=> date($format.' h:i:a',$intervals[$repeat_interval][0]),
				'end'=> date($format.' h:i:a',$intervals[$repeat_interval][1]),
			);

		}else{// no repeat interval values saved
			$start = !empty($post_meta['evcal_srow'])? date($format.' h:i:a', $post_meta['evcal_srow'][0]) :0;
			return array(
				'start'=> $start,
				'end'=> ( !empty($post_meta['evcal_erow'])? date($format.' h:i:a',$post_meta['evcal_erow'][0]): $start)
			);
		}
	}
	public function get_correct_event_repeat_time($post_meta, $repeat_interval=''){
		if(!empty($repeat_interval) && !empty($post_meta['repeat_intervals']) && $repeat_interval!='0'){
			$intervals = unserialize($post_meta['repeat_intervals'][0]);

			return array(
				'start'=> $intervals[$repeat_interval][0],
				'end'=> $intervals[$repeat_interval][1],
			);

		}else{// no repeat interval values saved
			$start = !empty($post_meta['evcal_srow'])? $post_meta['evcal_srow'][0] :0;
			return array(
				'start'=> $start,
				'end'=> ( !empty($post_meta['evcal_erow'])? $post_meta['evcal_erow'][0]: $start)
			);
		}
	}

}