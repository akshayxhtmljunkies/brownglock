<?php

/*
Widget Name: Toggles
Description: This widget Display Toggle.
Author: Ingenious Solutions
Author URI: http://ingenious-web.com/
*/

class Toggles extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'toggles',
			__('Toggle', 'addon-so-widgets-bundle'),
			array(
				'description' => __('Toggle Component.', 'addon-so-widgets-bundle'),
                'panels_icon' => 'dashicons dashicons-list-view',
                'panels_groups' => array('addonso')
			),
			array(

			),
			array(
				'widget_title' => array(
					'type' => 'text',
					'label' => __('Widget Title', 'addon-so-widgets-bundle'),
					'default' => ''
				),


                'toggle_repeater' => array(
                    'type' => 'repeater',
                    'label' => __( 'Toggles' , 'addon-so-widgets-bundle' ),
                    'item_name'  => __( 'Toggle', 'addon-so-widgets-bundle' ),
                    'item_label' => array(
                        'selector'     => "[id*='repeat_text']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(

                        'toggle_title' => array(
                            'type' => 'text',
                            'label' => __('Toggle Title', 'addon-so-widgets-bundle'),
                            'default' => ''
                        ),


                        'toggle_content' => array(
                            'type' => 'tinymce',
                            'label' => __( 'Toggle Content', 'addon-so-widgets-bundle' ),
                            'default' => '',
                            'rows' => 10,
                            'default_editor' => 'html',
                            'button_filters' => array(
                                'mce_buttons' => array( $this, 'filter_mce_buttons' ),
                                'mce_buttons_2' => array( $this, 'filter_mce_buttons_2' ),
                                'mce_buttons_3' => array( $this, 'filter_mce_buttons_3' ),
                                'mce_buttons_4' => array( $this, 'filter_mce_buttons_5' ),
                                'quicktags_settings' => array( $this, 'filter_quicktags_settings' ),
                            ),
                        ),


                    )
                ),

                'toggle_styling' => array(
                    'type' => 'section',
                    'label' => __( 'Widget styling' , 'addon-so-widgets-bundle' ),
                    'hide' => true,
                    'fields' => array(

                        'title_color' => array(
                            'type' => 'color',
                            'label' => __( 'Title color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),

                        'title_hover_color' => array(
                            'type' => 'color',
                            'label' => __( 'Title Hover color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),

                        'content_color' => array(
                            'type' => 'color',
                            'label' => __( 'Content color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),



                    )
                ),


			),
			plugin_dir_path(__FILE__)
		);
	}

	function get_template_name($instance) {
		return 'toggles-template';
	}

	function get_style_name($instance) {
		return 'toggles-style';
	}

    function get_less_variables( $instance ) {
        return array(
            'title_color' => $instance['toggle_styling']['title_color'],
            'title_hover_color' => $instance['toggle_styling']['title_hover_color'],
            'content_color' => $instance['toggle_styling']['content_color'],
        );
    }

}


siteorigin_widget_register('toggles', __FILE__, 'Toggles');