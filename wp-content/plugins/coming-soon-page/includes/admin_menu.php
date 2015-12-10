<?php 

/*############  Admin Menu Class for Coming Soon  ################*/

class coming_soon_admin_menu{
	
	private $menu_name;
	private $databese_parametrs;
	private $plugin_url;
	private $text_parametrs;

	function __construct($param){
		
		$this->text_parametrs=array(
			'parametrs_sucsses_saved'=>'Successfully saved.',
			'error_in_saving'=>'can\'t save "%s" plugin parameter<br>',
			'missing_title'=>'Type Message Title',
			'missing_fromname'=>'Type From Name',
			'missing_frommail'=>'Type From mail',
			'mising_massage'=>'Type Message',
			'sucsses_mailed'=>'Your message was sent successfully.',
			'error_maied'=>'error sending email',
			'authorize_problem' => 'Authorization Problem'
			
		);		
		
		$this->menu_name=$param['menu_name'];
		$this->databese_parametrs=$param['databese_parametrs'];
		if(isset($params['plugin_url']))
			$this->plugin_url=$params['plugin_url'];
		else
			$this->plugin_url=trailingslashit(dirname(plugins_url('',__FILE__)));

		add_action( 'wp_ajax_coming_soon_page_save', array($this,'save_in_databese') );
		add_action( 'wp_ajax_coming_soon_send_mail', array($this,'sending_mail') );
	}
	
	public function create_menu(){
		$main_page 	 		 = add_menu_page( $this->menu_name, $this->menu_name, 'manage_options', str_replace( ' ', '-', $this->menu_name), array($this, 'main_menu_function'),$this->plugin_url.'images/menu_icon.png');
		$page_coming_soon	  =	add_submenu_page($this->menu_name,  $this->menu_name,  $this->menu_name, 'manage_options', str_replace( ' ', '-', $this->menu_name), array($this, 'main_menu_function'));
		$page_coming_soon	  = add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Subscribers', 'Subscribers', 'manage_options', 'mailing-list-subscribers', array($this, 'mailing_list'));
		$page_featured	 	  = add_submenu_page( str_replace( ' ', '-', $this->menu_name), 'Featured Plugins', 'Featured Plugins', 'manage_options', 'coming-soon-featured-plugins', array($this, 'featured_plugins'));
		add_action('admin_print_styles-' .$main_page, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_coming_soon, array($this,'menu_requeried_scripts'));	
		add_action('admin_print_styles-' .$page_featured, array($this,'menu_requeried_scripts'));	
	}
	
	public function menu_requeried_scripts(){
		wp_enqueue_script('wp-color-picker');		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('angularejs');
		wp_enqueue_script( 'jquery-ui-datepicker' ); 
		wp_enqueue_script( 'coming-soon-script-admin' ); 
		wp_enqueue_style('jquery-ui-style');
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_style('coming-soon-admin-style');
		
		
		if (function_exists('add_thickbox')) add_thickbox();
	}
	
	private function generete_parametrs($page_name){
		$page_parametrs=array();
		if(isset($this->databese_parametrs[$page_name])){
			foreach($this->databese_parametrs[$page_name] as $key => $value){
				$page_parametrs[$key]=get_option($key,$value);
			}
			return $page_parametrs;
		}
		return NULL;
		
	}
	
	public function save_in_databese(){
		$kk=1;	
			
		if(isset($_POST['coming_soon_options_nonce']) && wp_verify_nonce( $_POST['coming_soon_options_nonce'],'coming_soon_options_nonce')){
			foreach($this->databese_parametrs[$_POST['curent_page']] as $key => $value){
				if(isset($_POST[$key]))
					update_option($key,$_POST[$key]);
				else{
					$kk=0;
					printf($this->text_parametrs['error_in_saving'],$key);
				}
			}	
		}
		else{		
			die($this->text_parametrs['authorize_problem']);
		}
		if($kk==0){
			exit;
		}
		die($this->text_parametrs['parametrs_sucsses_saved']);
	}
	
	public function main_menu_function(){	
	$enable_disable=$this->generete_parametrs('general_save_parametr');	
	$enable_disable=$enable_disable['coming_soon_page_mode'];
		?>
        <script>
        var coming_soon_ajaxurl="<?php echo admin_url( 'admin-ajax.php'); ?>";
		var comig_soon_plugin_url="<?php echo $this->plugin_url; ?>";
		var comin_soon_parametrs_sucsses_saved="<?php echo $this->text_parametrs['parametrs_sucsses_saved'] ?>";
		var comin_soon_all_parametrs = <?php echo json_encode($this->databese_parametrs); ?>;
        </script>
      <div class="coming_title"><h1>Coming Soon And Maintenance Mode <a style="text-decoration:none;" href="http://wpdevart.com/wordpress-coming-soon-plugin/"><span style="color: rgba(10, 154, 62, 1);"> (Upgrade to Pro Version)</span></a></h1></div>      
      <div id="coming_soon_enable" class="field switch">
		<label for="radio1" class="cb-enable <?php if($enable_disable=='on') echo 'selected'; ?>"><span>Enable</span></label>
		<label for="radio2" class="cb-disable <?php if($enable_disable=='off') echo 'selected'; ?>"><span>Disable</span></label>
        <span class="progress_enable_disable_buttons"><span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span><span class="error_massage"></span></span>
         <div style="clear:both">  </div>
	  </div>
	<br>
     
       <div class="wp-table right_margin">
        <table class="wp-list-table widefat fixed posts">
        	<thead>
                <tr>
                    <th>     
                     <h4 class="live_previev">Parameters <a target="_blank" href="<?php echo site_url(); ?>/?special_variable_for_live_previev=sdfg564sfdh645fds4ghs515vsr5g48strh846sd6g41513btsd">(live Preview)</a></h4>              
                   <span class="save_all_paramss"> <button type="button" id="save_all_parametrs" class="save_all_section_parametrs button button-primary"><span class="save_button_span">Save All Sections</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button></span>
                    </th>
                </tr>
         	</thead>
            <tbody>
            <tr>
            	<td>
                <div id="coming_soon_page">
    				<div class="left_sections">
						<?php
                       		$this->generete_logo_section($this->generete_parametrs('coming_logo'));
							$this->generete_title_section($this->generete_parametrs('coming_title'));
							$this->generete_message_section($this->generete_parametrs('coming_message'));
							$this->generete_countdown_section($this->generete_parametrs('coming_countdown'));	
							$this->generete_progressbar_section($this->generete_parametrs('coming_progressbar'));
							$this->generete_subscribe_section($this->generete_parametrs('coming_subscribe'));
							$this->generete_social_network_section($this->generete_parametrs('coming_social_networks'));	
							$this->generete_link_to_tashboard_section($this->generete_parametrs('coming_link_to_dashboard'));						
                       	?>
                     </div>
    				 <div class="right_sections">
                     <?php
					 		$this->generete_content_section($this->generete_parametrs('coming_content'));
							$this->generete_background_section($this->generete_parametrs('coming_background'));
							$this->generete_except_section($this->generete_parametrs('except_page'));
                       		$this->generete_search_engine_section($this->generete_parametrs('search_engine_and_favicon'));
                     ?>
                  </div><div style="clear:both"></div>
               </td>
       		</tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>                   
                    	<span class="save_all_paramss"><button type="button" id="save_all_parametrs" class="save_all_section_parametrs button button-primary"><span class="save_button_span">Save All Sections</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button></span>
                    </th>
                </tr>
         	</tfoot>
        </table>
        </div>      
       <?php
	  wp_nonce_field('coming_soon_options_nonce','coming_soon_options_nonce');
	}
	
	
	/*#########################  LOGO   #################################*/
	public function generete_logo_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/logo.png' ?>"></span>
				<span class="title_parametrs_group">Logo</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide logo <span title="Choose to show or hide your logo from Coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select id="coming_soon_page_logo_enable">
                                <option <?php selected($page_parametrs['coming_soon_page_logo_enable'],'1') ?> value="1">Show</option>
                                <option <?php selected($page_parametrs['coming_soon_page_logo_enable'],'0') ?> value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                	<tr>
						<td>
							Logo <span title="Click 'Upload' button to upload your logo." class="desription_class">?</span>
						</td>
						<td>
                            <input type="text"  class="upload" id="coming_soon_page_page_logo" name="coming_soon_page_page_logo"  value="<?php echo $page_parametrs['coming_soon_page_page_logo'] ?>"/>
                            <input class="upload-button button" type="button" value="Upload"/>	
                         </td>                
					</tr>                            
					<tr>
						<td>
							Logo position<span class="pro_feature"> (pro)</span> <span title="Here you can choose your logo position(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_logo_in_content_position">
                                <option  value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option  value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here your logo distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_logo_top_distance"  id="coming_soon_page_logo_top_distance" value="10">(Px)
						</td>                
					</tr>
					
                    <tr>
						<td>
							Logo max width<span class="pro_feature"> (pro)</span> <span title="Type here your logo maximum width." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_logo_max_width"   id="coming_soon_page_logo_max_width" value="">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Logo max height<span class="pro_feature"> (pro)</span> <span title="Type here your logo maximum height." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_logo_max_height"   id="coming_soon_page_logo_max_height" value="210">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Logo Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for your logo." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_logo_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Logo animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_logo_animation_after_time"  id="coming_soon_page_logo_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_logo" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Title   #################################*/
	public function generete_title_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/title.png' ?>"></span>
				<span class="title_parametrs_group">Title</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide title <span title="Choose to show or hide your Title from Coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select id="coming_soon_page_title_enable">
                                <option <?php selected($page_parametrs['coming_soon_page_title_enable'],'1') ?> value="1">Show</option>
                                <option <?php selected($page_parametrs['coming_soon_page_title_enable'],'0') ?> value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                	<tr>
						<td>
							Title <span title="Type here coming soon page Title." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_page_title" id="coming_soon_page_page_title" value="<?php echo $page_parametrs['coming_soon_page_page_title'] ?>"> 
                        </td>                
					</tr> 
                    <tr >
                        <td>
                            Title color<span class="pro_feature"> (pro)</span> <span title="Select the title color." class="desription_class">?</span>
                        </td>
                        <td>
                            <div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>
                         </td>                
                    </tr>
                     <tr>
						<td>
							Title Font Size<span class="pro_feature"> (pro)</span> <span title="Type here your coming soon page title font size." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" class="pro_input" name="coming_soon_page_page_title_font_size" id="coming_soon_page_page_title_font_size" value="55">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Title Font family<span class="pro_feature"> (pro)</span> <span title="Choose font family for title." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_font('coming_soon_page_page_title_font','Times New Roman,Times,Georgia,serif') ?>
						</td>                
					</tr>                           
					<tr>
						<td>
							Title position<span class="pro_feature"> (pro)</span> <span title="Choose your coming soon page Title position(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_title_in_content_position">
                                <option  value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here Title field distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_title_top_distance" id="coming_soon_page_title_top_distance" value="10">(Px)
						</td>                
					</tr>
					<tr>
						<td>
							Title Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for Title." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_title_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Title animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_title_animation_after_time"  id="coming_soon_page_title_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_title" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  MESSAGE   #################################*/
	public function generete_message_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/message.png' ?>"></span>
				<span class="title_parametrs_group">Message</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide Message <span title="Choose to show or hide Message box from Coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select id="coming_soon_page_message_enable">
                                <option <?php selected($page_parametrs['coming_soon_page_message_enable'],'1') ?> value="1">Show</option>
                                <option <?php selected($page_parametrs['coming_soon_page_message_enable'],'0') ?> value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                	<tr>
						<td colspan="2">
							<b>Message</b>
                             <div style="width:100%"> <?php wp_editor( stripslashes($page_parametrs['coming_soon_page_page_message']), 'coming_soon_page_page_message', $settings = array('media_buttons'=>false,'textarea_rows'=>5) ); ?></div>
						</td>
						               
					</tr>                            
					<tr>
						<td>
							Message position<span class="pro_feature"> (pro)</span> <span title="Choose position for Message box(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_message_in_content_position">
                                <option  value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here Message box distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_message_top_distance"  id="coming_soon_page_message_top_distance" value="10">(Px)
						</td>                
					</tr>
					<tr>
						<td>
							 Message Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for Message box." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_message_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Message box animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_message_animation_after_time"  id="coming_soon_page_message_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>                   
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_message" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Countdown   #################################*/
	public function generete_countdown_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/timer.png' ?>"></span>
				<span class="title_parametrs_group">Countdown <span class="pro_feature_label"> (Pro feature!)</span></span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide Countdown<span class="pro_feature"> (pro)</span> <span title="Choose to show or hide Countdown on Coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="coming_soon_page_countdown_enable">
                                <option  value="1">Show</option>
                                <option selected="selected" value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                     <tr>
						<td>
							Text for day field<span class="pro_feature"> (pro)</span> <span title="Type here text for Day field in front-end." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_countdown_days_text"  id="coming_soon_page_countdown_days_text" value="day">
						</td>                
					</tr>
                     <tr>
						<td>
							Text for hour field<span class="pro_feature"> (pro)</span> <span title="Type here text for Day field in front-end." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_countdown_hourse_text"   id="coming_soon_page_countdown_hourse_text" value="hour">
						</td>                
					</tr>
                     <tr>
						<td>
							Text for minute field<span class="pro_feature"> (pro)</span> <span title="Type here text for Minute field in front-end." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_countdown_minuts_text"  id="coming_soon_page_countdown_minuts_text" value="minute">
						</td>                
					</tr>
                     <tr>
						<td>
							Text for second field<span class="pro_feature"> (pro)</span> <span title="Type here text for Second field in front-end." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_countdown_seconds_text"   id="coming_soon_page_countdown_seconds_text" value="second">
						</td>                
					</tr>
                	<tr>
						<td>
							Countdown date<span class="pro_feature"> (pro)</span> <span title="Type here the Countdown time(days, hour), then select the Countdown start date." class="desription_class">?</span>
						</td>
						<td style="vertical-align: top !important;">
							
                            <span style="display:inline-block; width:45px;">
                            	<input class="pro_input" type="text" onchange="refresh_countdown()"  placeholder="Day"   id="coming_soon_page_countdownday" size="2" value=""/>
                            	<small style="display:block">Day</small>
                            </span>
                           	<span style="display:inline-block; width:65px;">
                                 <input class="pro_input" type="text"  onchange="refresh_countdown()"  placeholder="Hour" id="coming_soon_page_countdownhour" size="5" value=""/>
                                 <small>Hour</small>
                            </span>
                          	<span style="display:inline-block; width:100px;"> 
                            	<input class="pro_input" type="text"  onchange="refresh_countdown()"  placeholder="Start date"  id="coming_soon_page_countdownstart_day" size="9" value=""/>
                            	<small style="color:red">Start date</small>
                            </span>
                         </td>                
					</tr>                            
					<tr>
						<td>
							<span style="color:red">After Countdown expired</span><span class="pro_feature"> (pro)</span> <span title="Choose what will happens with your coming soon page when Countdown expired(Disable coming soon or only hide Countdown)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_countdownstart_on" >
                                <option value="on">Disable coming soon</option>
                                <option selected="selected" value="off">hide Countdown</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Countdown position<span class="pro_feature"> (pro)</span> <span title="Choose position for countdown(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_countdown_in_content_position">
                                <option value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here countdown distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_countdown_top_distance"  id="coming_soon_page_countdown_top_distance" value="10">(Px)
						</td>                
					</tr>
					
                    <tr>
						<td>
							Countdown Buttons type<span class="pro_feature"> (pro)</span> <span title="Choose the countdown buttons type(button, circle, vertical slider)" class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_countdown_type" class="coming_set_hiddens">
                                <option selected="selected" value="button">button</option>
                                <option  value="circle">Circle</option>
                                <option  value="vertical_slide">Vertical Slider</option>
                        	</select>
                         </td>                
					</tr>
                   
                    <tr class="tr_button tr_circle tr_vertical_slide">
                        <td>
                          Countdown text color<span class="pro_feature"> (pro)</span> <span title="Select the countdown text color." class="desription_class">?</span>
                        </td>
                        <td>
                            <div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>
                         </td>                
                    </tr>
                    <tr class="tr_button tr_circle tr_vertical_slide">
                        <td>
                            Countdown background color<span class="pro_feature"> (pro)</span> <span title="Select the countdown background color." class="desription_class">?</span>
                        </td>
                        <td>
                            <div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(255, 255, 255);"></a></div>
                            </div>
                         </td>                
                    </tr>
                       <tr  class="tr_circle">
						<td>
							Countdown Size<span class="pro_feature"> (pro)</span> <span title="Select the countdown size." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_countdown_circle_size"  id="coming_soon_page_countdown_circle_size" value="120">(Px)
						</td>                
					</tr>
                 
                     <tr  class="tr_circle">
						<td>
							Countdown  border width<span class="pro_feature"> (pro)</span> <span title="Select the countdown border width for circle buttons(only aapears when you choose Countedown circle buttons)." class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="coming_number_slider pro_input" data-max-val="100" data-min-val="0" name="coming_soon_page_countdown_circle_border" value="3" id="coming_soon_page_countdown_circle_border" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                     <tr class="tr_button">
						<td>
							Countdown border radius<span class="pro_feature"> (pro)</span> <span title="Type here the countdown buttons border radius." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="countdown_border_radius"  id="countdown_border_radius" value="15">(Px)
						</td>                
					</tr>
                     <tr  class="tr_button tr_vertical_slide">
						<td>
							Countdown font-size<span class="pro_feature"> (pro)</span> <span title="Type here the countedow text font-size." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="countdown_font_size" id="countdown_font_size" value="35">(Px)
						</td>                
					</tr>
                  
                     <tr  class="tr_button tr_circle tr_vertical_slide">
						<td>
							Countdown Font family<span class="pro_feature"> (pro)</span> <span title="Select the countdown text Font family." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_font('coming_soon_page_countdown_font_famaly','monospace') ?>
						</td>                
					</tr> 
                    <tr>
						<td>
							Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for countdown." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_countdown_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for countdown animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" class="pro_input" name="coming_soon_page_countdown_animation_after_time"  id="coming_soon_page_countdown_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>                   
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_countdown" class="pro_input button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  progress bar   #################################*/
	public function generete_progressbar_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/progressbar.png' ?>"></span>
				<span class="title_parametrs_group">Progress bar<span class="pro_feature_label">  (Pro feature!)</span></span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide Progress bar<span class="pro_feature"> (pro)</span> <span title="Choose to show or hide Progress bar on Coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="coming_soon_page_progressbar_enable">
                                <option  value="1">Show</option>
                                <option selected="selected" value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                	<tr>
						<td>
							Progress bar percentage<span class="pro_feature"> (pro)</span> <span title="Here you can select Progress bar percentage." class="desription_class">?</span>
						</td>
						<td>
                           <input type="text" size="3" class="coming_number_slider pro_input" data-max-val="100" data-min-val="0" name="coming_soon_page_progressbar_initial_pracent" value="25" id="coming_soon_page_progressbar_initial_pracent" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
                         </td>                
					</tr>
                    <tr>
						<td>
							 Width<span class="pro_feature"> (pro)</span> <span title="Type here Progress bar width." class="desription_class">?</span>
						</td>
						<td>
                           <input type="text" size="3" class="coming_number_slider pro_input" data-max-val="100" data-min-val="0" name="coming_soon_page_progressbar_width" value="100" id="coming_soon_page_progressbar_width" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
                         </td>                
					</tr>
                    <tr>
						<td>
							Progress bar position<span class="pro_feature"> (pro)</span> <span title="Choose position for Progress bar(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_progressbar_in_content_position">
                                <option value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here Progress bar distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_progressbar_top_distance"   id="coming_soon_page_progressbar_top_distance" value="10">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Progress bar load color<span class="pro_feature"> (pro)</span> <span title="Select progress bar load color." class="desription_class">?</span>
						</td>
						<td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(255, 255, 255);"></a></div>
                            </div>						</td>                
					</tr>
                    <tr>
						<td>
							Border color<span class="pro_feature"> (pro)</span> <span title="Select Progress bar border color." class="desription_class">?</span>
						</td>
						<td>
                          	<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>
                        </td>                
					</tr>                           
					<tr>
						<td>
							Border width<span class="pro_feature"> (pro)</span> <span title="Choose progress bar border width." class="desription_class">?</span>
						</td>
						<td>
                          	<input class="pro_input" type="text" name="coming_soon_page_progressbar_border_width" id="coming_soon_page_progressbar_border_width" value="3">(Px)
                        </td>                
					</tr>
                    <tr>
						<td>
							Border radius<span class="pro_feature"> (pro)</span> <span title="Choose progress bar border radius." class="desription_class">?</span>
						</td>
						<td>
                          	<input class="pro_input" type="text" name="coming_soon_page_progressbar_border_radius"  id="coming_soon_page_progressbar_border_radius" value="15">(Px)
                        </td>                
					</tr>
                                        
                    <tr>
						<td>
							Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for Progress bar." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_progressbar_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Progress bar animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_progressbar_animation_after_time"  id="coming_soon_page_progressbar_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>
					
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_progressbar" class="pro_input button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Subscribe   #################################*/
	public function generete_subscribe_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/subscribe.png' ?>"></span>
				<span class="title_parametrs_group">Subscribe form (Mailing list)<span class="pro_feature_label">  (Pro feature!)</span></span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide the Form<span class="pro_feature"> (pro)</span> <span title="Choose to show or hide Subscribe Form on Coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="enable_mailing_list">
                                <option value="on">Show</option>
                                <option selected="selected" value="off">Hide</option>
                        	</select>
						</td>                
					</tr>
                    <tr>
						<td>
							User First name text<span class="pro_feature"> (pro)</span> <span title="Type here text for user first name field." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_firstname"   id="coming_soon_page_subscribe_firstname" value="First name">
						</td>                
					</tr>
                    <tr>
						<td>
							User  Last name text<span class="pro_feature"> (pro)</span> <span title="Type here text for user last name field." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_lastname"   id="coming_soon_page_subscribe_lastname" value="Last name">
						</td>                
					</tr>
                	<tr>
						<td>
							Email field text<span class="pro_feature"> (pro)</span> <span title="Type here text for email field." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="mailing_list_value_of_emptyt"   id="mailing_list_value_of_emptyt" value="Email">
						</td>                
					</tr>
                    <tr>
						<td>
							Send button text<span class="pro_feature"> (pro)</span> <span title="Type here the Send button text." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="mailing_list_button_value"  id="mailing_list_button_value" value="Subscribe">
						</td>                
					</tr>
                     <tr>
						<td>
							Success email text<span class="pro_feature"> (pro)</span> <span title="Type here the text that will appear after users submit the correct email." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_after_text_sucsess"  id="coming_soon_page_subscribe_after_text_sucsess" value="You Have Been Successfully Subscribed!">
						</td>                
					</tr>
                     <tr>
						<td>
							Existing email text<span class="pro_feature"> (pro)</span> <span title="Type here the text that will appear after users type already submitted email." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_after_text_alredy_exsist"  id="coming_soon_page_subscribe_after_text_alredy_exsist" value="You're Already Subscribed!">
						</td>                
					</tr>
                     <tr>
						<td>
							Blank email field text<span class="pro_feature"> (pro)</span> <span title="Type here the text that will appear after users submit a blank field. " class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_after_text_none"  id="coming_soon_page_subscribe_after_text_none" value="Please Type Your Email">
						</td>                
					</tr>
                     <tr>
						<td>
							Invalid email text<span class="pro_feature"> (pro)</span> <span title="Type here the text that will appear after users submit invalid email." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_after_text_invalid"  id="coming_soon_page_subscribe_after_text_invalid" value="Email Doesn't Exist">
						</td>                
					</tr>
                                         
					<tr>
						<td>
							Subscribe Form position<span class="pro_feature"> (pro)</span> <span title="Choose position for Subscribe Form(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_subscribe_in_content_position">
                                <option value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here Subscribe Form distance from top. " class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_top_distance"   id="coming_soon_page_subscribe_top_distance" value="10">(Px)
						</td>                
					</tr>
                    </tr>
                     <tr>
						<td>
							Font Size<span class="pro_feature"> (pro)</span> <span title="Type here font size for all texts in Subscribe Form." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="sendmail_input_font_size"  id="sendmail_input_font_size" value="14">(Px)
						</td>                
					</tr>
                     <tr>
						<td>
							Email field border radius<span class="pro_feature"> (pro)</span> <span title="Type here border radius for email field." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_button_radius"  id="coming_soon_page_subscribe_button_radius" value="0">(Px)
						</td>                
					</tr>
                     <tr>
						<td>
							 Input max width<span class="pro_feature"> (pro)</span> <span title="Type here max with for input field." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_input_max_width"  id="coming_soon_page_subscribe_input_max_width" value="350">(Px)
						</td>                
					</tr>
                    
                     <tr>
						<td>
							Font family<span class="pro_feature"> (pro)</span> <span title="Type here font family for all texts in Subscribe Form." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_font('coming_soon_page_subscribe_font_famely','monospace') ?>
						</td>                
					</tr>
                    <tr>
						<td>
							Input field border color<span class="pro_feature"> (pro)</span> <span title="Select the input field border color." class="desription_class">?</span>
						</td>
						<td>
                          	<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(255, 255, 255);"></a></div>
                            </div>
                        </td>                
					</tr>
                    <tr>
						<td>
							Placeholder text color<span class="pro_feature"> (pro)</span> <span title="Select default text color for input fields. " class="desription_class">?</span>
						</td>
						<td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>                        </td>                
					</tr>
                    <tr>
						<td>
							Send button bg color<span class="pro_feature"> (pro)</span> <span title="Select the send button background color." class="desription_class">?</span>
						</td>
						<td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>                        </td>                
					</tr> 
                    <tr>
						<td>
							Send button text color<span class="pro_feature"> (pro)</span> <span title="Select the send button text color." class="desription_class">?</span>
						</td>
						<td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(255, 255, 255);"></a></div>
                            </div>                        </td>                
					</tr>
                    
                    <tr>
						<td>
							Input field text color<span class="pro_feature"> (pro)</span> <span title="Select the input field text color." class="desription_class">?</span>
						</td>
						<td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(255, 255, 255);"></a></div>
                            </div>                        </td>                
					</tr>
                    <tr>
						<td>
							After submit text color<span class="pro_feature"> (pro)</span> <span title="Select color of the text, that will apear after submit." class="desription_class">?</span>
						</td>
						<td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>                        
                         </td>                
					</tr> 
					<tr>
						<td>
							Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for Subscribe Form." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_subscribe_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Subscribe Form animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_subscribe_animation_after_time"  id="coming_soon_page_subscribe_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>
                    
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_subscribe" class="pro_input button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Socials Buttons   #################################*/
	public function generete_social_network_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/social_network.png' ?>"></span>
				<span class="title_parametrs_group">Socials buttons</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide social buttons <span title="Choose to show or hide social buttons on coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<select id="coming_soon_page_socialis_enable">
                                <option <?php selected($page_parametrs['coming_soon_page_socialis_enable'],'1') ?> value="1">Show</option>
                                <option <?php selected($page_parametrs['coming_soon_page_socialis_enable'],'0') ?> value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                    <tr>
						<td>
							Open in new tab <span title="If you want to open a social page in new tab enable this option" class="desription_class">?</span>
						</td>
						<td>
							<select id="coming_soon_page_open_new_tabe">
                                <option <?php selected($page_parametrs['coming_soon_page_open_new_tabe'],'1') ?> value="1">Enable</option>
                                <option <?php selected($page_parametrs['coming_soon_page_open_new_tabe'],'0') ?> value="0">Disable</option>
                        	</select>
						</td>                
					</tr>
                	<tr>
						<td>
							Facebook url <span title="Type here Facebook url." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_facebook"  id="coming_soon_page_facebook" value="<?php echo $page_parametrs['coming_soon_page_facebook'] ?>">
						</td>                
					</tr>   
                    <tr>
                        <td>
                            Facebook img url<span class="pro_feature"> (pro)</span>	<span title="Type here Facebook icon url or upload it." class="desription_class">?</span>
                        </td>
                        <td>
                          <input class="pro_input" type="text"   class="upload" id="social_facbook_bacground_image" name="social_facbook_bacground_image"  value=""/>
                          <input class="button pro_input" type="button" value="Upload"/>	
                         </td>                
                    </tr> 
                    <tr>
						<td>
							Twitter url <span title="Type here Twitter url." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_twitter"  id="coming_soon_page_twitter" value="<?php echo $page_parametrs['coming_soon_page_twitter'] ?>">
						</td>                
					</tr>   
                    <tr>
                        <td>
                            Twitter img url<span class="pro_feature"> (pro)</span>	<span title="Type here Twitter icon url or upload it." class="desription_class">?</span>
                        </td>
                        <td>
                          <input type="text" class="pro_input"  class="upload" id="social_twiter_bacground_image" name="social_twiter_bacground_image"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>	
                         </td>                
                    </tr> 
                    <tr>
						<td>
							Google Plus url <span title="Type here Google Plus url." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_google_plus"  id="coming_soon_page_google_plus" value="<?php echo $page_parametrs['coming_soon_page_google_plus'] ?>">
						</td>                
					</tr>   
                    <tr>
                        <td>
                            Google Plus img url<span class="pro_feature"> (pro)</span>	<span title="Type here Google Plus icon url or upload it." class="desription_class">?</span>
                        </td>
                        <td>
                          <input type="text" class="pro_input"  class="upload" id="social_google_bacground_image" name="social_google_bacground_image"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>	
                         </td>                
                    </tr> 
                    <tr>
						<td>
							YouTube url <span title="Type here YouTube url." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_youtube"  id="coming_soon_page_youtube" value="<?php echo $page_parametrs['coming_soon_page_youtube'] ?>">
						</td>                
					</tr>   
                    <tr>
                        <td>
                            YouTube img url<span class="pro_feature"> (pro)</span>	<span title="Type here YouTube icon url or upload it." class="desription_class">?</span>
                        </td>
                        <td>
                          <input type="text" class="pro_input"  class="upload" id="social_youtobe_bacground_image" name="social_youtobe_bacground_image"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>	
                         </td>                
                    </tr> 
                    <tr>
						<td>
							Instagram url <span title="Type here Instagram url." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_instagram"  id="coming_soon_page_instagram" value="<?php echo $page_parametrs['coming_soon_page_instagram'] ?>">
						</td>                
					</tr>   
                    <tr>
                        <td>
                            Instagram img url<span class="pro_feature"> (pro)</span>	<span title="Type here Instagram icon url or upload it." class="desription_class">?</span>
                        </td>
                        <td>
                          <input type="text" class="pro_input"  class="upload" id="social_instagram_bacground_image" name="social_instagram_bacground_image"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>	
                         </td>                
                    </tr>                           
					<tr>
						<td>
							Social buttons position<span class="pro_feature"> (pro)</span> <span title="Choose position for Social buttons(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select class="pro_select" id="coming_soon_page_socialis_in_content_position">
                                <option value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here Social buttons distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_socialis_top_distance"  placeholder="Enter Distance" id="coming_soon_page_socialis_top_distance" value="10">(Px)
						</td>                
					</tr>
					
                    <tr>
						<td>
							Social buttons max width<span class="pro_feature"> (pro)</span> <span title="Type here maximum width for Social buttons." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_socialis_max_width"   id="coming_soon_page_socialis_max_width" value="">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Social buttons max height<span class="pro_feature"> (pro)</span> <span title="Type here maximum height for Social buttons." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_socialis_max_height"   id="coming_soon_page_socialis_max_height" value="">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for Social buttons." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_socialis_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Social buttons animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_socialis_animation_after_time"  id="coming_soon_page_socialis_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_social_networks" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Link To Admin   #################################*/
	public function generete_link_to_tashboard_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/link_dashboard.png' ?>"></span>
				<span class="title_parametrs_group">Link to Admin<span class="pro_feature_label">  (Pro feature!)</span></span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Show/Hide<span class="pro_feature"> (pro)</span> <span title="Choose to show or hide Link To Admin." class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="coming_soon_page_link_to_dashboard_enable">
                                <option value="1">Show</option>
                                <option selected="selected" value="0">Hide</option>
                        	</select>
						</td>                
					</tr>
                	<tr>
						<td>
							Link To Admin text<span class="pro_feature"> (pro)</span> <span title="Type here Link To Admin text." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_page_link_to_dashboard"  placeholder="Enter Link Name" id="coming_soon_page_page_link_to_dashboard" value="Link To Admin"> 
                        </td>                
					</tr> 
                    <tr >
                        <td>
                           Text color<span class="pro_feature"> (pro)</span> <span title="Choose text color." class="desription_class">?</span>
                        </td>
                        <td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>                          
                    	</td>                
                    </tr>
                     <tr>
						<td>
							Font Size<span class="pro_feature"> (pro)</span> <span title="Type here text Font Size." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_page_link_to_dashboard_font_size"  id="coming_soon_page_page_link_to_dashboard_font_size" value="55">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Font family<span class="pro_feature"> (pro)</span> <span title="Select Font family for Link To Admin." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_font('coming_soon_page_page_link_to_dashboard_font','monospace') ?>
						</td>                
					</tr>                           
					<tr>
						<td>
							Link To Admin position<span class="pro_feature"> (pro)</span> <span title="Choose position for Link To Admin(Left, Center, Right)." class="desription_class">?</span>
						</td>
						<td>
                           <select id="coming_soon_page_link_to_dashboard_in_content_position">
                                <option value="0">Left</option>
                                <option selected="selected" value="1">Center</option>
                                <option value="2">Right</option>
                        	</select>
                         </td>                
					</tr>
                    <tr>
						<td>
							Distance from top<span class="pro_feature"> (pro)</span> <span title="Type here Link To Admin distance from top." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_link_to_dashboard_top_distance"  id="coming_soon_page_link_to_dashboard_top_distance" value="10">(Px)
						</td>                
					</tr>
					<tr>
						<td>
							Animation type<span class="pro_feature"> (pro)</span> <span title="Choose animation type for Link To Admin." class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_showing_effect('coming_soon_page_link_to_dashboard_animation_type','none'); ?>
						</td>                
					</tr>
					<tr>
						<td>
							Animation waiting time<span class="pro_feature"> (pro)</span> <span title="Type here waiting time for Link To Admin animation(in milliseconds)." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_link_to_dashboard_animation_after_time"  id="coming_soon_page_link_to_dashboard_animation_after_time" value="0">(milliseconds)
						</td>                
					</tr>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_link_to_dashboard" class="pro_input button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Search Engine   #################################*/
	public function generete_search_engine_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/seo.png' ?>"></span>
				<span class="title_parametrs_group">Search engine and Favicon</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody>                                
					<tr>
						<td>
							Title(SEO) <span title="Type here the Title for Search engines(It will be visable for search engines)." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_page_seo_title" id="coming_soon_page_page_seo_title" value="<?php echo $page_parametrs['coming_soon_page_page_seo_title'] ?>">
						</td>                
					</tr>
					<tr>
						<td>
							Favicon <span class="pro_feature"> (pro)</span> <span title="Here you can upload favicon for coming soon page." class="desription_class">?</span>
						</td>
						<td>
                            <input type="text"  class="upload pro_input" id="coming_soon_page_page_favicon" name="coming_soon_page_page_favicon"  value=""/>
                            <input class="pro_input button" type="button" value="Upload"/>	
                         </td>                
					</tr>
					<tr>
						<td>
							Enable Search Robots <span title="Here you can enable or disable coming soon page for search robots. " class="desription_class">?</span>
						</td>
						<td>
							<select id="coming_soon_page_enable_search_robots">
                                <option <?php selected($page_parametrs['coming_soon_page_enable_search_robots'],'1') ?> value="1">Enable</option>
                                <option <?php selected($page_parametrs['coming_soon_page_enable_search_robots'],'0') ?> value="0">Disable</option>
                        	</select>
						</td>                
					</tr>
					<tr>
						<td>
							Meta Keywords <span title="Type here meta keywords for coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_meta_keywords"  placeholder="Enter Meta Keywords" id="coming_soon_page_meta_keywords" value="<?php echo $page_parametrs['coming_soon_page_meta_keywords'] ?>">
						</td>                
					</tr>
                    <tr>
						<td>
							Meta Description <span title="Type here meta description for coming soon page." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="coming_soon_page_meta_description"  placeholder="Enter Meta Description" id="coming_soon_page_meta_description" value="<?php echo $page_parametrs['coming_soon_page_meta_description'] ?>">
						</td>                
					</tr>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="search_engine_and_favicon" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Except Page ip   #################################*/
	public function generete_except_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/except.png' ?>"></span>
				<span class="title_parametrs_group">Except pages and IPs</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Disable coming soon for this ips <span title="You can disable coming soon page for this ips, just type the ip and click anywhere, then type the next ip in next field that will appear." class="desription_class">?</span>
						</td>
						<td>
							 <div id="no_blocked_ips"></div>
						</td>                
					</tr>
                	<tr>
						<td>
							Disable coming soon for this urls<span class="pro_feature"> (pro)</span> <span title="ou can disable coming soon page for this urls, just type the url and click anywhere, then type the next url in next field that will appear." class="desription_class">?</span>
						</td>
						<td>
							 <input type="hidden" value="" id="coming_soon_page_showed_urls" name="coming_soon_page_showed_urls"> <div class="emelent_coming_soon_page_showed_urls"> <input class="pro_input" type="text" placeholder="Type Url Here" value=""><span class="remove_element remove_element_coming_soon_page_showed_urls"></span>  </div>
						</td>                
					</tr>                           
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="except_page" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
                <script>
				jQuery(document).ready(function(e) {
					many_inputs.main_element_for_inserting_element='no_blocked_ips';
					many_inputs.element_name_and_id='coming_soon_page_showed_ips';
					many_inputs.placeholder='Type Ip Here';
					many_inputs.value_jsone_encoded='<?php echo stripslashes($page_parametrs['coming_soon_page_showed_ips']) ?>';
					many_inputs.creates_elements();
					
					
				 });
                </script>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Background options   #################################*/
	public function generete_background_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/background.png' ?>"></span>
				<span class="title_parametrs_group">Background</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
                    <tbody> 
                        <tr>
                            <td>
                                Background type <span title="Select the background type you want to use for your coming soon page." class="desription_class">?</span>
                            </td>
                            <td>
                                <select id="coming_soon_page_radio_backroun" class="coming_set_hiddens">
                                    <option <?php selected($page_parametrs['coming_soon_page_radio_backroun'],'back_color') ?> value="back_color">Background Color</option>
                                    <option <?php selected($page_parametrs['coming_soon_page_radio_backroun'],'back_imge') ?> value="back_imge">Background Image</option>
                                    <option disabled value="back_imge">Background Slider<span class="pro_feature"> (pro)</span></option>
                                    <option disabled value="back_imge">Video background(not for mobile)<span class="pro_feature"> (pro)</span></option>
                                </select>
                            </td>                
                        </tr>
                        <tr class="tr_back_color white">
                            <td>
                                Set color <span title="Select the background color for coming soon page(option will apear if you choose 'Background color' type)." class="desription_class">?</span>
                            </td>
                            <td>
                                <input type="text" class="color_option" id="coming_soon_page_background_color" name="coming_soon_page_background_color"  value="<?php echo $page_parametrs['coming_soon_page_background_color'] ?>"/>
                             </td>                
                        </tr>                            
                        <tr class="tr_back_imge white">
                            <td>
                                Img url	<span title="ype the image url or just upload image for coming soon page background(option will apear if you choose "Background image" type). " class="desription_class">?</span>
                            </td>
                            <td>
                              <input type="text"  class="upload" id="coming_soon_page_background_img" name="coming_soon_page_background_img"  value="<?php echo $page_parametrs['coming_soon_page_background_img'] ?>"/>
                              <input class="upload-button button" type="button" value="Upload"/>	
                             </td>                
                        </tr>                        			
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" width="100%"><button type="button" id="coming_background" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
                        </tr>
                    </tfoot>       
                </table>
			</div>     
		</div>        
		<?php	
	}
	/*#########################  Content   #################################*/
	public function generete_content_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div closed_params " >
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo $this->plugin_url.'images/content.png' ?>"></span>
				<span class="title_parametrs_group">Content <span class="pro_feature_label">  (Pro feature!)</span></span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Content position<span class="pro_feature"> (pro)</span> <span title="Choose content position on coming soon page." class="desription_class">?</span>
						</td>
						<td>
                            <select class="pro_select"  id="page_content_position">
                                <option  value="left-top">Top Left</option>
                                <option  value="left-middle">Middle Left</option>
                                <option  value="left-bottom">Bottom Left</option>
                                <option  value="center-top">Top center</option>
                                <option selected="selected"  value="center-middle">Middle center</option>
                                <option  value="center-bottom">Bottom center</option>
                                <option  value="right-top">Top right</option>
                                <option  value="right-middle">Middle right</option>
                                <option  value="right-bottom">Bottom right</option>    
                            </select>
						</td>                
					</tr>
                 	<tr>
                        <td>
                            Content bg color<span class="pro_feature"> (pro)</span> <span title="Select content background color." class="desription_class">?</span>
                        </td>
                        <td>
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>                         
                         </td>                
                    </tr>
                    <tr>
						<td>
							Content transparency<span class="pro_feature"> (pro)</span> <span title="Select transparency for content." class="desription_class">?</span>
						</td>
						<td>
                            <input type="text" size="3" class="coming_number_slider pro_input" data-max-val="100" data-min-val="0" name="coming_soon_page_content_trasparensy" value="55" id="coming_soon_page_content_trasparensy" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
                         </td>                
					</tr>
                    <tr>
						<td>
							Border radius<span class="pro_feature"> (pro)</span> <span title="Type here border radius for content." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="page_content_boreder_radius"   id="page_content_boreder_radius" value="8">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Content max width<span class="pro_feature"> (pro)</span> <span title="Type here content maximum width." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_content_max_width"   id="coming_soon_page_content_max_width" value="740">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Padding<span class="pro_feature"> (pro)</span> <span title="Type here content padding value(padding properties define the space between the element border and the element content)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_content_padding"   id="coming_soon_page_content_padding" value="10">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Margin<span class="pro_feature"> (pro)</span> <span title="Type here content margin value(margin properties define the space around elements)." class="desription_class">?</span>
						</td>
						<td>
							<input class="pro_input" type="text" name="coming_soon_page_content_margin"   id="coming_soon_page_content_margin" value="15">(Px)
						</td>                
					</tr>
                    <tr>
						<td>
							Elements ordering<span class="pro_feature"> (pro)</span> <span title="Choose the order of showing elements on coming soon page(you can move all elements using drop down functionality)." class="desription_class">?</span>
						</td>
						<td>
                      
                            <ul id="coming_soon_sortable">
                                <li date-value="logo" class="ui-state-default">Logo<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
								<li date-value="title" class="ui-state-default">Title<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
								<li date-value="message" class="ui-state-default">Message<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
								<li date-value="countdown" class="ui-state-default">Countdown<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
								<li date-value="subscribe" class="ui-state-default">Subscribe<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
								<li date-value="loading_animation" class="ui-state-default">Progress bar<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>						
								<li date-value="link_to_dashboard" class="ui-state-default">Link to Admin<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>
								<li date-value="share_buttons" class="ui-state-default">Social buttons<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>                                
                             </ul>
                         </td>                
					</tr>
                    
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="coming_content" class="pro_input button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	private function generete_subscriber_table_lists($mailing_lsit_array){
			$generete='';
			if($mailing_lsit_array){
				foreach($mailing_lsit_array as $key=>$value){
					$generete.="{'email':'".$key."','firstname':'".$value['firstname']."','lastname':'".$value['lastname']."'},";	
				}
			$generete=rtrim($generete, ",");
			}
		?>
		<style>
		.description_row:nth-child(odd){
			background-color: #f9f9f9;
		}
		</style>
       	<script> 
		// jsone date for angiaulare js
			var my_table_list=<?php echo "[".$generete."]"; ?>
        </script>
		<div>
			<form method="post"  action="" id="admin_form" name="admin_form" ng-app="" ng-controller="customersController">
			<div class="tablenav top" style="width:95%">  
				<input type="text" placeholder="Search" ng-change="filtering_table();" ng-model="searchText">            
				<div class="tablenav-pages"><span class="displaying-num">{{filtering_table().length}} items</span>
				<span ng-show="(numberOfPages()-1)>=1">
					<span class="pagination-links"><a class="first-page" ng-class="{disabled:(curPage < 1 )}" title="Go to the first page" ng-click="curPage=0">«</a>
					<a class="prev-page" title="Go to the previous page" ng-class="{disabled:(curPage < 1 )}" ng-click="curPage=curPage-1; curect()">‹</a>
					<span class="paging-input"><span class="total-pages">{{curPage + 1}}</span> of <span class="total-pages">{{ numberOfPages() }}</span></span>
					<a class="next-page" title="Go to the next page" ng-class="{disabled:(curPage >= (numberOfPages() - 1))}" ng-click=" curPage=curPage+1; curect()">›</a>
					<a class="last-page" title="Go to the last page" ng-class="{disabled:(curPage >= (numberOfPages() - 1))}" ng-click="curPage=numberOfPages()-1">»</a></span></div>
				</span>
			</div>
			<table class="wp-list-table widefat fixed pages" style="width:95%">
				<thead>
					<tr>
						<th data-ng-click="order_by='email'; reverse=!reverse; ordering($event,order_by,reverse)" class="manage-column sortable desc"><a><span>Email</span><span class="sorting-indicator"></span></a></th>
						<th data-ng-click="order_by='firstname'; reverse=!reverse; ordering($event,order_by,reverse)" class="manage-column sortable desc"><a><span>First name</span><span class="sorting-indicator" ></span></a></th>
						<th data-ng-click="order_by='lastname'; reverse=!reverse; ordering($event,order_by,reverse)" class="manage-column sortable desc"><a><span>Last name</span><span class="sorting-indicator"></span></a></th>
						<th  style="width:80px">Delete</th>
					</tr>
				</thead>
				<tbody>
				 <tr ng-repeat="rows in names | filter:filtering_table" class="description_row">						 
						 <td><a href="#">{{rows.email}}</a></td>
						 <td><a href="#">{{rows.firstname}}</a></td>
						 <td><a href="#">{{rows.lastname}}</a></td>						 
						 <td><a href="admin.php?page=mailing-list-subscribers&task=remove_user&id={{rows.email}}">Delete</a></td>
							   
				  </tr> 
				</tbody>
			</table>
		</form>
		</div>
		<script>
        jQuery(document).ready(function(e) {
            jQuery('a.disabled').click(function(){return false});
            jQuery('form').on("keyup keypress", function(e) {
                var code = e.keyCode || e.which; 
                if (code  == 13) {               
                    e.preventDefault();
                    return false;
                }
            });
        });
        function customersController($scope,$filter) {
            var orderBy = $filter('orderBy');
            $scope.previsu_search_result='';
            $scope.oredering=new Array();
            $scope.baza = my_table_list;
            $scope.curPage = 0;
            $scope.pageSize = 10;
            $scope.names=$scope.baza.slice( $scope.curPage* $scope.pageSize,( $scope.curPage+1)* $scope.pageSize)
            $scope.numberOfPages = function(){
               return Math.ceil($scope.filtering_table().length / $scope.pageSize);
           };
           $scope.filtering_table=function(){
               var new_searched_date_array=new Array;
               new_searched_date_array=[];
               angular.forEach($scope.baza,function(value,key){
                   var catched=0;
                   angular.forEach(value,function(value_loc,key_loc){
                       if((''+value_loc).indexOf($scope.searchText)!=-1 || $scope.searchText=='' || typeof($scope.searchText) == 'undefined')
                          catched=1;
                   })
                  if(catched)
                      new_searched_date_array.push(value);
               })
               if($scope.previsu_search_result != $scope.searchText){
                  
                  $scope.previsu_search_result=$scope.searchText;
                   $scope.ordering($scope.oredering[0],$scope.oredering[1], $scope.oredering[2]);
                   
               }
               if(new_searched_date_array.length<=$scope.pageSize)
                    $scope.curPage = 0;
               return new_searched_date_array;
           }
           $scope.curect=function(){
               if( $scope.curPage<0){
                    $scope.curPage=0;
               }
               if( $scope.curPage> $scope.numberOfPages()-1)
                   $scope.curPage=$scope.numberOfPages()-1;
              $scope.names=$scope.filtering_table().slice( $scope.curPage* $scope.pageSize,( $scope.curPage+1)* $scope.pageSize)
           }
            
            $scope.ordering=function($event,order_by,revers){
               if( typeof($event) != 'undefined' && typeof($event.currentTarget) != 'undefined')
                    element=$event.currentTarget;
                else
                    element=jQuery();
               
                if(revers)
                  indicator='asc'
                else
                  indicator='desc'
                 $scope.oredering[0]=$event;
                 $scope.oredering[1]=order_by;
                 $scope.oredering[2]=revers;
                jQuery(element).parent().find('.manage-column').removeClass('sortable desc asc sorted');
                jQuery(element).parent().find('.manage-column').not(element).addClass('sortable desc');
                jQuery(element).addClass('sorted '+indicator);		  
                $scope.names=orderBy($scope.filtering_table(),order_by,revers).slice( $scope.curPage* $scope.pageSize,( $scope.curPage+1)* $scope.pageSize)
            }
        }
        </script>
		<?php
			
	}
	public function mailing_list(){
		$page_parametrs=$this->generete_parametrs('mailing_list');
		$mailing_lists=NULL;
		if($mailing_lists==NULL)
			$mailing_lists=array();
		if(isset($_GET['id']) && isset($_GET['task']) && $_GET['task']=='remove_user'){
			unset($mailing_lists[$_GET['id']]);
			update_option('users_mailer',json_encode($mailing_lists));
		}
		?><h2>Send Mail to all subscribed Users <a style="text-decoration:none;" href="http://wpdevart.com/wordpress-coming-soon-plugin/"><span style="color: rgba(10, 154, 62, 1);"> (Upgrade to Pro Version)</span></a></h2> 
       <p><span style="color:red">All fields are required</span></p>
        
        	<form method="post" id="coming_soon_options_form_send_mail" action="admin.php?page='<?php echo  str_replace( ' ', '-', $this->menu_name); ?>'">
            	<span class="user_information_inputs">
                    <input class="req_fields" type="text" value="" placeholder="Your display Email"  id="massage_from_mail" /><br />
                    <input class="req_fields" type="text" value="" placeholder="Your display Name " id="massage_from_name" /><br />
                    <input class="req_fields" type="text" value="" placeholder="Message title" id="massage_title" />
                </span>
                <textarea id="massage_description" placeholder="Message" style="width:400px; height:300px"></textarea><br /><br />
                <button type="button" id="send_mailing" class="save_button button button-primary"><span>Send Mail</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button>
                <br /><br />
                <span class="error_massage mailing_list"></span>
            </form>
		<h2>List of subscribed users <a style="text-decoration:none;" href="http://wpdevart.com/wordpress-coming-soon-plugin/"><span style="color: rgba(10, 154, 62, 1);"> (Upgrade to Pro Version)</span></a></h2> <?php        
		$this->generete_subscriber_table_lists($mailing_lists);
		?><h2>Subscribed users emails list<a style="text-decoration:none;" href="http://wpdevart.com/wordpress-coming-soon-plugin/"><span style="color: rgba(10, 154, 62, 1);"> (Upgrade to Pro Version)</span></a></h2><p><span style="color:red">You can copy emails list and send emails using Gmail or other email services.</span></p> <?php  
		?><textarea readonly style="min-height:200px;width:95%"><?php foreach($mailing_lists as $key => $value){ echo $key.',';} ?></textarea>
		
		
		
		<script>
			jQuery(document).ready(function(e) {
				jQuery('#send_mailing').click(function(){
					jQuery('#send_mailing').addClass('padding_loading');
					jQuery("#send_mailing").prop('disabled', true);
					jQuery('#coming_soon_options_form_send_mail .saving_in_progress').css('display','inline-block');
					
					jQuery.ajax({
						type:'POST',
						url: "<?php echo admin_url( 'admin-ajax.php?action=coming_soon_send_mail' ); ?>",
						data: {massage_from_mail:jQuery('#massage_from_mail').val(),massage_from_name:jQuery('#massage_from_name').val(),massage_description:jQuery('#massage_description').val(),massage_title:jQuery('#massage_title').val()},
					}).done(function(date) {
						switch(date){
							case "<?php echo $this->text_parametrs['sucsses_mailed'] ?>":
								jQuery('#coming_soon_options_form_send_mail .saving_in_progress').css('display','none');
								jQuery('#coming_soon_options_form_send_mail .sucsses_save').css('display','inline-block');
								setTimeout(function(){jQuery('.sucsses_save').css('display','none');jQuery('#send_mailing').removeClass('padding_loading');jQuery("#send_mailing").prop('disabled', false);},2500);
							break;
							case "<?php echo $this->text_parametrs['mising_massage'] ?>":
							case "<?php echo $this->text_parametrs['missing_fromname'] ?>":
							case "<?php echo $this->text_parametrs['missing_frommail'] ?>":
								jQuery('#coming_soon_options_form_send_mail .saving_in_progress').css('display','none');
								jQuery('#coming_soon_options_form_send_mail .error_in_saving').css('display','inline-block');
								jQuery('#coming_soon_options_form_send_mail .error_massage').css('display','inline-block');
								jQuery('#coming_soon_options_form_send_mail .error_massage').html(date);
								setTimeout(function(){jQuery('#coming_soon_options_form_send_mail .error_massage').css('display','none');jQuery('#coming_soon_options_form_send_mail .error_in_saving').css('display','none');jQuery('#send_mailing').removeClass('padding_loading');jQuery("#send_mailing").prop('disabled', false);},3000);
							break;
							case "<?php echo $this->text_parametrs['missing_title'] ?>":
								jQuery('#coming_soon_options_form_send_mail .saving_in_progress').css('display','none');
								jQuery('#coming_soon_options_form_send_mail .error_in_saving').css('display','inline-block');
								jQuery('#coming_soon_options_form_send_mail .error_massage').css('display','inline-block');
								jQuery('#coming_soon_options_form_send_mail .error_massage').html(date);
								setTimeout(function(){jQuery('#coming_soon_options_form_send_mail .error_massage').css('display','none');jQuery('#coming_soon_options_form_send_mail .error_in_saving').css('display','none');jQuery('#send_mailing').removeClass('padding_loading');jQuery("#send_mailing").prop('disabled', false);},3000);
							break;
							default:
								jQuery('#coming_soon_options_form_send_mail .saving_in_progress').css('display','none');
								jQuery('#coming_soon_options_form_send_mail .error_in_saving').css('display','inline-block');
								jQuery('#coming_soon_options_form_send_mail .error_massage').css('display','inline-block');
								jQuery('#coming_soon_options_form_send_mail .error_massage').html(date);
						}
					});  
				});
			});       
        </script>
		
		<?php 
	}
	/*######################################### SUBSCRIBE #######################################*/
	public function sending_mail(){
		$mailing_lists=json_decode(stripslashes(get_option('users_mailer','')), true);
		if($mailing_lists==NULL)
			$mailing_lists=array();
		$not_sending_mails=array();
		$sending_mails=array();
		if(!(isset($_POST['massage_title']) && $_POST['massage_title']!='')){
			echo $this->text_parametrs['missing_title'];
			die();
		}
		if(!(isset($_POST['massage_description']) && $_POST['massage_description']!='')){
			echo $this->text_parametrs['mising_massage'];
			die();
		}
		if(!(isset($_POST['massage_from_name']) && $_POST['massage_from_name']!='')){
			echo $this->text_parametrs['missing_fromname'];
			die();
		}
		if(!(isset($_POST['massage_from_mail']) && $_POST['massage_from_mail']!='')){
			echo $this->text_parametrs['missing_frommail'];
			die();
		}
		$mails_array=array();
		foreach($mailing_lists as $key => $mail){
			array_push($mails_array,$key);
		}
		$headers='From: '.$_POST['massage_from_name'].' <'.$_POST['massage_from_mail'].'>' . "\r\n";
		$send=wp_mail( $mails_array, $_POST['massage_title'], $_POST['massage_description'],$headers);
		if(!$send){		
			die($this->text_parametrs['error_maied']);
		}
		
		
		die($this->text_parametrs['sucsses_mailed']);
		
	}
	/*################################## FEATURED PLUGINS #########################################*/
	public function featured_plugins(){
		$plugins_array=array(
			'coming_soon'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/coming_soon.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
						'title'			=>	'Coming soon and Maintenance mode',
						'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
						),
			'Booking Calendar'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/Booking_calendar_featured.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-booking-calendar-plugin/',
						'title'			=>	'Booking Calendar',
						'description'	=>	'WordPress Booking Calendar plugin is an awesome tool to create a booking system for your website. Create booking calendars in a few minutes.'
						),	
			'youtube'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/youtube.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
						'title'			=>	'WordPress YouTube Embed',
						'description'	=>	'YouTube Embed plugin is an convenient tool for adding video to your website. Use YouTube Embed plugin to add YouTube videos in posts/pages, widgets.'
						),
			'countdown'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/countdown.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
						'title'			=>	'WordPress Countdown plugin',
						'description'	=>	'WordPress Countdown plugin is an nice tool to create and insert countdown timers into your posts/pages and widgets.'
						),
            'facebook-comments'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/facebook-comments-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-comments-plugin/',
						'title'			=>	'WordPress Facebook comments',
						'description'	=>	'Our Facebook comments plugin will help you to display Facebook Comments on your website. You can use Facebook Comments on your pages/posts.'
						),						
			'facebook'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/facebook.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
						'title'			=>	'Facebook Like Box',
						'description'	=>	'Our Facebook like box plugin will help you to display Facebook like box on your wesite, just add Facebook Like box widget to your sidebar or insert it into your posts/pages and use it.'
						),
			'poll'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/poll.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-polls-plugin',
						'title'			=>	'Poll',
						'description'	=>	'WordPress Polls plugin is an wonderful tool for creating polls and survey forms for your visitors. You can use our polls on widgets, posts and pages.'
						),
			'twitter'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/twitter.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-twitter-plugin',
						'title'			=>	'Twitter button plus',
						'description'	=>	'Twitter button plus is nice and useful tool to show Twitter tweet button on your website.'
						),															
			
		);
		?>
        <style>
         .featured_plugin_main{
			 background-color: #ffffff;
			 border: 1px solid #dedede;
			 box-sizing: border-box;
			 float:left;
			 margin-right:20px;
			 margin-bottom:20px;
			 
			 width:450px;
		 }
		.featured_plugin_image{
			padding: 15px;
			display: inline-block;
			float:left;
		}
		.featured_plugin_image a{
		  display: inline-block;
		}
		.featured_plugin_information{			
			float: left;
			width: auto;
			max-width: 282px;

		}
		.featured_plugin_title{
			color: #0073aa;
			font-size: 18px;
			display: inline-block;
		}
		.featured_plugin_title a{
			text-decoration:none;
					
		}
		.featured_plugin_title h4{
			margin:0px;
			margin-top: 20px;
			margin-bottom:8px;			  
		}
		.featured_plugin_description{
			display: inline-block;
		}
        
        </style>
        <script>
		
        jQuery(window).resize(wpdevart_countdown_feature_resize);
		jQuery(document).ready(function(e) {
            wpdevart_countdown_feature_resize();
        });
		
		function wpdevart_countdown_feature_resize(){
			var wpdevart_countdown_width=jQuery('.featured_plugin_main').eq(0).parent().width();
			var count_of_elements=Math.max(parseInt(wpdevart_countdown_width/450),1);
			var width_of_plugin=((wpdevart_countdown_width-count_of_elements*24-2)/count_of_elements);
			jQuery('.featured_plugin_main').width(width_of_plugin);
			jQuery('.featured_plugin_information').css('max-width',(width_of_plugin-160)+'px');
		}
       	</script>
        	<h2>Featured Plugins</h2>
            <br>
            <br>
            <?php foreach($plugins_array as $key=>$plugin) { ?>
            <div class="featured_plugin_main">
            	<span class="featured_plugin_image"><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><img src="<?php echo $plugin['image_url'] ?>"></a></span>
                <span class="featured_plugin_information">
                	<span class="featured_plugin_title"><h4><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><?php echo $plugin['title'] ?></a></h4></span>
                    <span class="featured_plugin_description"><?php echo $plugin['description'] ?></span>
                </span>
                <div style="clear:both"></div>                
            </div>
            <?php } 
	}
	
	/*######################### library functions  #############################*/	
	private function create_select_element_for_showing_effect($select_id='',$curent_effect='none'){
	?>
    <select class="pro_select" id="<?php echo $select_id; ?>" name="<?php echo $select_id; ?>">
   		  <option <?php selected('none',$curent_effect); ?> value="none">none</option>
          <option <?php selected('random',$curent_effect); ?> value="random">random</option>
        <optgroup label="Attention Seekers">
          <option <?php selected('bounce',$curent_effect); ?> value="bounce">bounce</option>
          <option <?php selected('flash',$curent_effect); ?> value="flash">flash</option>
          <option <?php selected('pulse',$curent_effect); ?> value="pulse">pulse</option>
          <option <?php selected('rubberBand',$curent_effect); ?> value="rubberBand">rubberBand</option>
          <option <?php selected('shake',$curent_effect); ?> value="shake">shake</option>
          <option <?php selected('swing',$curent_effect); ?> value="swing">swing</option>
          <option <?php selected('tada',$curent_effect); ?> value="tada">tada</option>
          <option <?php selected('wobble',$curent_effect); ?> value="wobble">wobble</option>
        </optgroup>

        <optgroup label="Bouncing Entrances">
          <option <?php selected('bounceIn',$curent_effect); ?> value="bounceIn">bounceIn</option>
          <option <?php selected('bounceInDown',$curent_effect); ?> value="bounceInDown">bounceInDown</option>
          <option <?php selected('bounceInLeft',$curent_effect); ?> value="bounceInLeft">bounceInLeft</option>
          <option <?php selected('bounceInRight',$curent_effect); ?> value="bounceInRight">bounceInRight</option>
          <option <?php selected('bounceInUp',$curent_effect); ?> value="bounceInUp">bounceInUp</option>
        </optgroup>

        <optgroup label="Fading Entrances">
          <option <?php selected('fadeIn',$curent_effect); ?> value="fadeIn">fadeIn</option>
          <option <?php selected('fadeInDown',$curent_effect); ?> value="fadeInDown">fadeInDown</option>
          <option <?php selected('fadeInDownBig',$curent_effect); ?> value="fadeInDownBig">fadeInDownBig</option>
          <option <?php selected('fadeInLeft',$curent_effect); ?> value="fadeInLeft">fadeInLeft</option>
          <option <?php selected('fadeInLeftBig',$curent_effect); ?> value="fadeInLeftBig">fadeInLeftBig</option>
          <option <?php selected('fadeInRight',$curent_effect); ?> value="fadeInRight">fadeInRight</option>
          <option <?php selected('fadeInRightBig',$curent_effect); ?> value="fadeInRightBig">fadeInRightBig</option>
          <option <?php selected('fadeInUp',$curent_effect); ?> value="fadeInUp">fadeInUp</option>
          <option <?php selected('fadeInUpBig',$curent_effect); ?> value="fadeInUpBig">fadeInUpBig</option>
        </optgroup>

        <optgroup label="Flippers">
          <option <?php selected('flip',$curent_effect); ?> value="flip">flip</option>
          <option <?php selected('flipInX',$curent_effect); ?> value="flipInX">flipInX</option>
          <option <?php selected('flipInY',$curent_effect); ?> value="flipInY">flipInY</option>
        </optgroup>

        <optgroup label="Lightspeed">
          <option <?php selected('lightSpeedIn',$curent_effect); ?> value="lightSpeedIn">lightSpeedIn</option>
        </optgroup>

        <optgroup label="Rotating Entrances">
          <option <?php selected('rotateIn',$curent_effect); ?> value="rotateIn">rotateIn</option>
          <option <?php selected('rotateInDownLeft',$curent_effect); ?> value="rotateInDownLeft">rotateInDownLeft</option>
          <option <?php selected('rotateInDownRight',$curent_effect); ?> value="rotateInDownRight">rotateInDownRight</option>
          <option <?php selected('rotateInUpLeft',$curent_effect); ?> value="rotateInUpLeft">rotateInUpLeft</option>
          <option <?php selected('rotateInUpRight',$curent_effect); ?> value="rotateInUpRight">rotateInUpRight</option>
        </optgroup>

        <optgroup label="Specials">
          
          <option <?php selected('rollIn',$curent_effect); ?> value="rollIn">rollIn</option>        
        </optgroup>

        <optgroup label="Zoom Entrances">
          <option <?php selected('zoomIn',$curent_effect); ?> value="zoomIn">zoomIn</option>
          <option <?php selected('zoomInDown',$curent_effect); ?> value="zoomInDown">zoomInDown</option>
          <option <?php selected('zoomInLeft',$curent_effect); ?> value="zoomInLeft">zoomInLeft</option>
          <option <?php selected('zoomInRight',$curent_effect); ?> value="zoomInRight">zoomInRight</option>
          <option <?php selected('zoomInUp',$curent_effect); ?> value="zoomInUp">zoomInUp</option>
        </optgroup>
      </select>
    <?php 
	}
	private function create_select_element_for_font($select_id='',$curent_font='none'){
	?>
   <select class="pro_select" id="<?php echo $select_id; ?>" name="<?php echo $select_id; ?>">
   
        <option <?php selected('Arial,Helvetica Neue,Helvetica,sans-serif',$curent_font); ?> value="Arial,Helvetica Neue,Helvetica,sans-serif">Arial *</option>
        <option <?php selected('Arial Black,Arial Bold,Arial,sans-serif',$curent_font); ?> value="Arial Black,Arial Bold,Arial,sans-serif">Arial Black *</option>
        <option <?php selected('Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif',$curent_font); ?> value="Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif">Arial Narrow *</option>
        <option <?php selected('Courier,Verdana,sans-serif',$curent_font); ?> value="Courier,Verdana,sans-serif">Courier *</option>
        <option <?php selected('Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Georgia,Times New Roman,Times,serif">Georgia *</option>
        <option <?php selected('Times New Roman,Times,Georgia,serif',$curent_font); ?> value="Times New Roman,Times,Georgia,serif">Times New Roman *</option>
        <option <?php selected('Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif',$curent_font); ?> value="Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif">Trebuchet MS *</option>
        <option <?php selected('Verdana,sans-serif',$curent_font); ?> value="Verdana,sans-serif">Verdana *</option>
        <option <?php selected('American Typewriter,Georgia,serif',$curent_font); ?> value="American Typewriter,Georgia,serif">American Typewriter</option>
        <option <?php selected('Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif',$curent_font); ?> value="Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif">Andale Mono</option>
        <option <?php selected('Baskerville,Times New Roman,Times,serif',$curent_font); ?> value="Baskerville,Times New Roman,Times,serif">Baskerville</option>
        <option <?php selected('Bookman Old Style,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Bookman Old Style,Georgia,Times New Roman,Times,serif">Bookman Old Style</option>
        <option <?php selected('Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif',$curent_font); ?> value="Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif">Calibri</option>
        <option <?php selected('Cambria,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Cambria,Georgia,Times New Roman,Times,serif">Cambria</option>
        <option <?php selected('Candara,Verdana,sans-serif',$curent_font); ?> value="Candara,Verdana,sans-serif">Candara</option>
        <option <?php selected('Century Gothic,Apple Gothic,Verdana,sans-serif',$curent_font); ?> value="Century Gothic,Apple Gothic,Verdana,sans-serif">Century Gothic</option>
        <option <?php selected('Century Schoolbook,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Century Schoolbook,Georgia,Times New Roman,Times,serif">Century Schoolbook</option>
        <option <?php selected('Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif',$curent_font); ?> value="Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif">Consolas</option>
        <option <?php selected('Constantia,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Constantia,Georgia,Times New Roman,Times,serif">Constantia</option>
        <option <?php selected('Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif',$curent_font); ?> value="Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif">Corbel</option>
        <option <?php selected('Franklin Gothic Medium,Arial,sans-serif',$curent_font); ?> value="Franklin Gothic Medium,Arial,sans-serif">Franklin Gothic Medium</option>
        <option <?php selected('Garamond,Hoefler Text,Times New Roman,Times,serif',$curent_font); ?> value="Garamond,Hoefler Text,Times New Roman,Times,serif">Garamond</option>
        <option <?php selected('Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif',$curent_font); ?> value="Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif">Gill Sans MT</option>
        <option <?php selected('Helvetica Neue,Helvetica,Arial,sans-serif',$curent_font); ?> value="Helvetica Neue,Helvetica,Arial,sans-serif">Helvetica Neue</option>
        <option <?php selected('Hoefler Text,Garamond,Times New Roman,Times,sans-serif',$curent_font); ?> value="Hoefler Text,Garamond,Times New Roman,Times,sans-serif">Hoefler Text</option>
        <option <?php selected('Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif">Lucida Bright</option>
        <option <?php selected('Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif',$curent_font); ?> value="Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif">Lucida Grande</option>
        <option <?php selected('monospace',$curent_font); ?> value="monospace">monospace</option>
        <option <?php selected('Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif">Palatino Linotype</option>
        <option <?php selected('Tahoma,Geneva,Verdana,sans-serif',$curent_font); ?> value="Tahoma,Geneva,Verdana,sans-serif">Tahoma</option>
        <option <?php selected('Rockwell, Arial Black, Arial Bold, Arial, sans-serif',$curent_font); ?> value="Rockwell, Arial Black, Arial Bold, Arial, sans-serif">Rockwell</option>
    </select>
    <?php
	}
	
}