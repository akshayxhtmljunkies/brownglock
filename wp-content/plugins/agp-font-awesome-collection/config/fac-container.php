<?php
return array(
    'template' =>'container',
    'displayName' => 'Shortcode Container',
    'note' => 'The element can be used as a wrapper for a custom shortcode for alignment it on a page',
    'fields' => array(
        'shortcode' => array(
            'label' => 'Custom Shortcode',            
            'type' => 'select',                        
            'fieldSet' => 'shortcodes',
            'default' => '',
            'class' => 'widefat fac-element',
            'note' => 'You own shortcode from the "Shortcodes" page',
        ),
        'text_align' => array(
            'label' => 'Text Align',            
            'type' => 'select',                        
            'fieldSet' => 'align',
            'default' => '',
            'class' => 'widefat fac-element',
            'note' => 'The property specifies the horizontal alignment of text in an element',
        ),
    ),
);
