<?php

function fac_sliders_init() {
  $labels = array(
    'name'               => __('Sliders', 'fac'), 
    'singular_name'      => __('Slider', 'fac'),
    'add_new'            => __('Add New', 'fac'),
    'add_new_item'       => __('Add New Slider', 'fac'),
    'edit_item'          => __('Edit Slider', 'fac'),
    'new_item'           => __('New Slider', 'fac'),
    'all_items'          => __('Sliders', 'fac'),
    'view_item'          => __('View Slider', 'fac'),
    'search_items'       => __('Search Slider', 'fac'),
    'not_found'          => __('No Sliders Found', 'fac'),
    'not_found_in_trash' => __('No Sliders Found in Trash', 'fac'),
    'parent_item_colon'  => '',
    'menu_name'          => __('Slider', 'fac')
  );

  $args = array(
    'labels'             => $labels, 
    'public'             => false,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => 'fac',
    'show_in_nav_menus'  => false,
    'query_var'          => true,
    'rewrite'            => array( 'slug' =>  _x( 'fac-sliders', 'URL slug'),  'with_front' => false ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 3,
    'supports'           => array( 
        'title', 
        //'thumbnail',
    ),
  );

  register_post_type( 'fac-sliders', $args );
  
  flush_rewrite_rules();    
}
add_action( 'init', 'fac_sliders_init' );
