=== Woocommerce Payment Gateway per Product ===
Contributors: dreamfox
Donate link: http://www.dreamfoxmedia.nl
Tags: woocommerce,payments,plugin,free
Requires at least: 3.0.1
Tested up to: 4.3.1
Stable tag: 1.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin for woocommerce lets you select the available payment gateways for each individual product. 

== Description ==

This plugin for woocommerce lets you select the available payment gateways for each individual product. 

5 STARS RATING FOR THIS PLUGIN! SEE:<br> 
http://www.dreamfoxmedia.nl/shop/woocommerce-payment-gateway-per-product-pro/

This plugin for woocommerce lets you select the available payment gateways for each individual product.<br>
You can select for eacht individual product the payment gateway that will be used by checkout.<br>
If no selection is made, then the default payment gateways are displayed.<br>
If you for example only select paypal then only paypal will available for that product by checking out.<br>
Works on latest Woocommerce version.<br>
This plugin allows you to improve your customer service by giving the best payment service for your customers.<br>
This version is limited in features (you can only select gateways for 10 products).<br>
For a small fee you can get the Pro version with no limitation at:<br>
<a href="http://www.dreamfoxmedia.nl/shop/woocommerce-payment-gateway-per-product-pro/" target="_blank">www.dreamfoxmedia.nl</a>.


**Pro version (19,95):**
**[Woocommerce Payment Gateway per Product Pro](http://www.dreamfoxmedia.nl/shop/woocommerce-payment-gateway-per-product-pro/ "Order Payment Gateway Pro")**<br>
<br>
Features:
<ol>
<li>Unlimited products</li>
</ol>

<strong>Demo is available (frontend & Backend)</strong>:<br>
**View Demo @ [http://demo.dreamfoxmedia.nl/wordpress/available-demos/demo-payment-gateway/](http://demo.dreamfoxmedia.nl/wordpress/available-demos/demo-payment-gateway/ "View Demo")**

<strong>other WooCommerce plugins from Dreamfox:</strong<br>
<ol>
<li>Shipping Gateway per Product: <a href="https://wordpress.org/plugins/woocommerce-shipping-gateway-per-product/" target="_blank">Information</a></li>
<li>WooCOmmerce Delivery Date Plugin: <a href="https://wordpress.org/plugins/woocommerce-delivery-date/" target="_blank">Information</a></li>
<li>WooCommerce Mailchimp Plugin: <a href="https://wordpress.org/plugins/woocommerce-mailchimp-plugin/" target="_blank">Information</a></li>
</ol>

<strong>Social media:</strong><br>
Twitter: <a href="https://twitter.com/dreamfoxmedia">https://twitter.com/dreamfoxmedia</a><br>
Facebook: <a href="https://www.facebook.com/dreamfoxmedia">https://www.facebook.com/dreamfoxmedia</a>

== Installation ==

= For automatic installation: =

The simplest way to install is to click on 'Plugins' then 'Add' and type 'Woocommerce Payment Gateway per Product' in the search field.

= For manual installation 1: =

1. Login to your website and go to the Plugins section of your admin panel.
1. Click the Add New button.
1. Under Install Plugins, click the Upload link.
1. Select the plugin zip file (woocommerce-product-payments.x.x.x.zip) from your computer then click the Install Now button.
1. You should see a message stating that the plugin was installed successfully.
1. Click the Activate Plugin link.

= For manual installation 2: =

1. You should have access to the server where WordPress is installed. If you don't, see your system administrator.
1. Copy the plugin zip file (woocommerce-product-payments.x.x.x.zip) up to your server and unzip it somewhere on the file system.
1. Copy the "woocommerce-product-payments" folder into the /wp-content/plugins directory of your WordPress installation.
1. Login to your website and go to the Plugins section of your admin panel.
1. Look for "woocommerce-product-payments" and click Activate.
1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= What happens if i add more then one product in the shoppingcard with different selected payment gateways? =

Allowed payment gateways goes before denied payment gateways. so if for example you set:
- product 1 to paypal
- product 2 to paypal & creditcard
Then both payment gateways are shown by checkout

= More Information =

For more information, feel free to visit the official website for this plugin: <a href="http://www.dreamfox.nl" target="_blank">Dreamfox</a>.


== Screenshots ==

1. screenshot1.png
2. screenshot2.png
3. screenshot3.png

== Changelog ==
= 1.2.4 =
* updated for latest WooCommerce

= 1.2.3 =
* save on un selecting all payments

= 1.2.2 =
* Fixed Quick edit error

= 1.2.1 =
* Updated to work with latest WooCOmmerce version

= 1.2.0 =
* Hide Disabled payment gateway from admin product list
* Code Formatting

= 1.1.9 =
* fixed common issue.

= 1.1.8 =
* Fix Warning: in_array() [function.in-array]: Wrong datatype for second argument

= 1.1.6 =
* Fix payment block display at the time of adding product( woocommerce 2.1.8 )

= 1.1.5 =
* Typo in decription

= 1.1.4 =
* Restrict to save for autosave & revision

= 1.1.3 =
* Remove duplicate added files 

= 1.1.2 =
* Fix conflicting with wp_mandrill plugin
 
= 1.1.1 =
* Fix bugs of available gateways for multiple products on cart

= 1.1.0 =
* Fix bugs of available gateways

= 1.0.9 =
* Fix country not available issue on switching settings

= 1.0.8 =
* replace deprecated functions with stable &  
* remove warnings

1.0.7 =
* add readme.txt file and fixes

= 1.0.6 =
* Rename default function to avoid conflict with function.php

= 1.0.5 =
* Fix Coding and flush

= 1.0.4 =
* Tested on Woocommerce 2.0.17

= 1.0.3 =
* add limit

= 1.0.2 =
* fixed typo

= 1.0.1 =
* added author

== Upgrade notice ==

= 1.0 =
Upgrade for new version

= 0.5 =
This version fixes a bug.  Upgrade immediately.
