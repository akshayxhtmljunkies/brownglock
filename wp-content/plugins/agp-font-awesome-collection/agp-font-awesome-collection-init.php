<?php
use Agp\FontAwesomeCollection\Core\Agp_Autoloader;

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'fac_output_buffer');
function fac_output_buffer() {
    ob_start();
}

if (file_exists(dirname(__FILE__) . '/agp-core/agp-core.php' )) {
    include_once (dirname(__FILE__) . '/agp-core/agp-core.php' );
} 

add_action( 'plugins_loaded', 'fac_activate_plugin' );
function fac_activate_plugin() {
    if (class_exists('Agp\FontAwesomeCollection\Core\Agp_Autoloader') && !function_exists('Fac')) {
        $autoloader = Agp_Autoloader::instance();
        $autoloader->setClassMap(array(
            'paths' => array(
                __DIR__ => array('classes'),
            ),
            'namespaces' => array(
                'Agp\FontAwesomeCollection\Core' => array(
                    __DIR__ => array('agp-core'),
                ),
            ),
            'classmaps' => array (
                __DIR__ => 'classmap.json',
            ),            
        ));
        //$autoloader->generateClassMap(__DIR__);

        function Fac() {
            return Fac::instance();
        }    

        Fac();                
    }
}

fac_activate_plugin();
