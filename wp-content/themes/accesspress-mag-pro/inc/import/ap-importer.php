<?php
function admin_import_scripts() {
    wp_enqueue_script( 'wp-import', get_template_directory_uri() . '/inc/import/ap-import.js', false, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'admin_import_scripts' );

add_action( 'wp_ajax_my_action', 'my_action_callback' );

function my_action_callback() 
{
    global $wpdb; 

    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

    if ( !is_plugin_active( 'wordpress-importer/wordpress-importer.php' ) ) {
        $class_wp_importer = get_template_directory() ."/inc/import/wordpress-importer.php";
        if ( file_exists( $class_wp_importer ) ){
            require_once $class_wp_importer;
        }
    }else{
        $importer_error = true;
    }


    if($importer_error){
        echo "Import error! Please uninstall Wordpress importer plugin and try again";
    }else{ 
        $import_filepath = get_template_directory() ."/inc/import/tmp/accesspressmagpro.xml" ; // Get the xml file from directory 

        require get_template_directory() . '/inc/import/ap-import.php';

        $wp_import = new ap_import();
        $wp_import->fetch_attachments = true;
        echo '<div class="import-hide">';
        $wp_import->import($import_filepath);
        echo '</div>';

        //$wp_import->set_customizer_option();
        $wp_import->set_menu();
        $wp_import->set_widgets();
        $wp_import->set_theme_option();

        $page = get_page_by_path('home');
        if ($page) {
            $page_id  = $page->ID;
        }

        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id );
    }

        die(); // this is required to return a proper result
}