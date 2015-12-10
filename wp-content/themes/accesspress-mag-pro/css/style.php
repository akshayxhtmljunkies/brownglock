<?php
$root = '../../../..';
if (file_exists($root . '/wp-load.php')) {
    require_once( $root . '/wp-load.php' );
} elseif (file_exists($root . '/wp-config.php')) {
    require_once( $root . '/wp-config.php' );
} else {
    die('/* Error */');
}

header("Content-type: text/css");

$image_url = get_template_directory_uri() . "/images/";
$overlay_url = get_template_directory_uri() . "/images/overlays/";
$custom_css = of_get_option('custom_css');

$page_background_option = of_get_option('page_background_option');
$page_background_image = of_get_option('page_background_image');
$page_background_color = of_get_option('page_background_color');
$page_background_pattern = of_get_option('page_background_pattern');
$select_preloader = of_get_option('select_preloader');

$body_typography = of_get_option('body_typography');
//var_dump($body_typography);
$h1_typography = of_get_option('h1_typography');
$h2_typography = of_get_option('h2_typography');
$h3_typography = of_get_option('h3_typography');
$h4_typography = of_get_option('h4_typography');
$h5_typography = of_get_option('h5_typography');
$h6_typography = of_get_option('h6_typography');
$topmenu_typography = of_get_option( 'topmenu_typography' );
//var_dump($topmenu_typography);
$primary_typography = of_get_option( 'primarymenu_typography' );
$footermenu_typography = of_get_option( 'footermenu_typography' );
$widget_title_typography = of_get_option( 'widgettitle_typography');
$h1_text_transform = of_get_option( 'h1_text_transform' );
$h2_text_transform = of_get_option( 'h2_text_transform' );
$h3_text_transform = of_get_option( 'h3_text_transform' );
$h4_text_transform = of_get_option( 'h4_text_transform' );
$h5_text_transform = of_get_option( 'h5_text_transform' );
$h6_text_transform = of_get_option( 'h6_text_transform' );
$top_menu_text_transform = of_get_option( 'top_menu_text_transform' );
$primary_menu_text_transform = of_get_option( 'primary_menu_text_transform' );
$footer_menu_text_transform = of_get_option( 'footer_menu_text_transform' );
$widget_title_text_transform = of_get_option('widget_title_text_transform' );

$big_slide_title_typography = of_get_option( 'bslidetitle_typography' );
$big_slide_title_transform = of_get_option( 'bslidetitle_transform' );
$small_slide_title_typography = of_get_option( 'sslidetitle_typography' );
$small_slide_title_transform = of_get_option( 'sslidetitle_transform' );
$slider_title_bg_color = of_get_option( 'slider_title_bg_color' );

$theme_color = of_get_option('theme_color');
$theme_color_hov = colourBrightness($theme_color, '-0.9');

$primary_menu_bg_color = of_get_option( 'primary_menu_bg_color' );
$sub_menu_bg_color = of_get_option( 'sub_menu_bg_color' );
$top_menu_hover_color = of_get_option( 'top_menu_hover_color' );
$footer_menu_hover_color = of_get_option( 'footer_menu_hover_color' );
$primary_menu_hover_color = of_get_option( 'primary_menu_hover_color' );
$footer_widget_bg = of_get_option( 'footer_widget_bg' );
$enable_breadcrumb_mobile = of_get_option(' enable_breadcrumb_mobile' );

$slider_cat_box_color = of_get_option( 'slider_cat_box_color' );
$slider_cat_title_color = of_get_option( 'slider_cat_title_color' );
$top_menu_bg_color = of_get_option( 'top_menu_bg_color' );

$accesspress_pro_css = ".abcxyz{ display:none }\n";
$accesspress_pro_css .= ".top-menu-wrapper{background:". $top_menu_bg_color ."}\n";
$accesspress_pro_css .= "#top-navigation .menu li a:hover, #top-right-navigation .menu li a:hover{color:". $top_menu_hover_color ."!important} \n";
$accesspress_pro_css .= "body{font-size:" . $body_typography['size'] . ";font-family:" . $body_typography['face'] . ";font-weight:" . $body_typography['style'] . ";color:" . $body_typography['color'] . "}\n";
$accesspress_pro_css .= "h1{ font-size:" . $h1_typography['size'] . ";font-family:" . $h1_typography['face'] . ";font-weight:" . $h1_typography['style'] . ";color:" . $h1_typography['color'] . ";text-transform:" . $h1_text_transform . "}\n";
$accesspress_pro_css .= "h2{ font-size:" . $h2_typography['size'] . ";font-family:" . $h2_typography['face'] . ";font-weight:" . $h2_typography['style'] . ";color:" . $h2_typography['color'] . ";text-transform:" . $h2_text_transform . "}\n";
$accesspress_pro_css .= "h2 a{color:" . $h2_typography['color'] . ";}\n";
$accesspress_pro_css .= "h3{ font-size:" . $h3_typography['size'] . ";font-family:" . $h3_typography['face'] . ";font-weight:" . $h3_typography['style'] . ";text-transform:" . $h3_text_transform . "}\n";
$accesspress_pro_css .= "h3 a{color:" . $h3_typography['color'] . ";}\n";
$accesspress_pro_css .= "h4{ font-size:" . $h4_typography['size'] . ";font-family:" . $h4_typography['face'] . ";font-weight:" . $h4_typography['style'] . ";color:" . $h4_typography['color'] . ";text-transform:" . $h4_text_transform . "}\n";
$accesspress_pro_css .= "h4 a{color:" . $h4_typography['color'] . ";}\n";
$accesspress_pro_css .= "h5{ font-size:" . $h5_typography['size'] . ";font-family:" . $h5_typography['face'] . ";font-weight:" . $h5_typography['style'] . ";color:" . $h5_typography['color'] . ";text-transform:" . $h5_text_transform . "}\n";
$accesspress_pro_css .= "h6{ font-size:" . $h6_typography['size'] . ";font-family:" . $h6_typography['face'] . ";font-weight:" . $h6_typography['style'] . ";color:" . $h6_typography['color'] . ";text-transform:" . $h6_text_transform . "}\n";
$accesspress_pro_css .= "#secondary-left .widget-title, #secondary-right .widget-title, #secondary .widget-title, #colophon .widget-title{ font-size:" . $widget_title_typography['size'] . ";font-family:" . $widget_title_typography['face'] . ";font-weight:" . $widget_title_typography['style'] . ";color:" . $widget_title_typography['color'] . ";text-transform:" . $widget_title_text_transform . "}\n";
//$accesspress_pro_css .= ".top-footer .widget-title{ font-size:" . $footer_title_typography['size'] . ";font-family:" . $footer_title_typography_face . ";font-weight:" . $footer_title_typography['style'] . ";color:" . $footer_title_typography['color'] . ";text-transform:" . $footer_title_text_transform . "}\n";
//$accesspress_pro_css .= ".top-footer .widget-title:after{border-color:" . $footer_title_typography['color'] . "}\n";
$accesspress_pro_css .= "#page-overlay{background-image:url(" . get_template_directory_uri() . "/images/preloader/" . $select_preloader . ".gif)}\n";
$accesspress_pro_css .= ".top-footer{background: none repeat scroll 0 0" . $footer_widget_bg . ";}\n";
$accesspress_pro_css .= "#colophon .widget-title > span {background: none repeat scroll 0 0". $footer_widget_bg ." }\n";
$accesspress_pro_css .= "#site-navigation{ background: url(". get_template_directory_uri() ."/images/slight-border.jpg) repeat-x scroll left bottom". $primary_menu_bg_color . "}\n";
$accesspress_pro_css .= "#site-navigation ul .mega-sub-menu{ background:". $sub_menu_bg_color ."}\n";
$accesspress_pro_css .= "#site-navigation ul li a{ font-size:" . $primary_typography['size'] . ";font-family:" . $primary_typography['face'] . ";font-weight:" . $primary_typography['style'] . ";color:" . $primary_typography['color'] . ";text-transform:" . $primary_menu_text_transform . "}\n";
$accesspress_pro_css .= "#top-navigation .menu li a, #top-right-navigation .menu li a{ font-size:" . $topmenu_typography['size'] . ";font-family:" . $topmenu_typography['face'] . ";font-weight:" . $topmenu_typography['style'] . ";color:" . $topmenu_typography['color'] . ";text-transform:" . $top_menu_text_transform . "}\n";
$accesspress_pro_css .= "#site-navigation ul li:hover > a{color:". $primary_menu_hover_color ."}\n";
$accesspress_pro_css .= "#site-navigation ul.sub-menu{background:". $sub_menu_bg_color ."} \n";
$accesspress_pro_css .= ".big-cat-box{background:". $slider_cat_box_color ."}\n";
$accesspress_pro_css .= ".small-slider-wrapper .cat-name {background:". $slider_cat_box_color ."}\n";
$accesspress_pro_css .= ".big-cat-box .cat-name, .apmag-slider-smallthumb .cat-name, .big-cat-box .comment_count, .big-cat-box .apmag-post-views {color:". $slider_cat_title_color ."}\n";
$accesspress_pro_css .= "#footer-navigation ul li a{ font-size:" . $footermenu_typography['size'] . ";font-family:" . $footermenu_typography['face'] . ";font-weight:" . $footermenu_typography['style'] . ";color:" . $footermenu_typography['color'] . ";text-transform:" . $footer_menu_text_transform . " }\n";
$accesspress_pro_css .= "#footer-navigation ul li a:hover{color:". $footer_menu_hover_color ."}\n";



if ($page_background_option == "image") {
	$accesspress_pro_css .= "body{ background-image:url(" . $page_background_image['image'] . "); background-repeat:" . $page_background_image['repeat'] . "; background-position:" . $page_background_image['position'] . ";background-attachment:" . $page_background_image['attachment'] . ";background-size:" . $page_background_image['size'] . "}\n";
} elseif ($page_background_option == "color") {
	$accesspress_pro_css .= "body.boxed-layout{ background-color:" . $page_background_color . "}\n";
} elseif ($page_background_option == "pattern") {
	$accesspress_pro_css .= "body{ background-image:url(" . get_template_directory_uri() . "/inc/option-framework/images/patterns/" . $page_background_pattern . ".png)}\n";
}
echo $accesspress_pro_css;
?>

 
.bttn:hover, 
button, 
input[type="button"]:hover, 
input[type="reset"]:hover, 
input[type="submit"]:hover,.comment-author .fn .url:hover,
.nav-toggle,.ak-search .search-form,
.slider-wrapper .bx-pager-item a,.slide-excerpt,.mag-slider-caption .slide-title,
.mag-small-slider-caption .slide-title,.mag-small-slider-caption .home-posted,
.big-image-overlay i,.error404 .error-num .num,.entry-meta .post-categories li a,
.bread-you,.post-source-wrapper .source,.post-via-wrapper .via,.percent-rating-bar-wrap div,
.owl-theme .owl-controls .owl-buttons div,
#back-top:hover,.social-shortcode a:hover,.ticker-title,.widget_newsletterwidget .newsletter-submit{
	background:<?php echo $theme_color; ?>;
}

a:hover, a:focus, a:active,a,.entry-footer a:hover,.navigation .nav-links a:hover,#cancel-comment-reply-link:before,
#cancel-comment-reply-link,.logged-in-as a,#site-navigation ul li:hover > a,
#site-navigation ul li.current-menu-item > a,#site-navigation ul li.current-menu-ancestor > a,
.search-icon > i:hover,.block-post-wrapper .post-title a:hover,.random-posts-wrapper .post-title a:hover,
.sidebar-posts-wrapper .post-title a:hover,.review-posts-wrapper .single-review .post-title a:hover,
.latest-single-post a:hover,.block-poston a:hover,.ratings-wrapper .star-value,
.bottom-footer .ak-info a:hover,.oops,.error404 .not_found,.widget ul li:hover a,
.widget ul li:hover:before,#accesspres-mag-breadcrumbs .ak-container > .current,
.post-review-wrapper .section-title,.review-featured-wrap .stars-count,
.post-review-wrapper .summery-label, article.hentry .entry-footer > span, .author-metabox .author-title,.navigation .nav-links a:hover,
#top-navigation .menu li a:hover, #top-right-navigation .menu li a:hover, 
.search-icon > i, 
.random-post-icon > a,
.owl-theme .owl-controls .owl-buttons div:hover .fa,
.total-reivew-wrapper .total-value,
.ticker-content > a:hover,
.total-reivew-wrapper .stars-count,
.cat-link.view-all-button:hover,
#accesspres-mag-breadcrumbs .ak-container > a:hover {
	color:<?php echo $theme_color; ?>;
}

.ak-search .search-form .search-submit:hover,
.ak-search .search-form .search-submit:hover,.widget_newsletterwidget .newsletter-submit:hover,
.edit-link .post-edit-link:hover {
	background:<?php echo $theme_color_hov; ?>;
}

.search-icon > i:hover, .random-post-icon > a:hover { 
	color:<?php echo $theme_color_hov; ?> 
}

.navigation .nav-links a,.bttn, button, input[type="button"], input[type="reset"], 
input[type="submit"],#top-navigation ul.sub-menu li:hover,#top-navigation ul.sub-menu li.current-menu-item,
#top-navigation ul.sub-menu li.current-menu-ancestor,#footer-navigation ul.sub-menu li:hover,
#footer-navigation ul.sub-menu li.current-menu-item,#footer-navigation ul.sub-menu li.current-menu-ancestor,
#top-right-navigation ul.sub-menu li:hover,#site-navigation ul.menu > li:hover > a:after,
#site-navigation ul.menu > li.current-menu-item > a:after,
#site-navigation ul.menu > li.current-menu-ancestor > a:after,
#site-navigation ul.sub-menu li:hover,#site-navigation ul.sub-menu li.current-menu-item,
#site-navigation ul.sub-menu li.current-menu-ancestor,.bread-you:after,
.social-shortcode a:hover,.bttn.ap-default-bttn.ap-outline-bttn,
.ap_tagline_box.ap-all-border-box,.ap_tagline_box.ap-left-border-box,
.ap_tagline_box.ap-top-border-box,.ticker-title:before{
	border-color:<?php echo $theme_color; ?>;
}
.bread-you:after{
    border-left: 5px solid <?php echo $theme_color; ?>;
		border-top: 5px solid transparent;
		border-bottom: 5px solid transparent;
}

.ticker-title:before{
    border-top: 6px solid transparent;
  border-bottom: 6px solid transparent;
  border-left: 6px solid <?php echo $theme_color; ?>;
}

.home-style1-layout .owl-next, .home-style1-layout .owl-prev {
	border: 1px solid <?php echo $theme_color; ?>;
}

.mag-slider-caption .slide-title {
	background:<?php echo $slider_title_bg_color ; ?>;
	color:<?php echo $big_slide_title_typography['color'] ; ?>;
	font-family:<?php echo $big_slide_title_typography['face']; ?>;
	font-size:<?php echo $big_slide_title_typography['size'];?>;
	text-transform:<?php echo $big_slide_title_transform ; ?>;
}

.mag-small-slider-caption .slide-title{
	background:<?php echo $slider_title_bg_color ; ?>;
	color:<?php echo $small_slide_title_typography['color'] ; ?>;
	font-family:<?php echo $small_slide_title_typography['face']; ?>;
	font-size:<?php echo $small_slide_title_typography['size'];?>;
	text-transform:<?php echo $small_slide_title_transform ; ?>;
}

@media screen and (max-width:768px){	
	<?php if ($enable_breadcrumb_mobile == '0') { ?>
	#accesspress-breadcrumb{display:none;};
	<?php } ?>
}

<?php
/* ===================================
  CUSTOM CSS
  =================================== */

if ( !empty( $custom_css ) ) {
	echo $custom_css;
}