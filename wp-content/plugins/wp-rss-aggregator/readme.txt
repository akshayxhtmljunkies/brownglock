=== WP RSS Aggregator ===
Contributors: jeangalea, Mekku, xedin.unknown, markzahra, doytch, chiragswadia
Plugin URI: http://www.wprssaggregator.com
Tags: rss, aggregation, autoblog, autoblog aggregator, autoblogger, autoblogging, autopost, content curation, feed aggregation, feed aggregator, feed import, feed reader, feed to post, feeds, multi feed import, multi feed importer, multi rss feeds, multiple feed import, multiple rss feeds,rss aggregator, rss feader, RSS Feed, rss feed to post, rss feeder, RSS import, rss multi importer, rss post importer, rss retriever, rss to post, syndication
Requires at least: 4.0
Tested up to: 4.3.1
Stable tag: 4.7.8
License: GPLv2 or later
The no.1 RSS feed importer for WordPress. Premium add-ons available for more functionality.


== Description ==

WP RSS Aggregator is the most comprehensive and elegant RSS feed solution for WordPress.

The original and best plugin for importing, merging and displaying RSS and Atom feeds on your WordPress site.

With WP RSS Aggregator, you can:

* Display feeds from one or more sites on your blog 
* Aggregate feeds from multiple sites 

You can add any number of feeds through an administration panel, the plugin will then pull feed items from these sites, merge them and display them in date order.

To [display your imported feed items](http://wordpress.org/plugins/wp-rss-aggregator/screenshots/), you can use a shortcode or call the display function directly from within your theme.

= Highlighted Features = 

* Export a custom RSS feed based on your feed sources
* Pagination
* Set the feed import time interval
* Scheduling of feed imports by feed source
* Various shortcode parameters you can use to further customize the output
* Choose whether to show/hide sources and dates
* Choose the date format
* Set the links as no-follow or not, or add no follow to meta tag
* Select how you would like the links to open (in a Lightbox, a new window, or the current window)
* Set the name of the feed source
* Select number of posts per feed you want to show and store
* Opens YouTube, DailyMotion and Vimeo videos directly 
* Limit number of feed items stored in the database
* Feed autodiscovery, which lets you add feeds without even knowing the exact URL. 
* Extendable via action and filter hooks
* Integrated with the Simplepie library that come with WordPress. This includes RSS 0.91 and RSS 1.0 formats, the popular RSS 2.0 format, Atom etc.

= Premium Add-Ons =	
Add-Ons that add more functionality to the core plugin are [available for purchase](http://www.wprssaggregator.com/extensions/). 

* [Feed to Post](http://www.wprssaggregator.com/extensions/feed-to-post) - an advanced importer that lets you import RSS to posts or custom post types. Populate a website in minutes (autoblog). This is the most popular extension.
* [Keyword Filtering](http://www.wprssaggregator.com/extensions/keyword-filtering) - filter imported feeds based on keywords, so you only get items you're interested in.
* [Excerpts & Thumbnails](http://www.wprssaggregator.com/extensions/excerpts-thumbnails) - display excerpts and thumbnails together with the title, date and source.
* [Categories](http://www.wprssaggregator.com/extensions/categories) - categorise your feed sources and display items from a particular category at will within your site.
* [WordAi](http://www.wprssaggregator.com/extension/wordai/) - WordAi allows users to take an RSS feed and turn it into new content that is both completely unique and completely readable.
* [Full Text RSS Feeds](http://www.wprssaggregator.com/extension/full-text-rss-feeds/) - connectivity to our Full Text Premium service, which gives you unlimited feed items returned per feed source.

We also provide a [Feed Creator](http://createfeed.wprssaggregator.com) service, that allows you to generate RSS feeds from any webpage, even if it doesn't have its own RSS feed.

= Demo =
The core plugin can be seen in use on the [demo page](http://www.wprssaggregator.com/demo/).

= Video Walkthrough =
[youtube http://www.youtube.com/watch?v=fcENPsmJbvc]

= Documentation =
Instructions for plugin usage are available on the plugin's [documentation page](http://www.wprssaggregator.com/documentation/).

= As featured on =
* [Latest WP](http://www.latestwp.com/2015/03/15/wp-rss-aggregator-plugin-review/)
* [WP Beginner](http://www.wpbeginner.com/plugins/how-to-fetch-feeds-in-wordpress-using-wp-rss-aggregator/)
* [WPEXplorer](http://www.wpexplorer.com/custom-rss-aggregator-plugin/)
* [WP Kube](http://www.wpkube.com/wp-rss-aggregator-wordpress-review/)
* [Torquemag](http://torquemag.io/wp-rss-aggregator-review-do-more-with-rss-feeds/)
* [MyWPExpert](http://www.mywpexpert.com/wordpress-rss-aggregator-plugin)
* [Kikolani](http://kikolani.com/create-latest-posts-portfolio-page-wp-rss-aggregator.html) 
* [ManageWP Plugins of the Month](http://managewp.com/free-wordpress-plugins-march-2014)
* [TidyRepo](http://tidyrepo.com/wp-rss-aggregator/)
* [WP Eka](http://www.wpeka.com/wp-rss-aggregators-plugin.html)
* [IndexWP](www.indexwp.com/wp-rss-aggregator-plugin-review/)
* [WPulsar](http://www.wpulsar.com/wp-rss-aggregator-plugin-feed-to-posts-keyword-filtering-review/)
* [Kevin Muldoon](http://www.kevinmuldoon.com/wp-rss-aggregator-wordpress-plugin/)

= Translations =
* Italian - Davide De Maestri
* Spanish - Andrew Kurtis
* Brazilian Portugese - Bruno Calheira
* Dutch - Erick Suiker

== Installation ==

1. Upload the `wp-rss-aggregator` folder to the `/wp-content/plugins/` directory
2. Activate the WP RSS Aggregator plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `RSS Aggregator` menu item that appears in your dashboard menu.
3. Use the shortcode in your posts or pages: `[wp-rss-aggregator]`

The parameters accepted are:

* links_before
* links_after
* link_before
* link_after
* limit
* source
* exclude
* pagination

An example of a shortcode with parameters:
`[wp_rss_aggregator link_before='<li class="feed-link">' link_after='</li>']`
It is advisable to use the 'HTML' view of the editor when inserting the shortcode with paramters.

For a full list of shortcode parameters and usage guide please refer to the [documentation](http://www.wprssaggregator.com/docs/shortcodes/).

__Usage within theme files__

An example of a function call from within the theme's files:
`
<?php 
wprss_display_feed_items( $args = array(
	'links_before' => '<ul>',
	'links_after' => '</ul>',
	'link_before' => '<li>',
	'link_after' => '</li>',
	'limit' => '8',
	'source' => '5,9'
	)); 
?>
`

OR 

`<?php do_shortcode('[wp-rss-aggregator]'); ?>`


== Frequently Asked Questions ==
= How do I display the imported feed items? =

You can either use the shortcode in your posts and pages:
`[wp-rss-aggregator]`

or you can call the function directly within your theme:
`<?php wprss_display_feed_items(); ?>`

= Is there a limit on the number of feed sources I can use? =

There is no limit in place for the number of feed sources. Having many (50+) feed sources should not present any problems in itself.

However, pulling in posts from many sites is bound to put your server under some stress, so you might want to consider using a hosting solution that goes beyond your typical shared host. 

Check out our dedicated page on [WordPress hosting](http://www.wprssaggregator.com/recommended-web-hosts/) recommendations.

= Does WP RSS Aggregator work using JSON as the source? =

No, our plugin does not currently import from JSON, it only imports from RSS and Atom structured XML.

= Why do I get “No feed items found” when I insert the shortcode on a page or post? =

Try adding a few more feed sources and make sure they are valid by using the RSS Feed validator.

Secondly make sure your WordPress cron system is working well. If not, the feeds cannot be imported. If in doubt you can go to RSS Aggregator > Debugging and hit the red button to re-import all feed items. If the problem persists contact support.

= Can I store imported feed items as posts? = 

Yes! You can do that with the [Feed to Post](http://www.wprssaggregator.com/extensions/feed-to-post) add-on. You will not only be able to store items as posts, but also as other custom post types, as well as set the author, auto set tags and categories, import images into the gallery or set featured images, and much more.

= Some RSS feeds only give a short excerpt. Any way around that? =

Yes, along with the [Feed to Post](http://www.wprssaggregator.com/extensions/feed-to-post) add-on we have another add-on called [Full Text RSS Feeds](http://www.wprssaggregator.com/extension/full-text-rss-feeds/) that can get the full content of those feeds that only supply a short excerpt.

= I’m not sure which premium add-ons are right for me. Can you help me out? =

Sure! We wrote a [post](http://www.wprssaggregator.com/add-ons-purchase/) just for you. Read about which add-ons you should buy, we explain the different types of usage so you’ll know what to expect when purchasing.

If you need any further help you can contact our support team [here](http://www.wprssaggregator.com/contact/).

= Where can I find the documentation for the plugin? =

The full documentation section can be found on the [WP RSS Aggregator website](http://docs.wprssaggregator.com/), the documentation also includes an extensive FAQ list.


== Screenshots ==

1. Feed items imported by WP RSS Aggregator displayed on the front-end using the shortcode.

2. Feed Items imported by WP RSS Aggregator and displayed with the [Excerpts & Thumbnails](http://www.wprssaggregator.com/extensions/excerpts-thumbnails) add-on installed.

3. Adding/Editing a feed source.

4. The feed sources.

5. The imported feeds items.

6. WP RSS Aggregator's Settings page.


== Changelog ==

= 4.7.8 (2015-11-18) =
* Fixed bug: Sticky posts no longer get deleted when truncating, unless imported from a feed source.
* Enhanced: Added autoloading and refactored licensing.
* Enhanced: Added button to download error log.
* Enhanced: Cosmetic changes and fixes.

= 4.7.7 (2015-10-19) =
* Enhanced: Optimized checking for plugin updates.

= 4.7.6 (2015-10-07) =
* Enhanced: Feeds that fail to validate due to whitespace at the beginning are now supported by the plugin.
* Fixed bug: Undefined variables in the System Info section in the Debugging page.
* Fixed bug: Add-on license expiration notices could not be dismissed.

= 4.7.5 (2015-09-02) =
* Usage tracking now disabled.
* Fixed bug: error related to undefined `ajaxurl` JS variable gone from frontend.
* Enhanced: Licensing errors will be output to debug log.
* Enhanced: Improved compatibility with plugins that allow AJAX searching in the backend.

= 4.7.4 (2015-08-20) =
* Requirement: WordPress 4.0 or greater now required.
* Fixed bug in image caching
* Fixed bug in admin interface due to incorrectly translated IDs

= 4.7.3 (2015-08-04) =
* Enhanced: Core now implements an image cache logic.
* Enhanced: Add-ons on the "Add-ons" page now have an installed-but-inactive status.
* Enhanced: Google Alerts permalinks will now be normalized.
* Enhanced: Russian translation added.
* Fixed bug: Inline help (tooltips) translations now work.
* Fixed bug: Link to the Feed to Post add-on on the welcome page is no longer broken.

= 4.7.2 (2015-06-30) =
* Enhanced: Copyright updated.
* Fixed bug: Word trimming no longer adds extra closing tags at the end.
* Fixed bug: Presence of `idna_convert` class no longer causes infinite redirects on some servers.
* Fixed bug: Warning of unterminated comment no longer thrown in PHP 5.5.
* Fixed bug: Added default value for "Unique Titles" option.
* Fixed bug: Having a the port number specified with the database host no longer causes issues with the `mysqli` adapter in System Info on some servers.
* Fixed bug: Nested options of inline help controller no longer cause a fatal error.
* Fixed bug: Notices will no longer be displayed during rendering of feed items due to absence of required default values.

= 4.7.1 (2015-04-23) =
* Fixed bug: No warning will be thrown when fetching feeds.

= 4.7 (2015-04-21) =
* New: Optionally import only items with titles that don't already exist.
* Enhanced: Accessing feeds over HTTPS is now possible.
* Enhanced: Added support for multibyte strings in some places.
* Enhanced: Increased JS compatibility with other plugins.
* Enhanced: Increased UI support for mobile devices.
* Fixed bug: Having no mysqli extension no longer causes an error to appear in the debug info.
* Fixed bug: Saving an empty license key no longer results in a warning. 

= 4.6.13 (2015-03-20) =
* Fixed bug: The "Force feed" option wasn't being correctly used.

= 4.6.12 (2015-03-09) =
* Fixed bug: The "Force feed" option was being removed by the Feed to Post add-on.

= 4.6.11 (2015-03-04) =
* Enhanced: The Help page now includes a support form if a premium add-on is detected.
* Enhanced: Updated some translations for admin options.
* Fixed bug: Help tooltips are now optimized for iPad screens.
* Fixed bug: Errors on the licensing page when a license code has not yet been entered.

= 4.6.10 (2015-02-10) =
* Enhanced: AJAX license activation.
* Enhanced: License form more reliable.
* Enhanced: license-related UI improvements
* New: Markdown library added. Changelog now read from readme.
* Fixed bug: Saving license keys not longer triggers error in some cases.

= 4.6.9 (2015-01-21) =
* Enhanced: Admin user will now be warned about invalid or expiring licenses.
* Enhanced: Admin notices logic centralized in this plugin.
* Fixed: Multiple small-scale security vulnerabilities.
* Fixed: Ampersand in feed URL no longer causes the product of generated feeds to be invalidated by W3C Validator.

= 4.6.8 (2015-01-07) =
* Enhanced: Added more logging during feed importing.
* Enhanced: Irrelevent metaboxes added by other plugins are now removed from the Add/Edit Feed Source page.
* Fixed bug: Valid feed URLS were being invalidated.
* Fixed bug: The Blacklist feature was being hidden when the Feed to Post add-on was enabled.
* Fixed bug: Patched a vulnerability where any user on the site can issue a feed fetch.
* Fixed bug: The "Activate" and "Pause" actions are not shown in the bulk actions dropdown in WordPress v4.1.

= 4.6.7 (2014-12-17) =
* Enhanced: Some minor interface updates.
* Enhanced: Added filters for use by the premium add-ons.

= 4.6.6 (2014-12-06) =
* Enhanced: Added output layouts for feed sources and feed items.
* Enhanced: Updated EDD updater class to version 1.5.
* Enhanced: Added time limit extending to prevent script from exhausting its execution time limit while importing.
* Fixed bug: The "Delete and Re-import" button was deleting items but not re-importing.
* Fixed bug: Non-object errors when a feed source is deleted while importing.

= 4.6.5 (2014-11-17) =
* Enhanced: Improved the logging.
* Enhanced: Improved the licensing fields.
* Enhanced: Updated the EDD updater class to the latest version.
* Fixed bug: Small random error when viewing the licenses page.

= 4.6.4 (2014-11-10) =
* Enhanced: Added filters to the custom feed.
* Enhanced: Updated some styles to improve the user interface.
* Fixed bug: The "Remove selected from Blacklist" button had no nonce associated with it.
* Fixed bug: The Blacklist menu entry was not always being shown.

= 4.6.3 (2014-11-3) =
Enhanced: Re-added the "Add New" link in the plugin's menu.
Enhanced: Improved error logging.
Enhanced: Bulk actions in the Feed Sources page are now also included in the bottom dropdown menu.
Fixed bug: Add-on updater was prone to conflicts. Now enclosed in an action.
Fixed bug: The Full Text RSS Feeds add-on was not showing as active in the "Add-ons" page.
Fixed bug: Broken links in the "Add-ons" page, to add-on pages on our site.

= 4.6.2 (2014-10-15) =
* Enhanced: Improved plugin responsiveness.
* Enhanced: Updated some help text in tooltips with better explainations and added clarity.
* Enhanced: Optimized some old SQL queries.
* Enhanced: Added better debug logging.
* Enhanced: Added a new filter to modify the text shown before author names.
* Fixed bug: Licenses were not showing as active, even though they were activated.

= 4.6.1 (2014-10-06) =
* Enhanced: Improved internationalization in the plugin, for better translations.
* Fixed bug: If the feed source age limit was left empty, the global setting was used instead of ignoring the limit.

= 4.6 (2014-09-22) =
* Enhanced: Improved the user interface, with better responsiveness and tooltips.
* Enhanced: Removes the ID column. The ID is now shown fixed in row actions.
* Enhanced: Feed Preview indicates if feed items have no dates.
* Fixed bug: If a feed item has no date, the date and time it was imported is used.

= 4.5.3 (2014-09-15) =
* New Featured: Added filter to allow adding RSS feeds to the head of your site's pages for CPTs.
* Enhanced: Columns in the feed sources table are now sortable.
* Enhanced: Removed the ID column in the feed sources table. The ID has been moved as a row action.
* Enhanced: Improved various interface elements.
* Enhanced: Better responsiveness for smaller screen.
* Fixed bug: The importing spinning icon would get stuck and spin for a very long time.
* Fixed bug: Removed an old description meta field.
* Fixed bug: Plugin was not removing all scheduled cron jobs when deactivated.

= 4.5.2 (2014-09-09) =
* Enhanced: Optimized plugin for WordPress 4.0.
* Enhanced: Improved template and added filters for add-on hooking.
* Fixed bug: Editor toolbar visible over the WP RSS shortcode dialog.

= 4.5.1 (2014-08-26) =
* Fixed bug: Last import feed item count stays at zero.
* Fixed bug: Datetime::setTimestamp error when using PHP 5.2 or earlier.
* Fixed bug: The display limit was not working.
* Fixed bug: Minor bug in licensing.

= 4.5 (2014-08-25) =
* New Feature: Bulk importer allows you to create multiple feed sources at once.
* Enhanced: Improved OPML importer with added hooks.
* Enhanced: Centralized add-on licensing, fixing multiple bugs.
* Fixed bug: Undefined `feed_limit` errors when using the shortcode.

= 4.4.4 (2014-08-19) =
* Fixed bug: Errors when using older PHP versions 5.3 or lower.

= 4.4.3 (2014-08-19) =
* Fixed bug: Errors when using older PHP versions 5.3 or lower.

= 4.4.2 (2014-08-19) =
* Fixed bug: Errors when using older PHP versions 5.3 or lower.

= 4.4.1 (2014-08-18) =
* Enhanced: Various improvements to the plugin interface and texts.
* Enhanced: Moved the restore default settings button farther down the Debugging page, to avoid confusion with the delete button.
* Fixed bug: Feed item dates were not being adjusted to the timezone when using a GMT offset.
* Fixed bug: Feed item dates are now adjusted according to daylight savings time.

= 4.4 (2014-08-11) =
* New Feature: Blacklist - delete items and blacklist them to never import them again.
* Enhanced: Added a button in the Debugging page to reset the plugin settings to default.
* Enhanced: WordPress Yoast SEO metaboxes and custom columns will no longer appear.

= 4.3.1 (2014-08-08) =
* Enhanced: Better wording on settings page.
* Fixed bug: The Links Behaviour option in the settings was not working.
* Fixed bug: The wrong feed items were being shown for some sources when using the "View Items" row action.

= 4.3 (2014-08-04) =
* New Feature: Feed items now also import authors.
* Enhanced: Custom feed is now in RSS 2.0 format.
* Enhanced: Improved the display template for feed items.
* Fixed bug: Custom feed was not working in Firefox.
* Fixed bug: Some feed items were showing items from another feed source.
* Fixed bug: The feed limit in the global settings was not working.

= 4.2.3 (2014-07-29) =
* Enhanced: Added an option to choose between the current pagination type, and numbered pagination.
* Enhanced: The Feed Preview now also shows the total number of items in the feed.
* Fixed bug: A PHP warning error was being shown in the System Info.
* Fixed bug: Language files were not always being referenced correctly.
* Fixed bug: Manually fetching a feed fails if the feed is scheduled to update in the next 10 minutes.
* Fixed bug: Bing RSS feeds were importing duplicates on every update.

= 4.2.2 (2014-07-23) =
* Enhanced: Facebook page feeds are now changed into RSS 2.0 feeds, rather than Atom 1.0 feeds.
* Enhanced: Improved live updating performace on the Feed Sources page.

= 4.2.1 (2014-07-17) =
* Enhanced: Feed Sources page is now more responsive.

= 4.2 (2014-07-17) =
* New Feature: Can now view each feed source's imported feed items separate from other feed sources' feed items.
* Enhanced: Major visual update to the Feed Sources page with new live updates.
* Enhanced: The custom feed now includes the feed source.
* Fixed bug: Google News feeds were importing duplicate items on every update.
* Fixed bug: Multiple minor bug fixes with old filters.

= 4.1.6 (2014-06-28) = 
* Fixed bug: Results returned by wprss_get_feed_items_for_source() will no longer be affected by filters.
* Fixed bug: Charset issue in titles

= 4.1.5 (2014-06-19) =
* Enhanced: The Feed Sources table now indicates which feed sources encountered errors during the last import.
* Fixed bug: Feed titles were not being decoded for HTML entities.

= 4.1.4 (2014-05-16) =
* Enhanced: Minor improvements to feed importing and handling.
* Fixed bug: HTML entities were not being decoded in feed item titles.

= 4.1.3 (2014-04-28) =
* Enhanced: Added a force feed option, for valid RSS feeds with incorrect header content types.
* Fixed bug: HTML entities in feed item titles are now being decoded.

= 4.1.2 (2014-04-22) =
* Enhanced: Improved the custom feed, by allowing a custom title.
* Enhanced: Improved shortcode, by adding the "pagination" parameter.
* Enhanced: Modified a filter to fix some bugs in the add-ons.

= 4.1.1 (2014-04-09) =
* Enhanced: Tracking notices only appear for admin users.
* Fixed bug: Auto Feed Discovery was not working.

= 4.1 (2014-04-03) =
* New Feature: Feed items can now link to enclosure links in the feed.
* Enhanced: Added a filter to allow add-ons to modify feed item queries.

= 4.0.9 (2014-03-27) =
* Enhanced: Added a filter to modify the feeds template.
* Fixed bug: Nested lists in feeds template.

= 4.0.8 (2014-03-20) =
* Fixed bug: Using the shortcode makes the comments section always open.

= 4.0.7 (2014-03-08) =
* Fixed bug: The plugin prevented uploading of header images.

= 4.0.6 (2014-03-05) =
* Fixed bug: Hook change in last version suspected reason for some installations having non-updated feed items.

= 4.0.5 (2014-03-03) =
* New Feature: Time ago added as an option.
* Enhanced: The plugin now allows the use of RSS and Atom feeds that do not specify the correct MIME type.
* Enhanced: Better performance due to better hook usage.
* Fixed bug: Facebook page feed URL conversion was not being triggered for new feed sources.
* Fixed bug: Styles fix for pagination.
* Fixed bug: Removed empty spaces in logging.

= 4.0.4 (2014-02-17) =
* Enhanced: Added Activate/Pause bulk actions in the Feed Sources page.
* Enhanced: Feed Sources page table has been re-designed.
* Enhanced: Logging is now site dependant on multisite.
* Fixed bug: Undefined display settings where appearing on the front end.

= 4.0.3 (2014-02-12) =
* Fixed bug: The general setting for deleting feed items by age was not working.

= 4.0.2 (2014-02-10) =
* Enhanced: Added a filter to change the html tags allowed in feed item content.

= 4.0.1 (2014-02-08) =
* Fixed bug: Empty array of feed items bug caused importing problems.

= 4.0 (2014-02-04) =
* Enhanced: Improved some internal queries, for better performance.
* Fixed bug: Feed limits were not working properly.

= 3.9.9 (2014-02-03) =
* Enhanced: The custom feed can now be extended by add-ons.

= 3.9.8 (2014-01-20) =
* Fixed bug: Removed excessive logging from Debugging Error Log.

= 3.9.7 (2014-01-17) =
* Fixed bug: Bug in admin-debugging.php causing trouble with admin login

= 3.9.6 (2014-01-17) =
* Enhanced: Added error logging.

= 3.9.5 (2014-01-02) =
* Enhanced: Added a feed validator link in the New/Edit Feed Sources page.
* Enhanced: The Next Update column also shows the time remaining for next update, for feed source on the global update interval.
* Enhanced: The custom feed has been improved, and is now identical to the feeds displayed with the shortcode.
* Enhanced: License notifications only appear on the main site when using WordPress multisite.
* Enhanced: Updated Colorbox script to 1.4.33
* Fixed bug: The Imported Items column was always showing zero.
* Fixed bug: Feed items not being imported with limit set to zero. Should be unlimited.
* Fixed bug: Fat header in Feed Sources page

= 3.9.4 (2013-12-24) =
* Enhanced: Added a column in the Feed Sources page that shows the number of feed items imported for each feed source.
* Fixed bug: Leaving the delete old feed items empty did not ignore the delete.

= 3.9.3 (2013-12-23) =
* Fixed bug: Fixed tracking pointer appearing on saving settings.

= 3.9.2 (2013-12-21) = 
* Fixed bug: Incorrect file include call.

= 3.9.1 (2013-12-12) =
* Enhanced: Improved date and time handling for imported feed items.
* Fixed bug: Incorrect values being shown in the Feed Processing metabox.
* Fixed bug: Feed limits set to zero were causing feeds to not be imported.

= 3.9 (2013-12-12) =
* New Feature: Feed sources can have their own update interval.
* New Feature: The time remaining until the next update has been added to the Feed Source table.

= 3.8 (2013-12-05) =
* New Feature: Feed items can be limited and deleted by their age.
* Enhanced: Added utility functions for shorter filters.
* Fixed bug: License codes were being erased when add-ons were deactivated.
* Fixed bug: Some feed sources could not be set to active from the table controls.
* Fixed bug: str_pos errors appear when custom feed url is left empty.
* Fixed bug: Some options were producing undefined index errors.

= 3.7 (2013-11-28) =
* New Feature: State system - Feed sources can be activated/paused.
* New Feature: State system - Feed sources can be set to activate or pause themselves at a specific date and time.
* Enhanced: Added compatibility with nested outline elements in OPML files.
* Enhanced: Admin menu icon image will change into a Dashicon, when WordPress is updated to 3.8 (Decemeber 2013).
* Fixed bug: Custom Post types were breaking when the plugin is activated.

= 3.6.1 (2013-11-17) =
* Fixed bug: Missing 2nd argument for wprss_shorten_title()

= 3.6 (2013-11-16) =
* New Feature: Can set the maximum length for titles. Long titles get trimmed.
* Fixed bug: Fixed errors with undefined indexes for unchecked checkboxes in the settings page.
* Fixed bug: Pagination on front static page was not working.

= 3.5.2 (2013-11-11) =
* Fixed bug: Invalid feed source url was producing an Undefined method notice.
* Fixed bug: Custom feed was producing a 404 page.
* Fixed bug: Presstrends code firing on admin_init, opt-in implementation coming soon

= 3.5.1 (2013-11-09) =
* Enhanced: Increased compatibility with RSS sources.
* Fixed bug: Pagination not working on home page

= 3.5 (2013-11-6) =
* New Feature: Can delete feed items for a particular source
* Enhanced: the 'Fetch feed items' row action for feed sources resets itself after 3.5 seconds.
* Enhanced: The feed image is saved for each url.
* Fixed bug: Link to source now links to correct url. Previously linked to site's feed.

= 3.4.6 (2013-11-1) =
* Enhanced: Added more hooks to debugging page for the Feed to Post add-on.
* Fixed bug: Uninitialized loop index

= 3.4.5 (2013-10-30) =
* Bug Fix: Feed items were not being imported while the WPML plugin was active.

= 3.4.4 (2013-10-26) =
* New feature: Pagination
* New feature: First implementation of editor button for easy shortcode creation
* Enhanced: Feed items and sources don't show up in link manager
* Enhanced: Included Presstrends code for plugin usage monitoring

= 3.4.3 (2013-10-20) =
* Fixed bug: Removed anonymous functions for backwards PHP compatibility
* Bug fix: Added suppress_filters in feed-display.php to prevent a user reported error
* Bug fix: Missing <li> in certain feed displays

= 3.4.2 (2013-9-19) =
* Enhanced: Added some hooks for Feed to Post compatibility
* Enhanced: Moved date settings to a more appropriate location

= 3.4.1 (2013-9-16) = 
* Fixed Bug: Minor issue with options page - PHP notice

= 3.4 (2013-9-15) =
* New Feature: Saving/Updating a feed source triggers an update for that source's feed items.
* New Feature: Option to change Youtube, Vimeo and Dailymotion feed item URLs to embedded video players URLs
* New Feature: Facebook Pages URLs are automatically detected and changed into Atom Feed URLs using FB's Graph
* Enhanced: Updated jQuery Colorbox library to 1.4.29
* Fixed Bug: Some settings did not have a default value set, and were throwing an 'Undefined Index' error
* Fixed Bug: Admin notices do not disappear immediately when dismissed.

= Version 3.3.3 (2013-09-08) =
* Fixed bug: Better function handling on uninstall, should remove uninstall issues

= Version 3.3.2 (2013-09-07) =
* New feature: Added exclude parameter to shortcode
* Enhanced: Added metabox links to documentation and add-ons
* Fixed bug: Custom feed linking to post on user site rather than original source
* Fixed bug: Custom post types issues when activitating the plugin

= Version 3.3.1 (2013-08-09) =
* Fixed Bug: Roles and Capabilities file had not been included
* Fixed Bug: Error on install, function not found

= Version 3.3 (2013-08-08) =
* New feature: OPML importer
* New feature: Feed item limits for individual Feed Sources
* New feature: Custom feed URL
* New feature: Feed limit on custom feed
* New feature: New 'Fetch feed items' action for each Feed Source in listing display
* New feature: Option to enable link to source
* Enhanced: Date strings now change according to locale being used (i.e. compatible with WPML)
* Enhanced: Capabilities implemented
* Enhanced: Feed Sources row action 'View' removed
* Fixed Bug: Proxy feed URLs resulting in the permalink: example.com/url

= Version 3.2 (2013-07-06) =
* New feature: Parameter to limit number of feeds displayed
* New feature: Paramter to limit feeds displayed to particular sources (via ID)
* Enhanced: Better feed import handling to handle large number of feed sources

= Version 3.1.1 (2013-06-06) =
* Fixed bug: Incompatibility with some other plugins due to function missing namespace

= Version 3.1 (2013-06-06) =
* New feature: Option to set the number of feed items imported from every feed (default 5)
* New feature: Import and Export aggregator settings and feed sources
* New feature: Debugging page allowing manual feed refresh and feed reset
* Enhanced: Faster handling of restoring sources from trash when feed limit is 0
* Fixed bug: Limiter on number of overall feeds stored not working
* Fixed bug: Incompatibility issue with Foobox plugin fixed 
* Fixed bug: Duplicate feeds sometimes imported

= Version 3.0 (2013-03-16) =
* New feature: Option to select cron frequency
* New feature: Code extensibility added to be compatible with add-ons
* New feature: Option to set a limit to the number of feeds stored (previously 50, hard coded)
* New feature: Option to define the format of the date shown below each feed item
* New feature: Option to show or hide source of feed item
* New feature: Option to show or hide publish date of feed item
* New feature: Option to set text preceding publish date
* New feature: Option to set text preceding source of feed item
* New feature: Option to link title or not
* New feature: Limit of 5 items imported for each source instead of 10
* Enhanced: Performance improvement when publishing * New feeds in admin
* Enhanced: Query tuning for better performance
* Enhanced: Major code rewrite, refactoring and inclusion of hooks
* Enhanced: Updated Colorbox to v1.4.1
* Enhanced: Better security implementations	
* Enhanced: Better feed preview display
* Fixed bug: Deletion of items upon source deletion not working properly
* Requires: WordPress 3.3

= Version 2.2.3 (2012-11-01) =
* Fixed bug: Tab navigation preventing typing in input boxes
* Removed: Feeds showing up in internal linking pop up

= Version 2.2.2 (2012-10-30) =
* Removed: Feeds showing up in site search results
* Enhanced: Better tab button navigation when adding a new feed
* Enhanced: Better guidance when a feed URL is invalid

= Version 2.2.1 (2012-10-17) =
* Fixed bug: wprss_feed_source_order assumes everyone is an admin

= Version 2.2 (2012-10-01) =
* Italian translation added
* Feed source order changed to alphabetical
* Fixed bug - repeated entries when having a non-valid feed source
* Fixed bug - all imported feeds deleted upon trashing a single feed source

= Version 2.1 (2012-09-27) =
* Now localised for translations
* Fixed bug with date string
* Fixed $link_before and $link_after, now working
* Added backwards compatibility for wp_rss_aggregator() function

= Version 2.0 (2012-09-21) =
* Bulk of code rewritten and refactored
* Added install and upgrade functions
* Added DB version setting
* Feed sources now stored as Custom Post Types
* Feed source list sortable ascending or descending by name
* Removed days subsections in feed display
* Ability to limit total number of feeds displayed
* Feeds now fetched via Cron
* Cron job to delete old feed items, keeps max of 50 items in DB
* Now requires WordPress 3.2
* Updated colorbox to v1.3.20.1
* Limit of 15 items max imported for each source
* Fixed issue of page content displaying incorrectly after feeds

= Version 1.1 (2012-08-13) =
* Now requires WordPress 3.0
* More flexible fetching of images directory
* Has its own top level menu item
* Added settings section
* Ability to open in lightbox, new window or default browser behaviour
* Ability to set links as follow or no follow
* Using constants for oftenly used locations
* Code refactoring
* Changes in file and folder structure

= Version 1.0 (2012-01-06) =
=== WP RSS Aggregator ===
Contributors: jeangalea, Mekku, xedin.unknown, markzahra, doytch, chiragswadia
Plugin URI: http://www.wprssaggregator.com
Tags: rss, aggregation, autoblog, autoblog aggregator, autoblogger, autoblogging, autopost, content curation, feed aggregation, feed aggregator, feed import, feed reader, feed to post, feeds, multi feed import, multi feed importer, multi rss feeds, multiple feed import, multiple rss feeds,rss aggregator, rss feader, RSS Feed, rss feed to post, rss feeder, RSS import, rss multi importer, rss post importer, rss retriever, rss to post, syndication
Requires at least: 4.0
Tested up to: 4.3.1
Stable tag: 4.7.7
License: GPLv2 or later
The no.1 RSS feed importer for WordPress. Premium add-ons available for more functionality.


== Description ==

WP RSS Aggregator is the most comprehensive and elegant RSS feed solution for WordPress.

The original and premier plugin for importing, merging and displaying RSS and Atom feeds on your WordPress site.

With WP RSS Aggregator, you can:

* Display feeds from one or more sites on your blog 
* Aggregate feeds from multiple sites 

You can add any number of feeds through an administration panel, the plugin will then pull feed items from these sites, merge them and display them in date order.

To [display your imported feed items](http://wordpress.org/plugins/wp-rss-aggregator/screenshots/), you can use a shortcode or call the display function directly from within your theme.

= Highlighted Features = 

* Export a custom RSS feed based on your feed sources
* Pagination
* Set the feed import time interval
* Scheduling of feed imports by feed source
* Various shortcode parameters you can use to further customize the output
* Choose whether to show/hide sources and dates
* Choose the date format
* Set the links as no-follow or not, or add no follow to meta tag
* Select how you would like the links to open (in a Lightbox, a new window, or the current window)
* Set the name of the feed source
* Select number of posts per feed you want to show and store
* Opens YouTube, DailyMotion and Vimeo videos directly 
* Limit number of feed items stored in the database
* Feed autodiscovery, which lets you add feeds without even knowing the exact URL. 
* Extendable via action and filter hooks
* Integrated with the Simplepie library that come with WordPress. This includes RSS 0.91 and RSS 1.0 formats, the popular RSS 2.0 format, Atom etc.

= Premium Add-Ons =	
Add-Ons that add more functionality to the core plugin are [available for purchase](http://www.wprssaggregator.com/extensions/). 

* [Feed to Post](http://www.wprssaggregator.com/extensions/feed-to-post) - an advanced importer that lets you import RSS to posts or custom post types. Populate a website in minutes (autoblog). This is the most popular extension.
* [Keyword Filtering](http://www.wprssaggregator.com/extensions/keyword-filtering) - filter imported feeds based on keywords, so you only get items you're interested in.
* [Excerpts & Thumbnails](http://www.wprssaggregator.com/extensions/excerpts-thumbnails) - display excerpts and thumbnails together with the title, date and source.
* [Categories](http://www.wprssaggregator.com/extensions/categories) - categorise your feed sources and display items from a particular category at will within your site.
* [WordAi](http://www.wprssaggregator.com/extension/wordai/) - WordAi allows users to take an RSS feed and turn it into new content that is both completely unique and completely readable.
* [Full Text RSS Feeds](http://www.wprssaggregator.com/extension/full-text-rss-feeds/) - connectivity to our Full Text Premium service, which gives you unlimited feed items returned per feed source.
* [Widget](http://www.wprssaggregator.com/extension/widget/) - Add a widget that displays imported feed items.

We also provide a [Feed Creator](http://createfeed.wprssaggregator.com) service, that allows you to generate RSS feeds from any webpage, even if it doesn't have its own RSS feed.

= Demo =
The core plugin can be seen in use on the [demo page](http://www.wprssaggregator.com/demo/).

= Video Walkthrough =
[youtube http://www.youtube.com/watch?v=fcENPsmJbvc]

= Documentation =
Instructions for plugin usage are available on the plugin's [documentation page](http://www.wprssaggregator.com/documentation/).

= As featured on =
* [Latest WP](http://www.latestwp.com/2015/03/15/wp-rss-aggregator-plugin-review/)
* [WP Beginner](http://www.wpbeginner.com/plugins/how-to-fetch-feeds-in-wordpress-using-wp-rss-aggregator/)
* [WPEXplorer](http://www.wpexplorer.com/custom-rss-aggregator-plugin/)
* [WP Kube](http://www.wpkube.com/wp-rss-aggregator-wordpress-review/)
* [Torquemag](http://torquemag.io/wp-rss-aggregator-review-do-more-with-rss-feeds/)
* [MyWPExpert](http://www.mywpexpert.com/wordpress-rss-aggregator-plugin)
* [Kikolani](http://kikolani.com/create-latest-posts-portfolio-page-wp-rss-aggregator.html) 
* [ManageWP Plugins of the Month](http://managewp.com/free-wordpress-plugins-march-2014)
* [TidyRepo](http://tidyrepo.com/wp-rss-aggregator/)
* [WP Eka](http://www.wpeka.com/wp-rss-aggregators-plugin.html)
* [IndexWP](www.indexwp.com/wp-rss-aggregator-plugin-review/)
* [WPulsar](http://www.wpulsar.com/wp-rss-aggregator-plugin-feed-to-posts-keyword-filtering-review/)
* [Kevin Muldoon](http://www.kevinmuldoon.com/wp-rss-aggregator-wordpress-plugin/)

= Translations =
* Italian - Davide De Maestri
* Spanish - Andrew Kurtis
* Brazilian Portugese - Bruno Calheira
* Dutch - Erick Suiker

== Installation ==

1. Upload the `wp-rss-aggregator` folder to the `/wp-content/plugins/` directory
2. Activate the WP RSS Aggregator plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `RSS Aggregator` menu item that appears in your dashboard menu.
3. Use the shortcode in your posts or pages: `[wp-rss-aggregator]`

The parameters accepted are:

* links_before
* links_after
* link_before
* link_after
* limit
* source
* exclude
* pagination

An example of a shortcode with parameters:
`[wp_rss_aggregator link_before='<li class="feed-link">' link_after='</li>']`
It is advisable to use the 'HTML' view of the editor when inserting the shortcode with paramters.

For a full list of shortcode parameters and usage guide please refer to the [documentation](http://www.wprssaggregator.com/docs/shortcodes/).

__Usage within theme files__

An example of a function call from within the theme's files:
`
<?php 
wprss_display_feed_items( $args = array(
	'links_before' => '<ul>',
	'links_after' => '</ul>',
	'link_before' => '<li>',
	'link_after' => '</li>',
	'limit' => '8',
	'source' => '5,9'
	)); 
?>
`

OR 

`<?php do_shortcode('[wp-rss-aggregator]'); ?>`


== Frequently Asked Questions ==
= How do I display the imported feed items? =

You can either use the shortcode in your posts and pages:
`[wp-rss-aggregator]`

or you can call the function directly within your theme:
`<?php wprss_display_feed_items(); ?>`

= Is there a limit on the number of feed sources I can use? =

There is no limit in place for the number of feed sources. Having many (50+) feed sources should not present any problems in itself.

However, pulling in posts from many sites is bound to put your server under some stress, so you might want to consider using a hosting solution that goes beyond your typical shared host. 

Check out our dedicated page for hosting recommendations.

= Does WP RSS Aggregator work using JSON as the source? =

No, our plugin does not currently import from JSON, it only imports from RSS and Atom structured XML.

= Why do I get “No feed items found” when I insert the shortcode on a page or post? =

Try adding a few more feed sources and make sure they are valid by using the RSS Feed validator.

Secondly make sure your WordPress cron system is working well. If not, the feeds cannot be imported. If in doubt you can go to RSS Aggregator > Debugging and hit the red button to re-import all feed items. If the problem persists contact support.

= Can I store imported feed items as posts? = 

Yes! You can do that with the [Feed to Post](http://www.wprssaggregator.com/extensions/feed-to-post) add-on. You will not only be able to store items as posts, but also as other custom post types, as well as set the author, auto set tags and categories, import images into the gallery or set featured images, and much more.

= Some RSS feeds only give a short excerpt. Any way around that? =

Yes, along with the [Feed to Post](http://www.wprssaggregator.com/extensions/feed-to-post) add-on we have another add-on called [Full Text RSS Feeds](http://www.wprssaggregator.com/extension/full-text-rss-feeds/) that can get the full content of those feeds that only supply a short excerpt.

= I’m not sure which premium add-ons are right for me. Can you help me out? =

Sure! We wrote a post just for you. Read about which add-ons you should buy, we explain the different types of usage so you’ll know what to expect when purchasing.

If you need any further help you can contact our support team [here](http://www.wprssaggregator.com/contact/).

= Where can I find the documentation for the plugin? =

The full documentation section can be found on the [WP RSS Aggregator website](www.wprssaggregator.com/documentation/), the documentation also includes an extensive FAQ list.


== Screenshots ==

1. Feed items imported by WP RSS Aggregator displayed on the front-end using the shortcode.

2. Feed Items imported by WP RSS Aggregator and displayed with the [Excerpts & Thumbnails](http://www.wprssaggregator.com/extensions/excerpts-thumbnails) add-on installed.

3. Adding/Editing a feed source.

4. The feed sources.

5. The imported feeds items.

6. WP RSS Aggregator's Settings page.


== Changelog ==

= 4.7.7 (2015-10-19) =
* Enhanced: Optimized checking for plugin updates.

= 4.7.6 (2015-10-07) =
* Enhanced: Feeds that fail to validate due to whitespace at the beginning are now supported by the plugin.
* Fixed bug: Undefined variables in the System Info section in the Debugging page.
* Fixed bug: Add-on license expiration notices could not be dismissed.

= 4.7.5 (2015-09-02) =
* Usage tracking now disabled.
* Fixed bug: error related to undefined `ajaxurl` JS variable gone from frontend.
* Enhanced: Licensing errors will be output to debug log.
* Enhanced: Improved compatibility with plugins that allow AJAX searching in the backend.

= 4.7.4 (2015-08-20) =
* Requirement: WordPress 4.0 or greater now required.
* Fixed bug in image caching
* Fixed bug in admin interface due to incorrectly translated IDs

= 4.7.3 (2015-08-04) =
* Enhanced: Core now implements an image cache logic.
* Enhanced: Add-ons on the "Add-ons" page now have an installed-but-inactive status.
* Enhanced: Google Alerts permalinks will now be normalized.
* Enhanced: Russian translation added.
* Fixed bug: Inline help (tooltips) translations now work.
* Fixed bug: Link to the Feed to Post add-on on the welcome page is no longer broken.

= 4.7.2 (2015-06-30) =
* Enhanced: Copyright updated.
* Fixed bug: Word trimming no longer adds extra closing tags at the end.
* Fixed bug: Presence of `idna_convert` class no longer causes infinite redirects on some servers.
* Fixed bug: Warning of unterminated comment no longer thrown in PHP 5.5.
* Fixed bug: Added default value for "Unique Titles" option.
* Fixed bug: Having a the port number specified with the database host no longer causes issues with the `mysqli` adapter in System Info on some servers.
* Fixed bug: Nested options of inline help controller no longer cause a fatal error.
* Fixed bug: Notices will no longer be displayed during rendering of feed items due to absence of required default values.

= 4.7.1 (2015-04-23) =
* Fixed bug: No warning will be thrown when fetching feeds.

= 4.7 (2015-04-21) =
* New: Optionally import only items with titles that don't already exist.
* Enhanced: Accessing feeds over HTTPS is now possible.
* Enhanced: Added support for multibyte strings in some places.
* Enhanced: Increased JS compatibility with other plugins.
* Enhanced: Increased UI support for mobile devices.
* Fixed bug: Having no mysqli extension no longer causes an error to appear in the debug info.
* Fixed bug: Saving an empty license key no longer results in a warning. 

= 4.6.13 (2015-03-20) =
* Fixed bug: The "Force feed" option wasn't being correctly used.

= 4.6.12 (2015-03-09) =
* Fixed bug: The "Force feed" option was being removed by the Feed to Post add-on.

= 4.6.11 (2015-03-04) =
* Enhanced: The Help page now includes a support form if a premium add-on is detected.
* Enhanced: Updated some translations for admin options.
* Fixed bug: Help tooltips are now optimized for iPad screens.
* Fixed bug: Errors on the licensing page when a license code has not yet been entered.

= 4.6.10 (2015-02-10) =
* Enhanced: AJAX license activation.
* Enhanced: License form more reliable.
* Enhanced: license-related UI improvements
* New: Markdown library added. Changelog now read from readme.
* Fixed bug: Saving license keys not longer triggers error in some cases.

= 4.6.9 (2015-01-21) =
* Enhanced: Admin user will now be warned about invalid or expiring licenses.
* Enhanced: Admin notices logic centralized in this plugin.
* Fixed: Multiple small-scale security vulnerabilities.
* Fixed: Ampersand in feed URL no longer causes the product of generated feeds to be invalidated by W3C Validator.

= 4.6.8 (2015-01-07) =
* Enhanced: Added more logging during feed importing.
* Enhanced: Irrelevent metaboxes added by other plugins are now removed from the Add/Edit Feed Source page.
* Fixed bug: Valid feed URLS were being invalidated.
* Fixed bug: The Blacklist feature was being hidden when the Feed to Post add-on was enabled.
* Fixed bug: Patched a vulnerability where any user on the site can issue a feed fetch.
* Fixed bug: The "Activate" and "Pause" actions are not shown in the bulk actions dropdown in WordPress v4.1.

= 4.6.7 (2014-12-17) =
* Enhanced: Some minor interface updates.
* Enhanced: Added filters for use by the premium add-ons.

= 4.6.6 (2014-12-06) =
* Enhanced: Added output layouts for feed sources and feed items.
* Enhanced: Updated EDD updater class to version 1.5.
* Enhanced: Added time limit extending to prevent script from exhausting its execution time limit while importing.
* Fixed bug: The "Delete and Re-import" button was deleting items but not re-importing.
* Fixed bug: Non-object errors when a feed source is deleted while importing.

= 4.6.5 (2014-11-17) =
* Enhanced: Improved the logging.
* Enhanced: Improved the licensing fields.
* Enhanced: Updated the EDD updater class to the latest version.
* Fixed bug: Small random error when viewing the licenses page.

= 4.6.4 (2014-11-10) =
* Enhanced: Added filters to the custom feed.
* Enhanced: Updated some styles to improve the user interface.
* Fixed bug: The "Remove selected from Blacklist" button had no nonce associated with it.
* Fixed bug: The Blacklist menu entry was not always being shown.

= 4.6.3 (2014-11-3) =
Enhanced: Re-added the "Add New" link in the plugin's menu.
Enhanced: Improved error logging.
Enhanced: Bulk actions in the Feed Sources page are now also included in the bottom dropdown menu.
Fixed bug: Add-on updater was prone to conflicts. Now enclosed in an action.
Fixed bug: The Full Text RSS Feeds add-on was not showing as active in the "Add-ons" page.
Fixed bug: Broken links in the "Add-ons" page, to add-on pages on our site.

= 4.6.2 (2014-10-15) =
* Enhanced: Improved plugin responsiveness.
* Enhanced: Updated some help text in tooltips with better explainations and added clarity.
* Enhanced: Optimized some old SQL queries.
* Enhanced: Added better debug logging.
* Enhanced: Added a new filter to modify the text shown before author names.
* Fixed bug: Licenses were not showing as active, even though they were activated.

= 4.6.1 (2014-10-06) =
* Enhanced: Improved internationalization in the plugin, for better translations.
* Fixed bug: If the feed source age limit was left empty, the global setting was used instead of ignoring the limit.

= 4.6 (2014-09-22) =
* Enhanced: Improved the user interface, with better responsiveness and tooltips.
* Enhanced: Removes the ID column. The ID is now shown fixed in row actions.
* Enhanced: Feed Preview indicates if feed items have no dates.
* Fixed bug: If a feed item has no date, the date and time it was imported is used.

= 4.5.3 (2014-09-15) =
* New Featured: Added filter to allow adding RSS feeds to the head of your site's pages for CPTs.
* Enhanced: Columns in the feed sources table are now sortable.
* Enhanced: Removed the ID column in the feed sources table. The ID has been moved as a row action.
* Enhanced: Improved various interface elements.
* Enhanced: Better responsiveness for smaller screen.
* Fixed bug: The importing spinning icon would get stuck and spin for a very long time.
* Fixed bug: Removed an old description meta field.
* Fixed bug: Plugin was not removing all scheduled cron jobs when deactivated.

= 4.5.2 (2014-09-09) =
* Enhanced: Optimized plugin for WordPress 4.0.
* Enhanced: Improved template and added filters for add-on hooking.
* Fixed bug: Editor toolbar visible over the WP RSS shortcode dialog.

= 4.5.1 (2014-08-26) =
* Fixed bug: Last import feed item count stays at zero.
* Fixed bug: Datetime::setTimestamp error when using PHP 5.2 or earlier.
* Fixed bug: The display limit was not working.
* Fixed bug: Minor bug in licensing.

= 4.5 (2014-08-25) =
* New Feature: Bulk importer allows you to create multiple feed sources at once.
* Enhanced: Improved OPML importer with added hooks.
* Enhanced: Centralized add-on licensing, fixing multiple bugs.
* Fixed bug: Undefined `feed_limit` errors when using the shortcode.

= 4.4.4 (2014-08-19) =
* Fixed bug: Errors when using older PHP versions 5.3 or lower.

= 4.4.3 (2014-08-19) =
* Fixed bug: Errors when using older PHP versions 5.3 or lower.

= 4.4.2 (2014-08-19) =
* Fixed bug: Errors when using older PHP versions 5.3 or lower.

= 4.4.1 (2014-08-18) =
* Enhanced: Various improvements to the plugin interface and texts.
* Enhanced: Moved the restore default settings button farther down the Debugging page, to avoid confusion with the delete button.
* Fixed bug: Feed item dates were not being adjusted to the timezone when using a GMT offset.
* Fixed bug: Feed item dates are now adjusted according to daylight savings time.

= 4.4 (2014-08-11) =
* New Feature: Blacklist - delete items and blacklist them to never import them again.
* Enhanced: Added a button in the Debugging page to reset the plugin settings to default.
* Enhanced: WordPress Yoast SEO metaboxes and custom columns will no longer appear.

= 4.3.1 (2014-08-08) =
* Enhanced: Better wording on settings page.
* Fixed bug: The Links Behaviour option in the settings was not working.
* Fixed bug: The wrong feed items were being shown for some sources when using the "View Items" row action.

= 4.3 (2014-08-04) =
* New Feature: Feed items now also import authors.
* Enhanced: Custom feed is now in RSS 2.0 format.
* Enhanced: Improved the display template for feed items.
* Fixed bug: Custom feed was not working in Firefox.
* Fixed bug: Some feed items were showing items from another feed source.
* Fixed bug: The feed limit in the global settings was not working.

= 4.2.3 (2014-07-29) =
* Enhanced: Added an option to choose between the current pagination type, and numbered pagination.
* Enhanced: The Feed Preview now also shows the total number of items in the feed.
* Fixed bug: A PHP warning error was being shown in the System Info.
* Fixed bug: Language files were not always being referenced correctly.
* Fixed bug: Manually fetching a feed fails if the feed is scheduled to update in the next 10 minutes.
* Fixed bug: Bing RSS feeds were importing duplicates on every update.

= 4.2.2 (2014-07-23) =
* Enhanced: Facebook page feeds are now changed into RSS 2.0 feeds, rather than Atom 1.0 feeds.
* Enhanced: Improved live updating performace on the Feed Sources page.

= 4.2.1 (2014-07-17) =
* Enhanced: Feed Sources page is now more responsive.

= 4.2 (2014-07-17) =
* New Feature: Can now view each feed source's imported feed items separate from other feed sources' feed items.
* Enhanced: Major visual update to the Feed Sources page with new live updates.
* Enhanced: The custom feed now includes the feed source.
* Fixed bug: Google News feeds were importing duplicate items on every update.
* Fixed bug: Multiple minor bug fixes with old filters.

= 4.1.6 (2014-06-28) = 
* Fixed bug: Results returned by wprss_get_feed_items_for_source() will no longer be affected by filters.
* Fixed bug: Charset issue in titles

= 4.1.5 (2014-06-19) =
* Enhanced: The Feed Sources table now indicates which feed sources encountered errors during the last import.
* Fixed bug: Feed titles were not being decoded for HTML entities.

= 4.1.4 (2014-05-16) =
* Enhanced: Minor improvements to feed importing and handling.
* Fixed bug: HTML entities were not being decoded in feed item titles.

= 4.1.3 (2014-04-28) =
* Enhanced: Added a force feed option, for valid RSS feeds with incorrect header content types.
* Fixed bug: HTML entities in feed item titles are now being decoded.

= 4.1.2 (2014-04-22) =
* Enhanced: Improved the custom feed, by allowing a custom title.
* Enhanced: Improved shortcode, by adding the "pagination" parameter.
* Enhanced: Modified a filter to fix some bugs in the add-ons.

= 4.1.1 (2014-04-09) =
* Enhanced: Tracking notices only appear for admin users.
* Fixed bug: Auto Feed Discovery was not working.

= 4.1 (2014-04-03) =
* New Feature: Feed items can now link to enclosure links in the feed.
* Enhanced: Added a filter to allow add-ons to modify feed item queries.

= 4.0.9 (2014-03-27) =
* Enhanced: Added a filter to modify the feeds template.
* Fixed bug: Nested lists in feeds template.

= 4.0.8 (2014-03-20) =
* Fixed bug: Using the shortcode makes the comments section always open.

= 4.0.7 (2014-03-08) =
* Fixed bug: The plugin prevented uploading of header images.

= 4.0.6 (2014-03-05) =
* Fixed bug: Hook change in last version suspected reason for some installations having non-updated feed items.

= 4.0.5 (2014-03-03) =
* New Feature: Time ago added as an option.
* Enhanced: The plugin now allows the use of RSS and Atom feeds that do not specify the correct MIME type.
* Enhanced: Better performance due to better hook usage.
* Fixed bug: Facebook page feed URL conversion was not being triggered for new feed sources.
* Fixed bug: Styles fix for pagination.
* Fixed bug: Removed empty spaces in logging.

= 4.0.4 (2014-02-17) =
* Enhanced: Added Activate/Pause bulk actions in the Feed Sources page.
* Enhanced: Feed Sources page table has been re-designed.
* Enhanced: Logging is now site dependant on multisite.
* Fixed bug: Undefined display settings where appearing on the front end.

= 4.0.3 (2014-02-12) =
* Fixed bug: The general setting for deleting feed items by age was not working.

= 4.0.2 (2014-02-10) =
* Enhanced: Added a filter to change the html tags allowed in feed item content.

= 4.0.1 (2014-02-08) =
* Fixed bug: Empty array of feed items bug caused importing problems.

= 4.0 (2014-02-04) =
* Enhanced: Improved some internal queries, for better performance.
* Fixed bug: Feed limits were not working properly.

= 3.9.9 (2014-02-03) =
* Enhanced: The custom feed can now be extended by add-ons.

= 3.9.8 (2014-01-20) =
* Fixed bug: Removed excessive logging from Debugging Error Log.

= 3.9.7 (2014-01-17) =
* Fixed bug: Bug in admin-debugging.php causing trouble with admin login

= 3.9.6 (2014-01-17) =
* Enhanced: Added error logging.

= 3.9.5 (2014-01-02) =
* Enhanced: Added a feed validator link in the New/Edit Feed Sources page.
* Enhanced: The Next Update column also shows the time remaining for next update, for feed source on the global update interval.
* Enhanced: The custom feed has been improved, and is now identical to the feeds displayed with the shortcode.
* Enhanced: License notifications only appear on the main site when using WordPress multisite.
* Enhanced: Updated Colorbox script to 1.4.33
* Fixed bug: The Imported Items column was always showing zero.
* Fixed bug: Feed items not being imported with limit set to zero. Should be unlimited.
* Fixed bug: Fat header in Feed Sources page

= 3.9.4 (2013-12-24) =
* Enhanced: Added a column in the Feed Sources page that shows the number of feed items imported for each feed source.
* Fixed bug: Leaving the delete old feed items empty did not ignore the delete.

= 3.9.3 (2013-12-23) =
* Fixed bug: Fixed tracking pointer appearing on saving settings.

= 3.9.2 (2013-12-21) = 
* Fixed bug: Incorrect file include call.

= 3.9.1 (2013-12-12) =
* Enhanced: Improved date and time handling for imported feed items.
* Fixed bug: Incorrect values being shown in the Feed Processing metabox.
* Fixed bug: Feed limits set to zero were causing feeds to not be imported.

= 3.9 (2013-12-12) =
* New Feature: Feed sources can have their own update interval.
* New Feature: The time remaining until the next update has been added to the Feed Source table.

= 3.8 (2013-12-05) =
* New Feature: Feed items can be limited and deleted by their age.
* Enhanced: Added utility functions for shorter filters.
* Fixed bug: License codes were being erased when add-ons were deactivated.
* Fixed bug: Some feed sources could not be set to active from the table controls.
* Fixed bug: str_pos errors appear when custom feed url is left empty.
* Fixed bug: Some options were producing undefined index errors.

= 3.7 (2013-11-28) =
* New Feature: State system - Feed sources can be activated/paused.
* New Feature: State system - Feed sources can be set to activate or pause themselves at a specific date and time.
* Enhanced: Added compatibility with nested outline elements in OPML files.
* Enhanced: Admin menu icon image will change into a Dashicon, when WordPress is updated to 3.8 (Decemeber 2013).
* Fixed bug: Custom Post types were breaking when the plugin is activated.

= 3.6.1 (2013-11-17) =
* Fixed bug: Missing 2nd argument for wprss_shorten_title()

= 3.6 (2013-11-16) =
* New Feature: Can set the maximum length for titles. Long titles get trimmed.
* Fixed bug: Fixed errors with undefined indexes for unchecked checkboxes in the settings page.
* Fixed bug: Pagination on front static page was not working.

= 3.5.2 (2013-11-11) =
* Fixed bug: Invalid feed source url was producing an Undefined method notice.
* Fixed bug: Custom feed was producing a 404 page.
* Fixed bug: Presstrends code firing on admin_init, opt-in implementation coming soon

= 3.5.1 (2013-11-09) =
* Enhanced: Increased compatibility with RSS sources.
* Fixed bug: Pagination not working on home page

= 3.5 (2013-11-6) =
* New Feature: Can delete feed items for a particular source
* Enhanced: the 'Fetch feed items' row action for feed sources resets itself after 3.5 seconds.
* Enhanced: The feed image is saved for each url.
* Fixed bug: Link to source now links to correct url. Previously linked to site's feed.

= 3.4.6 (2013-11-1) =
* Enhanced: Added more hooks to debugging page for the Feed to Post add-on.
* Fixed bug: Uninitialized loop index

= 3.4.5 (2013-10-30) =
* Bug Fix: Feed items were not being imported while the WPML plugin was active.

= 3.4.4 (2013-10-26) =
* New feature: Pagination
* New feature: First implementation of editor button for easy shortcode creation
* Enhanced: Feed items and sources don't show up in link manager
* Enhanced: Included Presstrends code for plugin usage monitoring

= 3.4.3 (2013-10-20) =
* Fixed bug: Removed anonymous functions for backwards PHP compatibility
* Bug fix: Added suppress_filters in feed-display.php to prevent a user reported error
* Bug fix: Missing <li> in certain feed displays

= 3.4.2 (2013-9-19) =
* Enhanced: Added some hooks for Feed to Post compatibility
* Enhanced: Moved date settings to a more appropriate location

= 3.4.1 (2013-9-16) = 
* Fixed Bug: Minor issue with options page - PHP notice

= 3.4 (2013-9-15) =
* New Feature: Saving/Updating a feed source triggers an update for that source's feed items.
* New Feature: Option to change Youtube, Vimeo and Dailymotion feed item URLs to embedded video players URLs
* New Feature: Facebook Pages URLs are automatically detected and changed into Atom Feed URLs using FB's Graph
* Enhanced: Updated jQuery Colorbox library to 1.4.29
* Fixed Bug: Some settings did not have a default value set, and were throwing an 'Undefined Index' error
* Fixed Bug: Admin notices do not disappear immediately when dismissed.

= Version 3.3.3 (2013-09-08) =
* Fixed bug: Better function handling on uninstall, should remove uninstall issues

= Version 3.3.2 (2013-09-07) =
* New feature: Added exclude parameter to shortcode
* Enhanced: Added metabox links to documentation and add-ons
* Fixed bug: Custom feed linking to post on user site rather than original source
* Fixed bug: Custom post types issues when activitating the plugin

= Version 3.3.1 (2013-08-09) =
* Fixed Bug: Roles and Capabilities file had not been included
* Fixed Bug: Error on install, function not found

= Version 3.3 (2013-08-08) =
* New feature: OPML importer
* New feature: Feed item limits for individual Feed Sources
* New feature: Custom feed URL
* New feature: Feed limit on custom feed
* New feature: New 'Fetch feed items' action for each Feed Source in listing display
* New feature: Option to enable link to source
* Enhanced: Date strings now change according to locale being used (i.e. compatible with WPML)
* Enhanced: Capabilities implemented
* Enhanced: Feed Sources row action 'View' removed
* Fixed Bug: Proxy feed URLs resulting in the permalink: example.com/url

= Version 3.2 (2013-07-06) =
* New feature: Parameter to limit number of feeds displayed
* New feature: Paramter to limit feeds displayed to particular sources (via ID)
* Enhanced: Better feed import handling to handle large number of feed sources

= Version 3.1.1 (2013-06-06) =
* Fixed bug: Incompatibility with some other plugins due to function missing namespace

= Version 3.1 (2013-06-06) =
* New feature: Option to set the number of feed items imported from every feed (default 5)
* New feature: Import and Export aggregator settings and feed sources
* New feature: Debugging page allowing manual feed refresh and feed reset
* Enhanced: Faster handling of restoring sources from trash when feed limit is 0
* Fixed bug: Limiter on number of overall feeds stored not working
* Fixed bug: Incompatibility issue with Foobox plugin fixed 
* Fixed bug: Duplicate feeds sometimes imported

= Version 3.0 (2013-03-16) =
* New feature: Option to select cron frequency
* New feature: Code extensibility added to be compatible with add-ons
* New feature: Option to set a limit to the number of feeds stored (previously 50, hard coded)
* New feature: Option to define the format of the date shown below each feed item
* New feature: Option to show or hide source of feed item
* New feature: Option to show or hide publish date of feed item
* New feature: Option to set text preceding publish date
* New feature: Option to set text preceding source of feed item
* New feature: Option to link title or not
* New feature: Limit of 5 items imported for each source instead of 10
* Enhanced: Performance improvement when publishing * New feeds in admin
* Enhanced: Query tuning for better performance
* Enhanced: Major code rewrite, refactoring and inclusion of hooks
* Enhanced: Updated Colorbox to v1.4.1
* Enhanced: Better security implementations	
* Enhanced: Better feed preview display
* Fixed bug: Deletion of items upon source deletion not working properly
* Requires: WordPress 3.3

= Version 2.2.3 (2012-11-01) =
* Fixed bug: Tab navigation preventing typing in input boxes
* Removed: Feeds showing up in internal linking pop up

= Version 2.2.2 (2012-10-30) =
* Removed: Feeds showing up in site search results
* Enhanced: Better tab button navigation when adding a new feed
* Enhanced: Better guidance when a feed URL is invalid

= Version 2.2.1 (2012-10-17) =
* Fixed bug: wprss_feed_source_order assumes everyone is an admin

= Version 2.2 (2012-10-01) =
* Italian translation added
* Feed source order changed to alphabetical
* Fixed bug - repeated entries when having a non-valid feed source
* Fixed bug - all imported feeds deleted upon trashing a single feed source

= Version 2.1 (2012-09-27) =
* Now localised for translations
* Fixed bug with date string
* Fixed $link_before and $link_after, now working
* Added backwards compatibility for wp_rss_aggregator() function

= Version 2.0 (2012-09-21) =
* Bulk of code rewritten and refactored
* Added install and upgrade functions
* Added DB version setting
* Feed sources now stored as Custom Post Types
* Feed source list sortable ascending or descending by name
* Removed days subsections in feed display
* Ability to limit total number of feeds displayed
* Feeds now fetched via Cron
* Cron job to delete old feed items, keeps max of 50 items in DB
* Now requires WordPress 3.2
* Updated colorbox to v1.3.20.1
* Limit of 15 items max imported for each source
* Fixed issue of page content displaying incorrectly after feeds

= Version 1.1 (2012-08-13) =
* Now requires WordPress 3.0
* More flexible fetching of images directory
* Has its own top level menu item
* Added settings section
* Ability to open in lightbox, new window or default browser behaviour
* Ability to set links as follow or no follow
* Using constants for oftenly used locations
* Code refactoring
* Changes in file and folder structure

= Version 1.0 (2012-01-06) =
* Initial release.
