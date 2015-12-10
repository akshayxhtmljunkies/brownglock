<?php
/**
 * Calendar body parts class
 *
 * @class  		evo_cal_body
 * @version		2.2.22
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */
class evo_cal_body{
	private $cal;
	/**
	 * construct the calendar body 
	 */
	public function __construct(){
		global $eventon;
		$this->cal = $eventon->evo_generator;
	}

	/**
	 * Above the mail calendar header HTML content
	 * @param  array $args 
	 * @return string       HTML
	 */
		public function cal_above_header($args){
			//print_r($args);

			extract($args);

			// jump months section
			$jumper_content ='';
			if($jumper=='yes'){
				$focused_year = (int)$focused_year;

				$jumper_content.= "<div class='evo_j_container' style='display:none' data-m='{$focused_month_num}' data-y='{$focused_year}'>
						<div class='evo_j_months evo_j_dates' data-val='m'>
							<p class='legend'>".eventon_get_custom_language($this->cal->evopt2, 'evcal_lang_jumpmonthsM','Month').": ";

					// months list
					$lang = (!empty($args['lang']))? $args['lang']: 'L1';
					$__months = eventon_get_oneL_months($this->cal->evopt2[$lang]);	
								
					$count = 1;
					foreach($__months as $m){
						$_current = ($focused_month_num == $count)? 'class="current set"':null;
						$jumper_content.= "<a data-val='{$count}' {$_current} title='". eventon_return_timely_names_('month_num_to_name',$count,'full',$lang)."' >{$m}</a>";
						$count ++;
					}

					// if jumper offset is set
						$__a='';
						$start_year = $focused_year-2+$jumper_offset;
						$number_of_years = apply_filters('eventon_jumper_years_count', 6);

						for($x=1; $x< $number_of_years; $x++){
							$__a .= '<a'. ( $start_year == $focused_year?" class='current set'":null ).' data-val="'.$start_year.'">'.$start_year.'</a>';
							$start_year++;
						}


						$jumper_content.= "</p><div class='clear'></div></div>
						
						<div class='evo_j_years evo_j_dates' data-val='y'>
							<p class='legend'>".eventon_get_custom_language($this->cal->evopt2, 'evcal_lang_jumpmonthsY','Year').": ".$__a."</p><div class='clear'></div>
						</div>
					</div>";
			}// end jump months

			// go to today or current month
				$gototoday_content = '';
				$gototoday_content .= "";

			// above calendar buttons
				$above_head = apply_filters('evo_cal_above_header_btn', 
					array(
						'evo-jumper-btn'=>eventon_get_custom_language($this->cal->evopt2, 'evcal_lang_jumpmonths','Jump Months'),
						'evo-gototoday-btn'=>eventon_get_custom_language($this->cal->evopt2, 'evcal_lang_gototoday','Go to Today'),
					), $args
				);

				// update array based on whether jumper is active or not
					if($jumper!='yes'){
						unset($above_head['evo-jumper-btn']);
					}

				$above_heade_content = apply_filters('evo_cal_above_header_content', 
					array(
						'evo-jumper-btn'=>$jumper_content,
						'evo-gototoday-btn'=>$gototoday_content,
					), $args
				);

				ob_start();
				if(count($above_head)>0){
					echo "<div class='evo_cal_above'>";
						foreach($above_head as $ff=>$v){
							if($ff=='evo-gototoday-btn'){
								echo "<span class='".$ff."' style='display:none' data-mo='{$focused_month_num}' data-yr='{$focused_year}' >".$v."</span>";
							}else{
								echo "<span class='".$ff."'>".$v."</span>";
							}							
						}
					echo "</div>";

					// content for evo_cal_above
					echo "<div class='evo_cal_above_content'>";
					foreach($above_heade_content as $cc){
						echo $cc;
					}
					echo "</div>";
				}

			return ob_get_clean();
		}

	/**
	 * get single calendar month body content
	 * @param  [type] $get_new_monthyear      [description]
	 * @param  string $focus_start_date_range [description]
	 * @param  string $focus_end_date_range   [description]
	 * @return HTML    
	 */
		public function get_calendar_month_body( $get_new_monthyear, $focus_start_date_range='', $focus_end_date_range=''){
				
			// CHECK if start and end day ranges are provided for this function
			$defined_date_ranges = ( empty($focus_start_date_range) && empty($focus_end_date_range) )?false: true;
			
			$args = $this->cal->shortcode_args;
			extract($args);

			// update the languages array
			$this->cal->reused();
			
			//print_r($args);
			
			// check if date ranges present
			if( !$defined_date_ranges){	
				
				date_default_timezone_set('UTC');
				
				// default start end date range -- for month view
				$get_new_monthyear = $get_new_monthyear;
				
				$focus_start_date_range = mktime( 0,0,0,$get_new_monthyear['month'],1,$get_new_monthyear['year'] );
				$time_string = $get_new_monthyear['year'].'-'.$get_new_monthyear['month'].'-1';		
				
				$focus_end_date_range = mktime(23,59,59,($get_new_monthyear['month']),(date('t',(strtotime($time_string) ))), ($get_new_monthyear['year']));
				
			}
				
				
			// generate events within the focused date range
			$eve_args = array(
				'focus_start_date_range'=>$focus_start_date_range,
				'focus_end_date_range'=>$focus_end_date_range,
				'sort_by'=>$sort_by, // by default sort events by start date					
				'event_count'=>$event_count,
				'filters'=>(!empty($filters)? $filters:''),
				'number_months'=>$number_of_months, // to determine empty label 			
			);
			
			/* @commented 2.2.25
			// add event type arguments		
			for($x=1; $x< $this->cal->event_types; $x++){
				$ab = ($x==1)? '':'_'.$x;
				$eve_args['ev_type'.$ab] = !empty($args['ev_type'.$ab])? $args['ev_type'.$ab]:null;
			}
			*/

			$eve_args =$this->cal->update_shortcode_arguments($eve_args);
			$content_li = $this->cal->eventon_generate_events($eve_args);	
			

			ob_start();
			if($content_li != 'empty'){
				// Eventon Calendar events list
				//echo "<div id='evcal_list' class='eventon_events_list'>";
				echo $content_li;
				//echo "</div>"; 
			}else{
				// ONLY UPCOMING LIST empty months
				if( $this->cal->is_upcoming_list && !empty($hide_empty_months) && $hide_empty_months=='yes'){
					echo 'false';
				}else{
					//echo "<div id='evcal_list' class='eventon_events_list'>";
					echo "<div class='eventon_list_event'><p class='no_events'>".$this->cal->lang_array['no_event']."</p></div>";
					//echo "</div>";
				}
			}
			
			return ob_get_clean();			
		}

	/**
	 * Calendar header content
	 * @param  array $arguments 
	 * @return string            HTML content
	 */
		function get_calendar_header($arguments){
			global $eventon;

			// SHORTCODE
			// at this point shortcode arguments are processed
			$args = $this->cal->shortcode_args;
			//print_r($args);		
			
			// FUNCTION
			$defaults = array(
				'focused_month_num'=>1,
				'focused_year'=>date('Y'),			
				'range_start'=>0,
				'range_end'=>0,
				'send_unix'=>false,
				'header_title'=>'',
				'_html_evcal_list'=>true,
				'sortbar'=>true,
				'_html_sort_section'=>true,
				'date_header'=>true,
			);

			// $arguments contain focused month num and focused year values
			// that need to be merged with existing values
			$arg_y = array_merge($defaults, $args, $arguments);
			extract($arg_y);

			//print_r($arg_y);

			
			// CONNECTION with action user addon
			do_action('eventon_cal_variable_action_au', $arg_y);	

			
			//BASE settings to pass to calendar
				$cal_version =  $eventon->version;			
				$eventcard_open = ($this->cal->is_eventcard_open)? 'eventcard="1"':null;	

			// calendar class names			
				$boxCal = (!empty($args['tiles']) && $args['tiles'] =='yes')?'boxy':null;
			
				$__cal_classes = array('ajde_evcal_calendar', $boxCal);
				
				if(!empty($args['rtl']) && $args['rtl'] =='yes')
					$__cal_classes[] = 'evortl';

				if(!empty($args['tile_style']) && $args['tile_style'] !='0')
					$__cal_classes[] = 'tile'.$args['tile_style'];

				if($this->cal->is_upcoming_list)
					$__cal_classes[] = 'ul';

				// tile count
					if(!empty($args['tile_count']) && $args['tile_count'] !=2)
						$__cal_classes[] = 'box_'.$args['tile_count'];


			// plugin hook
			$__cal_classes = apply_filters('eventon_cal_class', $__cal_classes);
			
			$lang = (!empty($args['lang']))? $args['lang']: 'L1';
			$cal_header_title = get_eventon_cal_title_month($focused_month_num, $focused_year, $lang);

			//echo $lang;


			// calendar data variables
				$_cd='';
				
				$ux_val = $args['ux_val'];
				// figure out UX_val - user interaction
					if($tiles=='yes'){ 
						// if tiles then dont do slide down 5/19
						$__ux_val = ($ux_val=='1')? '3':$ux_val;
					}else{
						if(!empty($ux_val) && $ux_val!='0'){
							$__ux_val =  $ux_val;
						}else{
							$__ux_val = '0';
						}
					}

				
				$cdata = apply_filters('eventon_cal_jqdata', array(
					'cyear'=>$focused_year,
					'cmonth'=>$focused_month_num,
					'runajax'=>'1',
					'evc_open'=>((!empty($args['evc_open']) && $args['evc_open']=='yes')? '1':'0'),
					'cal_ver'=>$cal_version,
					'mapscroll'=> ((!empty($this->cal->evopt1['evcal_gmap_scroll']) && $this->cal->evopt1['evcal_gmap_scroll']=='yes')?'false':'true'),
					'mapformat'=> ((!empty($this->cal->evopt1['evcal_gmap_format']))?$this->cal->evopt1['evcal_gmap_format']:'roadmap'),
					'mapzoom'=>((!empty($this->cal->evopt1['evcal_gmap_zoomlevel']))?$this->cal->evopt1['evcal_gmap_zoomlevel']:'12'),
					'ev_cnt'=>$args['event_count'], // event count
					'show_limit'=>$args['show_limit'],
					'tiles'=>$args['tiles'],
					'sort_by'=>$args['sort_by'],
					'filters_on'=>$this->cal->filters,
					'range_start'=>$range_start,
					'range_end'=>$range_end,
					'send_unix'=>( ($send_unix)?'1':'0'),
					'ux_val'=>$__ux_val,
					'accord'=>( (!empty($accord) && $accord== 'yes' )? '1': '0'),
				), $this->cal->evopt1);
				foreach ($cdata as $f=>$v){
					$_cd .='data-'.$f.'="'.$v.'" ';
				}

			$content='';
			// Calendar SHELL
			$content .= "<div id='evcal_calendar_".$cal_id."' class='".( implode(' ', $__cal_classes))."' >
				<div class='evo-data' {$_cd} ></div>";

					$sort_class = ($this->cal->evcal_hide_sort=='yes')?'evcal_nosort':null;
			
			// HTML 
				$content.="<div id='evcal_head' class='calendar_header ".$sort_class."' >";

			// if the calendar arrows and headers are to show 
				if($date_header){
					$hide_arrows_check = (!empty($this->cal->evopt1['evcal_arrow_hide']) && $this->cal->evopt1['evcal_arrow_hide']=='yes')?"style='display:none'":null;					
					
					$content .= $this->cal_above_header($arg_y);				


					$content.="<p id='evcal_cur'> ".$cal_header_title."</p>
						<span id='evcal_prev' class='evcal_arrows evcal_btn_prev' ".$hide_arrows_check."><i class='fa fa-angle-left'></i></span><span id='evcal_next' class='evcal_arrows evcal_btn_next' ".$hide_arrows_check."><i class='fa fa-angle-right'></i></span>";	
				}else if(!empty($header_title)){
					$content.="<p>". $header_title ."</p>";
				}
				
			// (---) Hook for addon
				if(has_action('eventon_calendar_header_content')){
					ob_start();
					do_action('eventon_calendar_header_content', $content,$args);
					$content.= ob_get_clean();
				}
			
			// Shortcode arguments
				$content.= $this->cal->shell->shortcode_args_for_cal();
				$content.="<div class='clear'></div></div>";
			
							
			// SORT BAR
				$sortbar =($hide_so=='yes')? false:true;
				$content.= ($_html_sort_section)? $this->cal->eventon_get_cal_sortbar($args, $sortbar):null;
		
			// RTL 
				$rtl = (!empty($args['rtl']) && $args['rtl'] =='yes')? 'evortl':null;
				$content .= ($_html_evcal_list)? "<div id='evcal_list' class='eventon_events_list {$rtl}'>":null;

			return $content;
		}

	// Independant components of the calendar body
	/**
	 * Header
	 * @param  array $arg 
	 * @return string      
	 */
		public function calendar_shell_header($arg){
			$defaults = array(
				'sort_bar'=> true,
				'title'=>'none',
				'date_header'=>true,
				'month'=>'1',
				'year'=>2014,
				'date_range_start'=>0,
				'date_range_end'=>0,
				'send_unix'=>false
			);

			$args = array_merge($defaults, $arg);

			$date_range_start =($args['date_range_start']!=0)? $args['date_range_start']: '0';
			$date_range_end =($args['date_range_end']!=0)? $args['date_range_end']: '0';

			$content ='';

			$content .= $this->get_calendar_header(
				array(
					'focused_month_num'=>$args['month'], 
					'focused_year'=>$args['year'], 
					'sortbar'=>$args['sort_bar'], 
					'date_header'=>$args['date_header'],
					'range_start'=>$date_range_start, 
					'range_end'=>$date_range_end , 
					'send_unix'=>$args['send_unix'],
					'header_title'=>$args['title']
				)
			);

			return $content;
		}

	/**
	 * footer
	 * @return string 
	 */
		public function calendar_shell_footer($args=''){

			global $eventon;

			ob_start();
			do_action('evo_cal_footer');

			?>
			<div class='clear'></div>
			</div><!-- #evcal_list-->
			<div class='clear'></div>
			<?php do_action('evo_cal_after_footer', $eventon->evo_generator->shortcode_args);?>
			</div><!-- .ajde_evcal_calendar-->

			<?php

			return ob_get_clean();
		}

	
	



}