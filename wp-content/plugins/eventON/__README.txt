=== EventON ===
Contributors: Ashan Jay
Plugin Name: EventON
Author URI: http://ashanjay.com/
Tags: calendar, event calendar, event posts
Requires at least: 3.8
Tested up to: 4.3
Stable tag: 2.3.10

Event calendar plugin for wordpress that utilizes WP's custom post type.  

== Description ==
Event calendar plugin for wordpress that utilizes WP's custom post type. This plugin integrate eventbrite API to create paid events, add limited capacity to events, and accept payments for paid events or allow registration for free events. This plugin will add an AJAX driven calendar with month-view of events to front-end of your website. Events on front-end can be sorted by date or title. You can easily add events with multiple attributes and customize the calendar layout or build your own calendar using event post meta data. 

== Installation ==

1. Unzip the download zip file
1. Upload 'eventon' to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 2.3.10 (2015-9-21) =
ADDED: Missing this month button appearance into settings
FIXED: end time not saving correct on event edit page
FIXED: Repeating events not saving correctly (thanks oliver from germany)
FIXED: organizer image not to be a square but medium size
FIXED: Eventtop event type not getting translated correctly
FIXED: location and organizer images not working on taxonomy term pages
FIXED: Colorpicker not loading right on event type term pages

= 2.3.9 (2015-8-25) =
FIXED: Compatibility with WP 4.3 widget error
FIXED: error when language settings saving
FIXED: Not able to feature events via star icon & other permission issues

= 2.3.8 (2015-8-5) =
FIXED: BCC email error on helper class
FIXED: Time cutoff to wordpress site's local time
UPDATED: Repeat intervals in eventon datetime class
UPDATED: email helper class & removal of HTML email filter when finished
UPDATED: to auto sync dynamic styles after update to new version

= 2.3.7 (2015-7-22) =
ADDED: Support for location cards for event locations
FIXED: Event top tags showing regardless of settings
FIXED: export events as CSV start time var name error
FIXED: ics summary and description correctly specifying title
FIXED: ics file summary going into new lines and breaking description
FIXED: repeating events not linking correct to single events page
FIXED: https error for chrome version 44
UPDATED: Featured image responsive styles for mobile
UPDATED: organizer field in language settings

= 2.3.6 (2015-7-9) =
ADDED: New AJDE library to help scaling of eventon
FIXED: Organizer image not able to save
FIXED: Repeating days of week not selected correct after save
FIXED: Organizer contact field not saving correct
FIXED: Custom meta data field titles not translating in event card
FIXED: Tile styles
FIXED: Slashes in location name
UPDATED: Styles for tiles layout mobile view
UPDATED: Event top event types be seperated by commas

= 2.3.5 (2015-6-9) =
FIXED: Individual event user interaction not working
UPDATED: Eventon helper function to support bcc type emailing
UPDATED: code yesno value for settings

= 2.3.4 (2015-5-28) = 
FIXED: Uninstall error
FIXED: event edit page not showing event settings
 
= 2.3.3 (2015-5-28) = 
FIXED: repeat events custom not saving times correct
FIXED: User interaction not working correct on tiles
FIXED: new shortcode variable to show repeating events while hide multiple occurance of event is active
UPDATED: POT language file with new and missing strings
REMOVED: eventon shortcode button from wysiwyg editor due to multiple conflicts with themes

= 2.3.2 (2015-5-20) =
ADDED: Ability to hide individual data in event card
ADDED: Support for event tags
ADDED: Ability to style featured events from appearance
ADDED: ability to sort events by posted date
ADDED: Option to not delete eventon settings when deleting plugin
ADDED: Ability to import and export language translations for eventon
FIXED: fullcal and dailyview not moveing featured events up
FIXED: Subscriber not showing for other views of calendar
FIXED: Tile layout to allow single event page clicks
UPDATED: Events tax in the calendar
UPDATED: Users taxonomy for calendar
UPDATED: minor code fixes
UPDATED: font aweosme icons to version 4.3

= 2.3.1 (2015-4-22) = 
FIXED: Missing translation for go to today
FIXED: Event Card not showing for single events page
FIXED: Event type categories not working correct
FIXED: Hide filtering options dropdowns
UPDATED: Events linking to external links to not load event card HTML
UPDATED: Missing event cancelled in language
UPDATED: JQuery triggers for goto today button

= 2.3 (2015-4-15) =
ADDED: Tile based layout for event calendar
ADDED: Timezone text support for event time
ADDED: Go to today button for calendar to return to current month
ADDED: Ability to export events 
ADDED: Support for event type category archive page
ADDED: Ability to add image for organizer
UPDATED: Minor updates to javascript for eventon
UPDATED: Re-organized files for plugin
UPDATED: theme and styles to incorporated missing new elements
UPDATED: ICS file slug causing errors
FIXED: Foreach error on language tab for RSVP
FIXED: Tooltip not showing up correct on page
FIXED: Cancel event styles on lightbox version
FIXED: google map not working off lat lon values
FIXED: repeating interval url when first creating event
FIXED: Year long events not appearing in addon versions

= 2.2.29 (2015-3-27) =
FIXED: Event location in eventcard and eventtop display errors
FIXED: Filter not working error caused by small mistake

= 2.2.28 (2015-3-25) =
ADDED: location name to schema for event
ADDED: shortened event description to ICS file instead of name
ADDED: event type category terms language translation
ADDED: Ability to remove meta data for eventon generator version
ADDED: Ability to cancel events and show on calendar
FIXED: NOT filter in shortcode not working when switching months
FIXED: Calendar ux_val to override event ux_val
FIXED: Event Paging setting to show all available pages
UPDATED: Event card HTML to not load when not needed
UPDATED: Addons not saving activations fixed
UPDATED: Language translations for admin side
UPDATED: Sorting custom repeat intervals on first save
UPDAETD: Get directions to use https
UPDATED: Improvement to code handling and reusage of code
UPDATED: main lang translation function
UPDATED: Support for event type translatable text
UPDATED: POT file for admin translations


= 2.2.27 (2015-2-24) =
ADDED: Location name to schema data for events
FIXED: Only featured events cal not working when switching months
FIXED: Location image picker not working in event edit page
UPDATED: Included class names for event type filter terms on frontend

= 2.2.26 (2015-2-14) =
FIXED: Settings not saving correctly for eventon

= 2.2.25 (2015-2-13) =
ADDED: Support for location and organizer filtering in shortcode
DEV: new action hook evo_cal_after_footer
UPDATED: Location and organizer taxonomies to show tax term id in wp-admin
UPDATED: addon deactivation process minor bugs solved
FIXED: Trash events past date to only delete events
FIXED: UID for .ICS file to stop replacing multiple ICS add to calendar
FIXED: Event list sorting not working fixed
FIXED: Widget class missing error
FIXED: All day events to save actual beginning and end of day times when saved
FIXED: Location name not showing in eventtop

= 2.2.24 (2015-2-9) =
ADDED: Basic upcoming widget to widget collection
ADDED: Event type category widget to show only certain events in widget
ADDED: Ultra repeater - now you can set custom repeating event times
ADDED: Submenus to the eventon settings menu
ADDED: Ability to feature event from edit event page
ADDED: Reset permalink button to eventon settings for easy access
FIXED: EventON Settings custom field count not working
FIXED: Styles for lists inside event details
FIXED: EventON theme file not loading error
FIXED: Event details more less background gradient colors
FIXED: hide_so shortcode variable with filtered events lists
UPDATED: Eventon license activation and updating system
UPDATED: Minor featured image size settings corrections

= 2.2.23 (2015-1-20) =
ADDED: Option to diable font awesome font from loading
FIXED: Events not showing up in the calendar for some
FIXED: Other minor bugs

= 2.2.22 (2015-1-13) =
ADDED: Support for google maps custom color styles
ADDED: Extra google map zoom level
ADDED: New Calendar theme feature - still at beta stage and more themes in future
ADDED: Month arrow color into appearance section
FIXED: Minor issues with license activation process
FIXED: Undefined settings error on eventon settings page
FIXED: Some of the missing styles added to appearance
FIXED: Hide organizer not working
FIXED: Incorrect featured image open status

= 2.2.21 (2014-12-18) =
FIXED: Widget arrow positioning
FIXED: End time still showing even when hide end time selected
FIXED: Arrow styles
FIXED: Single events page showing open event cards for sidebar
FIXED: Show more events not working on event lists calendars
FIXED: ActionUser users variable support
FIXED: Languages not working correct on sort options section
FIXED: https for backend datepicker
FIXED: Last day of months not showing solved with setting UTC timezone
FIXED: Codestyling localization compatibility 
UPDATED: Activate debug report section

= 2.2.20 (2014-10-13) =
ADDED: Backup shortcode generator in settings
FIXED: Saving events without time cause undefine error solved
FIXED: Sorting not working
FIXED: Custom meta fields more than 3 fields not working correct
FIXED: Mobile eventTop tap not working
FIXED: Event color hex code processing incorrectly
FIXED: Jumper not moving when changing months
FIXED: Arrow styles to better work for all
UPDATED: Featured image height style options for better visual
UPDATED: month arrow JS to work off body DOM tree
UPDATED: POT files

= 2.2.19 (2014-9-18) =
FIXED: Sorting and filtering not working for eventon and addons
FIXED: Events lists month name fix
FIXED: Updated available on eventon settings addon tab
FIXED: W3C validation fixes

= 2.2.18 (2014-9-16) =
FIXED: Minor style issues
FIXED: Sorting not working error
FIXED: ux_val=X not working
FIXED: EventCard open by default settings fixed
FIXED: Show more events text missing in language
FIXED: Google fonts for SSL based https urls
UPDATED: Different method to check installed addons

= 2.2.17 (2014-9-10) =
ADDED: Mobile tap on eventtop and jquery mobile support
ADDED: Upto 10 custom meta data fields for events can now be activated
ADDED: Event Location and organizer fields as filtering options
ADDED: Support for NOT event type taxonomy eg. event_type="NOT-23" will exclude tax tag 23 events
ADDED: Location name and address over location image
ADDED: View more events button for calendar to show events as needed
ADDED: The ability to offset jumper start year
ADDED: New event paging section to settings to manage event archive page templates and slug
FIXED: Featured image height 100% fix
FIXED: Location Image dissapearing when updating events
FIXED: Location address and name with aphostrophe not saving correct
FIXED: Event Card open by default not working properly
FIXED: Event type color override is not working on calendars
FIXED: Event type 2 term tags not showing correctly on event top
FIXED: Generate google maps default yes value not working
FIXED: All day event date name capitalized properly
FIXED: All day events showing incorrectly on eventcard
FIXED: Category translation not working on calendar
UPDATED: Addons page not showing installed addons
UPDATED: All addons to support new eventon exists check
UPDATED: Location and organizer meta box in event edit page
UPDATED: Remove _blank on get directions for only mobile
UPDATED: JQuery UI CSS to 1.11 version 
UPDATED: Event Edit page UI

= 2.2.16 (2014-8-19) =
FIXED: Jquery nodevalue error when passing shortcode arguments between months
FIXED: Featured image at full height when switching months
FIXED: Minor style issues
FIXED: Minor bugs related to eventtop fields
UPDATED: User capabilities for actionUser compatibility

= 2.2.15(2014-8-13) =
ADDED: WPML compatibility
ADDED: Event Subtitle can now be added and styled
ADDED: The ability to select all categories to be shown on eventTop
ADDED: Event Location Image field - this need to be configured in settings first to show
ADDED: Organizer meta field with similar event location method
ADDED: Disable onClick zoom effect on event featured image
ADDED: Ability to create year around event without a specific date
ADDED: Ability to auto trash old event posts from wp-admin
ADDED: Option to hide sort options section per each calendar in the shortcode
ADDED: Shortcode generator to ESE widget
ADDED: Class names to custom meta fields so styles can be applied
UPDATED: Event Location saving machanism with better verification and using terms
UPDATED: i18n fields with missing plugin textdomain for translation
UPDATED: event end time can now be set to last beyond start date and still visible in calendar
UPDATED: Responsive Featured image styles
UPDATED: Google maps generate to be set to yes by default
UPDATED: Lightbox eventcard close button X made lighter for visibility
UPDATED: minor style issues are solved
FIXED: upcoming list hide past not working
FIXED: hide end date honored in dailyview times
FIXED: Featured events only calendar issue
FIXED: evo addon class redeclare error
FIXED: Minor style issues
FIXED: Custom universal times remove end time from event card
FIXED: 23 hour format G to be recognized in wp-admin time selection fields
FIXED: RGB related Javascript error on wp-admin that was stopping yes/no button function


= 2.2.14(2014-7-3) =
ADDED: Ability to exclude events from calendars without deleting them
ADDED: Overall calendar user interaction do not interact value
UPDATED: Removed month header blank space from event list
UPDATED: Schema SEO data to have end date that was missing
UPDATED: Improvements to Paypal intergration into front-end
UPDATED: Seperate function to load for calendar footer with action hook evo_cal_footer
UPDATED: Pretty time on eventcard converted into proper language
FIXED: Repeat events for week of the month not showing correct
FIXED: Addon license activation page not working correctly
FIXED: Hide multiple occurence not showing events on other calendars on same page
FIXED: Repeating events time now showing correct on event card
FIXED: Schema SEO showing event page URL when someone dont have single events addon
FIXED: shortcode generator showing a body double

= 2.2.13 (2014-6-16) =
FIXED: Option for adding dynamic styles to inline page when dynamic styles are not saved
FIXED: featured image on eventTop not showing
FIXED: shortcode generator not opening from wysiwyg editor button
FIXED: eventtop styles and HTML that usually get overridden by theme styles
UPDATED: Eventon addons page to now use ajax to load content
UPDATED: New welcome screen - hope you guys will like this

= 2.2.12 (2014-6-1) =
ADDED: yes no buttons to be translatable via I18n
ADDED: the ability to select start or end date for past event cut off
ADDED: option to limit remote server checks option if eventon wp-admin pages are loading slow due to remote server checks
ADDED: Addon license activation system 
UPDATE: Did some serious improvements to cut down remote server check to increase speed
UPDATED: improvements to addon class and eventon remote updater classes
UPDATED: UI layout for addons and license page
FIXED: removed eventon shortcode button from WYSIWYG editor on event-edit post page
FIXED: error on class-calendar_generator line 1595 with event color value
FIXED: styles not saving correct in the settings
FIXED: on widget time and location to be rows by itself
FIXED: several other minor bugs

= 2.2.11 (2014-5-19) =
ADDED: rtl support
ADDED: event type #3 into shortcode options if activated
ADDED: shortcode option to expand sort options section on load per calendar
ADDED: the ability to show featured image for events at 100% height
ADDED: the ability to turn off schema data for events
ADDED: the ability to turn off google fonts completely
ADDED: extended repeat feature to support first, second etc. friday type repeat events
ADDED: option to copy auto generated dynamic styles in case appearance doesnt save changes
UPDATED: UI super smooth all CSS yes/no buttons
UPDATED: Color picker rainbow circle no more changed it to a button
UPDATED: unix for virtual repeat events to be stored from back-end to reduce load on front-end
UPDATED: sort options and filters to close when clicked outside
FIXED: jumper month names
FIXED: eventon javascripts to load only on events pages in backend
FIXED: license activation issue solved
FIXED: events menu not showing up for some on left menu
FIXED: eventon popup box not showing correct solved z-index
FIXED: small bugs

= 2.2.10 (2014-5-5) =
ADDED: you can now show only featured events in the calendar with only_ft shortcode variable
ADDED: load calendars pre-sorted by date or title with sort_by variable
ADDED: add to google calendar button and updated add to calendar button
ADDED: one letter month names for language translation for month jumper
ADDED: accordion like event card opening capabilty controlled via shortcode
ADDED: You can now add custom meta fields to eventTop
ADDED: custom meta field names can be translated in languages now
ADDED: End 3 letter month to eventTop date - now month shortname is always on
ADDED: ability to customize the eventCard time format
ADDED: ability to open links in new window for custom field content type = buttons
ADDED: wp-admin sort events by event location column
UPDATED: Month jumper to jump months upon first change in time
UPDATED: PO file for eventon Admin pages
UPDATED: Sort options section to be more intuitive for user
UPDATED: Events list event order DESC now order months in descending order as well
UPDATED: matching events menu icon based off font icons
FIXED: Arrow circle CSS for IE
FIXED: default event color missing # on hex code
FIXED: Wysiwyg editor eventon shortcode generator icon not opening lightbox
FIXED: Event type ID column for additional event type categories
FIXED: Lon lat not saving for location addresses
FIXED: Secondary languages not getting correct words when switching months
FIXED: improvements to speed eventON and cut down server requests
FIXED: featured image hover issues
FIXED: Custom meta field activation on eventCard and reordering bug
FIXED: font bold not reflecting on event details
FIXED: the content filter disable settings issue

= 2.2.9 (2014-3-26) =
ADDED: More/less text background gradient to be able to change from settings
ADDED: ability to enable upto 5 additional event type categories for events
ADDED: shortcode generator button to wysiwyg editor
ADDED: the ability to turn off content filter on event details
ADDED: Language field to widget
FIXED: minor responsive styles
FIXED: zoom cursor arrow now loads from element class
FIXED: Capitalize date format on eventcard
FIXED: Featured image hover effect removal issues
FIXED: Jump months missing month and year text added to Language
CHANGED: plugin url to use a function to support SSL links

= 2.2.8 (2014-3-13) =
ADDED: Reset to default colors button for appearance settings
ADDED: Jump months option to jump to any month you want
ADDED: Ability to assign colors by event type
ADDED: the ability to create custom field as a button
ADDED: User Interaction for events be able to override by overall variable value
UPDATED: We have integrated chat support direct into eventON settings
UPDATED: the Calendar header Interface design new arrows and cleaner design
TWEAKED: main event wp_Query closing function
FIXED: bulk edit event deleting meta values for event
FIXED: Lan lat driven google map centering on the marker issue solved
FIXED: all text translations to be included in sort menu

= 2.2.7 (2014-2-13) =
ADDED: filter to eventCard and eventTop time and date strings
ADDED: filter 'eventon_eventtop_html' to allow customization for eventTop html
ADDED: filter 'eventon_google_map_url' to load custom google maps API url with custom map languages
ADDED: ability to disable featured image hover effect
ADDED: shortcode support to open event card at first load
UPDATED: shortcode generator code to support conditional variable fields
UPDATED: html element attributes changed to data- in front-end calendar
UPDATED: new data element in calendar front-end to hold all attributes to keep the calendar HTML clean
UPDATED: event locations tax posts column removed - which was no use
FIXED: schema event url itemprop
FIXED: 'less' text not getting translated on eventcard
FIXED: timezone issues to correct hide past events hiding at correct time
FIXED: loading bar not appearing due to style error
FIXED: open event card at first on events list
FIXED: Custom language other than L1 to be updated for new calendars
FIXED: add to calendar ICS file content and timezone issue resolved
FIXED: hide multiple occurance for repeating events shortcode support

= 2.2.6 (2014-1-30) =
ADDED: Ability to collpase eventON setting menus
UPDATED: settings apperance sections can now be closed for space management
UPDATED: Language page UI and pluggability
FIXED: Missing sort option selector colors from setting appearance
FIXED: quick edit incorrect saving event data when 24hour format in active
FIXED: Event popup lightbox click on page scroll bar closing popup
FIXED: eventop background color not saving issue
FIXED: Custom meta fields not saving values for events
FIXED: Widget title to use wp universal filters

= 2.2.5 (2014-1-27) =
ADDED: Event Location Name to eventTop
ADDED: Custom fields can now have Wysiwyg editor or single line text field to enter data
UPDATED: dynamic styles loading method to create a tangible eventon_dynamic_styles.css file instead of using admin-ajax.php to avoid long load times
UPDATED: Appreance color picker UI and the ability to support pluggability
UPDATED: Datepicker to consider start date when selecting end date
FIXED: 3rd custom field value not showing on calendar
FIXED: make sure settings page styles are loaded in page header

NOTE: Make sure to click save on eventON appearance to save new styles

= 2.2.4 (2014-1-12) =
FIXED: Custom meta field values not appearing correct on events page and calendar

= 2.2.3(2014-1-10) =
ADDED: Event locations can now be saved and used again for new events
ADDED: Event location name field
ADDED: featured event color can not be selected from Settings> Appearance and override the set event color with this
ADDED: event class name for featured events
ADDED: New widget to execute any eventON shortcode on sidebar
ADDED: One additional custom meta field, now we have 3 extra fields
ADDED: Font-awesome Vector/SVG icons for retina-readiness
ADDED: more options to change appearances of eventON easily
UPDATED: eventon settings UI for color picker
CHANGED: month nav arrows are now <span> elements instead of <a> elements - to avoid redirects on arrow click
FIXED: 3 letter month name not showing under event date for eventTop
FIXED: eventON widget upcoming event small bug that stopping it from showing the calendar

= 2.2.2(2013-12-21) =
ADDED: capability to add magnifying glass cursor for featured images
ADDED: event type names translatability with eventON dual lang
UPDATED: UI compatibility with wp 3.8
UPDATED: shortcode generator tooltips UI
FIXED: missing eventon settings page i18n 
FIXED: eventTop line will be a <div> if the event slideDown or else it will be <a>
FIXED: more/less text translatability and other translation issues
FIXED: L2 calendar month name switching back to L1 language when switching months
FIXED: All (sort options) text added to language translation
FIXED: event popup CSS/HTML for feature image and event type line CSS
FIXED: ics file date zone to use wordpress i18n date and location incorrect value
FIXED: event custom meta values to go through formatted filter

= 2.2.1(2013-11-30) =
ADDED: couple of wordpress pluggable functions to main calendar
FIXED: event time hours difference on front end than whats saved - using date_i18n() instead of date() now
FIXED: dual language saved value disappearing when switching languages
FIXED: draft events showing up on calendar when switching months
FIXED: month increment messing up due to february
FIXED: all day translation fixed
FIXED: ics file download error on date()
FIXED: event organizer field missing in action
UPDATED: widget to be able to set ID and hide empty months for list
UPDATED: Changed dynamic styles to load as a file and not print on header

= 2.2 (2013-11-21) =
ADDED: event quick edit can now edit more event data on the fly
ADDED: class attribute names to events based on event type category event belong to
ADDED: Get directions field to eventCard - selectable from eventCard settings. Credit to Morten Bech for the suggestion
ADDED: The ability to rearrange the order of the eventCard data fields. Credit to Gilbert Dawed for the suggestion
ADDED: ICS file for each event so events can be added a users calendar
ADDED: new license activation server to stop all errors when activating eventON
ADDED: new add eventon shortcode button next to add media button on WYSIWYG editor
ADDED: brand spanking new shortcode generator popup box with super easy intuitive steps to customize shortcodes
ADDED: ability to reverse the event order ASC or DESC
ADDED: new shortcode "event_order" -- allow ability to set reverse order per calendar
ADDED: ability to add featured image thumbnail to eventTop
ADDED: new shortcode "show_et_ft_img" - allow to show featured image on eventTop or not
ADDED: new support tab to settings page
ADDED: i18n ready and compatible POT file for translation
UPDATED: we removed events lists options area from eventon settings and its now inside shortcode box
UPDATED: template loader function to look up templates in order
UPDATED: better event image full sizing when clicked to fit calendar
UPDATED: calendar eventCard UI - including a new close button
UPDATED: eventon wp-admin wide popup box design and functionality
UPDATED: wp-admin event edit UI - now you can hide each section of event meta data and declutter the space
FIXED: widget checkbox malfunction when there are more than one widgets.
FIXED: unnecessary google maps loading in wp-admin pages
FIXED: Addons & License tab errors some people were experiencing due to XML get file from myeventon server with addons latest info

IMPORTANT: all addons need to be updated to latest to run with eventon 2.2



= 2.1.19 (2013-10-12) =
ADDED: backend time selection now changes based on WP time format - 24hr
ADDED: All events edit page dates are now sync with sitewide date format
ADDED: new option for user interaction; open an even as a popup box
ADDED: the ability to hide end time on calendar -- end date must be same as start or empty
ADDED: the ability to hide multiple occurance of events spread across several months -- on upcoming list on shortcode calendar and widget
FIXED: shortcode button adding multiples of shortcodes 
FIXED: shortcode popup box appearing empty on second occasion
FIXED: CSS sort options button overlapping
FIXED: Upcoming list featured image expanding issues
FIXED: Gmaps event Location now works w/o the address in eventTop
FIXED: google maps init javascript issue on FF fixed 
UPDATED: Date and time selection UI
UPDATED: changed data-vocabulary microSEO data to schema.org and update to fields

= 2.1.18 (2013-9-17) =
ADDED: publish event capability to the list
FIXED: Day abbreviation for custom languages
FIXED: Addon error of scandir failed

= 2.1.17 (2013-9-16) =
ADDED: The EvenTop data customization options
ADDED: Hide past events option to eventON Calendar widget
ADDED: The ability to customize the format of calendar header month/year title
ADDED: The ability to edit color of text under event title on eventTop
ADDED: Event ID can now be found by hovering over events list in wp-admin events
ADDED: [core] new filter 'eventon_sorted_dates' to access sorted events list
UPDATED: JQuery UI css to latest version
UPDATED: Backend UI a little
UPDATED: [core] myEventON Settings page tab filter
FIXED: Backend events sorting incorrect issue on all event posts list
FIXED: EventON widget event type filtering issue when switching months
FIXED: EventON Shortcode popup window not closing issue
FIXED: EventCard featured image not expanding full height sometimes
FIXED: array_merge error some people were getting for event types

New Verbage: eventTop - the event line that opens up the eventCard

= 2.1.16 (2013-8-21) =
ADDED: UX - click on featured event image to expand the image to full height
TWEAKED: UI of the frontend calendar with clean tiny icons for time and location
FIXED: Event details overflowing when floated images
FIXED: bug with upcoming events set to hide causing events to not show up on full cal and other cals
FIXED: javascript delegate() has been changes to on() based on jQuery's new change
FIXED: time and location icons can now be edited from eventON settings

= 2.1.15 (2013-8-8) =
FIXED: sort options text not dissapearing when set to hide
FIXED: javascript issue causing eventON to stop work with WP 3.6
TWEAKED: eventon Addon data are now also checked via cURL if failed with file_get_content

= 2.1.14 (2013-8-6) =
UPDATED: Back-end widget UI to a whole new level which you gonna love
ADDED: shortcode variable "hide_past" to give the ability to hide past events per each shortcode
ADDED: Fixed month/year are now supported in widget
ADDED: Ability to select scroll wheel zoom on google maps or disable it
TWEAKED: Addons pull live addon details and the UI got a face lift.
TWEAKED: License tab reside in addons tab under eventon settings now
TWEAKED: events can now be repeated longer than 10 times
TWEAKED: sort options in a minimal dropdown menu
TWEAKED: Javascripts handles can now be called at will for AJAX driven pages
FIXED: Filtering issue when using multiple filters at once
FIXED: Quick edit for events

NOTES: If you are using eventON addons most of them will give minor bugs with newer version of eventON and you will NEED to update your eventON Addons to latest versions to get them working properly.


= 2.1.13 =
ADDED: Ability to add addresses using Latitude Longitude - for addresses that are not found correctly by google.
ADDED: Shortcode guide link to shortcode popup window
FIXED: Single quote values not saving correct for organizer
FIXED: Upcoming event list month text color
FIXED: Eventbrite non-connecting issue

= 2.1.12 =
FIXED: Google Map display issue when switching months
FIXED: Backend javascript not loading into wp-admin issue
TWEAKED: Minor fixes and compatibility updates for addons

= 2.1.11 =
FIXED: Colorpicker issue on Firefox
FIXED: Daily repeats addon not working

= 2.1.10 =
ADDED: Google microdata for SEO for events included in calendar
ADDED: Ability to choose height of the event's featured image from settings
ADDED: Ability to remove more/less button in long event descriotion
ADDED: You are not limited 5 colors now, you can select your on custom event color
ADDED: Now you can add upto 2 custom fields for events and eventcard
ADDED: Ability to set fixed starting month/year for upcoming events list in shortcode
ADDED: You can now select to show year in upcoming events list
ADDED: Yearly event repeats
ADDED: Ability to set event date without time for multi-day events
UPDATED: Minor improvements to code and UI
FIXED: Event date not saving correct in some languages due to WP default date format and JQ UI datepicker issue. Now you can select either to use wp default date format in backend date selection or not. (if you chose not, the date format will be yyyy/mm/dd)
FIXED: Template locator bug
FIXED: Incorrect new update available notifications

= 2.1.9 [2-13-5-6] =
FIXED: error on call to undefined function date_parse_from_format() for those running php 5.2
FIXED: Template error that cause entire site layout for some
FIXED: Widget title not appearing

= 2.1.8 [2013-5-1] =
ADDED: basic single event page support and "../events/" url slug can be used to show calendar now - which is coming from a new page called "Events" in WP admin pages. 
ADDED: more/less custom language support
FIXED: new events not showing on calendar
FIXED: issue with EventON widget messing other widgets
FIXED: incorrect day name on multi-day event
FIXED: license version to update to current version after an update
FIXED: weird download issue with autoupdate
FIXED: incorrect date saving for non-american time format

= 2.1.7 [2013-4-30] =
FIXED: event start date going to 1st of month error
FIXED: addons not showing issue
FIXED: error you get when saving styles
FIXED: array_merge error for addons

= 2.1.6 [2013-4-28] =
ADDED: ability to get automatic new updates
ADDED: new and exciting license management tab to myEventON settings
ADDED: new plugin update notifications 
ADDED: event date picker date format is now based off your site's date format
UPDATED: Event card got little jazzed up now
UPDATED: Main settings page - removed some junk
UPDATED: in-window pop up box, added new loading animation and notifications
UPDATED: EventON widgets UI
UPDATED: improved event generator class for faster loading
FIXED: issue with event close button not working for new months
FIXED: upcoming events list shortcode
FIXED: event time default value to 00
FIXED: minor style and functionality issues on eventON widget

= 2.1.5 [2013-4-18] =
ADDED: visible event type IDs to event types category page
ADDED: ability to duplicate events 
ADDED: more useful pluggable hooks into base plugin
ADDED: ability to disable google gmaps api partly and fully
ADDED: ability to set google maps zoom level
ADDED: close button at the bottom of each event details
UPDATED: frontend styles
UPDATED: backend settings tabs, better UI for language tab
UPDATED: event repeating UI
FIXED: issue with calendar font settings not working properly
FIXED: external event links not opening
FIXED: php template tag not working correctly

= 2.1.4 [2013-4-8] =
ADDED: a new shortcode popup box for better user experience

= 2.1.3 [2013-4-7] =
* Added support to open learn more links in new window
* Improvements to addon handling
* Few more minor bugs distroyed for good

= 2.1.2 [2013-4-5] =
* Minor bugs fixed
* Added the ability to disable google maps API
* Fix custom event type names on events column in backend
* Improvements on addon handling

= 2.1.1 [2013-3-28] =
* Fixed small bugs
* Added auto plugin update notifier for eventon
* Added upcoming events list support to widget

= 2.1 [2013-3-28] =
* Implemented hooks and filters for extensions and further customization
* You can now add addons to extend features of the calendar
* Fixed bunch more bugs
* Changed the name and a whole new shi-bang now
* Quick shortcode button on Page text editor

= 2.0.8 [2013-3-23]=
* Fixed bugs

= 2.0.7 [2013-3-17]=
* Fixed shortcode upcoming list issue
* Added the ability to hide empty months in upcoming list

= 2.0.6 [2013-2-28]=
* fixed minor error with usort array

= 2.0.5 [2013-2-25] =
* Added repeat events capability for monthly and weekly events
* Reconstructed the event computations system to support future expansions
* Now you can hide the sort bar from backend options
* Event card icons can be changed easily from backend now
* Added the template tag support for upcoming events list format
* Primary font for the calendar can also be changed from the backend options

= 2.0.4 [2013-2-11]=
* Added the ability to add an extra custom taxonomy for event sorting
* Custom taxonomies can be given custom names now
* Better control over front-end event sorting options
* Further minimalized the sort bar present on front-end calendar
* Fixed bugs on eventbrite and meetup api
* Added a learn more event link option
* Fixed event redirect when external link is empty
* Added 2 more different google map display types

= 2.0.3 [2013-1-13] =
* Fixed the bug with google map images

= 2.0.2 [2012-12-28] =
* Calendar arrow nav issue fixed in some themes

= 2.0.1 [2012-12-24] =
* Added the ability to create calendars with different starting months.

= 2.0 [2012-12-21] =
* Squished bugs in the code with data save and bunch of other stuff...
* Added Meetup API support to connect to meetup events and get event data in an interactive way.
* Updated eventbrite API to a more interactive event data-bridge setup.
* Added event organizer field.
* You can now link events to a url instead of opening event details.
* Event Calendar now support featured images for events right in the "event card".
* Added more animated effects to frontend of the calendar.
* Ditched the default skin to nail down some of the CSS issues with skins on "Slick"
* Updated event option saving method to streamline load time.
* Added TON of more customizable options

= 1.9 [2012-11- ]=
* Fixed saved dates and other custom event data dissapearing after auto event save in WP
* Improved custom style appending method
* Added Paypal direct link to event ticket payment
* Added easy color picker

= 1.8 [2012-10-23]=
* Added widget support
* UI Update to backend
* Existing skins update
* Improvements to algorithm

= 1.7 [2012-10-16]=
* Updated back-end UI
* Better hidden past event management
* Ability to disable month scrolling on front-end
* Added responsiveness to skins

= 1.6 [2012-5-31] =
* Multiple calendars in one page
* Calendar to show only certain event types with shortcode or template tags
* custom language for "no events"
* "Slick" new skin added
* Correct several CSS issues with parent CSS styles

= 1.5 [2012-5-1] =
* Improvement to code for faster loading
* Added smoother month transitions
* "Event Type" support for events
* Apply multiple colors to events and allow sorting by color
* Added "all day event" support
* Default wordpress main text editor is now used for event description box
* Better event data management

= 1.4 [2012-4-5] =
* CSS issues fixed
* Multiple Skin support 

= 1.3 [2012-1-31] =
* Minor changes to Interface design 
* New Loading spinner on AJAX calls
* Added auto Google Map API integration based on event location address
* Added control over past events display on the calendar
* Improvements to events algorithm for faster load time
* Bug fixed (End month and start month date issue)
* Bug fixed (Month filtering issues)

= 1.2 [2012-1-12] =
* Minor bugged fixed
* Back-end Internationalization
* Added plugin data cleanup upon deactivation

= 1.1 [2012-1-4] =
* Added custom language support

= 1.0 [2011-12-21] =
* Initial release