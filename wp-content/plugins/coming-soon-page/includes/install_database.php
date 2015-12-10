<?php 

/// here is the install databese class

class install_database{
	
	public $installed_options; // standard options
	private $plugin_url;

	function __construct(){
		
		if(isset($params['plugin_url']))
			$this->plugin_url=$params['plugin_url'];
		else
			$this->plugin_url=trailingslashit(dirname(plugins_url('',__FILE__)));

		
		$this->installed_options=array(
			"general_save_parametr"=>array(
				"coming_soon_page_mode"					=> "off",
			),
			"search_engine_and_favicon"=>array(				
				"coming_soon_page_page_seo_title"		=> get_bloginfo('name' ),				
				"coming_soon_page_enable_search_robots"	=> "1",
				"coming_soon_page_meta_keywords" 		=> "",
				"coming_soon_page_meta_description"		=> "",
			),
			"except_page"=>array(
				"coming_soon_page_showed_ips"			 => "",
			),
			"coming_logo"=>array(
				"coming_soon_page_page_logo" 				=> $this->plugin_url.'images/template1/logo.png',
				"coming_soon_page_logo_enable"				=> "1"
			),
			"coming_title"=>array(
				"coming_soon_page_title_enable"				=> "1",
				"coming_soon_page_page_title" 				=> "Coming Soon Page"
			),
			"coming_message"=>array(
				"coming_soon_page_message_enable"				=> "1",
				"coming_soon_page_page_message" 				=> "<h3>We will be back soon. View some useful information on our coming soon page, share it with friends</h3>"
			),
			"coming_countdown"=>array(
			),
			"coming_progressbar"=>array(			
			),
			"coming_subscribe"=>array(
			),
			"coming_social_networks"=>array(
				"coming_soon_page_socialis_enable"			=> "1",
				"coming_soon_page_open_new_tabe"			=> "0",
				
				"coming_soon_page_facebook"			   		=> "",
				"social_facbook_bacground_image" 	 	   	=> $this->plugin_url.'images/template1/facebook.png',				
				"coming_soon_page_twitter" 		  		 	=> "",
				"social_twiter_bacground_image" 	 	    => $this->plugin_url.'images/template1/twiter.png',				
				"coming_soon_page_google_plus" 			 	=> "",
				"social_google_bacground_image" 	 	    => $this->plugin_url.'images/template1/gmail.png',			
				"coming_soon_page_youtube" 			 	 	=> "",
				"social_youtobe_bacground_image" 	 	   	=> $this->plugin_url.'images/template1/youtobe.png',			
				"coming_soon_page_instagram"			   	=> "",
				"social_instagram_bacground_image" 	 	 	=> $this->plugin_url.'images/template1/instagram.png',
			),
			"coming_link_to_dashboard"=>array(
			),
			"coming_background"=>array(
				"coming_soon_page_radio_backroun" 			=> "back_imge",
				"coming_soon_page_background_color" 		=> "#cacaca",
				"coming_soon_page_background_img" 		  	=> $this->plugin_url.'images/template1/background.jpg',				
			),
			"coming_content"=>array(
			),
		
			"mailing_list"=>array(
			)
		);
		
		
	}
	public function install_databese(){
		foreach( $this->installed_options as $key => $option ){
			if( get_option($key,FALSE) === FALSE ){
				add_option($key,$option);
			}
		}		
	}
}