<?php
/**
 * Tabbed Widget ( Recent, Popular, Comments )
 * 
 * @package Accesspress Mag Pro
 */
 
add_action( 'widgets_init', 'register_apmag_featured_category_widget' );

function register_apmag_featured_category_widget() {
    register_widget('apmag_featured_category');
}

if( !class_exists( 'Apmag_Featured_Category' ) ):
class Apmag_Featured_Category extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'apmag_featured_category', 'AP-Mag :  Featured Category', array(
            'description' => __( 'A widget that shows posts from selected category.', 'accesspress-mag' )
                )
        );
    }
    
    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $featured_categories = array( ' ' => 'Select Featured Category' );
        $cat_args = array(
            	'type'                     => 'post',
                'child_of'                 => 0,
            	'orderby'                  => 'name',
            	'order'                    => 'ASC',
            	'hide_empty'               => 1,
            	'taxonomy'                 => 'category',
                );
        $categories = get_categories( $cat_args );
        foreach( $categories as $cat ) {
            $featured_categories[$cat->cat_ID] = $cat->name;
        }
        $fields = array(
            'featured_category_title' => array(
                'accesspress_mag_widgets_name' => 'featured_category_title',
                'accesspress_mag_widgets_title' => __( 'Title', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'title',
            ),
            'featured_category' => array(
                'accesspress_mag_widgets_name' => 'featured_category',
                'accesspress_mag_widgets_title' => __( 'Featured Categories', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'select',
                'accesspress_mag_widgets_field_options' => $featured_categories
            ),
            'featured_category_posts_count' => array(
                'accesspress_mag_widgets_name' => 'featured_category_posts_count',
                'accesspress_mag_widgets_title' => __( 'Number of Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'select',
                'accesspress_mag_widgets_field_options' => array( '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9',)
            ),
            'featured_category_view_all_show' => array(
                'accesspress_mag_widgets_name' => 'featured_category_view_all_show',
                'accesspress_mag_widgets_title' => __( 'Show View All button', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'checkbox',
            )
                    
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
        $featured_category_title = $instance['featured_category_title'];
        $featured_category =  $instance['featured_category'];
        $featured_category_posts_count = $instance['featured_category_posts_count'];
        $featured_category_view_all_show = $instance['featured_category_view_all_show']; 
        echo $before_widget; 
?>
        <div class="featured-category-wrapper">
            <h1 class="widget-title"><span><?php echo esc_attr( $featured_category_title ); ?></span></h1>
            <div class="content-wrapper">
                <?php 
                    if( !empty( $featured_category ) ):
                    $featured_cat_args = array(
                                    'cat'=>$featured_category,
                                    'post_status'=>'pubish',
                                    'posts_per_page'=>$featured_category_posts_count,
                                    'order'=>'DESC'
                                    );
                    $featured_cat_query = new WP_Query( $featured_cat_args );
                    $e_counter = 0;
                    $total_posts_editor = $featured_cat_query->found_posts;
                    $cat_link = get_category_link( $featured_category );
                    if( $featured_cat_query->have_posts() ) {
                        while( $featured_cat_query->have_posts() ) {
                            $e_counter++;
                            $featured_cat_query->the_post();
                            $featured_cat_image_id = get_post_thumbnail_id();
                            $featured_cat_big_image_path = wp_get_attachment_image_src( $featured_cat_image_id, 'accesspress-mag-block-big-thumb', true );
                            $featured_cat_small_image_path = wp_get_attachment_image_src( $featured_cat_image_id, 'accesspress-mag-block-small-thumb', true );
                            $featured_cat_image_alt = get_post_meta( $featured_cat_image_id, '_wp_attachment_image_alt', true );
                            $fallback_image_option = of_get_option( 'fallback_image_option', '1' );
                            $fallback_image = of_get_option( 'fallback_image', get_template_directory_uri(). '/images/no-image.jpg' );
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
                ?>
                    <div class="single_post clearfix <?php if( $e_counter == 1 ){ echo 'first-post non-zoomin'; } ?>">
                        
                            <div class="post-image">
                                <a href="<?php the_permalink();?>" title="<?php the_title();?>">
                                    <?php if( has_post_thumbnail() ) { ?>
                                        <img src="<?php if( $e_counter == 1 ){ echo esc_url( $featured_cat_big_image_path[0] );}else{ echo esc_url ( $featured_cat_small_image_path[0] ) ;}?>" alt="<?php echo esc_attr( $featured_cat_image_alt );?>" />
                                    <?php } else {
                                                if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
                                    ?>
                                        <img src="<?php echo esc_url( $fallback_image ); ?>" alt="Fallback Image" />
                                    <?php  } } ?>
                                </a>
                                <?php if( $e_counter == 1 ) { ?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title();?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php } ?>
                                <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $e_counter > 2 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                            </div>                                
                        <?php
                            if( $e_counter > 1 ){ echo '<div class="post-desc-wrapper">'; } 
                        ?>
                            <h4 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                            <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                            <div class="ratings-wrapper"><?php do_action('accesspress_mag_post_review');?></div>
                            <?php if( $e_counter > 1 ){ echo '</div>'; } if( $e_counter == 1 ):?><div class="post-content"><?php accesspress_mag_homepage_excerpt(); ?></div><?php endif ;?>
                    </div>
                <?php
                        }
                        if( !empty( $featured_category_view_all_show ) && $featured_category_view_all_show == 1 ) {
                            echo '<a href="'. esc_url( $cat_link ) .'" class="cat-link view-all-button">'. __( 'View all posts', 'accesspress-mag' ) .'</a>';
                        }
                    }
                    endif ;
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