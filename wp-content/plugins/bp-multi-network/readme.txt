=== BP Multi Network ===
Contributors: wpmuguru, johnjamesjacoby, boonebgorges
Tags: buddypress, multiple, multisite, network 
Requires at least: 3.2
Tested up to: 3.8
Stable tag: 0.1

Segregate your BP networks in a multi-network WP install.

== Description ==

Requires BuddyPress 1.5 or greater.

This plugin segregates BuddyPress social networks in a multi network WordPress install so that each WP network has a different social network. The user base is still shared across the WP install.

Multiple WP networks can be created with either:

* [WP Multi Network](http://wordpress.org/extend/plugins/wp-multi-network/)
* [Networks+](http://wpebooks.com/networks/)

Please see the installation instructions for proper installation.

== Installation ==

1. Upload bp-multi-network.php to `/wp-content/mu-plugins`. 
2. If you already have/had BP active on your sub networks:
	1. deactivate BuddyPress on your sub network(s). 
	2. Edit your database and remove the bp- settings for the subnetwork from `wp_sitemeta` and `wp_X_options` where X is the blog ID of the main site in your sub network.

3. If you are using the forum component see the (plugin page)http://wpmututorials.com/news/new-features/multiple-buddypress-social-networks/ for instructions on editing bb-config.php.
4. Activate BuddyPress on your sub network.
5. Repeat steps 2-4 for each sub network.

== Changelog ==

= 0.1 =
* Original version.
