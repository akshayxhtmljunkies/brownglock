<?php

function fac_shortcodes_init() {
  $labels = array(
    'name'               => __('Shortcodes', 'fac'), 
    'singular_name'      => __('Shortcode', 'fac'),
    'add_new'            => __('Add New', 'fac'),
    'add_new_item'       => __('Add New Shortcode', 'fac'),
    'edit_item'          => __('Edit Shortcode', 'fac'),
    'new_item'           => __('New Shortcode', 'fac'),
    'all_items'          => __('Shortcodes', 'fac'),
    'view_item'          => __('View Shortcode', 'fac'),
    'search_items'       => __('Search Shortcode', 'fac'),
    'not_found'          => __('No Shortcodes Found', 'fac'),
    'not_found_in_trash' => __('No Shortcodes Found in Trash', 'fac'),
    'parent_item_colon'  => '',
    'menu_name'          => __('Shortcode', 'fac')
  );

  $args = array(
    'labels'             => $labels, 
    'public'             => false,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => 'fac',
    'show_in_nav_menus'  => false,
    'query_var'          => true,
    'rewrite'            => array( 'slug' =>  _x( 'fac-shortcodes', 'URL slug'),  'with_front' => false ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 2,
    'supports'           => array( 
        'title', 
        'editor',
    ),
  );

  register_post_type( 'fac-shortcodes', $args );
  
  flush_rewrite_rules();    
}
add_action( 'init', 'fac_shortcodes_init' );
