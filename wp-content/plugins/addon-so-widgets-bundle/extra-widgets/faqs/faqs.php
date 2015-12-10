<?php

/*
Widget Name: FAQs
Description: This widget Display Faq.
Author: Ingenious Solutions
Author URI: http://ingenious-web.com/
*/

class Faqs extends SiteOrigin_Widget
{
    function __construct()
    {

        parent::__construct(
            'faqs',
            __('Faqs', 'addon-so-widgets-bundle'),
            array(
                'description' => __('FAQs Component', 'addon-so-widgets-bundle'),
                'panels_icon' => 'dashicons dashicons-exerpt-view',
                'panels_groups' => array('addonso')
            ),
            array(),
            array(
                'widget_title' => array(
                    'type' => 'text',
                    'label' => __('Widget Title.', 'addon-so-widgets-bundle'),
                    'default' => ''
                ),

                'posts' => array(
                    'type' => 'posts',
                    'label' => __('Select FAQs', 'addon-so-widgets-bundle'),
                ),

                'faqs_styling' => array(
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

    function get_template_name($instance)
    {
        return 'faqs-template';
    }

    function get_style_name($instance)
    {
        return 'faqs-style';
    }

    function get_less_variables( $instance ) {
        return array(
            'title_color' => $instance['faqs_styling']['title_color'],
            'title_hover_color' => $instance['faqs_styling']['title_hover_color'],
            'content_color' => $instance['faqs_styling']['content_color'],
        );
    }

}


function faq() {
    $labels = array(
        'name'               => _x( 'Faq', 'post type general name' ),
        'singular_name'      => _x( 'Faq', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Faq' ),
        'add_new_item'       => __( 'Add New Faq' ),
        'edit_item'          => __( 'Edit Faq' ),
        'new_item'           => __( 'New Faq' ),
        'all_items'          => __( 'All Faqs' ),
        'view_item'          => __( 'View Faq' ),
        'search_items'       => __( 'Search Faq' ),
        'not_found'          => __( 'No Faq found' ),
        'not_found_in_trash' => __( 'No Faq found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Faq'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our products and product specific data',
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'   => true,
    );
    register_post_type( 'faq', $args );
}
add_action( 'init', 'faq' );




siteorigin_widget_register('faqs', __FILE__, 'Faqs');