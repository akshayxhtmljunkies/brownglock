<?php
/**
 * Review Posts Widgets
 *
 * @package Accesspress Mag Pro
 */
/**
 * Adds accesspress_mag_latest_review_posts widget.
 */
add_action( 'widgets_init', 'register_latest_review_posts_widget' );

function register_latest_review_posts_widget() {
    register_widget('apmag_latest_review_posts');
}

if( !class_exists( 'Apmag_Latest_Review_Posts' ) ):
class Apmag_Latest_Review_Posts extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'apmag_latest_review_posts', 'AP-Mag : Review Posts', array(
            'description' => __('A widget that shows latest review posts', 'accesspress-mag')
                )
        );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'latest_review_post_title' => array(
                'accesspress_mag_widgets_name' => 'latest_review_post_title',
                'accesspress_mag_widgets_title' => __( 'Title', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'title',
            ),
            'latest_review_posts_type' => array(
                'accesspress_mag_widgets_name' => 'latest_review_posts_type',
                'accesspress_mag_widgets_title' => __( 'Review Type', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'select',
                'accesspress_mag_widgets_field_options' => array(
                    'rate_stars' => 'Stars',
                    'rate_percent' => 'Percentages',
                    'rate_point' => 'Points',
                )
            ),
            'latest_review_posts_count' => array(
                'accesspress_mag_widgets_name' => 'latest_review_posts_count',
                'accesspress_mag_widgets_title' => __( 'Number of Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'select',
                'accesspress_mag_widgets_field_options' => array( '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9',)
            ),            
        );

        return $fields;
    }
    
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);
        $latest_review_posts_title = $instance['latest_review_post_title'];
        $latest_review_posts_type = $instance['latest_review_posts_type'];
        $latest_review_posts_count = $instance['latest_review_posts_count'];
        echo $before_widget; ?>
        <div class="latest-review-posts clearfix">
           <h1 class="widget-title"><span><?php echo esc_attr($latest_review_posts_title); ?></span></h1>     
           <div class="review-posts-wrapper">
              <?php 
                    $review_args = array(
                    	'posts_per_page'   => $latest_review_posts_count,
                    	'offset'           => 0,
                    	'category'         => '',
                    	'category_name'    => '',
                    	'orderby'          => 'post_date',
                    	'order'            => 'DESC',
                    	'include'          => '',
                    	'exclude'          => '',
                    	'meta_key'         => 'product_review_option',
                    	'meta_value'       => $latest_review_posts_type,
                    	'post_type'        => 'post',
                    	'post_mime_type'   => '',
                    	'post_parent'      => '',
                    	'post_status'      => 'publish',
                    	'suppress_filters' => true 
                    );
                    $review_array = new WP_Query( $review_args );
                    $p_count = 0;
                    if( $review_array->have_posts() ){
                        while( $review_array->have_posts() ){
                            $review_array->the_post();
                            $p_count++;
                            $review_image_id = get_post_thumbnail_id();
                            $review_big_image_path = wp_get_attachment_image_src( $review_image_id,'accesspress-mag-block-big-thumb',true );
                            $review_small_image_path = wp_get_attachment_image_src( $review_image_id,'accesspress-mag-block-small-thumb',true );
                            $review_image_alt = get_post_meta( $review_image_id,'_wp_attachment_image_alt',true );
                            $apmag_overlay_icon = of_get_option( 'apmag_overlay_icon', 'fa-external-link' );
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
                            if( $p_count  == 1 ){
                    ?>
                        <div class="single-review top-post non-zoomin clearfix">
                                <div class="post-image">
                                    <?php if( has_post_thumbnail() ):?>
                                    <img src="<?php echo $review_big_image_path[0];?>" alt="<?php echo esc_attr($review_image_alt);?>" />
                                    <?php else :?>
                                    <img src="<?php echo get_template_directory_uri();?>/images/no-image-medium.jpg" alt="<?php _e( 'No image', 'accesspress-mag' );?>" />
                                    <?php endif ;?>
                                    <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title();?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a>
                                    <?php if( $show_icon == 'on' ) { ?><span class="format_icon"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                                </div>
                                
                                    <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="ratings-wrapper"><?php do_action('accesspress_mag_post_review');?></div>
                            
                                <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                                <div class="post-content"><?php echo '<p>'. accesspress_word_count(get_the_content(),25) .'</p>' ;?></div>
                        </div>
                    <?php } else { ?>
                        <div class="single-review clearfix">
                                <div class="post-image">
                                    <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                        <?php if( has_post_thumbnail() ) { ?>
                                            <img src="<?php echo $review_small_image_path[0];?>" alt="<?php echo esc_attr($review_image_alt);?>" />
                                        <?php } else { ?>
                                            <img src="<?php echo get_template_directory_uri();?>/images/no-image-small.jpg" alt="<?php _e( 'No image', 'accesspress-mag' );?>" />
                                        <?php } ?>
                                        <?php if( $show_icon == 'on' ) { ?><span class="format_icon small"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                                    </a>
                                </div>
                                <div class="post-desc-wrapper">
                                    <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <div class="ratings-wrapper"><?php do_action('accesspress_mag_post_review');?></div>
                                    <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                                </div>
                        </div>
                    <?php } ?>
                <?php
                        }
                    }
                ?>
           </div> 
        </div>
        <?php 
        echo $after_widget;
    }
    
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param	array	$new_instance	Values just sent to be saved.
     * @param	array	$old_instance	Previously saved values from database.
     *
     * @uses	accesspress_pro_widgets_updated_field_value()		defined in widget-fields.php
     *
     * @return	array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            extract($widget_field);

            // Use helper function to get updated field values
            $instance[$accesspress_mag_widgets_name] = accesspress_mag_widgets_updated_field_value($widget_field, $new_instance[$accesspress_mag_widgets_name]);
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param	array $instance Previously saved values from database.
     *
     * @uses	accesspress_pro_widgets_show_widget_field()		defined in widget-fields.php
     */
    public function form($instance) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            // Make array elements available as variables
            extract($widget_field);
            $accesspress_mag_widgets_field_value = !empty($instance[$accesspress_mag_widgets_name]) ? esc_attr($instance[$accesspress_mag_widgets_name]) : '';
            accesspress_mag_widgets_show_widget_field($this, $widget_field, $accesspress_mag_widgets_field_value);
        }
    }

}
endif;