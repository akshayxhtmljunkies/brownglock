<?php
return array(
    'template' =>'icon',
    'displayName' => 'Simple Icon',
    'note' => 'The element can be used for adding of a simple icon',
    'fields' => array(
        'icon' => array(
            'label' => 'Icon',            
            'type' => 'fa-select',                        
            'default' => '',
            'class' => 'widefat fac-element',
            'note' => 'Select icon from dropdown list',
        ),
        'color' => array(
            'label' => 'Color',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allow to set icon color with HEX color value',
        ), 
        'color_hover' => array(
            'label' => 'Color on Mouse Hover',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allow to set icon color with HEX color value on mouse hover',
        ),         
        'font_size' => array(
            'label' => 'Font Size',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allow to set icon size, positive digital value with "px"',
        ), 
    ),
);
