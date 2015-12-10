<?php

return array(
    'fac' => array(
        'page_title' => 'AGP Icons', 
        'menu_title' => 'AGP Icons', 
        'capability' => 'manage_options',
        'function' => '',
        'icon_url' => '', 
        'position' => null, 
        'hideInSubMenu' => TRUE,
        'submenu' => array(
            'fac-settings' => array(
                'page_title' => 'Settings', 
                'menu_title' => 'Settings', 
                'capability' => 'manage_options',
                'function' => array('Fac_Settings', 'renderSettingsPage'),                         
            ),
        ),       
    ),
);
    