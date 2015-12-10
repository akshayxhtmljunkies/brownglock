<?php

if(!class_exists('WPLMS_Instructor_Commission')){

	class WPLMS_Instructor_Commission{

	function get_order_report_data($start_date,$end_date) {
		global $wpdb;
		$order_item_meta_table=$wpdb->prefix.'woocommerce_order_itemmeta';
		$order_items_table=$wpdb->prefix.'woocommerce_order_items';
		$inst_commissions = $wpdb->get_results("SELECT order_items.order_id,order_meta.meta_key as instructor,order_meta.meta_value as commission
			FROM {$wpdb->posts} as posts
			LEFT JOIN $order_items_table as order_items ON posts.ID = order_items.order_id
			LEFT JOIN $order_item_meta_table as order_meta ON order_items.order_item_id = order_meta.order_item_id
			WHERE posts.post_type='shop_order'
			AND posts.post_status='wc-completed'
			AND posts.post_date BETWEEN '$start_date' AND '$end_date'
			AND order_meta.meta_key LIKE 'commission%'");
		
		if(is_array($inst_commissions)){
		foreach($inst_commissions as $inst_commission){
			$order_ids[]=$inst_commission->order_id;
			$inst=explode('commission',$inst_commission->instructor);
			if(is_numeric($inst[1])){
				$instructor_commissions[$inst[1]] += $inst_commission->commission;
			}
		}
		if(is_array($order_ids))
			$order_id_string = implode(',',$order_ids);
		}
		$query="SELECT order_items.order_item_id,order_meta.meta_value as total_sales
			FROM {$wpdb->posts} as posts
			LEFT JOIN $order_items_table as order_items ON posts.ID = order_items.order_id
			LEFT JOIN $order_item_meta_table as order_meta ON order_items.order_item_id = order_meta.order_item_id
			WHERE posts.post_type='shop_order'
			AND posts.post_status='wc-completed'
			AND posts.post_date BETWEEN '$start_date' AND '$end_date'
			AND order_meta.meta_key = '_line_total'";
		if(isset($order_id_string))	
			$query .="AND posts.ID NOT IN ($order_id_string)";
		$inst_commissions = $wpdb->get_results($query);

		$commissions = get_option('instructor_commissions');
		foreach($inst_commissions as $inst_commission){ 
			$oid=$inst_commission->order_item_id;
			$pid=woocommerce_get_order_item_meta($oid,'_product_id',true);
			$courses = vibe_sanitize(get_post_meta($pid,'vibe_courses',false));

			if(isset($courses) && is_array($courses) && count($courses)){
				$n=count($courses);
				foreach($courses as $course){
					$instructors = apply_filters('wplms_course_instructors',get_post_field('post_author', $course),$course);

					if(is_numeric($instructors)){
						if(!isset($commission[$course][$instructors]))
							$commission[$course][$instructors]=70;
						
						echo $commission[$course][$instructors]*$inst_commission->total_sales;
						$instructor_commissions[$instructors]+=$commission[$course][$instructors]*$inst_commission->total_sales;

					}else if(is_Array($instructors)){
						$k=count($instructors);
						foreach($instructors as $instructor){
							if(!isset($commission[$course][$instructor]))
								$commission[$course][$instructor]=floor(70/$k);
						
							$instructor_commissions[$instructor]+=$commission[$course][$instructor]*$inst_commission->total_sales;
						}
					}
				}
			}
		}

		return $instructor_commissions;
	}

	}
}

if(!class_exists('WPLMS_Commissions')){

	class WPLMS_Commissions extends WPLMS_Instructor_Commission{


		public function instructor_data($start_date,$end_date){// End function
			$instructor_data=$this->get_order_report_data($start_date,$end_date);		
			return $instructor_data;
		}

	}
}
?>