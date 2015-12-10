<?php

/**
 * Random Posts Widgets
 *
 * @package AccessPress Mag
 */
/**
 * Adds accesspress_mag_random_posts widget.
 */
add_action('widgets_init', 'register_random_posts_widget');

function register_random_posts_widget() {
    register_widget('accesspress_mag_register_random_posts');
}

class Accesspress_mag_register_random_posts extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'accesspress_mag_register_random_posts', 'AP-Mag :  Random Posts', array(
            'description' => __( 'A widget that shows Random posts', 'accesspress-mag' )
                )
        );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'random_posts_title' => array(
                'accesspress_mag_widgets_name' => 'random_posts_title',
                'accesspress_mag_widgets_title' => __( 'Title', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'title',
            ),
            'random_posts_count' => array(
                'accesspress_mag_widgets_name' => 'random_posts_count',
                'accesspress_mag_widgets_title' => __( 'Number of Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'select',
                'accesspress_mag_widgets_field_options' => array( '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9',)
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
    public function widget( $args, $instance ) {
        extract( $args );
        $random_posts_title = $instance['random_posts_title'];
        $random_posts_count = $instance['random_posts_count'];
        echo $before_widget; ?>
        <div class="random-posts clearfix">
           <h1 class="widget-title"><span><?php if( !empty( $random_posts_title ) ){ echo esc_attr( $random_posts_title ); } ?></span></h1>     
           <div class="random-posts-wrapper">
                <?php
                    $rand_posts_args = array( 'post_type'=>'post','post_status'=>'publish','posts_per_page'=>$random_posts_count,'orderby'=>'rand' );
                    $rand_posts_query = new WP_Query($rand_posts_args);
                    if($rand_posts_query->have_posts()){
                        while($rand_posts_query->have_posts()){
                            $rand_posts_query->the_post();
                            $image_id = get_post_thumbnail_id();
                            $image_path = wp_get_attachment_image_src( $image_id, 'accesspress-mag-block-small-thumb', true );
                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                ?>
                    <div class="rand-single-post clearfix">
                        <div class="post-img"><a href="<?php the_permalink();?>">
                            <?php if(has_post_thumbnail()): ?>
                            <img src="<?php echo esc_url( $image_path[0] );?>" alt="<?php echo esc_attr( $image_alt );?>" />
                            <?php else: ?>
                            <img src="<?php echo esc_url( get_template_directory_uri(). '/images/no-image-small.jpg' );?>" alt="<?php _e( 'No image', 'accesspress-mag' );?>" />                            
                            <?php endif ;?>
                        </a></div>
                        <div class="post-desc-wrapper">
                            <h3 class="post-title">
                                <a href="<?php the_permalink();?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                        </div>                    
                    </div>
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
