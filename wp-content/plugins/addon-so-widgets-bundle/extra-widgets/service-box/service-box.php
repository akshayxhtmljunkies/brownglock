<?php

/*
Widget Name: Service Box
Description: This widget displays a 'Service Box!'.
Author: Ingenious Solutions
Author URI: http://ingenious-web.com/
*/

class Service_Box extends SiteOrigin_Widget {
    function __construct() {

        parent::__construct(
            'service-box',
            __('Service Box', 'addon-so-widgets-bundle'),
            array(
                'description' => __('Service Box.', 'addon-so-widgets-bundle'),
                'panels_icon' => 'dashicons dashicons-id',
                'panels_groups' => array('addonso'),
            ),
            array(

            ),
            array(
                'widget_title' => array(
                    'type' => 'text',
                    'label' => __('Widget Title', 'addon-so-widgets-bundle'),
                    'default' => ''
                ),
                'opt_selector' => array(
                    'type' => 'select',
                    'label' => __( 'First choose an option', 'addon-so-widgets-bundle' ),
                    'default' => 'blank',
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array( 'opt_selector' )
                    ),
                    'options' => array(
                        'blank' => __( 'Select an option', 'addon-so-widgets-bundle' ),
                        'icon' => __( 'Icon', 'addon-so-widgets-bundle' ),
                        'icon_image' => __( 'Icon Image', 'addon-so-widgets-bundle' ),
                    )
                ),

                'icon_selection' => array(
                    'type' => 'radio',
                    'label' => __( 'Choose Alignment', 'addon-so-widgets-bundle' ),
                    'default' => 'top',
                    'options' => array(
                        'top' => __( 'Top', 'addon-so-widgets-bundle' ),
                        'left' => __( 'Left', 'addon-so-widgets-bundle' ),
                        'right' => __( 'Right', 'addon-so-widgets-bundle' ),
                    )
                ),


                //ICON SECTION
                'icon_section' => array(
                    'type' => 'section',
                    'label' => __( 'Icon' , 'addon-so-widgets-bundle' ),
                    'hide' => true,
                    'state_handler' => array(
                        'opt_selector[icon]' => array('show'),
                        'opt_selector[icon_image]' => array('hide'),
                        'opt_selector[blank]' => array('hide'),
                    ),

                    'fields' => array(

                        'icon_size' => array(
                            'type' => 'slider',
                            'label' => __( 'Set icon size', 'addon-so-widgets-bundle' ),
                            'default' => 3,
                            'min' => 2,
                            'max' => 500,
                            'integer' => true
                        ),

                        'icon' => array(
                            'type' => 'icon',
                            'label' => __('Select an icon', 'addon-so-widgets-bundle'),
                        ),


                    )
                ),


                //ICON IMAGE SECTION
                'icon_image_section' => array(
                    'type' => 'section',
                    'label' => __( 'Icon Image' , 'addon-so-widgets-bundle' ),
                    'hide' => true,
                    'state_handler' => array(
                        'opt_selector[icon_image]' => array('show'),
                        'opt_selector[icon]' => array('hide'),
                        'opt_selector[blank]' => array('hide'),
                    ),
                    'fields' => array(

                        'icon_img_width' => array(
                            'type' => 'slider',
                            'label' => __( 'Set Image Width', 'addon-so-widgets-bundle' ),
                            'default' => 3,
                            'min' => 2,
                            'max' => 500,
                            'integer' => true
                        ),

                        'icon_image' => array(
                            'type' => 'media',
                            'label' => __( 'Choose Image', 'addon-so-widgets-bundle' ),
                            'choose' => __( 'Choose image', 'addon-so-widgets-bundle' ),
                            'update' => __( 'Set image', 'addon-so-widgets-bundle' ),
                            'library' => 'image'
                        ),

                    )
                ),

                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'addon-so-widgets-bundle'),
                    'default' => ''
                ),


                'content' => array(
                    'type' => 'tinymce',
                    'label' => __( 'Content', 'addon-so-widgets-bundle' ),
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

                'btn_text' => array(
                    'type' => 'text',
                    'label' => __('Button Text', 'addon-so-widgets-bundle'),
                    'default' => ''
                ),


                'btn_url' => array(
                    'type' => 'text',
                    'label' => __('Link', 'addon-so-widgets-bundle'),
                    'default' => ''
                ),




                'styling' => array(
                    'type' => 'section',
                    'label' => __( 'Widget styling' , 'addon-so-widgets-bundle' ),
                    'hide' => true,
                    'fields' => array(

                        'bg_color' => array(
                            'type' => 'color',
                            'label' => __( 'Background color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),

                        'icon_color' => array(
                            'type' => 'color',
                            'label' => __( 'Icon color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),
                        'title_color' => array(
                            'type' => 'color',
                            'label' => __( 'Title color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),

                        'content_color' => array(
                            'type' => 'color',
                            'label' => __( 'Content color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),
                        'border_color' => array(
                            'type' => 'color',
                            'label' => __( 'Border color', 'addon-so-widgets-bundle' ),
                            'default' => ''
                        ),

                        'box_padding' => array(
                            'type' => 'slider',
                            'label' => __( 'Service box padding', 'addon-so-widgets-bundle' ),
                            'default' => 24,
                            'min' => 2,
                            'max' => 37,
                            'integer' => true
                        ),
                        'box_border' => array(
                            'type' => 'select',
                            'label' => __( 'Border styled', 'addon-so-widgets-bundle' ),
                            'default' => '',
                            'options' => array(
                                'solid' => __( 'Solid', 'addon-so-widgets-bundle' ),
                                'dashed' => __( 'Dashed', 'addon-so-widgets-bundle' ),
                                'dotted' => __( 'Dotted', 'addon-so-widgets-bundle' ),
                            )
                        ),
                        'box_border_width' => array(
                            'type' => 'slider',
                            'label' => __( 'Border Width', 'addon-so-widgets-bundle' ),
                            'default' => 0,
                            'min' => 2,
                            'max' => 37,
                            'integer' => true
                        ),




                    )
                ),


            ),
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'service-box-template';
    }

    function get_style_name($instance) {
        return 'service-box-style';
    }

    function get_less_variables( $instance ) {
        return array(
            'background_color' => $instance['styling']['bg_color'],
            'icon_color' => $instance['styling']['icon_color'],
            'title_color' => $instance['styling']['title_color'],
            'content_color' => $instance['styling']['content_color'],
            'box_padding' => $instance['styling']['box_padding'].'px',
            'box_border' => $instance['styling']['box_border'],
            'box_border_width' => $instance['styling']['box_border_width'].'px',
            'box_border_color' => $instance['styling']['border_color'],
            'square_shape_bg_color' => $instance['styling']['square_shape_bg_color'],
            'square_shape_padding' => $instance['styling']['square_shape_padding'].'px',
        );
    }



}


siteorigin_widget_register('service-box', __FILE__, 'Service_Box');