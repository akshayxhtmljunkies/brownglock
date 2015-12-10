<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package AccessPress Mag Pro
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

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Header scripts 
 */
 
if( ! function_exists( 'accesspress_header_scripts' ) ): 
function accesspress_header_scripts(){
    
    $custom_js = of_get_option( 'custom_script' );
    echo $custom_js;
}
endif;

add_action('wp_head', 'accesspress_header_scripts');
/*----------------------------------------------------------------------------------------------------------------------------------------*/

 function apmag_admin_scripts(){
    // Enqueue custom admin panel JS
		wp_enqueue_script(
			'apmag-custom-admin',
			OPTIONS_FRAMEWORK_DIRECTORY . 'js/custom-admin.js',
			array( 'jquery')
			);    
 }
 add_action('admin_enqueue_scripts','apmag_admin_scripts');
 
/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Homepage slider
 */ 
 
if( ! function_exists( 'accesspress_mag_slider_cb' ) ):
function accesspress_mag_slider_cb(){
        $slider_post_option = of_get_option( 'slide_post_option', 'by_latest_post' );       
        $slider_category = of_get_option( 'homepage_slider_category' );
        $category_box_option = of_get_option( 'slider_cat_box_option', '1' );
        $slide_count = of_get_option( 'count_slides' , '2' );
        if( $slide_count == 0 ){
            $posts_perpage_value = 4;
        } elseif( empty( $slider_category ) && $slider_post_option == 'by_category' ){
            $posts_perpage_value = 4;
        } else {
            $posts_perpage_value = $slide_count*4;
        }
        $slide_info = of_get_option( 'slider_info', '1' );
        $posts_perpage_value = apply_filters( 'slider_posts', $posts_perpage_value );
        $posts_order_vlaue = 'DESC';
        $posts_order_vlaue = apply_filters( 'slider_order', $posts_order_vlaue );
        $slider_args = array(
                    'post_type'=>'post',
                    'post_status'=>'publish',
                    'posts_per_page'=>$posts_perpage_value,
                    'order'=>$posts_order_vlaue,
                    );
        if( $slider_post_option == 'by_category' ){
            $slider_args['category_name'] = $slider_category;
            $slider_args['meta_query'] = array(
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        )
                                    );
        }
        elseif( $slider_post_option == 'by_featured_post' ){
            $slider_args['meta_query'] = array(
                                    'relation' => 'AND',
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        ),
                                        array(
                                            'key' => 'post_featured_on_slider',
                                            'value' => '1',
                                            'compare' => '='                                            
                                        )                                    
                        );
        } else{
            $slider_args['meta_query'] = array(
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        )
                                    );
            
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
                $post_image_id = get_post_thumbnail_id();
                $post_big_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-slider-big-thumb', true );
                $post_small_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-slider-small-thumb', true );
                $post_single_image_path = wp_get_attachment_image_src( $post_image_id, 'accesspress-mag-singlepost-default', true );
                $post_image_alt = get_post_meta( $post_image_id, '_wp_attachment_image_alt', true );
                $slider_layout = of_get_option( 'slider_layout', 'slider-default' );
                if( $slider_layout == 'slider-default' ) {
                if( $slide_counter%4 == 1 ){
            ?>                        
                    <div class="slider">
                        <div class="apmag-slider-bigthumb">
                            <a href="<?php echo the_permalink();?>" title="<?php the_title(); ?>">
                            <div class="big_slide wow fadeInLeft">
                                <div class="slide-image">
                                    <img src="<?php echo $post_big_image_path[0];?>" alt="<?php echo esc_attr($post_image_alt);?>" />
                                    <?php if( $category_box_option == '1' ) { ?>
                                        <div class="big-cat-box">
                                            <?php 
                                                category_details( get_the_ID() );
                                                do_action( 'accesspress_mag_post_meta' );
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if($slide_info==1):?>
                                <div class="mag-slider-caption">
                                  <h2 class="slide-title"><?php the_title();?></h2>
                                </div>
                                <?php endif;?>
                            </div>
                            </a>
                        </div>                                 
                        
            <?php } else { if( $slide_counter%4 == 2 ){ echo '<div class="small-slider-wrapper wow fadeInRight">'; } ?>                
                       <div class="apmag-slider-smallthumb">
                       <a href="<?php echo the_permalink();?>" title="<?php the_title(); ?>">
                        <div class="small_slide">
                            <?php 
                                $cat_info = get_the_category(); 
                                $cat_link = get_category_link( $cat_info[0]->cat_ID );
                                $cat_name = $cat_info[0]->name; 
                            ?>
                            <div class="slide-image"><img src="<?php echo esc_url( $post_small_image_path[0] ); ?>" alt="<?php echo esc_attr( $post_image_alt );?>" /></div>
                            <div class="mag-small-slider-caption">
                              <?php if( $slide_info == 1 ):?><h3 class="slide-title"><?php the_title();?></h3><?php endif; ?>
                            </div>                            
                        </div>
                       </a>
                       <?php category_details( get_the_ID() ); ?>
                       </div>
            <?php 
                 }
                 if($slide_counter%4==0){
            ?>
                    </div>
                    </div>
            <?php 
                  }
                } else {
            ?>
                    <div class="slider">
                        <div class="apmag-slider-single">
                            <a href="<?php echo the_permalink();?>" title="<?php the_title(); ?>">
                            <div class="big-single-slide wow fadeInLeft">
                                <div class="slide-image non-zoomin">
                                    <img src="<?php echo $post_single_image_path[0];?>" alt="<?php echo esc_attr( $post_image_alt );?>" />
                                    <?php if( $category_box_option == '1' ) { ?>
                                        <div class="big-cat-box">
                                            <?php 
                                                category_details( get_the_ID() );
                                                do_action( 'accesspress_mag_post_meta' );
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if( $slide_info == 1 ) { ?> <div class="mag-slider-caption"><h2 class="slide-title"><?php the_title();?></h2></div><?php } ?>
                            </div>
                            </a>
                        </div>
                    </div>
            <?php
                }                
            }                
            echo '</div>';
        }
        wp_reset_query();
 }

endif;
 
add_action( 'accesspress_mag_slider', 'accesspress_mag_slider_cb', 10 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Homepage Slider section without slider effect
 */
if( !function_exists( 'accesspress_mag_non_slider_cb' ) ):
    function accesspress_mag_non_slider_cb() {
        $non_slider_post_option = of_get_option( 'slide_post_option', 'by_latest_post' );
        $non_slider_category = of_get_option( 'homepage_slider_category' );
        $slide_info = of_get_option( 'slider_info', '1' );
        $posts_order_vlaue = 'DESC';
        $posts_order_vlaue = apply_filters( 'slider_order', $posts_order_vlaue );
        $non_slider_args = array(
                        'post_type'=>'post',
                        'post_status'=>'publish',
                        'posts_per_page'=>5,
                        'order'=>$posts_order_vlaue,
                        );
        if( $non_slider_post_option == 'by_category' ){
            $non_slider_args['category_name'] = $non_slider_category;
            $non_slider_args['meta_query'] = array(
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        )
                                    );
        }
        elseif( $non_slider_post_option == 'by_featured_post' ){
            $non_slider_args['meta_query'] = array(
                                    'relation' => 'AND',
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        ),
                                        array(
                                            'key' => 'post_featured_on_slider',
                                            'value' => '1',
                                            'compare' => '='                                            
                                        )                                    
                        );
        } else{
            $non_slider_args['meta_query'] = array(
                                        array(
                                            'key' => '_thumbnail_id',
                                            'compare' => '!=',
                                            'value' => null
                                        )
                                    );
            
        }
        $non_slider_query = new WP_Query($non_slider_args);
        $grid_conter = 0;
        if( $non_slider_query->have_posts() ) {
?>
        <div class="apmag-nonslider-grid">
            <?php
                while( $non_slider_query->have_posts() ) {
                    $grid_conter++;
                    $non_slider_query->the_post();
                    $image_id = get_post_thumbnail_id();
                    $image_path = wp_get_attachment_image_src( $image_id, 'accesspress-mag-slider-big-thumb', true );
                    $small_image_path = wp_get_attachment_image_src( $image_id, 'accesspress-mag-singlepost-style1', true );
                    $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                    if( $grid_conter == 1 ) {
            ?>
                    <div class="grid-big-post">
                        <div class="grid-post-thumb">
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                <figure><img src="<?php echo esc_url( $image_path[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" /></figure>
                            </a>
                        <?php if( !empty( $slide_info ) && $slide_info == '1' ) {?>
                        <div class="grid-post-info-wrapper">
                            <div class="grid-post-info">
                                <div class="grid-post-categories"><?php post_multiple_categories( get_the_ID() ); ?></div>
                                <h3 class="entry-title grid-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                            </div>
                            <div class="grid-post-meta-info">
                                <span class="post-author"><?php the_author_posts_link(); ?></span>
                                <?php do_action( 'accesspress_mag_home_posted_on' );?>
                            </div>
                        </div>
                        <?php } ?>    
                        </div>
                        
                    </div>
            <?php
                } else {
                    if ($grid_conter == 2) {
                            echo '<div class="grid-posts-bunch"><div class="grid-posts-bunch-inner clearfix">';
                        }
            ?>
                    <div class="grid-small-post grid-small-common">
                        <div class="grid-post-thumb">
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                <figure><img src="<?php echo esc_url( $small_image_path[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" /></figure>
                            </a>
                        <?php if( !empty( $slide_info ) && $slide_info == '1' ) {?>
                        <div class="grid-post-info">
                            <h3 class="entry-title grid-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                            <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' ); ?></div>
                        </div>
                        <?php } ?>
                        </div>
                    </div>
            <?php
                if ($grid_conter == 5) {
                        echo '</div></div>';
                    }
                }
            }
            ?>
        </div>
<?php       
        }
    }
endif;
 
add_action( 'accesspress_mag_non_slider', 'accesspress_mag_non_slider_cb', 10 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Slider scripts
 */

if( ! function_exists( 'accesspress_mag_slider_script' ) ):
function accesspress_mag_slider_script(){
    $accesspress_slider_controls = ( of_get_option( 'slider_controls' ) == "1" ) ? "true" : "false";
    $accesspress_slider_auto_transaction = ( of_get_option( 'slider_auto_transition' ) == "1" ) ? "true" : "false";
    $accesspress_slider_pager = ( of_get_option( 'slider_pager' ) == "1" ) ? "true" : "false";
    $accesspress_slider_transition = of_get_option( 'slider_transition' );
    $accesspress_slider_speed = (!of_get_option('slider_speed')) ? "5000" : of_get_option('slider_speed');
    $accesspress_slider_pause = (!of_get_option('slider_pause')) ? "5000" : of_get_option('slider_pause');
?>
    <script type="text/javascript">
        jQuery(function($){
            $("#homeslider").bxSlider({
                adaptiveHeight: true,
                pager:<?php echo esc_attr( $accesspress_slider_pager );?>,
                controls:<?php echo esc_attr( $accesspress_slider_controls ); ?>,
                mode: '<?php echo esc_attr( $accesspress_slider_transition ); ?>',
                auto:<?php echo esc_attr( $accesspress_slider_auto_transaction );?>,
                pause: '<?php echo $accesspress_slider_pause; ?>',
                speed: '<?php echo $accesspress_slider_speed; ?>'
            });
            });
    </script>
<?php
}

endif;

add_action( 'wp_head', 'accesspress_mag_slider_script' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Homepage Popular Block
 */
if( !function_exists( 'accesspress_mag_popular_block_callback' ) ):
    function accesspress_mag_popular_block_callback() {
        $popular_block_title = of_get_option( 'popular_block_name', 'Popular Articles' );
        $popular_block_category = of_get_option( 'popular_block_category', 'all' );
        $popular_posts_per_page = of_get_option( 'popular_posts_count', '5' );
        $apmag_overlay_icon = of_get_option( 'apmag_overlay_icon', 'fa-external-link' );
        $pbc_args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => $popular_posts_per_page,
                        'meta_key' => 'post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                        );
        if( !empty( $popular_block_category ) && $popular_block_category != 'all' ) {
            $pbc_args['category_name'] = $popular_block_category;
        }
        $pbc_query = new WP_Query( $pbc_args );
?>
        <div class="popular-block-wrapper">
            <h2 class="block-cat-name"><span><?php echo esc_attr( $popular_block_title ); ?></span></h2>            
            <?php
                $p_total_posts = $pbc_query->post_count;
                $p_counter = 0; 
                if( $pbc_query->have_posts() ) {
                    while( $pbc_query->have_posts() ) {
                        $p_counter++;
                        $pbc_query->the_post();
                        if( $p_counter == 1 ) {
                            $p_image_size = 'accesspress-mag-singlepost-default';
                        } else {
                            $p_image_size = 'accesspress-mag-block-big-thumb';
                        }
                        $p_image_id = get_post_thumbnail_id();
                        $p_image_path = wp_get_attachment_image_src( $p_image_id, $p_image_size, true );
                        $p_image_alt = get_post_meta( $p_image_id, '_wp_attachment_image_alt', true );
                        $post_format = get_post_format( get_the_ID() );
                        if( $post_format == 'video' ){
                            $post_format_icon = 'fa-video-camera';
                            $show_icon = 'on';
                        } elseif( $post_format == 'audio' ){
                            $post_format_icon = 'fa-music';
                            $show_icon = 'on';
                        } elseif( $post_format == 'gallery' ){
                            $post_format_icon = 'fa-picture-o';
                            $show_icon = 'on';
                        } else{
                            $show_icon = 'off';
                        }
                        if( $p_counter > 1 && $p_counter == 2 ){ echo '<div class="posts-grid-wrapper">'; }
            ?>
                        <div class="single_post clearfix <?php if( $p_counter == 1 ) { echo 'first-post'; } ?> non-zoomin">
                            <?php if( has_post_thumbnail() ): ?>   
                                <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php echo esc_url( $p_image_path[0] );?>" alt="<?php echo esc_attr( $p_image_alt );?>" /></a>
                                    <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a>
                                    <?php if( $show_icon == 'on' ){ ?><span class="format_icon"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>    
                                </div>                                    
                            <?php endif ; ?>
                                <div class="post-desc-wrapper">
                                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                                    <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                                    <div class="ratings-wrapper"><?php do_action( 'accesspress_mag_post_review' );?></div>
                                </div>
                                <?php if( $p_counter > 1 ){ ?><div class="post-content"><?php accesspress_mag_homepage_excerpt(); ?></div><?php } ?>
                        </div>
            <?php
                        if( $p_counter == $p_total_posts ) { echo '</div>'; }
                    }
                }
            ?>
        </div>
<?php
    }
endif;
add_action( 'accesspress_mag_popular_block', 'accesspress_mag_popular_block_callback', 10 ); 
/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Get category name and it's link  
 */
 if( ! function_exists( 'category_details' ) ):
 function category_details($post_id){
    $cat_sec = '';
    $cat_details = get_the_category($post_id);
    $cat_id = $cat_details[0]->cat_ID;
    $cat_name = $cat_details[0]->name;             
    $cat_link = get_category_link( $cat_id );
    $cat_sec .= '<a href="'. esc_url( $cat_link ) .'" title="'. get_the_title( $post_id ) .'"><span class="cat-name">'. esc_attr( $cat_name ) .'</span></a>';
    echo $cat_sec ;
 }
endif;

/**
 * Display multiple categories with link
 */
 if( !function_exists( 'post_multiple_categories' ) ):
    function post_multiple_categories( $post_id ) {
        $multi_cat_section = '';
        $get_multi_categories = get_the_category( $post_id );
        $multi_cat_section .= '<div class="multi-categories-wrapper">';
        if( !empty( $get_multi_categories ) ) {
            foreach( $get_multi_categories as $categories ) {
                $cat_link = get_category_link( $categories->cat_ID );
                $cat_name = $categories->name;
                $multi_cat_section .= '<a href="'. esc_url( $cat_link ) .'" title"'. get_the_title( $post_id ) .'">';
                $multi_cat_section .= '<span class="multi-catname">'. esc_attr( $cat_name ) .'</span></a>';
            }
        }
        $multi_cat_section .= '</div>';
        echo $multi_cat_section;
    }
 endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Product Review for default posts in home page
 */
if( ! function_exists( 'accesspress_mag_post_review_cb' )):
function accesspress_mag_post_review_cb(){
    global $post; 
    //echo $post_id = $post->ID;
    $post_review_type = get_post_meta( $post->ID, 'product_review_option', true );
    switch ($post_review_type){
        case 'rate_stars':
            $post_meta_name = 'star_rating';
            $post_meta_value = 'feature_star';
            break;
        case 'rate_percent':
            $post_meta_name = 'percent_rating';
            $post_meta_value = 'feature_percent';
            break;
        case 'rate_point':
            $post_meta_name = 'points_rating';
            $post_meta_value = 'feature_points';
            break;
        default:
            $post_meta_name = 'star_rating';
            $post_meta_value = 'feature_star';
    }
    if( $post_review_type != 'norate' && !empty( $post_review_type ) ){
        $product_rating = get_post_meta( $post->ID, $post_meta_name, true );
        $count = count($product_rating);
        $total_review = 0;
        foreach ( $product_rating as $key => $value ) {
            $rate_value = $value[ $post_meta_value ];
            $total_review = $total_review+$rate_value;
        }
        if( $post_meta_name == 'star_rating' ){
            $total_review = $total_review/$count;
            $final_value = round( $total_review, 1, PHP_ROUND_HALF_UP );
            $final_value = ceiling( $total_review, 0.5 ) ;
            echo display_product_rating( $final_value );
        } elseif( $post_meta_name == 'percent_rating' ){
            $total_review = $total_review/$count;
            $percent_review = round( $total_review, 2 );
            $percent_output = '';
            $percent_output .= '<span class="show-total-precent">'.esc_attr( $total_review).'%</span>';
            $percent_output .= '<div class="percent-rating-bar-wrap animate-progress"><div style="width:'.esc_attr( $percent_review ).'%"><span></span></div></div>';
            echo $percent_output;            
        } elseif( $post_meta_name == 'points_rating' ){
            $total_review = $total_review/$count;
            $point_show_value = round( $total_review, 2 );
            $point_value = $point_show_value * 10;
            $point_output = '';
            $point_output .= '<span class="show-total-point">'.esc_attr( $point_show_value ).'/10 </span>';
            $point_output .= '<div class="percent-rating-bar-wrap animate-progress"><div style="width:'.esc_attr( $point_value ).'%"><span></span></div></div>';
            echo $point_output ;
        }
    }
}

endif;

add_action( 'accesspress_mag_post_review', 'accesspress_mag_post_review_cb', 10 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * 
 * Product Review for single post
 * 
 */

if( ! function_exists( 'accesspress_mag_single_post_review_cb' ) ):
function accesspress_mag_single_post_review_cb(){
    global $post;
    $trans_summary = of_get_option( 'trans_summary' );
    if( empty( $trans_summary ) ){ $trans_summary = 'Summary'; }
    $trans_review = of_get_option( 'trans_review_overview' );
    if( empty( $trans_review ) ){ $trans_review = 'Review overview' ; }
    $post_review_type = get_post_meta( $post -> ID, 'product_review_option', true );
    $product_rating_description = get_post_meta($post->ID, 'product_rate_description', true);
    
    if($post_review_type!='norate' && $post_review_type =='rate_stars'){
        $star_rating = get_post_meta($post -> ID, 'star_rating', true);
        if( !empty ( $star_rating ) ){
    ?>
        <div class="post-review-wrapper">
            <span class="section-title"><?php echo esc_attr( $trans_review ) ;?></span>
                <div class="stars-review-wrapper">
                    <?php 
                        $count = 0;
                        $total_review = 0;
                        foreach ( $star_rating as $key => $value ) {
                            if( !empty( $key['feature_name'] ) || !empty( $value['feature_star'] ) ) {
                            $count++;
                            $featured_name = $value['feature_name'];
                            $star_value = $value['feature_star'];
                            if( empty( $star_value ) ) $star_value = '0.5';
                            $total_review = $total_review+$star_value;
                    ?>
                      <div class="review-featured-wrap clearfix">  
                        <span class="review-featured-name"><?php echo esc_attr( $featured_name ); ?></span>
                        <span class="stars-count"><?php display_product_rating( $star_value );?></span>
                      </div>
                    <?php
                            }
                        }
                         $total_review = $total_review/$count;
                         //$final_value = round( $total_review, 1);
                         $final_value = ceiling($total_review, 0.5) ;                     
                    ?>
                </div>
            <div class="summary-wrapper clearfix">
                <div class="summary-details">
                    <span class="summery-label"><?php echo esc_attr( $trans_summary ) ;?></span>
                    <span class="summary-comments"><?php echo esc_textarea( $product_rating_description ); ?></span>
                </div>
                <div class="total-reivew-wrapper">
                    <span class="total-value"><?php echo esc_attr( $final_value ) ;?></span>
                    <span class="stars-count"><?php display_product_rating( $final_value );?></span>
                </div>
            </div>
        </div>
    <?php
        }
    }
    elseif( $post_review_type != 'norate' && $post_review_type == 'rate_percent' ) {
        $percent_rating = get_post_meta($post -> ID, 'percent_rating', true);
        if( !empty ( $percent_rating ) ){
   ?>
        <div class="post-review-wrapper">
            <span class="section-title"><?php echo esc_attr( $trans_review ) ;?></span>
                <div class="percent-review-wrapper">
                    <?php 
                        $count = count( $percent_rating );
                        $total_review = 0;
                        foreach ( $percent_rating as $key => $value ) {                    
                        $featured_name = $value['feature_name'];
                        $percent_value = $value['feature_percent'];
                        if( empty( $percent_value ) ) $percent_value = '1';
                        $total_review = $total_review+$percent_value;
                    ?>
                    <div class="percent-wrap clearfix">  
                        <span class="featured-name"><?php echo esc_attr( $featured_name ); ?></span> - <span class="percent-value"><?php echo esc_attr( $percent_value );?> &#37; </span>
                        <div class="percent-rating-bar-wrap"><div style="width:<?php echo esc_attr( $percent_value )?>%"></div></div>
                    </div>
                    <?php 
                        }
                        $total_review = $total_review/$count; 
                        $total_review = round( $total_review, 2 ); 
                    ?>
                </div>
                <div class="summary-wrapper clearfix">
                <div class="summary-details">
                    <span class="summery-label"><?php echo esc_attr( $trans_summary ) ;?></span>
                    <span class="summary-comments"><?php echo esc_textarea( $product_rating_description ); ?></span>
                </div>
                <div class="total-reivew-wrapper">
                    <span class="total-value"><?php echo esc_attr( $total_review ) ;?> <span class="tt-per"> &#37;</span> </span>
                </div>
            </div>
        </div>
   <?php    
        }
    }
    elseif( $post_review_type !='norate' && $post_review_type == 'rate_point' ){
        $points_rating = get_post_meta($post -> ID, 'points_rating', true);
        if( !empty ( $points_rating ) ){
   ?>
        <div class="post-review-wrapper">
            <span class="section-title"><?php echo esc_attr( $trans_review ) ;?></span>
                <div class="points-review-wrapper">
                    <?php 
                        $count = count( $points_rating );
                        $total_review = 0;
                        foreach ( $points_rating as $key => $value ) {                    
                        $featured_name = $value['feature_name'];
                        $points_value = $value['feature_points'];
                        if( empty( $points_value ) ) $points_value = '0.1';
                        $total_review = $total_review+$points_value;
                        $points_bar = $points_value * 10;
                    ?>
                    <div class="percent-wrap clearfix">  
                        <span class="featured-name"><?php echo esc_attr( $featured_name ); ?></span> - <span class="percent-value"><?php echo esc_attr( $points_value );?></span>
                        <div class="percent-rating-bar-wrap"><div style="width:<?php echo esc_attr( $points_bar )?>%"></div></div>
                    </div>
                    <?php 
                        }
                        $total_review = $total_review/$count;
                        $total_review = round( $total_review, 2 ); 
                    ?>
                </div>
                <div class="summary-wrapper clearfix">
                <div class="summary-details">
                    <span class="summery-label"><?php echo esc_attr( $trans_summary ) ;?></span>
                    <span class="summary-comments"><?php echo esc_textarea( $product_rating_description ); ?></span>
                </div>
                <div class="total-reivew-wrapper">
                    <span class="total-value"><?php echo esc_attr ( $total_review ) ;?></span>
                </div>
            </div>
        </div>
   <?php    
        }
    }
}
endif;

add_action( 'accesspress_mag_single_post_review', 'accesspress_mag_single_post_review_cb', 10 );

/*-----------------------------------------------------------------------------------------------------------------------*/


if( !function_exists('ceiling') )
{
    function ceiling( $number, $significance = 1 )
    {
        return ( is_numeric( $number ) && is_numeric( $significance ) ) ? (ceil( $number/$significance )*$significance ) : false;
    }
}

/*-----------------------------------------------------------------------------------------------------------------------*/

/**
 * 
 * Rating fuction
 * 
*/

if( ! function_exists( 'display_product_rating' ) ):
function display_product_rating ( $number ) {
    // Convert any entered number into a float
    // Because the rating can be a decimal e.g. 4.5
    if( empty( $number ) ){
        $number = 0.5;
    } else {
        $number = number_format ( $number, 1 );
    
        // Get the integer part of the number
        $intpart = floor ( $number );
    
        // Get the fraction part
        $fraction = $number - $intpart;
    
        // Rating is out of 5
        // Get how many stars should be left blank
        $unrated = 5 - ceil ( $number );
    
        // Populate the full-rated stars
        if ( $intpart <= 5 ) {
            for ( $i=0; $i<$intpart; $i++ )
    	    echo '<span class="star-value"><i class="fa fa-star"></i></span>';
        }
        
        // Populate the half-rated star, if any
        if ( $fraction == 0.5 ) {
            echo '<span class="star-value"><i class="fa fa-star-half-o"></i></span>';        
        }
        
        
        // Populate the unrated stars, if any
        if ( $unrated > 0 ) {
            for ( $j=0; $j<$unrated; $j++ )
    	    echo '<span class="star-value"><i class="fa fa-star-o"></i></span>';
        }
    }
}

endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * 
 * Get/Set post views
 * 
 */

if( ! function_exists( 'getPostViews' ) ):
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

endif;

if( ! function_exists( 'setPostViews' ) ):
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

endif;

// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
 
/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Sidebar layout for post & pages
 */
 
function accesspress_mag_sidebar_layout_class($classes){
    global $post;
    	if( is_404() ) {
    	$classes[] = ' ';
    	} elseif( is_singular() ) {
 	    $global_sidebar= of_get_option( 'global_post_sidebar', 'right-sidebar' );
    	$post_sidebar = get_post_meta( $post -> ID, 'accesspress_mag_sidebar_layout', true );        
        $page_sidebar = get_post_meta( $post -> ID, 'accesspress_mag_page_sidebar_layout', true );
        if( 'post' == get_post_type() ) {
            if( $post_sidebar == 'global-sidebar' || empty( $post_sidebar ) ){
                $post_class = $global_sidebar;
            } else {
                $post_class = $post_sidebar;
            }
        	$classes[] = 'single-post-'.$post_class;
        } else {
            $classes[] = 'page-'.$page_sidebar;
        }
    	} elseif( is_archive() ) {
    	   if( is_category() ) {
    	        $cat_id = get_query_var( 'cat' );
                $global_sidebar = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
                $category_sidebar = of_get_option( $cat_id.'_cat_sidebar', 'global-sidebar' );
                if( $category_sidebar == 'global-sidebar' ){
                    $sidebar_option = $global_sidebar;
                } else {
                    $sidebar_option = $category_sidebar;
                }
                $classes[] = 'archive-'.$sidebar_option;
    	   } else {
    	        $archive_sidebar = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
                $classes[] = 'archive-'.$archive_sidebar;
    	   }    	   
        } elseif( is_search() ) {
            $archive_sidebar = of_get_option( 'global_archive_sidebar', 'right-sidebar' );
            $classes[] = 'archive-'.$archive_sidebar;
        }else{
    	$classes[] = 'page-right-sidebar';	
    	}
    	return $classes;
    }
    
add_filter( 'body_class', 'accesspress_mag_sidebar_layout_class' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Header style class
 */
function accesspress_mag_header_style_class( $classes ){
    $header_style = of_get_option( 'header_style_option' );
    if( $header_style == '2' ){
        $classes[] = 'header_style2';
    } elseif( $header_style == '3' ){
        $classes[] = 'header_style3';
    } elseif( $header_style == '4' ){
        $classes[] = 'header_style4';
    } else{
        $classes[] = 'header_style1';
    }
    $body_home_layout = of_get_option( 'homepage_layout', 'home-default' );
    $classes[] = $body_home_layout.'-layout';
    return $classes;
}

add_filter( 'body_class', 'accesspress_mag_header_style_class' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * 
 * Template style layout for post & pages
 * 
 */
 
function accesspress_mag_template_layout_class($classes){
    global $post;
    	if( is_404()){
    	$classes[] = ' ';
    	}elseif(is_singular()){
 	    $global_template = of_get_option( 'global_post_template' );
    	$post_template = get_post_meta( $post->ID, 'accesspress_mag_post_template_layout', true );
        if( 'post' == get_post_type() ) {  
            if( $post_template == 'global-template' || empty( $post_template ) ){
                $post_template_class = $global_template;
            } else {
                $post_template_class = $post_template;
            }
        	$classes[] = 'single-post-'.$post_template_class;
        }       
    	} elseif(is_archive()){
    	   if( is_category() ) {
                 $cat_id = get_query_var('cat');
                 $cat_template_id = $cat_id.'_cat_template';
                 $category_layout_template = of_get_option( $cat_template_id, 'global-archive-default' );
                 if( empty( $category_layout_template ) || $category_layout_template == 'global-archive-default' ){
                    $archive_template = of_get_option( 'global_archive_template', 'archive-default' );
                 } else {
                    $archive_template = $category_layout_template;
                 }     
             } else {
                $archive_template = of_get_option( 'global_archive_template', 'archive-default' );
             }
            //$archive_template = of_get_option( 'global_archive_template', 'archive-default' );
            $classes[] = 'archive-page-'.$archive_template;
        } elseif(is_search()){
            $archive_template = of_get_option( 'global_archive_template', 'archive-default' );
            $classes[] = 'archive-page-'.$archive_template;
        }else{
    	$classes[] = 'page-default-template';	
    	}
    	return $classes;
    }
    
add_filter( 'body_class', 'accesspress_mag_template_layout_class' );


// add category nicenames in body and post class
global $current_class;
$current_class = 'right-side';

/**
 * Post clas for archive style 2
 */
 
function archive_single_class( $classes ) {
	global $post, $current_class;
    if( is_archive() ){
        if( is_category() ) {
             $cat_id = get_query_var('cat');
             $cat_template_id = $cat_id.'_cat_template';
             $category_layout_template = of_get_option( $cat_template_id, 'global-archive-default' );
             if( empty( $category_layout_template ) || $category_layout_template == 'global-archive-default' ){
                $archive_template = of_get_option( 'global_archive_template', 'archive-default' );
             } else {
                $archive_template = $category_layout_template;
             }     
         } else {
            $archive_template = of_get_option( 'global_archive_template', 'archive-default' );
         }
        //$archive_template = of_get_option( 'global_archive_template' );
        if( $archive_template == 'archive-style2' ){
            $classes[] = 'alternate-layout';
            $current_class = ($current_class == 'right-side') ? 'left-side' : 'right-side';
            $classes[] = $current_class;        
        }
    }
	return $classes;
}

add_filter( 'post_class', 'archive_single_class' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Website layout
 */

function accesspress_mag_website_layout_class( $classes ){
    $website_layout = of_get_option( 'website_layout_option' );
    if($website_layout == 'boxed' ){
        $classes[] = 'boxed-layout';
    } else {
        $classes[] = 'fullwidth-layout';
    }
    return $classes;
}

add_filter( 'body_class', 'accesspress_mag_website_layout_class' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Posted on meta for general
 */

if( ! function_exists( 'accesspress_mag_post_meta_cb' ) ):
function accesspress_mag_post_meta_cb(){
    global $post;
    $show_post_views = of_get_option('show_post_views');
    $show_comment_count = of_get_option('show_comment_count');
    if($show_comment_count==1){
        $post_comment_count = get_comments_number( $post->ID );
        echo '<span class="comment_count"><i class="fa fa-comments"></i>'.esc_attr( $post_comment_count ).'</span>';
    }
    if($show_post_views==1){
        echo '<span class="apmag-post-views"><i class="fa fa-eye"></i>'.getPostViews(get_the_ID()).'</span>';
    }
}

endif;

add_action( 'accesspress_mag_post_meta', 'accesspress_mag_post_meta_cb', 10 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Posted on meta for home
 */

if( ! function_exists( 'accesspress_mag_home_posted_on_cb' ) ) :
function accesspress_mag_home_posted_on_cb(){
    global $post;
    $show_post_views = of_get_option('show_post_views');
    $show_comment_count = of_get_option('show_comment_count');
    $show_post_date = of_get_option('post_show_date');
    
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
    if($show_post_views==1){
        echo '<span class="apmag-post-views"><i class="fa fa-eye"></i>'.getPostViews(get_the_ID()).'</span>';
    }
}

endif;

add_action( 'accesspress_mag_home_posted_on', 'accesspress_mag_home_posted_on_cb', 10 );
/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Excerpt length
 */

if( ! function_exists( 'accesspress_customize_excerpt_more' ) ):
function accesspress_customize_excerpt_more( $more ) {
	return '...';
}
endif;
add_filter( 'excerpt_more', 'accesspress_customize_excerpt_more' );

if( ! function_exists( 'accesspress_word_count' ) ):
function accesspress_word_count( $string, $limit ) {
    $string = strip_tags( $string );
    $string = strip_shortcodes( $string );
	$words = explode( ' ', $string );    
	return implode( ' ', array_slice( $words, 0, $limit ));
}
endif;

if( ! function_exists( 'accesspress_letter_count' ) ):
function accesspress_letter_count( $content, $limit ) {
	$striped_content = strip_tags( $content );
	$striped_content = strip_shortcodes( $striped_content );
	$limit_content = mb_substr( $striped_content, 0 , $limit );
	if( $limit_content < $content ){
		$limit_content .= "..."; 
	}
	return $limit_content;
}
endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Get excerpt content
 */

if( ! function_exists( 'accesspress_mag_excerpt' ) ):
function accesspress_mag_excerpt(){
    global $post;
    $excerpt_type = of_get_option( 'excerpt_type', ' ' );
    $excerpt_length = of_get_option( 'excerpt_lenght', '50' );
    $excerpt_content = get_the_content( $post->ID );
    if( $excerpt_type == 'letters' && !empty( $excerpt_type ) ){
        $excerpt_content = accesspress_letter_count( $excerpt_content, $excerpt_length );
    } else {
        $excerpt_content = accesspress_word_count( $excerpt_content, $excerpt_length );
    }
    echo '<p>'.esc_html( $excerpt_content ).'</p>';
}
endif;

/**
 * Get excerpt content for Homepage Posts
 */

if( ! function_exists( 'accesspress_mag_homepage_excerpt' ) ):
function accesspress_mag_homepage_excerpt(){
    global $post;
    $excerpt_type = of_get_option( 'home_excerpt_type', ' ' );
    $excerpt_length = of_get_option( 'home_excerpt_lenght', '30' );
    $excerpt_content = get_the_content( $post->ID );
    if( $excerpt_type == 'letters' && !empty( $excerpt_type ) ){
        $excerpt_content = accesspress_letter_count( $excerpt_content, $excerpt_length );
    } else {
        $excerpt_content = accesspress_word_count( $excerpt_content, $excerpt_length );
    }
    echo '<p>'.esc_html( $excerpt_content ).'</p>';
}
endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Dynamic color function
 */
if( ! function_exists( 'colourBrightness' ) ):
function colourBrightness($hex, $percent) {
    // Work out if hash given
    $hash = '';
    if (stristr($hex, '#')) {
        $hex = str_replace('#', '', $hex);
        $hash = '#';
    }
    /// HEX TO RGB
    $rgb = array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
    //// CALCULATE 
    for ($i = 0; $i < 3; $i++) {
        // See if brighter or darker
        if ($percent > 0) {
            // Lighter
            $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1 - $percent));
        } else {
            // Darker
            $positivePercent = $percent - ($percent * 2);
            $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1 - $positivePercent));
        }
        // In case rounding up causes us to go to 256
        if ($rgb[$i] > 255) {
            $rgb[$i] = 255;
        }
    }
    //// RBG to Hex
    $hex = '';
    for ($i = 0; $i < 3; $i++) {
        // Convert the decimal digit to hex
        $hexDigit = dechex($rgb[$i]);
        // Add a leading zero if necessary
        if (strlen($hexDigit) == 1) {
            $hexDigit = "0" . $hexDigit;
        }
        // Append to the hex string
        $hex .= $hexDigit;
    }
    return $hash . $hex;
}

endif;

if( ! function_exists( 'hex2rgb' ) ):
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if (strlen($hex) == 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}

endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 *  Function for breadcrumbs
 */
if( ! function_exists( 'accesspress_breadcrumbs' ) ):
function accesspress_breadcrumbs() {
  wp_reset_postdata();
  global $post;
  $trans_here = of_get_option( 'trans_you_are_here' );
  if( empty( $trans_here ) ){ $trans_here = 'You are here'; }
  //$trans_search = of_get_option( '' );
  //if( empty() )

    $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = of_get_option( 'breadcrumb_seperator' );
    if (isset($delimiter)) {
        $delimiter = of_get_option( 'breadcrumb_seperator' );
    } else {
        $delimiter = '&gt;'; // delimiter between crumbs
    }
    $home = of_get_option( 'breadcrumb_home_text' );
    if (isset($home)) {
        $home = of_get_option( 'breadcrumb_home_text' );
    } else {
        $home = 'Home'; // text for the 'Home' link
    }
    $showHomeLink = of_get_option( 'show_home_link_breadcrumbs' );

  $showCurrent = of_get_option( 'show_article_breadcrumbs' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
  
  $homeLink = home_url();
  
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
      echo $before . __( "Search results for", "accesspress-mag" ).' "' . get_search_query() . '"' . $after;
  
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
    
/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * WooCommerce breadcrumbs function
 */

add_filter( 'woocommerce_breadcrumb_defaults', 'accesspress_woocommerce_breadcrumbs' ); 
if( ! function_exists( 'accesspress_woocommerce_breadcrumbs' ) ):
function accesspress_woocommerce_breadcrumbs() { 

$delimiter = of_get_option( 'breadcrumb_seperator' );
    if (isset($delimiter)) {
        $delimiter = of_get_option( 'breadcrumb_seperator' );
    } else {
        $delimiter = '&gt;'; // delimiter between crumbs
    }
$home = of_get_option( 'breadcrumb_home_text' );
if (isset($home)) {
    $home = of_get_option( 'breadcrumb_home_text' );
} else {
    $home = 'Home'; // text for the 'Home' link
}

$trans_here = of_get_option( 'trans_you_are_here' );
if( empty( $trans_here ) ){ $trans_here = 'You are here'; }
//$home_text =of_get_option( 'breadcrumb_home' ); 
return array( 
'delimiter' => " ".$delimiter." ", 
'before' => '', 
'after' => '', 
'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb"><span class="bread-you">'.$trans_here.'</span><div class="ak-container">', 
'wrap_after' => '</div></nav>', 
'home' => $home
); 
} 

endif;

add_action( 'init', 'accesspress_remove_wc_breadcrumbs' ); 

function accesspress_remove_wc_breadcrumbs() { 
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 ); 
} 

$accesspress_show_breadcrumb = of_get_option( 'show_hide_breadcrumbs' ); 
if((function_exists('accesspress_woocommerce_breadcrumbs') && $accesspress_show_breadcrumb == 1)) { 
    add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 10, 0 ); 
}
 
/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Remove bbpress breadcrumbs
 */
if( ! function_exists( 'apmag_bbp_no_breadcrumb' ) ):
function apmag_bbp_no_breadcrumb ($arg){
    return true ;
}
endif;

add_filter('bbp_no_breadcrumb', 'apmag_bbp_no_breadcrumb' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Enqueue admin css 
 */
 
if( ! function_exists( 'accesspress_mag_admin_css' ) ): 
function accesspress_mag_admin_css(){
    wp_enqueue_style('apmag-admin', get_template_directory_uri(). '/inc/option-framework/css/apmag-admin.css');    
}
endif;
add_action('admin_head','accesspress_mag_admin_css');

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Display the related posts
 */
 
if ( ! function_exists( 'accesspress_mag_similar_posts_function' ) ):
   
   function accesspress_mag_similar_posts_function() {
      wp_reset_postdata();
      global $post, $authordata;
      $similar_type = of_get_option( 'similar_article_type' );
      $similar_count = of_get_option( 'similar_article_count' );

      // Define shared post arguments
      $args = array(
         'no_found_rows'            => true,
         'update_post_meta_cache'   => false,
         'update_post_term_cache'   => false,
         'ignore_sticky_posts'      => 1,
         'orderby'               => 'rand',
         'post__not_in'          => array($post->ID),
         'posts_per_page'        => $similar_count
      );
      
      // Similar by categories
      if ( $similar_type == 'category' ) {

         $cats = get_post_meta($post->ID, 'similar-posts', true);

         if ( !$cats ) {
            $cats = wp_get_post_categories($post->ID, array('fields'=>'ids'));
            $args['category__in'] = $cats;
         } else {
            $args['cat'] = $cats;
         }
      }
            // Similar by tags
      if ( $similar_type == 'tag' ) {

         $tags = get_post_meta($post->ID, 'similar-posts', true);

         if ( !$tags ) {
            $tags = wp_get_post_tags($post->ID, array('fields'=>'ids'));
            $args['tag__in'] = $tags;
         } else {
            $args['tag_slug__in'] = explode(',', $tags);
         }
         if ( !$tags ) { $break = true; }
      }
      
      $query = !isset($break)?new WP_Query($args):new WP_Query;
      return $query;
   }

endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/

/**
 * Get attachment id
 */
 
if ( ! function_exists( 'accesspress_mag_get_attachment_id_from_url' ) ):
    function accesspress_mag_get_attachment_id_from_url( $attachment_url ) {
     
        global $wpdb;
        $attachment_id = false;
     
        // If there is no url, return.
        if ( '' == $attachment_url )
            return;
     
        // Get the upload directory paths
        $upload_dir_paths = wp_upload_dir();
     
        // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
        if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
     
            // If this is the URL of an auto-generated thumbnail, get the URL of the original image
            $attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
     
            // Remove the upload path base directory from the attachment URL
            $attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
     
            // Finally, run a custom database query to get the attachment ID from the modified attachment URL
            $attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
     
        }
     
        return $attachment_id;
    }
endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Google font variants
*/

if( ! function_exists( 'accesspress_get_google_font_variants' ) ):
function accesspress_get_google_font_variants()
{
    $accesspress_pro_font_list = get_option( 'accesspress_mag_google_font');
    
    $font_family = $_REQUEST['font_family'];
    
    $font_array = accesspress_search_key($accesspress_pro_font_list,'family', $font_family);

    $variants_array = $font_array['0']['variants'] ;
    $options_array = "";
    foreach ($variants_array  as $key=>$variants ) {
        $options_array .= '<option value="'.$key.'">'.$variants.'</option>';
    }
    echo $options_array;
    die();
}
endif;

add_action("wp_ajax_accesspress_get_google_font_variants", "accesspress_get_google_font_variants");

if( ! function_exists( 'accesspress_search_key' ) ):
function accesspress_search_key($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, accesspress_search_key($subarray, $key, $value));
        }
    }

    return $results;
}
endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Add extra input fields in user profile
*/
/*
add_action( 'show_user_profile', 'accesspress_mag_extra_profile_fields' );
add_action( 'edit_user_profile', 'accesspress_mag_extra_profile_fields' );

function accesspress_mag_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">

		<tr>
			<th><label for="twitter">Twitter</label></th>

			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Twitter username.</span>
			</td>
		</tr>

	</table>
<?php }

*/
/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Kriesi pagination
*/
if( ! function_exists( 'kriesi_pagination' ) ):
function kriesi_pagination($pages = '', $range = 1) {
    $showitems = ($range * 2) + 1;

    global $paged;
    if (empty($paged))
        $paged = 1;

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }

    if (1 != $pages) {
        echo "<div class='accesspress_pagination'>";
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
        if ($paged > 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($pages) . "'>&raquo;</a>";
        echo "</div>\n";
    }
}
endif;

/*----------------------------------------------------------------------------------------------------------------------------------------*/
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
   <div class="random-post-icon">
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

/*---------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * youtube t param from url (ex: http://youtu.be/AgFeZr5ptV8?t=5s)
 */
function getYoutubeTimeParam($videoUrl) {
    $query_string = array();
    parse_str(parse_url($videoUrl, PHP_URL_QUERY), $query_string);
    if (!empty($query_string["t"])) {

        if (strpos($query_string["t"], 'm')) {
            //take minutes
            $explode_for_minutes = explode('m', $query_string["t"]);
            $minutes = trim($explode_for_minutes[0]);

            //take seconds
            $explode_for_seconds = explode('s', $explode_for_minutes[1]);
            $seconds = trim($explode_for_seconds[0]);

            $startTime = ($minutes * 60) + $seconds;
        } else {
            //take seconds
            $explode_for_seconds = explode('s', $query_string["t"]);
            $seconds = trim($explode_for_seconds[0]);

            $startTime = $seconds;
        }

        return '&start=' . $startTime;
    } else {
        return '';
    }
}

function covtime($duration) {
    preg_match_all('/(\d+)/',$duration,$parts);

     //Put in zeros if we have less than 3 numbers.
    if (count($parts[0]) == 1) {
        array_unshift($parts[0], "0", "0");
    } elseif (count($parts[0]) == 2) {
        array_unshift($parts[0], "0");
    }

    $sec_init = $parts[0][2];
    $seconds = $sec_init%60;
    $seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
    $seconds_overflow = floor($sec_init/60);

    $min_init = $parts[0][1] + $seconds_overflow;
    $minutes = ($min_init)%60;
    $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    $minutes_overflow = floor(($min_init)/60);

    $hours = $parts[0][0] + $minutes_overflow;
    

    if($hours != 0)
    {
        return $hours.':'.$minutes.':'.$seconds;
    } else {
        return $minutes.':'.$seconds;
    }        
}