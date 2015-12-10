<?php
/**
 * EventON Ajax Handlers
 *
 * Handles AJAX requests via wp_ajax hook (both admin and front-end events)
 *
 * @author 		AJDE
 * @category 	Core
 * @package 	EventON/Functions/AJAX
 * @version     2.3.9
 */

class evo_ajax{
	/**
	 * Hook into ajax events
	 */
	public function __construct(){
		$ajax_events = array(
			'ics_download'=>'eventon_ics_download',
			'deactivate_lic'=>'eventon_deactivate_evo',
			'the_ajax_hook'=>'evcal_ajax_callback',
			'evo_dynamic_css'=>'eventon_dymanic_css',
			'the_post_ajax_hook_3'=>'evcal_ajax_callback_3',
			'the_post_ajax_hook_2'=>'evcal_ajax_callback_2',
			'validate_license'=>'validate_license',
			'verify_key'=>'verify_key',
			'remote_validity'=>'remote_validity',
			'deactivate_addon'=>'deactivate_addon',
			'remote_test'=>'remote_test',
			'export_events'=>'export_events',
		);
		foreach ( $ajax_events as $ajax_event => $class ) {

			$prepend = ( in_array($ajax_event, array('the_ajax_hook','evo_dynamic_css','the_post_ajax_hook_3','the_post_ajax_hook_2')) )? '': 'eventon_';

			add_action( 'wp_ajax_'. $prepend . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_'. $prepend . $ajax_event, array( $this, $class ) );
		}

		add_action('wp_ajax_eventon-feature-event', array($this, 'eventon_feature_event'));
	}

	// OUTPUT: json headers
		private function json_headers() {
			header( 'Content-Type: application/json; charset=utf-8' );
		}

	// for event post repeat intervals 
	// @return converted unix time stamp on UTC timezone
		public function repeat_interval(){
			$date_format = $_POST['date_format'];
		}

	// Primary function to load event data 
		function evcal_ajax_callback(){
			global $eventon;
			$shortcode_args='';
			$status = 'GOOD';

			$evodata = !empty($_POST['evodata'])? $_POST['evodata']: false;
			
			// Initial values
				$current_month = (int)(!empty($evodata['cmonth'])? ($evodata['cmonth']): $_POST['current_month']);
				$current_year = (int)(!empty($evodata['cyear'])? $evodata['cyear']: $_POST['current_year']);	

				$send_unix = (isset($evodata['send_unix']))? $evodata['send_unix']:null;
				$direction = $_POST['direction'];
				$sort_by = (!empty($_POST['sort_by']))? $_POST['sort_by']: 
					( !empty($evodata['sort_by'])? $evodata['sort_by'] :'sort_date');
			
			// generate new UNIX range dates for calendar
				if($send_unix=='1'){
					$focus_start_date_range = (isset($evodata['range_start']))? (int)($evodata['range_start']):null;
					$focus_end_date_range = (isset($evodata['range_end']))? (int)($evodata['range_end']):null;	
					
					$focused_month_num = $current_month;
					$focused_year = $current_year;

				}else{
					if($direction=='none'){
						$focused_month_num = $current_month;
						$focused_year = $current_year;
					}else{
						$focused_month_num = ($direction=='next')?
							(($current_month==12)? 1:$current_month+1):
							(($current_month==1)? 12:$current_month-1);
						
						$focused_year = ($direction=='next')? 
							(($current_month==12)? $current_year+1:$current_year):
							(($current_month==1)? $current_year-1:$current_year);
					}	
					
						
					$focus_start_date_range = mktime( 0,0,0,$focused_month_num,1,$focused_year );
					$time_string = $focused_year.'-'.$focused_month_num.'-1';		
					$focus_end_date_range = mktime(23,59,59,($focused_month_num),(date('t',(strtotime($time_string) ))), ($focused_year));
				}
				
			// base calendar arguments at this stage
				$eve_args = array(
					'focus_start_date_range'=>$focus_start_date_range,
					'focus_end_date_range'=>$focus_end_date_range,
					'sort_by'=>$sort_by,		
					'event_count'=>(!empty($_POST['event_count']))? $_POST['event_count']: 
						( !empty($evodata['ev_cnt'])? $evodata['ev_cnt']: '' ),
					'filters'=>((isset($_POST['filters']))? $_POST['filters']:null)
				);
				//print_r($eve_args);
			

			// shortcode arguments USED to build calendar
				$shortcode_args_arr = $_POST['shortcode'];
				
				if(!empty($shortcode_args_arr) && count($shortcode_args_arr)>0){
					foreach($shortcode_args_arr as $f=>$v){
						$shortcode_args[$f]=$v;
					}
					$eve_args = array_merge($eve_args, $shortcode_args);
					$lang = $shortcode_args_arr['lang'];
				}else{
					$lang ='';
				}
				
					
			// GET calendar header month year values
				$calendar_month_title = get_eventon_cal_title_month($focused_month_num, $focused_year, $lang);
					
			// AJAX Addon hook
				$eve_args = apply_filters('eventon_ajax_arguments',$eve_args, $_POST);
							
			$content_li = $eventon->evo_generator->eventon_generate_events( $eve_args);

			
			// RETURN VALUES
			// Array of content for the calendar's AJAX call returned in JSON format
			$return_content = array(
				'status'=>(!$evodata? 'Need updated':$status),	
				'content'=>$content_li,
				'cal_month_title'=>$calendar_month_title,
				'month'=>$focused_month_num,
				'year'=>$focused_year,
				'focus_start_date_range'=>$focus_start_date_range,
				'focus_end_date_range'=>$focus_end_date_range,		
			);			
			
			
			echo json_encode($return_content);
			exit;
		}

	// ICS file generation for add to calendar buttons
		function eventon_ics_download(){
			$event_id = (int)($_GET['event_id']);
			$sunix = (int)($_GET['sunix']);
			$eunix = (int)($_GET['eunix']);

			//error_reporting(E_ALL);
			//ini_set('display_errors', '1');
			
			//$the_event = get_post($event_id);
			$ev_vals = get_post_custom($event_id);
			
			$event_start_unix = $sunix;
			$event_end_unix = (!empty($eunix))? $eunix : $sunix;
			
			
			$name = $summary = get_the_title($event_id);

			// summary for ICS file
			$event = get_post($event_id);
			$content = (!empty($event->post_content))? $event->post_content:'';
			if(!empty($content)){
				$content = strip_tags($content);
				$content = str_replace(']]>', ']]&gt;', $content);
				$summary = wp_trim_words($content, 50, '[..]');
				//$summary = substr($content, 0, 500).' [..]';
			}			
			
			
			$location = (!empty($ev_vals['evcal_location']))? $ev_vals['evcal_location'][0] : ''; 
			$start = evo_get_adjusted_utc($event_start_unix);
			$end = evo_get_adjusted_utc($event_end_unix);
			$uid = uniqid();
			//$description = $the_event->post_content;
			
			ob_clean();
			
			//$slug = strtolower(str_replace(array(' ', "'", '.'), array('_', '', ''), $name));
			$slug = $event->post_name;
			
			
			header("Content-Type: text/Calendar; charset=utf-8");
			header("Content-Disposition: inline; filename={$slug}.ics");
			echo "BEGIN:VCALENDAR\n";
			echo "VERSION:2.0\n";
			echo "PRODID:-//eventon.com NONSGML v1.0//EN\n";
			//echo "METHOD:REQUEST\n"; // requied by Outlook
			echo "BEGIN:VEVENT\n";
			echo "UID:{$uid}\n"; // required by Outlok
			echo "DTSTAMP:".date_i18n('Ymd').'T'.date_i18n('His')."\n"; // required by Outlook
			echo "DTSTART:{$start}\n"; 
			echo "DTEND:{$end}\n";
			echo "LOCATION:{$location}\n";
			echo "SUMMARY:{$name}\n";
			echo "DESCRIPTION: {$summary}\n";
			echo "END:VEVENT\n";
			echo "END:VCALENDAR";
			exit;
		}

	// export events as CSV
	// @version 2.2.30
		function export_events(){
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=Eventon_events_".date("d-m-y").".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			$evo_opt = get_option('evcal_options_evcal_1');
			$event_type_count = evo_get_ett_count($evo_opt);
			$cmd_count = evo_calculate_cmd_count($evo_opt);

			$fields = array(
				'publish_status',				
				'evcal_event_color'=>'color',
				'event_name',				
				'event_description','event_start_date','event_start_time','event_end_date','event_end_time',

				'evcal_allday'=>'all_day',
				'evo_hide_endtime'=>'hide_end_time',
				'evcal_gmap_gen'=>'event_gmap',
				'_featured'=>'featured',

				'evcal_location_name'=>'location_name',
				'evcal_location'=>'event_location',				
				'evcal_organizer'=>'event_organizer',
				'evcal_subtitle'=>'evcal_subtitle',
				'image_url'
			);
			
			foreach($fields as $var=>$val){	echo $val.',';	}

			// event types
				for($y=1; $y<=$event_type_count;  $y++){
					$_ett_name = ($y==1)? 'event_type': 'event_type_'.$y;
					echo $_ett_name.',';
				}
			// for event custom meta data
				for($z=1; $z<=$cmd_count;  $z++){
					$_cmd_name = 'cmd_'.$z;
					echo $_cmd_name.",";
				}

			echo "\n";
 
			$events = new WP_Query(array(
				'posts_per_page'=>-1,
				'post_type' => 'ajde_events',
				'post_status'=>'any'			
			));

			if($events->have_posts()):
				date_default_timezone_set('UTC');

				while($events->have_posts()): $events->the_post();
					$__id = get_the_ID();
					$pmv = get_post_meta($__id);

					echo get_post_status($__id).",";
					//echo (!empty($pmv['_featured'])?$pmv['_featured'][0]:'no').",";
					echo (!empty($pmv['evcal_event_color'])? $pmv['evcal_event_color'][0]:'').",";
					echo '"'.get_the_title().'",';
					$event_content = get_the_content();
					echo '"'.str_replace('"', "'", $event_content).'",';

					// start time
						$start = (!empty($pmv['evcal_srow'])?$pmv['evcal_srow'][0]:'');
						if(!empty($start)){
							echo date('n/j/Y,g:i:A', $start).',';
						}else{ echo "'','',";	}

					// end time
						$end = (!empty($pmv['evcal_erow'])?$pmv['evcal_erow'][0]:'');
						if(!empty($end)){
							echo date('n/j/Y,g:i:A',$end).',';
						}else{ echo "'','',";	}

					foreach($fields as $var=>$val){
						
						// yes no values
						if(in_array($val, array('featured','all_day','hide_end_time','event_gmap'))){
							echo ( (!empty($pmv[$var]) && $pmv[$var][0]=='yes') ? 'yesf': 'no').',';
						}

						// skip fields
						if(in_array($val, array('featured','all_day','hide_end_time','event_gmap','color','publish_status','event_name','event_description','event_start_date','event_start_time','event_end_date','event_end_time'))) continue;

						// image
						if($val =='image_url'){
							$img_id =get_post_thumbnail_id($__id);
							if($img_id!=''){
								$img_src = wp_get_attachment_image_src($img_id,'full');
								echo $img_src[0].",";
							}else{ echo ",";}
						}else{
							echo (!empty($pmv[$var])? 	'"'.$pmv[$var][0].'"':'').",";
						}
					}

					// event types
						for($y=1; $y<=$event_type_count;  $y++){
							$_ett_name = ($y==1)? 'event_type': 'event_type_'.$y;
							$terms = get_the_terms( $__id, $_ett_name );

							if ( $terms && ! is_wp_error( $terms ) ){
								echo '"';
								foreach ( $terms as $term ) {
									echo $term->term_id.',';
								}
								echo '",';
							}else{ echo ",";}
						}
					// for event custom meta data
						for($z=1; $z<=$cmd_count;  $z++){
							$cmd_name = '_evcal_ec_f'.$z.'a1_cus';
							echo (!empty($pmv[$cmd_name])? 
								'"'.str_replace('"', "'",$pmv[$cmd_name][0]).'"'
								:'');
							echo ",";
						}

					echo "\n";

				endwhile;

			endif;

			wp_reset_postdata();
		}

	// Activate EventON Product
		// validate the license key	
			function validate_license(){
				global $eventon;
				$key = $_POST['key'];
				$verifyformat = $eventon->evo_updater->product->purchase_key_format($key);

				$return_content = array(
					'status'=>($verifyformat?'good':'bad'),
					'error_msg'=>(!$verifyformat? $eventon->evo_updater->error_code_('10'):''),
				);
				echo json_encode($return_content);		
				exit;
			}
		// verify license key
			function verify_key(){
				global $eventon;

				// initial values
					$debug = $content = $addition_msg ='';
					$status = 'success';
					$error_code = '00';
					$error_msg='';

				// padding data
					$__passing_instance = (!empty($_POST['instance'])?(int)$_POST['instance']:'1');
					$__data = array(
						'slug'=> addslashes ($_POST['slug']),
						'key'=> addslashes( str_replace(' ','',$_POST['key']) ),
						'email'=>(!empty($_POST['email'])? $_POST['email']: null),
						'product_id'=>(!empty($_POST['product_id'])?$_POST['product_id']:''),
						'instance'=>$__passing_instance,
					);

				$status_ = $eventon->evo_updater->verify_product_license($__data);

				// for eventon
				if($_POST['slug']=='eventon'){
					$__save_new_lic = $eventon->evo_updater->product->save_license(
						$__data['slug'],
						$__data['key']
					);
					$content = $status; // url to envato json API
				}else{
					//content for success activation
						$content ="License Status: <strong>Activated</strong>";

					// save verified eventon addon product info
						$__save_new_lic = $eventon->evo_updater->product->save_license(
							$__data['slug'],
							$__data['key'],
							$__data['email'],
							$__data['product_id'],
							'valid','', (!empty($status_->instance)? $status_->instance:'1')
						);

					// CHECK remote validation results
					if($status_){
						// if activated value is true
						if($status_->activated){							
							$status = 'success';

							// append additional mesages passed from remote server
							$addition_msg = !empty($status_->message)? $status_->message:null;

						}else{ // return activated to be not true
							// if there were errors returned from eventon server
							if(!empty($status_->code) && $status_->code=='103' && $__passing_instance=='1'){
								$status = 'success';
								$error_code = '12';
							}elseif(!empty($status_->code) && $status_->code=='103'){
								$status = 'bad';
								$error_code = '103'; //exceeded max activations
							}else{
								$status = 'success';
								$error_code = '13'; //general validation failed
							}				
						}
					}else{ // couldnt connect to myeventon.com to check
						$status = 'good';
						$error_code = '13';							
					}
				}


				$return_content = array(
					'status'=>$status,
					'error_msg'=>$eventon->evo_updater->error_code_($error_code),
					'addition_msg'=>$addition_msg,
					'this_content'=>$content,
					'extra'=>$status_,
				);
				echo json_encode($return_content);		
				exit;				
			}

			function check_addon_verification(){}
			
		// update remote validity status of a license
			function remote_validity(){
				global $eventon;

				$status = $eventon->evo_updater->product->update_field($_POST['slug'], 'remote_validity', $_POST['validity']);
				$return_content = array(
					'status'=>($status?'good':'bad'),					
				);
				echo json_encode($return_content);		
				exit;
			}
		// deactivate addon 
			function deactivate_addon(){
				global $eventon;

				// initial values
					$debug = $content ='';
					$status = 'success';
					$error_code = '00';
					$error_msg='';

				// deactivate the license locally
				$dea_local = $eventon->evo_updater->product->deactivate($_POST['slug']);
				
				// padding data
					$__data = array(
						'slug'=> addslashes ($_POST['slug']),
						'key'=> addslashes( str_replace(' ','',$_POST['key']) ),
						'email'=>(!empty($_POST['email'])? $_POST['email']: null),
						'product_id'=>(!empty($_POST['product_id'])? $_POST['product_id']: null),
					);

				// deactivate addon from remote server
					$url='http://www.myeventon.com/woocommerce/?wc-api=software-api&request=deactivation&email='.$__data['email'].'&licence_key='.$__data['key'].'&instance=0&product_id='.$__data['product_id'];

					$request = wp_remote_get($url);

					if (!is_wp_error($request) && $request['response']['code']===200) {

						$status_ = (!empty($request['body']))? json_decode($request['body']): $request; 
					}
				
				$return_content = array(
					'status'=>$status,					
					'extra'=>$status_,
					'error_msg'=>$eventon->evo_updater->error_code_($error_code),
					'content'=>"License Status: <strong>Deactivated</strong>"
				);
				echo json_encode($return_content);		
				exit;
			}

	// deactivate eventon license
		function eventon_deactivate_evo(){
			global $eventon;
			$error_msg ='';

			$status = $eventon->evo_updater->product->deactivate('eventon');

			if($status)	$status = 'success';
			else	$error_msg = $eventon->evo_updater->error_code_();

			$return_content = array(
				'status'=>$status,		
				'error_msg'=>$error_msg
			);
			echo json_encode($return_content);		
			exit;
		}

	/** Feature an event from admin */
		function eventon_feature_event() {

			if ( ! is_admin() ) die;

			if ( ! current_user_can('edit_eventons') ) wp_die( __( 'You do not have sufficient permissions to access this page.', 'eventon' ) );

			if ( ! check_admin_referer('eventon-feature-event')) wp_die( __( 'You have taken too long. Please go back and retry.', 'eventon' ) );

			$post_id = isset( $_GET['eventID'] ) && (int) $_GET['eventID'] ? (int) $_GET['eventID'] : '';

			if (!$post_id) die;

			$post = get_post($post_id);

			if ( ! $post || $post->post_type !== 'ajde_events' ) die;

			$featured = get_post_meta( $post->ID, '_featured', true );

			if ( $featured == 'yes' )
				update_post_meta($post->ID, '_featured', 'no');
			else
				update_post_meta($post->ID, '_featured', 'yes');

			wp_safe_redirect( remove_query_arg( array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer() ) );
		}

	/* dynamic styles */
		function eventon_dymanic_css(){
			//global $foodpress_menus;
			require('admin/inline-styles.php');
			exit;
		}

	// EVENTBRITE
		function evcal_ajax_callback_3(){
			// pre
			$code = $status = $message = '';
			$evcal_opt1= get_option('evcal_options_evcal_1');
			
			$eb_event_id = $_POST['event_id'];
			$eb_api = $evcal_opt1['evcal_evb_api'];
			
			$xml =simplexml_load_file('http://www.eventbrite.com/xml/event_get?app_key='.$eb_api.'&id='.$eb_event_id );					

			if($xml->getName()!='error'):		
				$status=1;
				
				if($xml->status =='Completed'){
					$message='past';
				}
				
				// pre
				$venue = $xml->venue;
				$location = ((!empty($venue->address) )? $venue->address.', ':null ).
					$venue->city.' '.$venue->region.' '.
					$venue->postal_code;
					
				
				$code.= "<div var='title' class='evcal_data_row '>
					<p>Event Name</p>
					<p class='value'>".$xml->title."</p>
					<em class='clear'></em>
				</div>";
				
				$code.= "<div var='evcal_location' class='evcal_data_row '>
					<p>Location</p>
					<p class='value'>".$location."</p>
					<em class='clear'></em>
				</div>";
				$code.= "<div var='capacity' class='evcal_data_row '>
					<p>Event Capacity</p>
					<p class='value'>".$xml->capacity."</p>
					<em class='clear'></em>
				</div>";
				$code.= "<div var='price' class='evcal_data_row '>
					<p>Ticket Price</p>
					<p class='value'>".$xml->tickets->ticket->currency.' '.$xml->tickets->ticket->price."</p>
					<em class='clear'></em>
				</div>";		
				$code.= "<div var='url' class='evcal_data_row '>
					<p>Buy Now Ticket URL</p>
					<p class='value'>".$xml->url."</p>								
				</div><p class='clear'></p>	";
				
			else:
				$status =0;
			endif;	

			$return_content = array(
				'status'=>$status,
				'message'=>$message,
				'code'=>$code	
			);			
			echo json_encode($return_content);		
			exit;
		}

	// MEETUP
		function evcal_ajax_callback_2(){
			
			// pre
			$code = $status = '';
			$evcal_opt1= get_option('evcal_options_evcal_1');
			$wp_time_format = get_option('time_format');
			
			$mu_event_id = $_POST['event_id'];
			$mu_api = $evcal_opt1['evcal_api_mu_key'];
			
			$xml =simplexml_load_file('http://api.meetup.com/2/event/'.
				$mu_event_id.'.xml?key='.$mu_api.'&sign=true');					

			if($xml->getName()!='error'):
				$status=1;
				// pre
				$venue = $xml->venue;
				$location = $venue->address_1.', '.
					$venue->city.' '.$venue->state.' '.
					$venue->zip;
					
				$utc_offset = substr($xml->utc_offset, 0, -3);
				$time_raw = substr($xml->time, 0, -3);
				
				$time_s = ((int)($time_raw)) + ((int)($utc_offset));
				
				
				$time_formated = date("l F j, Y",$time_s);
				$time_formated_2 = date("n/j/Y",$time_s);
				$s_hour = date("g",$time_s);
				$s_min = date("i",$time_s);
				$s_ampm = date("A",$time_s);
				//print_r( $location);
				
				
				$code.= "<div var='title' class='evcal_data_row '>
					<p>Event Name</p>
					<p class='value'>".$xml->name."</p>
					<em class='clear'></em>
				</div>";
				$code.= "<div var='evcal_location' class='evcal_data_row '>
					<p>Location</p>
					<p class='value'>".$location."</p>
					<em class='clear'></em>
				</div>";
				
				$code.= "<div var='time' class='evcal_data_row '>
					<p>Time</p>
					<p class='value' ftime='".$time_formated_2."' hr='".$s_hour."' min='".$s_min."' ampm='".$s_ampm."'>".$time_formated."</p>
				</div>";
											
				
				$code.= "<div var='url' class='evcal_data_row '>
					<p>Event URL</p>
					<p class='value'>".$xml->event_url."</p>								
				</div><p class='clear'></p>	";
				
			else:
				$status =0;
			endif;	

			$return_content = array(
				'status'=>$status,
				'code'=>$code
			);			
			echo json_encode($return_content);		
			exit;
							
		}	

	// remote text
		public function remote_test(){
			global $wp_version, $eventon;
		
			$args = array('slug' => $_POST['slug']);
			$request_string = array(
				'body' => array(
					'action' => 'evo_latest_version', 
					'request' => serialize($args),
					'api-key' => md5(get_bloginfo('url'))
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);				
		
	        $request = wp_remote_post($eventon->evo_updater->api_url, $request_string);
	        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
	            $version = $request['body'];
	        }else{
	        	// get locally saved remote version
    			$version = $eventon->evo_updater->product->get_remote_version();
	        }

	        $return_content = array(
				'status'=>'good',		
				'api_url'=>$eventon->evo_updater->api_url,
				'version'=>$request,
			);
			echo json_encode($return_content);		
			exit;
		}


}
new evo_ajax();