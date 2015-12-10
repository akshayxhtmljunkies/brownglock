=== Category Featured Images ===
Contributors: blocknot.es
Tags: images,categories,posts,Post
Donate link: http://www.blocknot.es/home/me/
Requires at least: 3.5.0
Tested up to: 4.1
Stable tag: trunk
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Set a featured image for all the posts of a category.

== Description ==

This plugin allows to set a featured image for each category.
Posts without a featured image set will show the category's featured image instead.
Featured images usually are placed by the theme in the single post page, in the latest posts page, etc.
But can also be shown using the shortcode *[cfi_featured_image]* or the PHP function *cfi_featured_image()*. To get the featured image URL I added the function *cfi_featured_image_url()*.

Shortcode/PHP function optional arguments:

* 'size': 'thumbnail', 'medium', 'large', 'full'
* 'class': class of the image tag
* 'alt': alternative text of the image tag
* 'title': title of the image tag
* 'cat_id': select a specific category id

Shortcode example:

	[cfi_featured_image size="large" title="This is a test..." class="my-image" alt="My image"]

Function example 1:

	cfi_featured_image( array( 'size' => 'large', 'title' => 'This is a test...', 'class' => 'my-image', 'alt' => 'My image' ) );

Function example 2:

	cfi_featured_image_url( array( 'size' => 'large' ) );

Notes:

* If a post has more than a category with a featured image the first available is loaded
* If a category hasn't a featured image but it has a parent category with a featured image the parent one is loaded

== Installation ==

1. Install and activate the plugin
2. Go in Posts \\ Categories
3. Edit a category
4. Set the category featured image

== Screenshots ==
1. Edit category page

== Upgrade Notice ==

= 1.1.8 =
* Added 'cat_id' param to cfi_featured_image
= 1.1.5 =
* Loads parent category image if nothing is found before
= 1.1.2 =
* Added 'cat_id' param to cfi_featured_image_url
= 1.1.0 =
* Improved cfi_featured_image in category archive pages
* Added PHP function: cfi_featured_image_url
= 1.0.6 =
* Added PHP function and shortcode: cfi_featured_image

== Changelog ==

= 1.1.8 =
* Added 'cat_id' param to cfi_featured_image
= 1.1.5 =
* Loads parent category image if nothing is found before
= 1.1.2 =
* Added 'cat_id' param to cfi_featured_image_url
= 1.1.0 =
* Improvement: cfi_featured_image in category archive pages shows the current image
* New PHP function: cfi_featured_image_url()
= 1.0.6 =
* New shortcode: cfi_featured_image()
* New PHP function: cfi_featured_image()
