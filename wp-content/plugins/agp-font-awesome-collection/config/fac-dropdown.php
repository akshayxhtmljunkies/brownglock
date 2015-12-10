<?php
return array(
    'template' =>'dropdown',
    'displayName' => 'Dropdown list',
    'developerOnly' => TRUE,
    'fields' => array(
        'icon' => array(
            'label' => 'Icon',            
            'type' => 'fa-select',                        
            'default' => '',
            'class' => 'widefat fac-element',
        ),
        'name' => array(
            'label' => 'Unique Name',            
            'type' => 'text',                        
            'default' => 'fac-dropdown-id',            
            'class' => 'widefat fac-element',
        ),
    ),
);
