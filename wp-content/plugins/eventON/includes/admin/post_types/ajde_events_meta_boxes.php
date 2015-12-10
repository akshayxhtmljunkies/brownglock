<?php
/**
 * Meta boxes for ajde_events
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin/ajde_events
 * @version     2.3.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Init the meta boxes. */
	function eventon_meta_boxes(){

		$evcal_opt1= get_option('evcal_options_evcal_1');

		// ajde_events meta boxes
		add_meta_box('ajdeevcal_mb2',__('Event Color','eventon'), 'ajde_evcal_show_box_2','ajde_events', 'side', 'core');
		add_meta_box('ajdeevcal_mb1', __('Event Details','eventon'), 'ajde_evcal_show_box','ajde_events', 'normal', 'high');	
		
		// if third party is enabled
		if(( $evcal_opt1['evcal_evb_events']=='yes' && !empty($evcal_opt1['evcal_evb_api']) ) || ($evcal_opt1['evcal_api_meetup']=='yes' 
					&& !empty($evcal_opt1['evcal_api_mu_key']) ) || ($evcal_opt1['evcal_paypal_pay']=='yes') )
			add_meta_box('ajdeevcal_mb3','Third Party Settings', 'ajde_evcal_show_box_3','ajde_events', 'normal', 'core');
		
		do_action('eventon_add_meta_boxes');
	}
	add_action( 'add_meta_boxes', 'eventon_meta_boxes' );
	add_action( 'save_post', 'eventon_save_meta_data', 1, 2 );
	
// EXTRA event settings for the page
	function ajde_events_settings_per_post(){
		global $post, $eventon, $ajde;

		if ( ! is_object( $post ) ) return;

		if ( $post->post_type != 'ajde_events' ) return;

		if ( isset( $_GET['post'] ) ) {

			$event_pmv = get_post_custom($post->ID);

			$evo_exclude_ev = evo_meta($event_pmv, 'evo_exclude_ev');
			$_featured = evo_meta($event_pmv, '_featured');
			$_cancel = evo_meta($event_pmv, '_cancel');
		?>
			<div class="misc-pub-section" >
			<div class='evo_event_opts'>
				<p class='yesno_row evo'>
					<?php 	echo $ajde->wp_admin->html_yesnobtn(
						array(
							'id'=>'evo_exclude_ev', 
							'var'=>$evo_exclude_ev,
							'input'=>true,
							'label'=>__('Exclude from calendar','eventon'),
							'guide'=>__('Set this to Yes to hide event from showing in all calendars','eventon'),
							'guide_position'=>'L'
						));
					?>
				</p>
				<p class='yesno_row evo'>
					<?php 	echo $ajde->wp_admin->html_yesnobtn(
						array(
							'id'=>'_featured', 
							'var'=>$_featured,
							'input'=>true,
							'label'=>__('Featured Event','eventon'),
							'guide'=>__('Make this event a featured event','eventon'),
							'guide_position'=>'L'
						));
					?>	
				</p>
				<p class='yesno_row evo'>
					<?php 	echo $ajde->wp_admin->html_yesnobtn(
						array(
							'id'=>'_cancel', 
							'var'=>$_cancel,
							'input'=>true,
							'label'=>__('Cancel Event','eventon'),
							'guide'=>__('Cancel this event','eventon'),
							'guide_position'=>'L',
							'attr'=>array('afterstatement'=>'evo_editevent_cancel_text')
						));
					?>	
				</p>
				<?php
					$_cancel_reason = evo_meta($event_pmv,'_cancel_reason');
				?>
				<p id='evo_editevent_cancel_text' style='display:<?php echo (!empty($_cancel) && $_cancel=='yes')? 'block':'none';?>'><textarea name="_cancel_reason" style='width:100%' rows="3" placeholder='<?php _e('Type the reason for cancelling','eventon');?>'><?php echo $_cancel_reason;?></textarea></p>
				<?php
					// @since 2.2.28
					do_action('eventon_event_submitbox_misc_actions',$post->ID, $event_pmv);
				?>
			</div>
			</div>
		<?php
		}
	}
	add_action( 'post_submitbox_misc_actions', 'ajde_events_settings_per_post' );

/** Event Color Meta box. */	
	function ajde_evcal_show_box_2(){
			
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'evo_noncename_2' );
		$p_id = get_the_ID();
		$ev_vals = get_post_custom($p_id);
		
		$evOpt = get_option('evcal_options_evcal_1');

	?>		
			<table id="meta_tb2" class="form-table meta_tb" >
			<tr>
				<td>
				<?php
					// Hex value cleaning
					$hexcolor = eventon_get_hex_color($ev_vals,'', $evOpt );	
				?>			
				<div id='color_selector' >
					<em id='evColor' style='background-color:<?php echo (!empty($hexcolor) )? $hexcolor: 'na'; ?>'></em>
					<p class='evselectedColor'>
						<span class='evcal_color_hex evcal_chex'  ><?php echo (!empty($hexcolor) )? $hexcolor: 'Hex code'; ?></span>
						<span class='evcal_color_selector_text evcal_chex'><?php _e('Click here to pick a color');?></span>
					</p>
				</div>
				<p style='margin-bottom:0; padding-bottom:0'><i><?php _e('OR Select from other colors','eventon');?></i></p>
				
				<div id='evcal_colors'>
					<?php 
					
						$other_events = get_posts(array(
							'posts_per_page'=>-1,
							'post_type'=>'ajde_events',
							'meta_key' => 'evcal_event_color'
						));
						
						$other_colors='';
						
						foreach($other_events as $ev){ setup_postdata($ev);
							$this_id = $ev->ID;
							
							$hexval = get_post_meta($this_id,'evcal_event_color',true);
							$hexval_num = get_post_meta($this_id,'evcal_event_color_n',true);
							
							
							// hex color cleaning
							$hexval = ($hexval[0]=='#')? substr($hexval,1):$hexval;
							
							
							if(!empty( $hexval) && (empty($other_colors) || (is_array($other_colors) && !in_array($hexval, $other_colors)	)	)	){
								echo "<div class='evcal_color_box' style='background-color:#".$hexval."'color_n='".$hexval_num."' color='".$hexval."'></div>";
								
								$other_colors[]=$hexval;
							}				
						}
						
					?>				
				</div>
				<div class='clear'></div>
				
				
				
				<input id='evcal_event_color' type='hidden' name='evcal_event_color' 
					value='<?php echo str_replace('#','',$hexcolor); ?>'/>
				<input id='evcal_event_color_n' type='hidden' name='evcal_event_color_n' 
					value='<?php echo (!empty($ev_vals["evcal_event_color_n"]) )? $ev_vals["evcal_event_color_n"][0]: null ?>'/>
				</td>
			</tr>
			<?php do_action('eventon_metab2_end'); ?>
			</table>
	<?php }
	
/** Main meta box. */
	function ajde_evcal_show_box(){
		global $eventon, $ajde;
		
		$evcal_opt1= get_option('evcal_options_evcal_1');
		$evcal_opt2= get_option('evcal_options_evcal_2');
		
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'evo_noncename' );
		
		// The actual fields for data entry
		$p_id = get_the_ID();
		$ev_vals = get_post_custom($p_id);
		
		
		$evcal_allday = (!empty($ev_vals["evcal_allday"]))? $ev_vals["evcal_allday"][0]:null;		
		$show_style_code = ($evcal_allday=='yes') ? "style='display:none'":null;

		$select_a_arr= array('AM','PM');
		
		// --- TIME variations
		$evcal_date_format = eventon_get_timeNdate_format($evcal_opt1);
		$time_hour_span= ($evcal_date_format[2])?25:13;
		
		
		// GET DATE and TIME values
		$_START=(!empty($ev_vals['evcal_srow'][0]))?
			eventon_get_editevent_kaalaya($ev_vals['evcal_srow'][0],$evcal_date_format[1], $evcal_date_format[2]):false;
		$_END=(!empty($ev_vals['evcal_erow'][0]))?
			eventon_get_editevent_kaalaya($ev_vals['evcal_erow'][0],$evcal_date_format[1], $evcal_date_format[2]):false;
		
		//print_r($_START);
		//print_r($ev_vals);
	?>

<?php

	// --------------------------
	// HTML - Subtitle
		ob_start();
		?>
			<div class='evcal_data_block_style1'>
				<div class='evcal_db_data'>
					<input type='text' id='evcal_subtitle' name='evcal_subtitle' value="<?php echo evo_meta($ev_vals, 'evcal_subtitle', true) ?>" style='width:100%'/>
				</div>
			</div>
		<?php
		$_html_ST = ob_get_clean();
		$__hiddenVAL_ST = '';
	
	// --------------------------
	// HTML - date
		ob_start();
		?>
		<!-- date and time formats to use -->
		<input type='hidden' name='_evo_date_format' value='<?php echo $evcal_date_format[1];?>'/>
		<input type='hidden' name='_evo_time_format' value='<?php echo ($evcal_date_format[2])?'24h':'12h';?>'/>	
		<div id='evcal_dates' date_format='<?php echo $evcal_date_format[0];?>'>	
			<p class='yesno_row evo fcw'>
				<?php 	echo $ajde->wp_admin->html_yesnobtn(array(
					'id'=>'evcal_allday_yn_btn', 
					'var'=>$evcal_allday, 
					'attr'=>array('allday_switch'=>'1',)
					));?>			
				<input type='hidden' name='evcal_allday' value="<?php echo ($evcal_allday=='yes')?'yes':'no';?>"/>
				<label for='evcal_allday_yn_btn'><?php _e('All Day Event', 'eventon')?></label>
			</p>
			<p style='clear:both'></p>
			
			<!-- START TIME-->
			<div class='evo_start_event evo_datetimes'>
				<div class='evo_date'>
					<p id='evcal_start_date_label'><?php _e('Event Start Date', 'eventon')?></p>
					<input id='evo_dp_from' class='evcal_data_picker datapicker_on' type='text' id='evcal_start_date' name='evcal_start_date' value='<?php echo ($_START)?$_START[0]:null?>' placeholder='<?php echo $evcal_date_format[1];?>'/>					
					<span><?php _e('Select a Date', 'eventon')?></span>
				</div>					
				<div class='evcal_date_time switch_for_evsdate evcal_time_selector' <?php echo $show_style_code?>>
					<div class='evcal_select'>
						<select id='evcal_start_time_hour' class='evcal_date_select' name='evcal_start_time_hour'>
							<?php
								//echo "<option value=''>--</option>";
								$start_time_h = ($_START)?$_START[1]:null;						
							for($x=1; $x<$time_hour_span;$x++){	
								$y = ($time_hour_span==25)? sprintf("%02d",($x-1)): $x;							
								echo "<option value='$y'".(($start_time_h==$y)?'selected="selected"':'').">$y</option>";
							}?>
						</select>
					</div><p style='display:inline; font-size:24px;padding:4px 2px'>:</p>
					<div class='evcal_select'>						
						<select id='evcal_start_time_min' class='evcal_date_select' name='evcal_start_time_min'>
							<?php	
								//echo "<option value=''>--</option>";
								$start_time_m = ($_START)?	$_START[2]: null;
							for($x=0; $x<12;$x++){
								$min = ($x<2)?('0'.$x*5):$x*5;
								echo "<option value='$min'".(($start_time_m==$min)?'selected="selected"':'').">$min</option>";
							}?>
						</select>
					</div>
					
					<?php if(!$evcal_date_format[2]):?>
					<div class='evcal_select evcal_ampm_sel'>
						<select name='evcal_st_ampm' id='evcal_st_ampm' >
							<?php
								$evcal_st_ampm = ($_START)?$_START[3]:null;
								foreach($select_a_arr as $sar){
									echo "<option value='".$sar."' ".(($evcal_st_ampm==$sar)?'selected="selected"':'').">".$sar."</option>";
								}
							?>								
						</select>
					</div>	
					<?php endif;?>
					<br/>
					<span><?php _e('Select a Time', 'eventon')?></span>
				</div><div class='clear'></div>
			</div>
			
			<!-- END TIME -->
			<?php 
				$evo_hide_endtime = (!empty($ev_vals["evo_hide_endtime"]) )? $ev_vals["evo_hide_endtime"][0]:null;
			?>
			<div class='evo_end_event evo_datetimes switch_for_evsdate'>
				<div class='evo_enddate_selection' style='<?php echo ($evo_hide_endtime=='yes')?'opacity:0.5':null;?>'>
				<div class='evo_date'>
					<p><?php _e('Event End Date','eventon')?></p>
					<input id='evo_dp_to' class='evcal_data_picker datapicker_on' type='text' id='evcal_end_date' name='evcal_end_date' value='<?php echo ($_END)? $_END[0]:null; ?>'/>					
					<span><?php _e('Select a Date','eventon')?></span>					
				</div>
				<div class='evcal_date_time evcal_time_selector' <?php echo $show_style_code?>>
					<div class='evcal_select'>
						<select class='evcal_date_select' name='evcal_end_time_hour'>
							<?php	
								//echo "<option value=''>--</option>";
								$end_time_h = ($_END)?$_END[1]:null;
								for($x=1; $x<$time_hour_span;$x++){
									$y = ($time_hour_span==25)? sprintf("%02d",($x-1)): $x;								
									echo "<option value='$y'".(($end_time_h==$y)?'selected="selected"':'').">$y</option>";
								}
							?>
						</select>
					</div><p style='display:inline; font-size:24px;padding:4px'>:</p>
					<div class='evcal_select'>
						<select class='evcal_date_select' name='evcal_end_time_min'>
							<?php	
								//echo "<option value=''>--</option>";
								$end_time_m = ($_END[2])?$_END[2]:null;
								for($x=0; $x<12;$x++){
									$min = ($x<2)?('0'.$x*5):$x*5;
									echo "<option value='$min'".(($end_time_m==$min)?'selected="selected"':'').">$min</option>";
								}
							?>
						</select>
					</div>					
					<?php if(!$evcal_date_format[2]):?>
					<div class='evcal_select evcal_ampm_sel'>
						<select name='evcal_et_ampm'>
							<?php
								$evcal_et_ampm = ($_END)?$_END[3]:null;								
								foreach($select_a_arr as $sar){
									echo "<option value='".$sar."' ".(($evcal_et_ampm==$sar)?'selected="selected"':'').">".$sar."</option>";
								}
							?>								
						</select>
					</div>
					<?php endif;?>
					<br/>
					<span><?php _e('Select the Time','eventon')?></span>
				</div><div class='clear'></div>
				</div>

				<!-- timezone value -->				
				<p style='padding-top:10px'><input type='text' name='evo_event_timezone' value='<?php echo (!empty($ev_vals["evo_event_timezone"]) )? $ev_vals["evo_event_timezone"][0]:null;?>' placeholder='<?php _e('Timezone text eg.PST','eventon');?>'/><label for=""><?php _e('Event timezone','eventon');?><?php $ajde->wp_admin->tooltips( __('Timezone text you type in here ex. PST will show next to event time on calendar.','eventon'),'',true);?></label></p>
				
				
				<!-- end time yes/no option -->					
				<p class='yesno_row evo '>
					<?php 	echo $ajde->wp_admin->html_yesnobtn(array('id'=>'evo_endtime', 'var'=>$evo_hide_endtime, 'attr'=>array('afterstatement'=>'evo_span_hidden_end')));?>
					
					<input type='hidden' name='evo_hide_endtime' value="<?php echo ($evo_hide_endtime=='yes')?'yes':'no';?>"/>
					<label for='evo_hide_endtime'><?php _e('Hide End Time from calendar', 'eventon')?></label>
				</p>
				<?php 
					// span event to hidden end time
					$evo_span_hidden_end = (!empty($ev_vals["evo_span_hidden_end"]) )? $ev_vals["evo_span_hidden_end"][0]:null;
					$evo_span_hidd_display = ($evo_hide_endtime && $evo_hide_endtime=='yes')? 'block':'none';
				?>
				<p class='yesno_row evo ' id='evo_span_hidden_end' style='display:<?php echo $evo_span_hidd_display;?>'>
					<?php 	echo $ajde->wp_admin->html_yesnobtn(array('id'=>'evo_span_hidden_end', 'var'=>$evo_span_hidden_end));?>
					
					<input type='hidden' name='evo_span_hidden_end' value="<?php echo ($evo_span_hidden_end=='yes')?'yes':'no';?>"/>
					<label for='evo_span_hidden_end'><?php _e('Span the event until hidden end time','eventon')?><?php $ajde->wp_admin->tooltips( __('If event end time goes beyond start time +  and you want the event to show in the calendar until end time expire, select this.','eventon'),'',true);?></label>
				</p>

				<?php 
					// Year long event
					$evo_year_long = (!empty($ev_vals["evo_year_long"]) )? $ev_vals["evo_year_long"][0]:null;
					$event_year = (!empty($ev_vals["event_year"]) )? $ev_vals["event_year"][0]:null;
					
				?>
				<p class='yesno_row evo ' id='evo_year_long' >
					<?php 	echo $ajde->wp_admin->html_yesnobtn(array('id'=>'evo_year_long', 'var'=>$evo_year_long));?>
					
					<input type='hidden' name='evo_year_long' value="<?php echo ($evo_year_long=='yes')?'yes':'no';?>"/>					
					<label for='evo_year_long'><?php _e('Show this event for the entire year','eventon')?><?php $ajde->wp_admin->tooltips( __('This will show this event on every month of the year. The year will be based off the start date you choose above','eventon'),'',true);?></label>
				</p>
				<input id='evo_event_year' type='hidden' name='event_year' value="<?php echo $event_year;?>"/>

				<p style='clear:both'></p>
			</div>
			<div style='clear:both'></div>			
			<?php 
				// Recurring events 
				$evcal_repeat = (!empty($ev_vals["evcal_repeat"]) )? $ev_vals["evcal_repeat"][0]:null;
			?>
			<div id='evcal_rep' class='evd'>
				<div class='evcalr_1'>
					<p class='yesno_row evo '>
						<?php 	
						echo $ajde->wp_admin->html_yesnobtn(array(
							'id'=>'evd_repeat', 
							'var'=>$evcal_repeat,
							'attr'=>array(
								'afterstatement'=>'evo_editevent_repeatevents'
							)
						));
						?>						
						<input type='hidden' name='evcal_repeat' value="<?php echo ($evcal_repeat=='yes')?'yes':'no';?>"/>
						<label for='evcal_repeat'><?php _e('Repeating event', 'eventon')?></label>
					</p>
					<p style='clear:both'></p>
				</div>
				<p class='eventon_ev_post_set_line'></p>
				<?php
					// initial values
					$display = (!empty($ev_vals["evcal_repeat"]) && $evcal_repeat=='yes')? '':'none';
					// repeat frequency array
					$repeat_freq= apply_filters('evo_repeat_intervals', array('daily'=>'days','weekly'=>'weeks','monthly'=>'months','yearly'=>'years', 'custom'=>'custom') );
					$evcal_rep_gap = (!empty($ev_vals['evcal_rep_gap']) )?$ev_vals['evcal_rep_gap'][0]:1;
					$freq = (!empty($ev_vals["evcal_rep_freq"]) )?
							 ($repeat_freq[ $ev_vals["evcal_rep_freq"][0] ]): null;
				?>
				<div id='evo_editevent_repeatevents' class='evcalr_2 evo_repeat_options' style='display:<?php echo $display ?>'>

					<p class='repeat_type evcalr_2_freq evcalr_2_p'><span class='evo_form_label'><?php _e('Event Repeat Type','eventon');?>:</span> <select id='evcal_rep_freq' name='evcal_rep_freq'>
					<?php
						$evcal_rep_freq = (!empty($ev_vals['evcal_rep_freq']))?$ev_vals['evcal_rep_freq'][0]:null;
						foreach($repeat_freq as $refv=>$ref){
							echo "<option field='".$ref."' value='".$refv."' ".(($evcal_rep_freq==$refv)?'selected="selected"':'').">".$refv."</option>";
						}						
					?></select></p><!--.repeat_type-->
					
					<div class='evo_preset_repeat_settings' style='display:<?php echo (!empty($ev_vals['evcal_rep_freq']) && $ev_vals['evcal_rep_freq'][0]=='custom')? 'none':'block';?>'>		
						<p class='gap evcalr_2_rep evcalr_2_p'><span class='evo_form_label'><?php _e('Gap between repeats','eventon');?>:</span>
						<input type='number' name='evcal_rep_gap' min='1' max='100' value='<?php echo $evcal_rep_gap;?>' placeholder='1'/>	 <span id='evcal_re'><?php echo $freq;?></span></p>
					<?php
						
						// repeat number
							$evcal_rep_num = (!empty($ev_vals['evcal_rep_num']) )?  $ev_vals['evcal_rep_num'][0]:1;
						
						// repeat by
							$evp_repeat_rb = (!empty($ev_vals['evp_repeat_rb']) )? $ev_vals['evp_repeat_rb'][0]: null;	
							$evo_rep_WK = (!empty($ev_vals['evo_rep_WK']) )? unserialize($ev_vals['evo_rep_WK'][0]): array();
							$evo_repeat_wom = (!empty($ev_vals['evo_repeat_wom']) )? $ev_vals['evo_repeat_wom'][0]: null;
							
						// display none section
							$__display_none_1 =  (!empty($ev_vals['evcal_rep_freq']) && $ev_vals['evcal_rep_freq'][0] =='monthly')? 'block': 'none';
							$__display_none_2 =  ($__display_none_1=='block' && !empty($ev_vals['evp_repeat_rb']) && $ev_vals['evp_repeat_rb'][0] =='dow')? 'block': 'none';
					?>
						
					<?php // monthly only ?>
						<p class='repeat_by evcalr_2_p evo_rep_month' style='display:<?php echo $__display_none_1;?>'>
							<span class='evo_form_label'><?php _e('Repeat by','eventon');?>:</span>
							<select id='evo_rep_by' name='evp_repeat_rb'>
								<option value='dom' <?php echo ('dom'==$evp_repeat_rb)? 'selected="selected"':null;?>><?php _e('Day of the month','eventon');?></option>
								<option value='dow' <?php echo ('dow'==$evp_repeat_rb)? 'selected="selected"':null;?>><?php _e('Day of the week','eventon');?></option>
							</select>
						</p>
						<p class='evo_days_list evo_rep_month_2'  style='display:<?php echo $__display_none_2;?>'>
							<span class='evo_form_label'><?php _e('Repeat on selected days','eventon');?>: </span>
							<?php
								$days = array('S','M','T','W','T','F','S');
								for($x=0; $x<7; $x++){
									echo "<em><input type='checkbox' name='evo_rep_WK[]' value='{$x}' ". ((in_array($x, $evo_rep_WK))? 'checked="checked"':null)."><label>".$days[$x]."</label></em>";
								}
							?>
						</p>
						<p class='evcalr_2_p evo_rep_month_2'  style='display:<?php echo $__display_none_2;?>'>
							<span class='evo_form_label'><?php _e('Week of month to repeat','eventon');?>: </span>
							<select id='evo_wom' name='evo_repeat_wom'>
								<option value='none' <?php echo ('none'== $evo_repeat_wom)? 'selected="selected"':null;?>><?php _e('None','eventon');?></option>
								<?php
									for($x=0; $x<7; $x++){
										echo "<option value='{$x}' ".(($x== $evo_repeat_wom)? 'selected="selected"':null).">{$x}</option>";
									}
								?>
							</select>
						</p>
					
						<p class='evo_month_rep_value evo_rep_month_2' style='display:none'></p>
						
						<p class='evcalr_2_numr evcalr_2_p'><span class='evo_form_label'><?php _e('Number of repeats','eventon');?>:</span>
							<input type='number' name='evcal_rep_num' min='1' max='100' value='<?php echo $evcal_rep_num;?>' placeholder='1'/>						
						</p>
					</div><!--evo_preset_repeat_settings-->
					
					<!-- Custom repeat -->
					<div class='repeat_information' style='display:<?php echo (!empty($ev_vals['evcal_rep_freq']) && $ev_vals['evcal_rep_freq'][0]=='custom')? 'block':'none';?>'>
						<p><?php _e('CUSTOM REPEAT TIMES','eventon');?><br/><i style='opacity:0.7'><?php _e('NOTE: Below repeat intervals are in addition to the above main event time.','eventon');?></i></p>
						<?php

							//print_r(unserialize($ev_vals['aaa'][0]));					
							date_default_timezone_set('UTC');	
							echo "<ul class='evo_custom_repeat_list'>";
							$count =0;
							if(!empty($ev_vals['repeat_intervals'])){								
								$repeat_times = (unserialize($ev_vals['repeat_intervals'][0]));
								// datre format sting to display for repeats
								$date_format_string = $evcal_date_format[1].' '.( $evcal_date_format[2]? 'G:i':'h:ia');
								
								foreach($repeat_times as $rt){
									echo '<li style="display:'.(($count==0 || $count>3)?'none':'block').'" class="'.($count==0?'initial':'').($count>3?' over':'').'"><span>'.__('from','eventon').'</span> '.date($date_format_string,$rt[0]).' <span class="e">End</span> '.date($date_format_string,$rt[1]).'<em alt="Delete">x</em>
									<input type="hidden" name="repeat_intervals['.$count.'][0]" value="'.$rt[0].'"/><input type="hidden" name="repeat_intervals['.$count.'][1]" value="'.$rt[1].'"/></li>';
									$count++;
								}								
							}
							echo "</ul>";
							echo ($count>3 && !empty($ev_vals['repeat_intervals']))? "<p style='padding-bottom:20px'>There are ".($count-1)." repeat intervals. <span class='evo_repeat_interval_view_all' data-show='no'>".__('View All','eventon')."</span></p>":null;
						?>
						<div class='evo_repeat_interval_new' style='display:none'>
							<p><span><?php _e('FROM','eventon');?>:</span><input class='ristD' name='repeat_date'/> <input class='ristT' name='repeat_time'/><br/><span><?php _e('TO','eventon');?>:</span><input class='rietD' name='repeat_date'/> <input class='rietT' name='repeat_time'/></p>
						</div>
						<p class='evo_repeat_interval_button'><a id='evo_add_repeat_interval' class='button_evo'>+ <?php _e('Add New Repeat Interval','eventon');?></a><span></span></p>
					</div>	
				</div>
			</div>	
		</div>
		
		<?php 
		$_html_TD = ob_get_clean();
		$__hiddenVAL_TD = '';
		
	
	// --------------------------
	// HTML - location
		ob_start();
		?>
			<div class='evcal_data_block_style1'>
				<p class='edb_icon evcal_edb_map'></p>
				<div class='evcal_db_data'>			
					<p>
					<?php

						// location terms for event post
						$loc_term_id = $termMeta= '';
						$location_terms = get_the_terms($p_id, 'event_location');
						if ( $location_terms && ! is_wp_error( $location_terms ) ){
							foreach($location_terms as $location_term){
								$loc_term_id = $location_term->term_id;
								$termMeta = get_option( "taxonomy_$loc_term_id");
							}
						}
						//print_r($location_terms);

						// GET all available location terms
						$terms = get_terms('event_location', array('hide_empty'=>false));
						
						if(count($terms)>0){

							echo "<select id='evcal_location_field' name='evcal_location_name_select' class='evo_select_field'>
								<option value=''>".__('Select a saved location','eventon')."</option>";
						    foreach ( $terms as $term ) {

						    	$loc_img_src = $loc_img_id='';
						    	$t_id = $term->term_id;
						    	$term_meta = get_option( "taxonomy_$t_id" );
						    	$__selected = ($loc_term_id== $t_id)? "selected='selected'":null;

						    	// location image
						    	$loc_img_id = (!empty($term_meta['evo_loc_img'])? $term_meta['evo_loc_img']:null);
								$img_src = (!empty($loc_img_id))? 
									wp_get_attachment_image_src($loc_img_id,'medium'): false;
									$loc_img_src = ($img_src)? $img_src[0]: '';

						       	echo "<option value='". $term->name ."' data-tid='{$t_id}' data-address='".((!empty( $term_meta['location_address'] )) ? esc_attr( $term_meta['location_address'] ) : '')  ."' data-lat='". ( (!empty( $term_meta['location_lat'] )) ? esc_attr( $term_meta['location_lat'] ) : '' ) ."' data-lon='". ( (!empty( $term_meta['location_lon'] )) ? esc_attr( $term_meta['location_lon'] ) : '') ."' {$__selected} data-loc_img_id='".$loc_img_id."' data-loc_img_src='{$loc_img_src}'>" . $term->name . "</option>";						        
						    }
						    echo "</select> <label for='evcal_location_field'>".__('Choose already saved location or type new one below','eventon')."</label>";
						}

					
					?>
					<input id='evo_location_tax' type='hidden' name='evo_location_tax_id' value='<?php echo $loc_term_id;?>'/>
					<input type='text' id='evcal_location_name' name='evcal_location_name' value="<?php echo evo_meta($ev_vals, 'evcal_location_name', true); ?>" style='width:100%' placeholder='<?php _e('eg. Irving City Park','eventon');?>'/><label for='evcal_location_name'><?php _e('Event Location Name','eventon')?></label></p>
					<p><input type='text' id='evcal_location' name='evcal_location' value="<?php echo evo_meta($ev_vals, 'evcal_location', true); ?>" style='width:100%' placeholder='<?php _e('eg. 12 Rue de Rivoli, Paris','eventon');?>'/><label for='evcal_location'><?php _e('Event Location Address','eventon')?></label></p>
					
					
					<p><input type='text' id='evcal_lat' class='evcal_latlon' name='evcal_lat' value='<?php echo evo_meta($ev_vals, 'evcal_lat') ?>' placeholder='<?php _e('Latitude','eventon');?>'/>
					<input type='text' id='evcal_lon' class='evcal_latlon' name='evcal_lon' value='<?php echo evo_meta($ev_vals, 'evcal_lon')?>' placeholder='<?php _e('Longitude','eventon')?>'/></p>
					<p><i><?php _e('NOTE: If Latlon provided, Latlon will be used for generating google maps while location address will be shown as text address. <br/>Location address field is <b>REQUIRED</b> for this to work.','eventon')?> <a style='color:#B3DDEC' href='http://itouchmap.com/latlong.html' target='_blank'><?php _e('Find LanLat for address','eventon');?></a></i></p>

					<!-- image -->
					<?php 
						$loc_img_id = (!empty($ev_vals['evo_loc_img'])? 
							$ev_vals['evo_loc_img'][0]:false);

						// image soruce array
						$img_src = ($loc_img_id)? 
							wp_get_attachment_image_src($loc_img_id,'medium'): null;

							$loc_img_src = (!empty($img_src))? $img_src[0]: null;

						$__button_text = (!empty($loc_img_id))? __('Remove Image','eventon'): __('Choose Image','eventon');
						$__button_text_not = (empty($loc_img_id))? __('Remove Image','eventon'): __('Choose Image','eventon');
						$__button_class = (!empty($loc_img_id))? 'removeimg':'chooseimg';

						// /echo $loc_img_id.' '.$img_src.'66';
					?>
					<div class='evo_metafield_image' style='padding-top:10px'>
						
						<p >
							<input id='evo_loc_img_id' class='evo_loc_img custom_upload_image evo_meta_img' name="evo_loc_img" type="hidden" value="<?php echo ($loc_img_id)? $loc_img_id: null;?>" /> 
                    		<input class="custom_upload_image_button button <?php echo $__button_class;?>" data-txt='<?php echo $__button_text_not;?>' type="button" value="<?php echo $__button_text;?>" /><br/>
                    		<span class='evo_loc_image_src image_src'>
                    			<img src='<?php echo $loc_img_src;?>' style='<?php echo !empty($loc_img_id)?'':'display:none';?>'/>
                    		</span>
                    		<label><?php _e('Event Location Image','eventon');?></label>
                    	</p>

                    </div>
					
					<!-- HIDE google map option -->
					<p class='yesno_row evo'>
						<?php 	
						$location_val = (!empty($ev_vals["evcal_gmap_gen"]))? $ev_vals["evcal_gmap_gen"][0]: 'yes';
						echo $ajde->wp_admin->html_yesnobtn(array('id'=>'evo_genGmap', 'var'=>$location_val));?>
						
						<input type='hidden' name='evcal_gmap_gen' value="<?php echo (!empty($ev_vals["evcal_gmap_gen"]) && $ev_vals["evcal_gmap_gen"][0]=='yes')?'yes': ( empty($ev_vals["evcal_gmap_gen"])? 'yes':'no' );?>"/>
						<label for='evcal_gmap_gen'><?php _e('Generate Google Map from the address','eventon')?></label>
					</p>
					<p style='clear:both'></p>

					<!-- Show location name over image -->
					<p class='yesno_row evo'>
						<?php 	
						$evcal_name_over_img = (!empty($ev_vals["evcal_name_over_img"]))? $ev_vals["evcal_name_over_img"][0]: 'no';
						echo $ajde->wp_admin->html_yesnobtn(array('id'=>'evcal_name_over_img', 'var'=>$evcal_name_over_img));?>
						
						<input type='hidden' name='evcal_name_over_img' value="<?php echo (!empty($ev_vals["evcal_name_over_img"]) && $ev_vals["evcal_name_over_img"][0]=='yes')?'yes':'no';?>"/>
						<label for='evcal_name_over_img'><?php _e('Show location name & address over location image (If location image exist)','eventon')?></label>
					</p>
					<p style='clear:both'></p>
				</div>
			</div>
		<?php
		
		$_html_LOC = ob_get_clean();
		$__hiddenVAL_LOC = '';
			
	// --------------------------
	// HTML - Organizer
		ob_start();
		?>
			<div class='evcal_data_block_style1'>
				<p class='edb_icon evcal_edb_map'></p>
				<div class='evcal_db_data'>			
					<p>
					<?php
						// organier terms for event post
						$organizer_terms = get_the_terms($p_id, 'event_organizer');

						$termMeta = $org_term_id = '';

						if ( $organizer_terms && ! is_wp_error( $organizer_terms ) ){
							foreach($organizer_terms as $org_term){
								$org_term_id = $org_term->term_id;
								$termMeta = get_option( "taxonomy_$org_term_id");
							}
						}

						// Get all available organizer terms
						$terms = get_terms('event_organizer', array('hide_empty'=>false));
						
						if(count($terms)>0){
							echo "<select id='evcal_organizer_field' name='evcal_organizer_name_select' class='evo_select_field'>
								<option value=''>".__('Select a saved organizer','eventon')."</option>";
						    foreach ( $terms as $term ) {

						    	$t_id = $term->term_id;
						    	$term_meta = get_option( "taxonomy_$t_id" );
						    	$__selected = ($org_term_id== $t_id)? "selected='selected'":null;

						       	echo "<option value='". $term->name ."' data-tid='{$t_id}' data-contact='".((!empty( $term_meta['evcal_org_contact'] )) ? esc_attr( $term_meta['evcal_org_contact'] ) : '')  ."' data-img='". ( (!empty( $term_meta['evcal_org_img'] )) ? esc_attr( $term_meta['evcal_org_img'] ) : '' ) ."' {$__selected}>" . $term->name . "</option>";						        
						    }
						    echo "</select> <label for='evcal_organizer_field'>".__('Choose already saved organier or type new one below','eventon')."</label>";
						}

					
					?>
					<input id='evo_organizer_tax_id' type='hidden' name='evo_organizer_tax_id' value='<?php echo $org_term_id;?>'/>
					<input type='text' id='evcal_organizer_name' name='evcal_organizer' value="<?php echo (!empty($ev_vals["evcal_organizer"]) )? $ev_vals["evcal_organizer"][0]:null?>" style='width:100%' placeholder='<?php _e('eg. Blue Light Band','eventon');?>'/><label for='evcal_organizer'><?php _e('Event Organizer Name','eventon')?></label></p>
					<?php
						$organizer_contact_info = (!empty($termMeta["evcal_org_contact"]) )? $termMeta["evcal_org_contact"]:( !empty($ev_vals['evcal_org_contact'])? $ev_vals['evcal_org_contact'][0]:false);
						$organizer_contact_info = ($organizer_contact_info)? stripslashes(str_replace('"', "'", $organizer_contact_info)): null;
					?>
					<p><input type='text' id='evcal_org_contact' name='evcal_org_contact' value="<?php echo $organizer_contact_info;?>" style='width:100%' placeholder='<?php _e('eg. noone[at] thismail.com','eventon');?>'/><label for='evcal_org_contact'><?php _e('(Optional) Organizer Contact Information','eventon')?></label></p>
					
					<!-- image -->
					<?php 
						$org_img_id = (!empty($ev_vals['evo_org_img'])? 
							$ev_vals['evo_org_img'][0]:false);

						// image soruce array
						$img_src = ($org_img_id)? 
							wp_get_attachment_image_src($org_img_id,'medium'): null;

							$org_img_src = (!empty($img_src))? $img_src[0]: null;

						$__button_text = (!empty($org_img_id))? __('Remove Image','eventon'): __('Choose Image','eventon');
						$__button_text_not = (empty($org_img_id))? __('Remove Image','eventon'): __('Choose Image','eventon');
						$__button_class = (!empty($org_img_id))? 'removeimg':'chooseimg';
						// /echo $loc_img_id.' '.$img_src.'66';
					?>
					<div class='evo_metafield_image' style='padding-top:10px'>
						<p>
							<input id='evo_org_img_id' class='evo_org_img custom_upload_image evo_meta_img' name="evo_org_img" type="hidden" value="<?php echo ($org_img_id)? $org_img_id: null;?>" /> 
                    		<input class="custom_upload_image_button button <?php echo $__button_class;?>" data-txt='<?php echo $__button_text_not;?>' type="button" value="<?php echo $__button_text;?>" /><br/>
                    		<span class='evo_org_image_src image_src'>
                    			<img src='<?php echo $org_img_src;?>' style='<?php echo !empty($org_img_id)?'':'display:none';?> margin-top:8px'/>
                    		</span>
                    		<label><?php _e('Event Organizer Image','eventon');?> (<?php _e('Recommended Resolution 80x80px','eventon');?>)</label>
                    	</p>
                    </div>
					
					<!-- yea no field - hide organizer field from eventcard -->
					<p class='yesno_row evo'>
						<?php 	
						$evo_evcrd_field_org = (!empty($ev_vals["evo_evcrd_field_org"]))? $ev_vals["evo_evcrd_field_org"][0]: null;
						echo $ajde->wp_admin->html_yesnobtn(array('id'=>'evo_org_field_ec', 'var'=>$evo_evcrd_field_org));?>
						
						<input type='hidden' name='evo_evcrd_field_org' value="<?php echo (!empty($ev_vals["evo_evcrd_field_org"]) && $ev_vals["evo_evcrd_field_org"][0]=='yes')?'yes':'no';?>"/>
						<label for='evo_evcrd_field_org'><?php _e('Hide Organizer field from EventCard','eventon')?></label>
					</p>
					<p style='clear:both'></p>
				</div>
			</div>

		<?php
		$_html_OR = ob_get_clean();
		$__hiddenVAL_OR = '';
		
	// --------------------------
	// HTML - User Interaction
		ob_start();
		?>
			<div class='evcal_data_block_style1'>
				<div class='evcal_db_data'>
					
					<?php
						$exlink_option = (!empty($ev_vals["_evcal_exlink_option"]))? $ev_vals["_evcal_exlink_option"][0]:1;
						$exlink_target = (!empty($ev_vals["_evcal_exlink_target"]) && $ev_vals["_evcal_exlink_target"][0]=='yes')?
							$ev_vals["_evcal_exlink_target"][0]:null;

						//echo $ev_vals["_evcal_exlink_target"][0].'tt';
					?>
					
					<input id='evcal_exlink_option' type='hidden' name='_evcal_exlink_option' value='<?php echo $exlink_option; ?>'/>
					
					<input id='evcal_exlink_target' type='hidden' name='_evcal_exlink_target' value='<?php echo ($exlink_target) ?>'/>
					
					<?php
						$display_link_input = (!empty($ev_vals["_evcal_exlink_option"]) && $ev_vals["_evcal_exlink_option"][0]!='1')? 'display:block':'display:none';
				
					?>
					<p <?php echo ($exlink_option=='1' || $exlink_option=='3')?"style='display:none'":null;?> id='evo_new_window_io' class='<?php echo ($exlink_target=='yes')?'selected':null;?>'><span></span> <?php _e('Open in new window','eventon');?></p>
					
					<!-- external link field-->
					<input id='evcal_exlink' placeholder='<?php _e('Type the URL address','eventon');?>' type='text' name='evcal_exlink' value='<?php echo (!empty($ev_vals["evcal_exlink"]) )? $ev_vals["evcal_exlink"][0]:null?>' style='width:100%; <?php echo $display_link_input;?>'/>
					
					<div class='evcal_db_uis'>
						<a link='no'  class='evcal_db_ui evcal_db_ui_1 <?php echo ($exlink_option=='1')?'selected':null;?>' title='<?php _e('Slide Down Event Card','eventon');?>' value='1'></a>
						
						<!-- open as link-->
						<a link='yes' class='evcal_db_ui evcal_db_ui_2 <?php echo ($exlink_option=='2')?'selected':null;?>' title='<?php _e('External Link','eventon');?>' value='2'></a>	
						
						<!-- open as popup -->
						<a link='yes' class='evcal_db_ui evcal_db_ui_3 <?php echo ($exlink_option=='3')?' selected':null;?>' title='<?php _e('Popup Window','eventon');?>' value='3'></a>
						
						<?php
							// (-- addon --)
							if(has_action('evcal_ui_click_additions')){
								do_action('evcal_ui_click_additions');
							}
						?>							
						<div class='clear'></div>
					</div>
				</div>
			</div>
		<?php
		$_html_UIN = ob_get_clean();
		$__hiddenVAL_UIN = '';
		
	// --------------------------
	// HTML - Learn More
		ob_start();
		?>
			<div class='evcal_data_block_style1'>
				<div class='evcal_db_data'>
					<input type='text' id='evcal_lmlink' name='evcal_lmlink' value='<?php echo (!empty($ev_vals["evcal_lmlink"]) )? $ev_vals["evcal_lmlink"][0]:null?>' style='width:100%'/><br/>
					<input type='checkbox' name='evcal_lmlink_target' value='yes' <?php echo (!empty($ev_vals["evcal_lmlink_target"]) && $ev_vals["evcal_lmlink_target"][0]=='yes')? 'checked="checked"':null?>/> <?php _e('Open in New window','eventon'); ?>
				</div>
			</div>
		<?php
		$_html_LM = ob_get_clean();
		$__hiddenVAL_LM = '';		
		
	/** custom fields **/
		$evMB_custom = array();
		$num = evo_calculate_cmd_count($evcal_opt1);
		for($x =1; $x<=$num; $x++){	
			
			if(eventon_is_custom_meta_field_good($x)){
				
				$fa_icon_class = $evcal_opt1['evcal__fai_00c'.$x];
				
				ob_start();
				
				echo "<div class='evcal_data_block_style1'>
						<div class='evcal_db_data'>";

					// FIELD
					$__saved_field_value = (!empty($ev_vals["_evcal_ec_f".$x."a1_cus"]) )? $ev_vals["_evcal_ec_f".$x."a1_cus"][0]:null ;
					$__field_id = '_evcal_ec_f'.$x.'a1_cus';

					// wysiwyg editor
					if(!empty($evcal_opt1['evcal_ec_f'.$x.'a2']) && 
						$evcal_opt1['evcal_ec_f'.$x.'a2']=='textarea'){
						
						wp_editor($__saved_field_value, $__field_id);
						
					// button
					}elseif(!empty($evcal_opt1['evcal_ec_f'.$x.'a2']) && 
						$evcal_opt1['evcal_ec_f'.$x.'a2']=='button'){
						
						$__saved_field_link = (!empty($ev_vals["_evcal_ec_f".$x."a1_cusL"]) )? $ev_vals["_evcal_ec_f".$x."a1_cusL"][0]:null ;


						echo "<input type='text' id='".$__field_id."' name='_evcal_ec_f".$x."a1_cus' ";
						echo 'value="'. $__saved_field_value.'"';						
						echo "style='width:100%' placeholder='Button Text' title='Button Text'/>";

						echo "<input type='text' id='".$__field_id."' name='_evcal_ec_f".$x."a1_cusL' ";
						echo 'value="'. $__saved_field_link.'"';						
						echo "style='width:100%' placeholder='Button Link' title='Button Link'/>";

							$onw = (!empty($ev_vals["_evcal_ec_f".$x."_onw"]) )? $ev_vals["_evcal_ec_f".$x."_onw"][0]:null ;
						?>
						<input type='checkbox' name='_evcal_ec_f<?php echo $x;?>_onw' value='yes' <?php echo (!empty($onw) && $onw=='yes')? 'checked="checked"':null?>/> <?php _e('Open in New window','eventon'); ?>
						<?php
					
					// text	
					}else{
						echo "<input type='text' id='".$__field_id."' name='_evcal_ec_f".$x."a1_cus' ";
								
						echo 'value="'. $__saved_field_value.'"';						
						echo "style='width:100%'/>";
						
					}

				echo "</div></div>";


				$__html = ob_get_clean();
				
				$evMB_custom[]= array(
					'id'=>'evcal_ec_f'.$x.'a1',
					'variation'=>'customfield',
					'name'=>$evcal_opt1['evcal_ec_f'.$x.'a1'],		
					'iconURL'=>$fa_icon_class,
					'iconPOS'=>'',
					'type'=>'code',
					'content'=>$__html,
					'slug'=>'evcal_ec_f'.$x.'a1'
				);
			}
		}
	
	// array of all meta boxes
		$metabox_array = apply_filters('eventon_event_metaboxs', array(
			array(
				'id'=>'ev_subtitle',
				'name'=>__('Event SubTitle','eventon'),
				'variation'=>'customfield',	
				'hiddenVal'=>$__hiddenVAL_ST,	
				'iconURL'=>'fa-pencil',
				'iconPOS'=>'',
				'type'=>'code',
				'content'=>$_html_ST,
				'slug'=>'ev_subtitle'
			),array(
				'id'=>'ev_timedate',
				'name'=>__('Time and Date','eventon'),	
				'hiddenVal'=>$__hiddenVAL_TD,	
				'iconURL'=>'fa-clock-o','variation'=>'customfield','iconPOS'=>'',
				'type'=>'code',
				'content'=>$_html_TD,
				'slug'=>'ev_timedate'
			),array(
				'id'=>'ev_location',
				'name'=>__('Location and Venue','eventon'),	
				'hiddenVal'=>$__hiddenVAL_LOC,	
				'iconURL'=>'fa-map-marker','variation'=>'customfield','iconPOS'=>'',
				'type'=>'code',
				'content'=>$_html_LOC,
				'slug'=>'ev_location',
				'guide'=>''
			),array(
				'id'=>'ev_organizer',
				'name'=>__('Organizer','eventon'),	
				'hiddenVal'=>$__hiddenVAL_OR,	
				'iconURL'=>'fa-microphone','variation'=>'customfield','iconPOS'=>'',
				'type'=>'code',
				'content'=>$_html_OR,
				'slug'=>'ev_organizer'
			),array(
				'id'=>'ev_uint',
				'name'=>__('User Interaction for event click','eventon'),	
				'hiddenVal'=>$__hiddenVAL_UIN,	
				'iconURL'=>'fa-street-view','variation'=>'customfield','iconPOS'=>'',
				'type'=>'code',
				'content'=>$_html_UIN,
				'slug'=>'ev_uint',
				'guide'=>'This define how you want the events to expand following a click on the eventTop by a user'
			),array(
				'id'=>'ev_learnmore',
				'name'=>__('Learn more about event link','eventon'),	
				'hiddenVal'=>$__hiddenVAL_LM,	
				'iconURL'=>'fa-random','variation'=>'customfield','iconPOS'=>'',
				'type'=>'code',
				'content'=>$_html_LM,
				'slug'=>'ev_learnmore',
				'guide'=>'This will create a learn more link in the event card. Make sure your links start with http://'
			)
		));
	
	// combine array with custom fields
	$metabox_array = (!empty($evMB_custom) && count($evMB_custom)>0)? array_merge($metabox_array , $evMB_custom): $metabox_array;
	
	$closedmeta = eventon_get_collapse_metaboxes($p_id);
	
	//print_r($closedmeta);
?>	
	
	<div id='evo_mb' class='eventon_mb'>
		<input type='hidden' id='evo_collapse_meta_boxes' name='evo_collapse_meta_boxes' value=''/>
	<?php
		foreach($metabox_array as $mBOX):
			
			// ICONS
			$icon_style = (!empty($mBOX['iconURL']))?
				'background-image:url('.$mBOX['iconURL'].')'
				:'background-position:'.$mBOX['iconPOS'];
			$icon_class = (!empty($mBOX['iconPOS']))? 'evIcons':'evII';
			
			$guide = (!empty($mBOX['guide']))? 
				$ajde->wp_admin->tooltips($mBOX['guide']):null;
			
			$hiddenVal = (!empty($mBOX['hiddenVal']))?
				'<span class="hiddenVal">'.$mBOX['hiddenVal'].'</span>':null;
			
			$closed = (!empty($closedmeta) && in_array($mBOX['id'], $closedmeta))? 'closed':null;
	?>
		<div class='evomb_section' id='<?php echo $mBOX['id'];?>'>			
			<div class='evomb_header'>
				<?php // custom field with icons
					if(!empty($mBOX['variation']) && $mBOX['variation']	=='customfield'):?>	
					<span class='evomb_icon <?php echo $icon_class;?>'><i class='fa <?php echo $mBOX['iconURL']; ?>'></i></span>
					
				<?php else:
					
				?>
					<span class='evomb_icon <?php echo $icon_class;?>' style='<?php echo $icon_style?>'></span>
				<?php endif; ?>
				<p><?php echo $mBOX['name'];?><?php echo $hiddenVal;?><?php echo $guide;?></p>
			</div>
			<div class='evomb_body <?php echo $closed;?>' box_id='<?php echo $mBOX['id'];?>'>
				<?php echo $mBOX['content'];?>
			</div>
		</div>
	<?php
		endforeach;
	?>
		<div class='evMB_end'></div>
	</div>

<?php }
	
/*	THIRD PARTY event related settings */
	function ajde_evcal_show_box_3(){	
		
		global $eventon, $ajde;
		
		$evcal_opt1= get_option('evcal_options_evcal_1');
			$evcal_opt2= get_option('evcal_options_evcal_2');
			
			// Use nonce for verification
			wp_nonce_field( plugin_basename( __FILE__ ), 'evo_noncename' );
			
			// The actual fields for data entry
			$p_id = get_the_ID();
			$ev_vals = get_post_custom($p_id);
		
		?>
		<table id="meta_tb" class="form-table meta_tb evoThirdparty_meta" >
			<?php
				// (---) hook for addons
				if(has_action('eventon_post_settings_metabox_table'))
					do_action('eventon_post_settings_metabox_table');
			?>
			
			<?php
				// (---) hook for addons
				if(has_action('eventon_post_time_settings'))
					do_action('eventon_post_time_settings');
			?>
			
			<?php 
			// Event brite
			if($evcal_opt1['evcal_evb_events']=='yes'
				&& !empty($evcal_opt1['evcal_evb_api']) ):?>
				
				<tr>
					<td colspan='2'>
					<div class='evcal_data_block_style1'>
						<p class='edb_icon'><img src='<?php echo AJDE_EVCAL_URL ?>/assets/images/backend_post/eventbrite_icon.png'/></p>
						
						<p class='evcal_db_data'>
						<?php
							if(!empty($ev_vals["evcal_evb_id"]) ){
								echo "<span id='evcal_eb5'>Currently Connected to <b id='evcal_eb2'>".$ev_vals["evcal_evb_id"][0]."</b><br/></span>";
								$html_eb2 = "  <input type='button' class='evo_admin_btn btn_prime' id='evcal_eventb_btn_dis' value='Disconnect this'/>";
							}else{
								$html_eb2='';
							}
							$html_eb1 = 'Connect to Eventbrite Event';
						?>	
						<input type='button' class='evo_admin_btn btn_prime' id='evcal_eventb_btn' value='<?php echo $html_eb1?>'/><?php echo $html_eb2?></p>
						
						<input type='hidden' name='evcal_evb_id' id='evcal_eventb_ev_d2' value='<?php echo (!empty($ev_vals["evcal_evb_id"]))? $ev_vals["evcal_evb_id"][0]: null; ?>'/>
						<input type='hidden' name='evcal_eventb_data_set' id='evcal_eventb_ev_d1' value='<?php echo (!empty($ev_vals["evcal_eventb_data_set"]))? $ev_vals["evcal_eventb_data_set"][0]: null; ?>'/>
					</div>	
					</td>
				</tr>
				<?php
					// URL
					$display = (!empty($ev_vals["evcal_eventb_url"]) )? '':'none';
				?>
				<tr class='divide evcal_eb_url evcal_eb_r' style='display:<?php echo $display ?>'>
					<td colspan='2'><p class='div_bar div_bar_sm'></p></td></tr>
				<tr class='evcal_eb_url evcal_eb_r' style='display:<?php echo $display?>'>
					<td colspan='2'>
						<p style='margin-bottom:2px'>Eventbrite Buy Ticket URL</p>
						<input style='width:100%' id='evcal_ebv_url' type='text' name='evcal_eventb_url' value='<?php echo (!empty($ev_vals["evcal_eventb_url"]))? $ev_vals["evcal_eventb_url"][0]: null; ?>' />
					</td>
				</tr>
				<?php
					// CAPACITY
					$display = (!empty($ev_vals["evcal_eventb_capacity"]) )? '':'none';
				?>
				<tr class='divide evcal_eb_capacity evcal_eb_r' style='display:<?php echo $display ?>'>
					<td colspan='2'><p class='div_bar div_bar_sm'></p></td></tr>
				<tr class='evcal_eb_capacity evcal_eb_r' style='display:<?php echo $display?>'>
					<td colspan='2'>
						<?php $evcal_eventb_capacity = (!empty($ev_vals["evcal_eventb_capacity"]))? $ev_vals["evcal_eventb_capacity"][0]: null; ?>
						<p style='margin-bottom:2px'>Eventbrite Event Capacity: <b id='evcal_eb3'><?php echo $evcal_eventb_capacity?></b></p>
						<input id='evcal_ebv_capacity' type='hidden' name='evcal_eventb_capacity' value='<?php echo $evcal_eventb_capacity?>' />
					</td>
				</tr>
				<?php
					// TICKET PRICE
					$display = (!empty($ev_vals["evcal_eventb_tprice"]) )? '':'none';
				?>
				<tr class='divide evcal_eb_price evcal_eb_r' style='display:<?php echo $display ?>'>
					<td colspan='2'><p class='div_bar div_bar_sm'></p></td></tr>
				<tr class='evcal_eb_price evcal_eb_r' style='display:<?php echo $display?>'>
					<td colspan='2'>
						<?php $evcal_eventb_tprice = (!empty($ev_vals["evcal_eventb_tprice"]))? $ev_vals["evcal_eventb_tprice"][0]: null; ?>
						<p style='margin-bottom:2px'>Eventbrite Ticket Price: <b id='evcal_eb4'><?php echo $evcal_eventb_tprice?></b></p>
						<input id='evcal_ebv_price' type='hidden' name='evcal_eventb_tprice' value='<?php echo $evcal_eventb_tprice?>' />
					</td>
				</tr>
				
				<tr id='evcal_eventb_data' style='display:none'><td colspan='2'>
					<div class='evcal_row_dark' >
						<p id='evcal_eventb_msg' class='event_api_msg' style='display:none'>Message</p>
						<div class='col50'>
							<p><input type='text' id='evcal_eventb_ev_id' value='' style='width:100%'/></p>
							<p class='legend_mf'>Enter Eventbrite Event ID</p>
						</div>
						<div class='col50'>
							<div class='padl20'>
								<p><input id='evcal_eventb_btn_2' style='margin-left:10px'type='button' class='evo_admin_btn btn_prime' value='Get Event Data from Eventbrite'/></p>
							</div>
						</div>			
						
						<p class='clear'></p>					
						<p class='divider'></p>					
						<div id='evcal_eventb_s1' style='display:none'>
							<h5 class='mu_ev_id'>Retrived Event Data for: <b id='evcal_eb1'>321786</b></h5>
							<p class='legend_mf'>Click on each eventbrite event data section to connect to this event.</p>
							
							<div id='evcal_eventb_data_tb'></div>
						</div>
					</div>
				</td></tr>
			
			
			<?php endif;?>
			
			<?php 
				// MEETUP
				
				if($evcal_opt1['evcal_api_meetup']=='yes' 
					&& !empty($evcal_opt1['evcal_api_mu_key']) ):
			?>
				<tr class='divide'><td colspan='2'><p class='div_bar div_bar_sm '></p></td></tr>
				<tr>
					<td colspan='2'>
					<div class='evcal_data_block_style1'>
						<p class='edb_icon'><img src='<?php echo AJDE_EVCAL_URL ?>/assets/images/backend_post/meetup_icon.png'/></p>
						
						<p class='evcal_db_data'>
						<?php
							if(!empty($ev_vals["evcal_meetup_ev_id"]) ){
								echo "<span id='evcal_mu2'>Currently Connected to <b id='evcal_002'>".$ev_vals["evcal_meetup_ev_id"][0]."</b><br/></span>";
								$html_mu2 = "  <input type='button' class='button' id='evcal_meetup_btn_dis' value='Disconnect this'/>";
							}else{
								$html_mu2 ='';
							}
							$html_mu1 = 'Connect to Meetup Event';
						?>	
						<input type='button' class='button' id='evcal_meetup_btn' value='<?php echo $html_mu1?>'/><?php echo $html_mu2?></p>
						
						<input type='hidden' name='evcal_meetup_data_set' id='evcal_meetup_ev_d1' value='<?php echo (!empty($ev_vals["evcal_meetup_data_set"]))? $ev_vals["evcal_meetup_data_set"][0]: null; ?>'/>
						<input type='hidden' name='evcal_meetup_ev_id' id='evcal_meetup_ev_d2' value='<?php echo (!empty($ev_vals["evcal_meetup_ev_id"]))? $ev_vals["evcal_meetup_ev_id"][0]: null; ?>'/>
					</div>	
					</td>
				</tr>
				
				
				<tr id='evcal_meetup_data' style='display:none'><td colspan='2'>
					<div class='evcal_row_dark' >
						<p id='evcal_meetup_msg' class='event_api_msg' style='display:none'>Message</p>
						<div class='col50'>
							<p><input type='text' id='evcal_meetup_ev_id' value='' style='width:100%'/></p>
							<p class='legend_mf'>Enter Meetup Event ID</p>
						</div>
						<div class='col50'>
							<div class='padl20'>
								<p><input id='evcal_meetup_btn_2' style='margin-left:10px'type='button' class='button' value='Get Event Data from Meetup'/></p>
							</div>
						</div>			
						
						<p class='clear'></p>					
						<p class='divider'></p>					
						<div id='evcal_meetup_s1' style='display:none'>
							<h5 class='mu_ev_id'>Retrived Event Data for: <b id='evcal_001'>321786</b></h5>
							<p class='legend_mf'>Click on each meetup event data section to populate this event with meetup event information.</p>						
							<div id='evcal_meetup_data_tb'></div>
						</div>
					</div>
				</td></tr>
			<?php endif; ?>
			
			<?php
				// PAYPAL
				if($evcal_opt1['evcal_paypal_pay']=='yes'):
			?>
			<tr>
				<td colspan='2' class='evo_thirdparty_table_td'>
					<div class='evo_thirdparty_section_header evcal_data_block_style1'>
						<p class='edb_icon'><img src='<?php echo AJDE_EVCAL_URL ?>/assets/images/backend_post/evcal_pp.png'/></p>
						<p class='evcal_db_data'>Paypal Buy Now button</p>
					</div>	
					<p class='evo_thirdparty'><label for='evcal_paypal_text'><?php _e('Text to show above buy now button')?></label><br/>			
						<input type='text' id='evcal_paypal_text' name='evcal_paypal_text' value='<?php echo (!empty($ev_vals["evcal_paypal_text"]) )? $ev_vals["evcal_paypal_text"][0]:null?>' style='width:100%'/>
					</p>
					<p class='evo_thirdparty'><label for='evcal_paypal_item_price'><?php _e('Enter the price for paypal buy now button <i>eg. 23.99</i>')?><?php $ajde->wp_admin->tooltips('Type the price without currency symbol to create a buy now button for this event. This will show on front-end calendar for this event','',true);?></label><br/>			
						<input placeholder='eg. 29.99' type='text' id='evcal_paypal_item_price' name='evcal_paypal_item_price' value='<?php echo (!empty($ev_vals["evcal_paypal_item_price"]) )? $ev_vals["evcal_paypal_item_price"][0]:null?>' style='width:100%'/>
					</p>			
				</td>			
			</tr>
			<?php endif;?>
			</table>
		<?php

	}
	
	
/** Save the Event data meta box. **/
	function eventon_save_meta_data($post_id, $post){
		if($post->post_type!='ajde_events')
			return;
			
		// Stop WP from clearing custom fields on autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		// Prevent quick edit from clearing custom fields
		if (defined('DOING_AJAX') && DOING_AJAX)
			return;

		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if( isset($_POST['evo_noncename']) ){
			if ( !wp_verify_nonce( $_POST['evo_noncename'], plugin_basename( __FILE__ ) ) ){
				return;
			}
		}
		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;	

		global $pagenow;
		$_allowed = array( 'post-new.php', 'post.php' );
		if(!in_array($pagenow, $_allowed)) return;
		
		
		// $_POST FIELDS array
			$fields_ar =apply_filters('eventon_event_metafields', array(
				'evcal_allday','evcal_event_color','evcal_event_color_n',
				'evcal_location','evcal_location_name','evo_location_tax','evo_loc_img','evo_org_img','evcal_name_over_img',
				'evcal_organizer','evcal_org_contact','evcal_org_img',
				'evcal_exlink','evcal_lmlink','evcal_subtitle',
				'evcal_gmap_gen','evcal_mu_id','evcal_paypal_item_price','evcal_paypal_text',
				'evcal_eventb_data_set','evcal_evb_id','evcal_eventb_url','evcal_eventb_capacity','evcal_eventb_tprice',
				'evcal_meetup_data_set','evcal_meetup_url','evcal_meetup_ev_id',
				'evcal_repeat','evcal_rep_freq','evcal_rep_gap','evcal_rep_num',
				'evp_repeat_rb','evo_repeat_wom','evo_rep_WK',
				'evcal_lmlink_target','_evcal_exlink_target','_evcal_exlink_option',
				'evo_hide_endtime','evo_span_hidden_end','evo_year_long','event_year',
				'evo_evcrd_field_org','evo_event_timezone',

				'evo_exclude_ev',
				'_featured',
				'_cancel','_cancel_reason',
				
				'evcal_lat','evcal_lon',
			));

		// append custom fields based on activated number
			$evcal_opt1= get_option('evcal_options_evcal_1');
			$num = evo_calculate_cmd_count($evcal_opt1);
			for($x =1; $x<=$num; $x++){	
				if(eventon_is_custom_meta_field_good($x)){
					$fields_ar[]= '_evcal_ec_f'.$x.'a1_cus';
					$fields_ar[]= '_evcal_ec_f'.$x.'a1_cusL';
					$fields_ar[]= '_evcal_ec_f'.$x.'_onw';
				}
			}
					
		// field names that pertains only to event date information
			$fields_sub_ar = apply_filters('eventon_event_date_metafields', array(
				'evcal_start_date','evcal_end_date', 'evcal_start_time_hour','evcal_start_time_min','evcal_st_ampm',
				'evcal_end_time_hour','evcal_end_time_min','evcal_et_ampm','evcal_allday'
				)
			);
			
		
		// DATE and TIME data
			$date_POST_values='';
			foreach($fields_sub_ar as $ff){
				
				// end date value fix for -- hide end date
				if($ff=='evcal_end_date' && !empty($_POST['evo_hide_endtime']) && $_POST['evo_hide_endtime']=='yes'){

					if($_POST['evo_span_hidden_end'] && $_POST['evo_span_hidden_end']=='yes'){
						$date_POST_values['evcal_end_date']=$_POST['evcal_end_date'];
					}else{
						$date_POST_values['evcal_end_date']=$_POST['evcal_start_date'];
					}
					//$date_POST_values['evcal_end_date']=$_POST['evcal_end_date'];
					
				}else{
					if(!empty($_POST[$ff]))
						$date_POST_values[$ff]=$_POST[$ff];
				}
				// remove these values from previously saved
				delete_post_meta($post_id, $ff);
			}
		
		// convert the post times into proper unix time stamps
			if(!empty($_POST['_evo_date_format']) && !empty($_POST['_evo_time_format']))
				$proper_time = eventon_get_unix_time($date_POST_values, $_POST['_evo_date_format'], $_POST['_evo_time_format']);		

		// if Repeating event save repeating intervals
			if( eventon_is_good_repeat_data() && !empty($proper_time['unix_start']) ){

				$unix_E = (!empty($proper_time['unix_end']))? $proper_time['unix_end']: $proper_time['unix_start'];
				$repeat_intervals = eventon_get_repeat_intervals($proper_time['unix_start'], $unix_E);

				// save repeat interval array as post meta
				if ( !empty($repeat_intervals) ){
					asort($repeat_intervals);
					update_post_meta( $post_id, 'repeat_intervals', $repeat_intervals);
				}
			}

			//update_post_meta($post_id, 'aaa', $_POST['repeat_intervals']);

		// run through all the custom meta fields
			foreach($fields_ar as $f_val){
				
				if(!empty ($_POST[$f_val])){
					
					$post_value = ( $_POST[$f_val]);
					update_post_meta( $post_id, $f_val,$post_value);		
					
				}else{
					if(defined('DOING_AUTOSAVE') && !DOING_AUTOSAVE){
						// if the meta value is set to empty, then delete that meta value
						delete_post_meta($post_id, $f_val);
					}
					delete_post_meta($post_id, $f_val);
				}
				
			}
					
		// full time converted to unix time stamp
			if ( !empty($proper_time['unix_start']) )
				update_post_meta( $post_id, 'evcal_srow', $proper_time['unix_start']);
			
			if ( !empty($proper_time['unix_end']) )
				update_post_meta( $post_id, 'evcal_erow', $proper_time['unix_end']);

		// save event year if not set
			if( (empty($_POST['event_year']) && !empty($proper_time['unix_start'])) || 
				(!empty($_POST['event_year']) &&
					$_POST['event_year']=='yes')
			){
				$year = date('Y', $proper_time['unix_start']);
				update_post_meta( $post_id, 'event_year', $year);
			}
				
		//set event color code to 1 for none select colors
			if ( !isset( $_POST['evcal_event_color_n'] ) )
				update_post_meta( $post_id, 'evcal_event_color_n',1);
							
		// save featured event data default value no
			$_featured = get_post_meta($post_id, '_featured',true);
			if(empty( $_featured) )
				update_post_meta( $post_id, '_featured','no');
		
		// LOCATION as taxonomy
			// if location name is choosen from the list
			if(isset($_POST['evcal_location_name_select'], $_POST['evcal_location_name']) && $_POST['evcal_location_name_select'] == $_POST['evcal_location_name']){
				
				// 
				if(!empty($_POST['evo_location_tax_id'])){
					$term_name = esc_attr($_POST['evcal_location_name']);
					$term_meta = array();
					$term_meta['location_lon'] = (isset($_POST['evcal_lon']))?$_POST['evcal_lon']:null;
					$term_meta['location_lat'] = (isset($_POST['evcal_lat']))?$_POST['evcal_lat']:null;
					$term_meta['location_address'] = (isset($_POST['evcal_location']))?$_POST['evcal_location']:null;
					$term_meta['evo_loc_img'] = (isset($_POST['evo_loc_img']))?$_POST['evo_loc_img']:null;
					update_option("taxonomy_".$_POST['evo_location_tax_id'], $term_meta);
					wp_set_post_terms( $post_id, $term_name, 'event_location');	
				}
				
			}elseif(isset($_POST['evcal_location_name'])){
			// create new taxonomy from new values

				$term_name = esc_attr($_POST['evcal_location_name']);
				$term_slug = str_replace(" ", "-", $term_name);

				// create wp term
				$new_term_ = wp_insert_term( $term_name, 'event_location', array('slug'=>$term_slug) );

				if(!is_wp_error($new_term_)){
					$term_meta = array();
					$term_meta['location_lon'] = (isset($_POST['evcal_lon']))? $_POST['evcal_lon']:null;
					$term_meta['location_lat'] = (isset($_POST['evcal_lat']))?$_POST['evcal_lat']:null;
					$term_meta['location_address'] = (isset($_POST['evcal_location']))?$_POST['evcal_location']:null;
					$term_meta['evo_loc_img'] = (isset($_POST['evo_loc_img']))?$_POST['evo_loc_img']:null;
					update_option("taxonomy_".$new_term_['term_id'], $term_meta);
					wp_set_post_terms( $post_id, $term_name, 'event_location');					
				}						
			}

		// ORGANIZER as taxonomy
			// Selected value from list - update other values
			if(isset($_POST['evcal_organizer_name_select'], $_POST['evcal_organizer']) && $_POST['evcal_organizer_name_select'] == $_POST['evcal_organizer']){

				if(!empty($_POST['evo_organizer_tax_id'])){
					$term_name = esc_attr($_POST['evcal_organizer']);
					$term_meta = array();
					$term_meta['evcal_org_contact'] = (isset($_POST['evcal_org_contact']))?
						str_replace('"', "'", $_POST['evcal_org_contact']):null;
					$term_meta['evo_org_img'] = (isset($_POST['evo_org_img']))?$_POST['evo_org_img']:null;;
					update_option("taxonomy_".$_POST['evo_organizer_tax_id'], $term_meta);
					wp_set_post_terms( $post_id, $term_name, 'event_organizer');
				}
			}elseif(isset($_POST['evcal_organizer'])){
			// create new taxonomy from new values

				$term_name = esc_attr($_POST['evcal_organizer']);
				$term_slug = str_replace(" ", "-", $term_name);

				// create wp term
				$new_term_ = wp_insert_term( $term_name, 'event_organizer', array('slug'=>$term_slug) );

				if(!is_wp_error($new_term_)){
					$term_meta = array();
					$term_meta['evcal_org_contact'] = (isset($_POST['evcal_org_contact']))?
						str_replace('"', "'", $_POST['evcal_org_contact']):null;
					$term_meta['evo_org_img'] = (isset($_POST['evo_org_img']))?$_POST['evo_org_img']:null;;
					update_option("taxonomy_".$new_term_['term_id'], $term_meta);

					wp_set_post_terms( $post_id, $term_name, 'event_organizer');
				}				
			}
		
		// (---) hook for addons
		do_action('eventon_save_meta', $fields_ar, $post_id);

		// save user closed meta field boxes
		if(!empty($_POST['evo_collapse_meta_boxes']))
			eventon_save_collapse_metaboxes($post_id, $_POST['evo_collapse_meta_boxes'],true );
			
	}