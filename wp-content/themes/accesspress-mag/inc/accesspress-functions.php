<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package AccessPress Mag
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
 
if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	
	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function accesspress_mag_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
    
	add_action( 'wp_head', 'accesspress_mag_render_title' );
endif;

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue custom admin panel JS
 */
 
function accesspress_mag_admin_scripts(){
    wp_enqueue_script( 'accesspress-mag-custom-admin', get_template_directory_uri(). '/inc/option-framework/js/custom-admin.js', array( 'jquery' ) );    
 }
add_action( 'admin_enqueue_scripts', 'accesspress_mag_admin_scripts' );

/**
 * Enqueue admin css
 */
 
function accesspress_mag_admin_css(){
    wp_enqueue_style( 'accesspress-mag-admin', get_template_directory_uri(). '/inc/option-framework/css/accesspress-mag-admin.css' );    
}
add_action( 'admin_head', 'accesspress_mag_admin_css' );

/**
 * Enqueue custom css
 */

function accesspress_mag_custom_styles() {
    
    $accesspress_mag_custom_css = of_get_option( 'custom_css', '' );
    wp_add_inline_style( 'accesspress-mag-style', $accesspress_mag_custom_css );
}
add_action( 'wp_enqueue_scripts', 'accesspress_mag_custom_styles' );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * News ticker section in the header
 */
 
if ( ! function_exists( 'accesspress_mag_ticker' ) ) :
function accesspress_mag_ticker() {
   $get_featured_posts = new WP_Query( array(
      'posts_per_page'        => 5,
      'post_type'             => 'post',
      'ignore_sticky_posts'   => true
   ) );
?>
   <div class="apmag-news-ticker">
        <div class="apmag-container">
            <ul id="apmag-news" class="js-hidden">
              <?php while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post(); ?>
                 <li class="news-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
              <?php endwhile; ?>
            </ul>
        </div>
   </div>
   <?php
   // Reset Post Data
   wp_reset_query();
}
endif;

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Homepage Slider settings 
 */
if ( ! function_exists( 'accesspress_mag_slider_cb' ) ) : 
function accesspress_mag_slider_cb(){
        $slider_posts_option = of_get_option( 'slider_post_option', ' ' );
        $slider_category = of_get_option( 'homepage_slider_category' );
        $slide_count = of_get_option( 'count_slides' );
        if( $slide_count == 0 ){
            $posts_perpage_value = 4;
        } elseif( empty( $slider_category ) && $slider_posts_option == 'cat' ) {
            $posts_perpage_value = 4;
        }
        else {
            $posts_perpage_value = $slide_count*4;
        }
        $slide_info = of_get_option( 'slider_info' );
        $slider_args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => $posts_perpage_value,
                    'order' => 'DESC',
                    'meta_query' => array(
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        )
                                    )
                        );
        if( ( $slider_posts_option == 'cat' ) && ( !empty( $slider_category ) ) ){
            $slider_args['category_name'] = $slider_category;
        }
        $slider_query = new WP_Query( $slider_args );
        $slide_counter = 0; 
        if( $slider_query->have_posts() )
        {
            echo '<div id="homeslider">';
            while( $slider_query->have_posts() )
            {
                $slide_counter++;                                                            
                $slider_query->the_post();
                $post_id = get_the_ID();
                $post_image_id = get_post_thumbnail_id();
                $post_big_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-slider-big-thumb', true );
                $post_small_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-slider-small-thumb', true );
                $post_image_alt = get_post_meta( $post_image_id, '_wp_attachment_image_alt', true );
                if( $slide_counter%4==1 ){
            ?>                        
                    <div class="slider">
                        <a href="<?php echo the_permalink();?>">
                            <div class="big_slide wow fadeInLeft">
                                <div class="big-cat-box">
                                    <?php accesspress_mag_category_details( $post_id );?>
                                    <?php do_action('accesspress_mag_post_meta');?>
                                </div>
                                    <div class="slide-image"><img src="<?php echo esc_url( $post_big_image_path[0] );?>" alt="<?php echo esc_attr($post_image_alt);?>" /></div>
                                    <?php if( $slide_info == 1 ){?><div class="mag-slider-caption"><h3 class="slide-title"><?php the_title();?></h3></div><?php } ?>
                            </div>
                        </a>
            <?php } else { if( $slide_counter%4==2 ){echo '<div class="small-slider-wrapper wow fadeInRight">';}?>                
                        <a href="<?php echo the_permalink();?>">
                            <div class="small_slide">
                                <?php accesspress_mag_category_details( $post_id );?>                            
                                    <div class="slide-image"><img src="<?php echo esc_url( $post_small_image_path[0] );?>" alt="<?php echo esc_attr($post_image_alt);?>" /></div>
                                    <?php if( $slide_info == 1 ){?><div class="mag-small-slider-caption"><h3 class="slide-title"><?php the_title();?></h3></div><?php } ?>
                            </div>
                        </a>
            <?php 
                 }
                 if( $slide_counter%4==0 ){
            ?>
                    </div>
                    </div>
            <?php }
                
                }
                wp_reset_query();
            echo '</div>';
            }
 }
 endif ;
add_action( 'accesspress_mag_slider', 'accesspress_mag_slider_cb', 10 );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Homepage Slider settings mobile
 */
if ( ! function_exists( 'accesspress_mag_slider_mobile_cb' ) ) : 
function accesspress_mag_slider_mobile_cb(){
        $slider_posts_option = of_get_option( 'slider_post_option', ' ' );
        $slider_category = of_get_option( 'homepage_slider_category' );
        $slide_count = of_get_option( 'count_slides' );
        if( $slide_count == 0 ){
            $posts_perpage_value = 4;
        } elseif( empty( $slider_category ) && $slider_posts_option == 'cat' ) {
            $posts_perpage_value = 4;
        }
        else {
            $posts_perpage_value = $slide_count*4;
        }
        $slide_info = of_get_option( 'slider_info' );
        $slider_args = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => $posts_perpage_value,
                    'order' => 'DESC',
                    'meta_query' => array(
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        )
                                    )
                        );
        if( ( $slider_posts_option == 'cat' ) && ( !empty( $slider_category ) ) ){
            $slider_args['category_name'] = $slider_category;
        }
        $slider_query = new WP_Query( $slider_args );
        $slide_counter = 0; 
        if( $slider_query->have_posts() )
        {
            echo '<div id="homeslider-mobile">';
            while( $slider_query->have_posts() )
            {
                $slide_counter++;                                                            
                $slider_query->the_post();
                $post_id = get_the_ID();
                $post_image_id = get_post_thumbnail_id();
                $post_big_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-slider-big-thumb', true );
                $post_small_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-slider-small-thumb', true );
                $post_image_alt = get_post_meta( $post_image_id, '_wp_attachment_image_alt', true );
            ?>                        
                    <div class="slider">
                        <a href="<?php echo the_permalink();?>">
                            <div class="big_slide wow fadeInLeft">
                                <div class="big-cat-box">
                                    <?php accesspress_mag_category_details( $post_id );?>
                                    <?php do_action('accesspress_mag_post_meta');?>
                                </div>
                                    <div class="slide-image"><img src="<?php echo esc_url( $post_big_image_path[0] );?>" alt="<?php echo esc_attr($post_image_alt);?>" /></div>
                                    <?php if( $slide_info == 1 ){?><div class="mag-slider-caption"><h3 class="slide-title"><?php the_title();?></h3></div><?php } ?>
                            </div>
                        </a>
                    </div>
           <?php                
                }
                wp_reset_query();
            echo '</div>';
            }
 }
 endif ;
add_action( 'accesspress_mag_slider_mobile', 'accesspress_mag_slider_mobile_cb', 10 );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Function scripts for header
 */
 
if( ! function_exists( 'accesspress_mag_function_script' ) ): 
function accesspress_mag_function_script(){
    $slider_controls = ( of_get_option( 'slider_controls' ) == "1" ) ? "true" : "false";
    $slider_auto_transaction = ( of_get_option( 'slider_auto_transition' ) == "1" ) ? "true" : "false";
    $slider_pager = ( of_get_option( 'slider_pager' ) == "1" ) ? "true" : "false";
    $slider_pause = of_get_option( 'slider_pause', '6000' );
    $ticker_caption = esc_attr( of_get_option( 'ticker_caption', __( 'Latest', 'accesspress-mag' ) ) );
    ?>
    <script type="text/javascript">
        jQuery(function($){
            if( $('body').hasClass('rtl') ){
                var directionClass = 'rtl';
            } else {
                var directionClass = 'ltr';
            }
        
        /*--------------For Home page slider-------------------*/
        
            $("#homeslider").bxSlider({
                controls: <?php echo esc_attr( $slider_controls ); ?>,
                pager: <?php echo esc_attr( $slider_pager ); ?>,
                pause: <?php echo intval( $slider_pause ); ?>,
                speed: 1000,
                auto: <?php echo esc_attr( $slider_auto_transaction ); ?>
                                        
            });
            
            $("#homeslider-mobile").bxSlider({
                controls: <?php echo esc_attr( $slider_controls ); ?>,
                pager: <?php echo esc_attr( $slider_pager ); ?>,
                pause: <?php echo intval( $slider_pause ); ?>,
                speed: 1000,
                auto: <?php echo esc_attr( $slider_auto_transaction ); ?>
                                        
            });

        /*--------------For news ticker----------------*/

            <?php if ( of_get_option( 'news_ticker_option', '1' ) == '1' ) {  ?>
            $('#apmag-news').ticker({
                speed: 0.10,
                feedType: 'xml',
                displayType: 'reveal',
                htmlFeed: true,
                debugMode: true,    
                fadeInSpeed: 600,
                //displayType: 'fade',
                pauseOnItems: 4000,
                direction: directionClass,
                titleText: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo esc_attr( $ticker_caption ); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            });
            <?php } ?>
            
            });
    </script>
    <?php
}
endif ;
add_action( 'wp_head', 'accesspress_mag_function_script' );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Get category name and it's link  
 */
 if( ! function_exists( 'accesspress_mag_category_details' ) ):
 function accesspress_mag_category_details($post_id){
    $cat_details = get_the_category($post_id);
    foreach( $cat_details as $single_info ){
        $cat_id = $single_info -> term_id;
        $cat_name = $single_info -> name;
    }
    $cat_link = get_category_link( $cat_id );
    $cat_sec = '<span class="cat-name">'. esc_attr( $cat_name ) .'</span>';
    echo $cat_sec ;
 }
endif;

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Sidebar layout for post & pages
 */ 
function accesspress_mag_sidebar_layout_class($classes){
    global $post;
    	if( is_404()){
    	$classes[] = ' ';
    	}elseif(is_singular()){
 	    $global_sidebar= esc_attr( of_get_option( 'global_post_sidebar' ) );
    	$post_sidebar = get_post_meta( $post -> ID, 'accesspress_mag_sidebar_layout', true );        
        $page_sidebar = get_post_meta( $post -> ID, 'accesspress_mag_page_sidebar_layout', true );
        if( 'post'==get_post_type() ){
             if( $post_sidebar == 'global-sidebar' || empty( $post_sidebar ) ){
                $post_class = $global_sidebar;
            } else {
                $post_class = $post_sidebar;
            }
        	$classes[] = 'single-post-'.$post_class;
        } else {
            $classes[] = 'page-'.$page_sidebar;
        }
    	} elseif(is_archive()){
    	   $archive_sidebar = esc_attr( of_get_option( 'global_archive_sidebar' ) );
            $classes[] = 'archive-'.$archive_sidebar;
        } elseif(is_search()){
            $archive_sidebar = esc_attr( of_get_option( 'global_archive_sidebar' ) );
            $classes[] = 'archive-'.$archive_sidebar;
        }else{
    	$classes[] = 'page-right-sidebar';	
    	}
    	return $classes;
    }
add_filter( 'body_class', 'accesspress_mag_sidebar_layout_class' );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Template style layout for post and pages
 */

function accesspress_mag_template_layout_class($classes){
    global $post;
    	if( is_404()){
    	$classes[] = ' ';
    	}elseif(is_singular()){
 	    $global_template= esc_attr( of_get_option( 'global_post_template' ) );
    	$post_template = get_post_meta( $post -> ID, 'accesspress_mag_post_template_layout', true );
        if('post'==get_post_type()){
            if( $post_template=='global-template' || empty( $post_template ) ){
                $post_template_class = $global_template;
            } else {
                $post_template_class = $post_template;
            }
        	$classes[] = 'single-post-'.$post_template_class;
        }       
    	} elseif(is_archive()){
            $archive_template = esc_attr( of_get_option( 'global_archive_template' ) );
            $classes[] = 'archive-page-'.$archive_template;
        } elseif(is_search()){
            $archive_template = esc_attr( of_get_option( 'global_archive_template' ) );
            $classes[] = 'archive-page-'.$archive_template;
        }else{
    	$classes[] = 'page-default-template';	
    	}
    	return $classes;
    }
add_filter( 'body_class', 'accesspress_mag_template_layout_class' );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Website layout 
 */

function accesspress_mag_website_layout_class( $classes ){
    $website_layout = esc_attr( of_get_option( 'website_layout_option' ) );
    if($website_layout == 'boxed' ){
        $classes[] = 'boxed-layout';
    } else {
        $classes[] = 'fullwidth-layout';
    }
    return $classes;
}
add_filter( 'body_class', 'accesspress_mag_website_layout_class' );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Meta post on
 */

if( ! function_exists( 'accesspress_mag_post_meta_cb' ) ): 
function accesspress_mag_post_meta_cb(){
    global $post;
    //$show_post_views = of_get_option( 'show_post_views' );
    $show_comment_count = of_get_option( 'show_comment_count' );
    if($show_comment_count==1){
        $post_comment_count = get_comments_number( $post->ID );
        echo '<span class="comment_count"><i class="fa fa-comments"></i>'.esc_attr( $post_comment_count ).'</span>';
    }
}
endif ;
add_action( 'accesspress_mag_post_meta', 'accesspress_mag_post_meta_cb', 10 );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Posted on for home page
 */

if( ! function_exists( 'accesspress_mag_home_posted_on_cb' ) ):  
function accesspress_mag_home_posted_on_cb(){
    global $post;
    
    $show_comment_count = of_get_option( 'show_comment_count' );
    $show_post_date = of_get_option( 'post_show_date' );
    
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    if($show_post_date==1){
	  $posted_on = sprintf(
    		_x( '%s', 'post date', 'accesspress-mag' ),
    		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    	);	   
	} else {
        $posted_on = '';
    }
    echo '<span class="posted-on">' . $posted_on . '</span>';
    if($show_comment_count==1){
        $post_comment_count = get_comments_number( $post->ID );
        echo '<span class="comment_count"><i class="fa fa-comments"></i>'.esc_attr( $post_comment_count ).'</span>';
    }
}
endif;
add_action( 'accesspress_mag_home_posted_on', 'accesspress_mag_home_posted_on_cb', 10 );

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Excerpt length (Effect on archive page only) 
 */
if( ! function_exists( 'accesspress_mag_customize_excerpt_more' ) ):
function accesspress_mag_customize_excerpt_more( $more ) {
	return '...';
}
endif;
add_filter( 'excerpt_more', 'accesspress_mag_customize_excerpt_more' );

if( ! function_exists( 'accesspress_mag_word_count' ) ):
function accesspress_mag_word_count( $string, $limit ) {
    $string = strip_tags( $string );
    $string = strip_shortcodes( $string );
    $words = explode( ' ', $string );
	return implode( ' ', array_slice( $words, 0, $limit ));
}
endif;

if( ! function_exists( 'accesspress_mag_letter_count' ) ):
function accesspress_mag_letter_count( $content, $limit ) {
	$striped_content = strip_tags( $content );
	$striped_content = strip_shortcodes( $striped_content );
	$limit_content = mb_substr( $striped_content, 0 , $limit );
	if( $limit_content < $content ){
		$limit_content .= "..."; 
	}
	return $limit_content;
}
endif;

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Get excerpt content (Effect only in archive page)
 */

if( ! function_exists( 'accesspress_mag_excerpt' ) ):
function accesspress_mag_excerpt(){
    global $post;
    $excerpt_type = esc_attr( of_get_option( 'excerpt_type' ) );
    $excerpt_length = intval( of_get_option( 'excerpt_lenght' ) );
    $excerpt_content = get_the_content($post -> ID);
    if( $excerpt_type == 'letters' ){
        $excerpt_content = accesspress_mag_letter_count( $excerpt_content, $excerpt_length );
    } else {
        $excerpt_content = accesspress_mag_word_count( $excerpt_content, $excerpt_length );
    }
    echo '<p>'. $excerpt_content .'</p>';
}
endif;

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * BreadCrumb Settings
 */

if( ! function_exists( 'accesspress_mag_breadcrumbs' ) ):
function accesspress_mag_breadcrumbs() {
  wp_reset_postdata();
  global $post;
  $trans_here = esc_attr( of_get_option( 'trans_you_are_here', 'You are here' ) );
  if( empty( $trans_here ) ){ $trans_here = __( 'You are here', 'accesspress-mag' ); }
  
  $trans_home = esc_attr( of_get_option( 'trans_home', 'Home' ) );
  if( empty( $trans_home ) ){ $trans_home = __( 'Home', 'accesspress-mag' ); }
  
  $search_result_text = esc_attr( of_get_option( 'trans_search_results_for', 'Search Results for' ) );
  if( empty( $search_result_text ) ) { $search_result_text = __( 'Search Results for ', 'accesspress-mag' ); }
  
    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = '<span class="bread_arrow"> > </span>'; // delimiter between crumbs
    $home = $trans_home; // text for the 'Home' link
    $showHomeLink = of_get_option( 'show_home_link_breadcrumbs' );

  $showCurrent = of_get_option( 'show_article_breadcrumbs' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
  
  $homeLink = esc_url( home_url() );
  
  if (is_home() || is_front_page()) {
  
    if ($showOnHome == 1) echo '<div id="accesspres-mag-breadcrumbs"><div class="ak-container"><a href="' . $homeLink . '">' . $home . '</a></div></div>';
  
  } else {
       if($showHomeLink == 1){ 
           echo '<div id="accesspres-mag-breadcrumbs" class="clearfix"><span class="bread-you">'.esc_attr( $trans_here ).'</span><div class="ak-container"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
         } else {
           echo '<div id="accesspres-mag-breadcrumbs" class="clearfix"><span class="bread-you">'.esc_attr( $trans_here ).'</span><div class="ak-container">' . $home . ' ' . $delimiter . ' ';
        }
  
    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before .  single_cat_title('', false) . $after;
  
    } elseif ( is_search() ) {
      echo $before . $search_result_text.' "' . get_search_query() . '"' . $after;
  
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
  
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
  
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
  
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
  
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
  
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
  
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
  
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Author: ' . $userdata->display_name . $after;
  
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
    else
    {
        
    }
  
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page' , 'accesspress-mag') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }	  
    echo '</div></div>';	  
  }
}
endif;

/*---------------------------------------------------------------------------------------------------------------------------------------*/
    
/**
 * 
 * Replace function for WooCommerce breadcrumbs
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'accesspress_mag_woocommerce_breadcrumbs' ); 
if( ! function_exists( 'accesspress_mag_woocommerce_breadcrumbs' ) ):
function accesspress_mag_woocommerce_breadcrumbs() { 
$seperator = ' <span class="bread_arrow"> > </span> ';  
$trans_home = esc_attr( of_get_option( 'trans_home', 'Home' ) );
if( empty( $trans_home ) ){ $trans_home = __( 'Home', 'accesspress-mag' ); }
$home_text = $trans_home ;

$trans_here = esc_attr( of_get_option( 'trans_you_are_here', 'You are here' ) );
if( empty( $trans_here ) ){ $trans_here = __( 'You are here', 'accesspress-mag' ); }
    return array( 
        'delimiter' => " ".$seperator." ", 
        'before' => '', 
        'after' => '', 
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb"><span class="bread-you">'.$trans_here.'</span><div class="ak-container">', 
        'wrap_after' => '</div></nav>', 
        'home' =>  $home_text
    ); 
}
endif;

add_action( 'init', 'accesspress_mag_remove_wc_breadcrumbs' );
 
function accesspress_mag_remove_wc_breadcrumbs() { 
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 ); 
} 

$accesspress_show_breadcrumb = of_get_option( 'show_hide_breadcrumbs' ); 
if((function_exists('accesspress_mag_woocommerce_breadcrumbs') && $accesspress_show_breadcrumb == 1)) { 
add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 10, 0 ); 
}
/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Remove bbpress breadcrumbs
 */
if( ! function_exists( 'accesspress_mag_bbp_no_breadcrumb' ) ):
function accesspress_mag_bbp_no_breadcrumb ($arg){
    return true ;
}
endif;
add_filter('bbp_no_breadcrumb', 'accesspress_mag_bbp_no_breadcrumb' );

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Random Post in header
 */
if ( ! function_exists( 'accesspress_mag_random_post' ) ) :
function accesspress_mag_random_post() {
   $get_random_post = new WP_Query( array(
      'posts_per_page'        => 1,
      'post_type'             => 'post',
      'ignore_sticky_posts'   => true,
      'orderby'               => 'rand'
   ) );
?>
   <div class="random-post">
      <?php 
        if( $get_random_post->have_posts() ) {
            while( $get_random_post->have_posts() ) {
                $get_random_post->the_post();
      ?>
        <a href="<?php the_permalink(); ?>" title="<?php _e( 'View a random post', 'accesspress-mag' ); ?>"><i class="fa fa-random"></i></a>
      <?php
            }
        }
      ?>
   </div>
   <?php
   wp_reset_query();
}
endif;