<?php
return array(
    'fac-global-settings' => array(
        'sections' => array(
            'features' => array(
                'label' => 'Public Section',
            ),            
            'promo_features' => array(
                'label' => 'Promotion Section',
            ),                        
            'dev_features' => array(
                'label' => 'Developer Section',
            ),                        
        ),
        'fields' => array(
            'uniqueId' => array(
                'label' => 'Unique ID',            
                'type' => 'hidden',                        
                'default' => '65780f7e18fca',
                'section' => 'features',
                'class' => '',
                'note' => '',
            ),                                                
            'm_visual_constructor' => array(
                'type' => 'checkbox',
                'label' => 'Visual Constructor',
                'default' => 1,
                'section' => 'features',
                'class' => '',
                'note' => 'You can add icons and buttons just in few clicks with the visual shortcodes constructor in TinyMCE editor. Just push small button with "FA" icon at the top panel of editor and select needed parameters in the popup window',
            ),              
            'm_shortcodes' => array(
                'type' => 'checkbox',
                'label' => 'Shortcodes',
                'default' => 1,
                'section' => 'features',
                'class' => '',
                'note' => 'You can create you own shortcodes in the Administrator Panel on the "Shortcodes" page and use it within visual constructor for different pages or posts as many times as needed',
            ),                          
            'm_sliders' => array(
                'type' => 'checkbox',
                'label' => 'Sliders',
                'default' => 1,
                'section' => 'features',
                'class' => '',
                'note' => 'You can create and configure own sliders in the Administrator Panel on the "Sliders" page. Each slider is attached to a personal shortcode and can be used via Administrator Panel in TinyMCE editor areas and directly in a code',
            ),       
            'm_menu_icons' => array(
                'type' => 'checkbox',
                'label' => 'Menu Icons',
                'default' => 1,
                'section' => 'features',
                'class' => '',
                'note' => 'You can use the Font Awesome Icons in the WordPress menu',
            ),                   
            'm_promotion_widget' => array(
                'type' => 'checkbox',
                'label' => 'Promotion Widget',
                'default' => 1,
                'section' => 'promo_features',
                'class' => '',
                'note' => 'You can create and show small animated information block in sidebar, that contains Font Awesome icon, headline, description and link to URL. Also You can setup colors for text and background of the widget content', 
            ),                                                              
            'm_promotion_slider_widget' => array(
                'type' => 'checkbox',
                'label' => 'Promotion Slider Widget',
                'default' => 1,
                'section' => 'promo_features',
                'class' => '',
                'note' => 'You can create and show small animated information block in sidebar, that contains Your personal slider with Font Awesome icons, headlines, descriptions and links to URL', 
            ),                                                                          
            'm_tax_icons' => array(
                'type' => 'checkbox',
                'label' => 'Category Icons',
                'default' => 0,
                'section' => 'dev_features',
                'class' => '',
                'note' => 'You can add the Font Awesome Icon and position of this icon for each terms of any taxonomy or category and use this parameters in PHP and HTML code', 
            ),                                                  
            
        ),
    ),
);