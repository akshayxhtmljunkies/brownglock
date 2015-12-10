<?php
/*
Widget Name: MaxButtons
Description: MaxButtons widget
Author: Max Foundry
Author URI: https://maxbuttons.com
*/

class Widget_MaxButtons_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'sow-maxbutton',
			__('MaxButton', 'maxbuttons'),
			array(
				'description' => __('MaxButtons for the page builder.', 'maxbuttons'),
				 'panels_groups' => array('maxbuttons'),
 				'has_preview' => false, 
			),
			array(

			),
			array(
				'id' => array('type' => 'MaxButton', 
							  'label' => __('Select a maxbutton','maxbuttons'), 
							//  'library' => 'maxbuttons', 
				), 
			 	'text' => array(
					'type' => 'text',
					'label' => __('Button text [optional]', 'maxbuttons'),
				),

				'url' => array(
					'type' => 'link',
					'label' => __('Destination URL [optional]', 'maxbuttons'),
				),

				'window' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Open in a new window [optional]', 'maxbuttons'),
				),
 
			), 
			plugin_dir_path(__FILE__)
		);

	}
	
	function get_template_name($instance) {
		return 'base';
	}
		
/* 
	function initialize() {
		$this->register_frontend_styles(
			array(
				array(
					'sow-button-base',
					plugin_dir_url(__FILE__) . 'css/style.css',
					array(),
					SOW_BUNDLE_VERSION
				),
			)
		);
	}



	function get_style_name($instance) {
		if(empty($instance['design']['theme'])) return 'atom';
		return $instance['design']['theme'];
	} */

	/**
	 * Get the variables that we'll be injecting into the less stylesheet.
	 *
	 * @param $instance
	 *
	 * @return array
	 */
	/* function get_less_variables($instance){
		if( empty( $instance ) || empty( $instance['design'] ) ) return array();

		return array(
			'button_color' => $instance['design']['button_color'],
			'text_color' => $instance['design']['text_color'],

			'font_size' => $instance['design']['font_size'] . 'em',
			'rounding' => $instance['design']['rounding'] . 'em',
			'padding' => $instance['design']['padding'] . 'em',
			'has_text' => empty( $instance['text'] ) ? 'false' : 'true',
		);
	} */

	/**
	 * Make sure the instance is the most up to date version.
	 *
	 * @param $instance
	 *
	 * @return mixed
	 */
	/*function modify_instance($instance){

		if( empty($instance['button_icon']) ) {
			$instance['button_icon'] = array();

			if(isset($instance['icon_selected'])) $instance['button_icon']['icon_selected'] = $instance['icon_selected'];
			if(isset($instance['icon_color'])) $instance['button_icon']['icon_color'] = $instance['icon_color'];
			if(isset($instance['icon'])) $instance['button_icon']['icon'] = $instance['icon'];

			unset($instance['icon_selected']);
			unset($instance['icon_color']);
			unset($instance['icon']);
		}

		if( empty($instance['design']) ) {
			$instance['design'] = array();

			if(isset($instance['align'])) $instance['design']['align'] = $instance['align'];
			if(isset($instance['theme'])) $instance['design']['theme'] = $instance['theme'];
			if(isset($instance['button_color'])) $instance['design']['button_color'] = $instance['button_color'];
			if(isset($instance['text_color'])) $instance['design']['text_color'] = $instance['text_color'];
			if(isset($instance['hover'])) $instance['design']['hover'] = $instance['hover'];
			if(isset($instance['font_size'])) $instance['design']['font_size'] = $instance['font_size'];
			if(isset($instance['rounding'])) $instance['design']['rounding'] = $instance['rounding'];
			if(isset($instance['padding'])) $instance['design']['padding'] = $instance['padding'];

			unset($instance['align']);
			unset($instance['theme']);
			unset($instance['button_color']);
			unset($instance['text_color']);
			unset($instance['hover']);
			unset($instance['font_size']);
			unset($instance['rounding']);
			unset($instance['padding']);
		}

		if( empty($instance['attributes']) ) {
			$instance['attributes'] = array();
			if(isset($instance['id'])) $instance['attributes']['id'] = $instance['id'];
			unset($instance['id']);
		}

		return $instance;
	} */
}

siteorigin_widget_register('sow-maxbutton', __FILE__, 'Widget_MaxButtons_Widget');
