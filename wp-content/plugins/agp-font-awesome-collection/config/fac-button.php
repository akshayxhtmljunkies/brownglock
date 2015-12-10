<?php
return array(
    'template' =>'button',
    'displayName' => 'Icon Button',
    'note' => 'The element can be used for adding of a simple button with icon',
    'fields' => array(
        'icon' => array(
            'label' => 'Icon',            
            'type' => 'fa-select',                        
            'default' => '',
            'class' => 'widefat fac-element',
            'note' => 'Select icon from dropdown list',            
        ),
        'name' => array(
            'label' => 'Unique Name',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allows to set unique button name (link ID attribute). This parameter can be used for development purpose (e.g. JavaScript)',            
        ),        
        'title' => array(
            'label' => 'Title',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allows to set text for button hover (link "title" attribute)',            
        ),         
        'text' => array(
            'label' => 'Text',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allows to set text value that displays at the right side of the icon',            
        ),
        'link' => array(
            'label' => 'Link URL',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allows to set link URL',            
        ),           
        'target' => array(
            'label' => 'Target',            
            'type' => 'select',     
            'fieldSet' => 'link_target',
            'default' => '_self',            
            'class' => 'widefat regular-select fac-element',            
            'note' => 'The target attribute specifies where to open the linked document.<br/>'
            . '"_self" - Opens the linked document in the same frame as it was clicked<br/>'
            . '"_blank" - Opens the linked document in a new window or tab<br/>'
            . '"_parent" - Opens the linked document in the parent frame<br/>'
            . '"_top" - Opens the linked document in the full body of the window',
        ),            
        'color' =>  array(
            'label' => 'Text and Icon Color',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allows to set text and icon color with HEX color value',            
        ),        
        'color_hover' =>  array(
            'label' => 'Text and Icon Color on Mouse Hover',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allows to set text and icon color with HEX color value on mouse hover',            
        ),                
        'background' => array(
            'label' => 'Button Background Color',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allows to set button background color with HEX color value',            
        ),        
        'background_hover' => array(
            'label' => 'Button Background Color on Mouse Hover',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allows to set button background color with HEX color value on mouse hover',            
        ),                
        'border_width' => array(
            'label' => 'Border Width',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allows to set button border width, positive digital value with "px"',            
        ),        
        'border_color' => array(
            'label' => 'Border Color',            
            'type' => 'colorpicker',                        
            'default' => '',
            'class' => 'fac-element',
            'note' => 'Allows to set button border color with HEX color value',            
        ),        
        'border_radius' => array(
            'label' => 'Border Radius',            
            'type' => 'text',                        
            'default' => '',            
            'class' => 'widefat fac-element',
            'note' => 'Allows to set button corner rounding. One positive digital value with "px" allows to set equal corner rounding for all corners. Also can be used following values (for example): border_radius="10px 0" – corner rounding for left-top and right-bottom corners and vice versa border_radius="0 10px" - corner rounding for right-top and left-bottom corners; border_radius=" 10px 0 0" – corner rounding for left-top corner etc. For more references check "border-radius" CSS property',            
        ),        
    ),
);
