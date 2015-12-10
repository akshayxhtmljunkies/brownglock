=== AGP Font Awesome Collection ===
Contributors: agolubnichenko
Tags: agp, fontawesome, font-awesome, font awesome, Font Awesome, fa, fa-icon, fa icons, icon font, icons, font, button, buttons, developer, developer tools, tools, shortcode, social icons, social links, social buttons, ui, tinymce, visualizer, promotion, ad promotion, slider, content slider, widget slider, responsive slider, responsive, rwd, wp menu, menus, nav menu, navigation, navigation menu, walker, menu icons, social, media, profiles, widget
Requires at least: 3.5.0
Tested up to: 4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk

The latest Font Awesome icons with HTML and shortcodes usage, dynamic visualizer for TinyMCE, promotion widget and other features in the one plugin

== Description ==

The plugin allows to integrate the Font Awesome icons to Your WordPress website.
With this plugin you can use various types of the Font Awesome based icons, buttons etc. using simple shortcodes with flexible parameters or the Visual Constructor for a lazy.
WordPress Developers also can find with this plugin some useful features for usage within the code.

You can find [Live Demo](http://www.profosbox.com/) on the plugin site.

= New Features =

* You can change the color of the icons, text and background on mouse hover with help of the additional parameters of visual constructor
* You can use the Font Awesome Icons in the WordPress menu. At first, You must enable "Menu Icons" module on the plugin settings page in the Admin Panel. Then go to the Appearance > Menus and select necessary menu. For each menu item you can find additional drop-down list with the FA icons.

= Plugin Features =

* Install and activate the plugin. That's all you need to start using **605 Font Awesome 4.5.0 icons** on Your website;
* Font Awesome icons can be used as simple [HTML code](http://fortawesome.github.io/Font-Awesome/examples/) or [shortcodes](https://wordpress.org/plugins/agp-font-awesome-collection/faq/);
* You can add any of the Font Awesome icons and use it as simple icon, icon with text, icon button, icon button with text and easy customize it for your needs;
* All shortcodes can be used via Administrator Panel in TinyMCE editor area and directly in code;
* For buttons can be added URL attribute that can lead to a page, an external site or a social networks profile. You can use this feature for a personal promotion, downloads link, donation etc.;
* The plugin has some additional features for advertise and promotion on Your website;
* Minimum required PHP version is **5.3.0**;

= Visual Constructor =

You can add icons and buttons just in few clicks with the visual shortcodes constructor in TinyMCE editor. Just push small button with "FA" icon at the top panel of editor and select needed parameters in popup window.

= Personal Shortcodes =

You can create you own shortcodes in the Administrator Panel on the "Shortcodes" page and use it within Visual Constructor for different pages or posts as many times as needed.

= Shortcode Container =

Shortcode Container can be used as a wrapper for a custom shortcode for alignment it on a page. 
Just create your own shortcode with any elements in the Administrator Panel on the "Shortcodes" page and select it in Visual Constructor as parameter for "Shortcode Container" element.

= Sliders =

**Slider** - is a set of small animated information blocks with Font Awesome icon, headline, description and link to URL.
You can create and configure own sliders in the Administrator Panel on the "Sliders" page. 
Each slider is attached to a personal shortcode and can be used via Administrator Panel in TinyMCE editor areas and directly in code.
Also You can use Your slider in the "AGP Font Awesome Promotion Slider" widget.

**The slider is supported for mobile devices!**

= Promotion Widget =

You can use "AGP Font Awesome Promotion" widget for advertise and promotion.
You can create and show small animated information block in sidebar, that contains Font Awesome icon, headline, description and link to URL. Also You can setup colors for text and background of the widget content.

**The widget is supported for mobile devices!**

= Promotion Slider Widget =

You can use "AGP Font Awesome Promotion Slider" widget for advertise and promotion.
You can create and show small animated information block in sidebar, that contains Your personal **slider** with Font Awesome icons, headlines, descriptions and links to URL.

**The widget is supported for mobile devices!**

= Features for Developers =

* Specific Font Awesome Version shortcode allows to show current Font Awesome version, e.g. for your own plugin.
* As developer, you can add dropdown with the Font Awesome icons list and use it for your purpose.
* For developers are available some object and classes that implement convenient and flexible methods for access to Font Awesome icons properties.
* You can add the Font Awesome Icon and position of this icon for each terms of any taxonomy or category and use this parameters in PHP and HTML code.

= Looking for more info? =

More info you can find on the [screenshots](https://wordpress.org/plugins/agp-font-awesome-collection/screenshots/) and [FAQ](https://wordpress.org/plugins/agp-font-awesome-collection/faq/) tabs.

Also You can find [Live Demo](http://www.profosbox.com/) on the plugin site.

= How to use visual constructor =

[youtube http://www.youtube.com/watch?v=TJ3QqH4BsYY]

= How to create own shortcode =

[youtube http://www.youtube.com/watch?v=BT02i79Vmts]

= How to use promotion widget =

[youtube http://www.youtube.com/watch?v=HkK9recE8Ds]

= How to use shortcode container =

[youtube http://www.youtube.com/watch?v=pj5X73Ygna4]

== Installation ==

1. Download a copy of the plugin
2. Unzip and Upload 'AGP Font Awesome Collection' to a sub directory in '/wp-content/plugins/'.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= How to change the Shortcode content =

The plugin includes some templates for each shortcode in "templates/" folder. 
You can copy any template in your active theme and customize it for your needs. 
Path to the templates folder inside the active theme:

[ActiveTheme]/templates/agp-font-awesome-collection/

= How to style the Shortcode content =

The plugin includes CSS file "assets/css/style.css". 
You can copy this file in your active theme and customize it for your needs. 
Path to the styles inside the active theme:

[ActiveTheme]/templates/agp-font-awesome-collection/assets/css/style.css

= How to use "Simple Icon" shortcode =

Following shortcode can be used for adding of a simple icon:

`[fac_icon icon="camera"]`

* **icon** – Font Awesome  icon name without "fa-" prefix

Shortcode with additional attributes:

`[fac_icon icon="camera" font_size="20px" color="#ff0000"]`

* **font_size** – allow to set icon size, positive digital value with "px"
* **color** – allow to set icon color with HEX color value

= How to use "Simple Icon with text and shape" shortcode =

Following shortcode can be used for adding of a simple icon with additional text and shape:

`[fac_icontext icon="heart" text="Default"]`

* **icon** – Font Awesome  icon name without "fa-" prefix
* **text** – allows to set text value that displays at the right side of the icon

Shortcode with additional attributes:

`[fac_icontext icon="heart" text="Default" shape_type="round" shape_bg="#000000" icon_color="ffffff" text_color="000000"]`

* **shape_type** – preset shape type ( square / rounded / round )
* **shape_bg** – allows to set shape background color with HEX color value
* **icon_color** – allows to set icon color with HEX color value
* **text_color** – allows to set text color with HEX color value

As general references you can use Font Awesome official examples:  http://fortawesome.github.io/Font-Awesome/examples/ .

As for Font Awesome icon names references you can check following link: http://fortawesome.github.io/Font-Awesome/cheatsheet/ .

= How to use "Buttons" shortcode =

Following shortcode can be used for adding of a simple icon button:

`[fac_button icon="facebook" name="button_1" title="Find Us on Facebook" link="www.facebook.com" target="_blank"]`

* **icon** – Font Awesome  icon name without "fa-" prefix
* **title** – allows to set text for button hover (link "title" attribute)
* **link** – allows to set link URL
* **target** – allows to set target attribute for the link URL
* **name** – allows to set unique button name (link ID attribute); this parameter can be used for development purpose (e.g. JavaScript).

Shortcode with additional attributes for icon button with text:

`[fac_button icon="facebook" name="button_1" title="Find Us on Facebook" link=www.facebook.com text=" Find Us on Facebook "]`

* **text** – allows to set text value that displays at the right side of the icon

Shortcode with additional attributes for icon button with text customization:

`[fac_button icon="facebook" name="button_1" title="Find Us on Facebook" link=www.facebook.com text=" Find Us on Facebook " background="#0d47a1" color="#ffffff" border_radius="4px" border_width="4px" border_color="#0d47a1"]`

* **color** – allows to set text and icon color with HEX color value
* **background** – allows to set button background color with HEX color value
* **border_width** – allows to set button border width, positive digital value with "px"
* **border_color** – allows to set button border color with HEX color value
* **border_radius** – allows to set button corner rounding; one positive digital value with "px"  allows to set equal corner rounding for all corners. Also can be used following values (for example): border_radius="10px 0" – corner rounding for left-top and right-bottom corners and vice versa border_radius="0 10px" - corner rounding for right-top and left-bottom corners; border_radius=" 10px 0 0" – corner rounding for left-top corner etc.  For more references check "border-radius" CSS property.


= How to use the Font Awesome Icons in the WordPress menu =

At first, You must enable "Menu Icons" module on the plugin settings page in the Admin Panel. Then go to the Appearance > Menus and select necessary menu. For each menu item you can find additional drop-down list with the FA icons.

= (For Developers) How to add "Dropdown list" shortcode =

Following shortcode can be used for adding of dropdown with Font Awesome icons list:

`[fac_dropdown icon="cc-visa" name="myselectid_1"]`

* **icon** – allows to set Font Awesome icon that shows by default ; use Font Awesome  icon name without "fa-" prefix
* **name** – allows to set unique dropdown name (select ID attribute); this parameter can be used for development purpose (e.g. JavaScript)

Also this shortcode can be used directly in code.

= (For Developers) How to add "Current Version" shortcode =

Following shortcode can be used for adding of info box with current Font Awesome version:

`[fac_version]`

= (For Developers) How to get access to icons collection?  =

If you need to have access to object of collection you can use following code:

`<?php  $iconRepository = Fac()->getIconRepository(); ?>`

This object contains list of the entity of Font Awesome icons collection and access methods for these objects.

You can find general objects access methods below:

* **getAll()** – allow to get full list of the entity of Font Awesome icons
* **findById($id)** – allow to get icon entity ($id – icon name)
* **getCount()** – allows to get total count of icons
* **getAllCategories()** – allows to get list of icons categories
* **getAllByCategory($category)** – allows to get list of icons for specified category
* **getVersion()** – allows to get current Font Awesome version
* etc.

Each icon is an Object and also has properties and methods.
For example, if you need to get and show icon display name (e.g. "adn"), you need to use following code:

`<?php echo Fac()->getIconRepository()->findById('adn')->getName(); ?>`

As result, will be displayed: "App.net"
For more references you can check realization for "Fac_IconRepository" and  "Fac_IconEntity" classes in plugin code.
Also you can send any questions in plugin [support](https://wordpress.org/support/plugin/agp-font-awesome-collection) tab.

= (For Developers) How to use taxonomy icons in the PHP code? =

The first, You must enable "Category Icons" feature on the "Settings" page of plugin in the Admin Panel.
After that You must setup icons and icons position on the terms of taxonomy.

You can get the icon data for a specified term of taxonomy with the following code:

`<?php $data = Fac()->getTaxonomyIcons()->getTaxIcon( $term_id ); ?>`

where:

* **$term_id** - term_id of the specified term of taxonomy;
* **$data['icon']** - stored icon;
* **$data['position']** - stored icon position.

== Screenshots ==

1. Examples of Usage: various icons, buttons, promo banners & sliders
2. TinyMCE Visual Constructor Button
3. Visual Constructor
4. Shortcodes List
5. Custom Shortcode
6. Custom Shortcodes in the TinyMCE
7. Custom Shortcode Result
8. Promo Banner for sidebar (results you can find on screenshote 1 - left & right sidebars)
9. Sliders List
10. Custom Slider
11. Custom Slider for sidebar
11. Custom Slider in the TinyMCE
13. Custom Slider Result
14. Active Plugin Modules Management
15. Font Awesome dropdown (developers only)
16. Curent Font Avesome Version
17. Font Awesome Icons for menu
18. Menus with Font Awesome Icons

== Changelog ==
= 2.7.1 = 
* Minor changes for compatibility with WordPress 4.4

= 2.7.0 = 
* Added: Support for the Font Awesome v4.5.0

= 2.6.2 = 
* Fixed: Issue "Shortcode Editor and Divi Page Builder - z-index"
* Fixed: Issue "HTTPS insecure element"
* Changed: Typo "Simpe Icon" to "Simple Icon"
* Other minor changes

= 2.6.1 =
* Changed: Check for the minimum required PHP version on a server

= 2.6.0 =
* Added: Check for the minimum required PHP version on a server
* Changed: Minor changes of the plugin core

= 2.5.3 =
* Changed: Minor loading speed optimization

= 2.5.2 =
* Changed: Minor loading speed optimization

= 2.5.1 =
* Changed: Connection of the Font Awesome Icons moved to the footer of the page for more stability and resolving many conflicts with other styles in head section

= 2.5.0 =
* Added: Support of the Font Awesome v4.4.0

= 2.4.2 =
* Changed: Link to the [Live Demo](http://www.profosbox.com/) site

= 2.4.1 =
* Added: Now You can change the color of the icons, text and background on mouse hover with help of the additional parameters of visual constructor
* Minor changes of default elements styling

= 2.4.0 =
* Added: Now You can use the Font Awesome Icons in the WordPress menu

= 2.3.0 =
* Added: "Target" attribute for promotion widget and for every single slide of sliders
* Added: Settings page, where you can enable or disable plugin features
* Added: New shortcode "fac_container" that can be used as a wrapper for a custom shortcode for alignment it on a page
* Added: Short description of the selected element in visual constructor
* Fixed: Issue with displaying promotion widgets and shortcodes with empty description or empty headline and icon
* Minor changes of default elements styling
* Minor changes of the plugin core
* Minor bugfixing
* Added: **(For Developers)** Now You can adding Font Awesome Icon and position of this icon for each terms of any taxonomy or category and use this parameters in  PHP and HTML code

= 2.2.2 =
* Added: Possibility of reordering slides on a slider settings page
* Added: "Target" attribute for "Buttons" shortcode
* Changed: Currently "FA" button is available in the TinyMCE without previously saving for a new post or page
* Minor changes of default elements styling
* Minor changes of the plugin core

= 2.2.1 =
* Fixed issue "Fatal error when trying to activate plugin" for PHP 5.3
* Fixed issue for AJAX request with enabled Zlib-compression

= 2.2.0 =
* Changed: The plugin core has been changed and optimized for adding a new useful features
* Changed: Minimum required PHP version - 5.3.0 
* Added: "Sliders" page for creation and configuration different responsive sliders based on a Font Awesome Icons
* Added: AGP Font Awesome Promotion Slider widget

= 2.1.1 =
* Global changes of the plugin core

= 2.1.0 =
* Added AGP Font Awesome Promotion widget
* User guide moved from Description tab to FAQ tab
* Minor changes

= 2.0.0 =
* Added Visual Constructor for elements (icons/buttons)
* Added possibility to create preset custom shortcodes for elements (icons/buttons)
* Changes and cleanup of default elements styling
* Code refactoring

= 1.1.2 =
* Minor changes of the plugin core

= 1.1.1 =
* Minor bugfixing.

= 1.1.0 =
* Changes and cleanup of default elements styling
* Changes and cleanup of default elements templates
* Were added extended parameters for existing shortcodes
* Was added new shortcode "fac_icontext" for simple icon with text
* Added extended plugin user guide
* Minor changes

= 1.0.1 =
* Button for adding Font Awesome icons in TinyMCE editor
* Minor changes

= 1.0.0 =
* Initial release.