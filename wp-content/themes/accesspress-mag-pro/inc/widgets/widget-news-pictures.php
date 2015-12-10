<?php
/**
 * Widget for News display in pictures only
 *
 * @package Accesspress Mag Pro
 */
 
add_action( 'widgets_init', 'register_news_pictures_widget' );

function register_news_pictures_widget() {
    register_widget( 'apmag_news_pictures' );
}

if( !class_exists( 'Apmag_News_Pictures' ) ):
class Apmag_News_Pictures extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'apmag_news_pictures', 'AP-Mag : News Pictures', array(
            'description' => __( 'A widget that display only images from selected categories.', 'accesspress-mag' )
                )
        );
    }
    
    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $apmag_cat_args = array(
                        	'type'                     => 'post',
                            'child_of'                 => 0,
                        	'orderby'                  => 'name',
                        	'order'                    => 'ASC',
                        	'hide_empty'               => 1,
                        	'taxonomy'                 => 'category',
                            );
        $apmag_categories = get_categories( $apmag_cat_args );
        $apmag_categories_lists = array();
        foreach( $apmag_categories as $category ) {
            $apmag_categories_lists[$category->term_id] = $category->name;
        }

        $fields = array(
            'news_pictures_title' => array(
                'accesspress_mag_widgets_name' => 'news_pictures_title',
                'accesspress_mag_widgets_title' => __( 'Title', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'title',
                ),
            'news_pictures_posts_count' => array(
                'accesspress_mag_widgets_name' => 'news_pictures_posts_count',
                'accesspress_mag_widgets_title' => __( 'Number of Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'number',
            ),
            'news_pictures_categories' => array(
                'accesspress_mag_widgets_name' => 'news_pictures_categories',
                'accesspress_mag_widgets_title' => __( 'Select Multiple Categories', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'multicheckboxes',
                'accesspress_mag_mulicheckbox_title' => __( 'Select Categories', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_options' => $apmag_categories_lists
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
        extract($args);
        $news_picture_title = $instance[ 'news_pictures_title' ];
        $news_pictures_posts_count = $instance['news_pictures_posts_count'];
        $news_pictures_categories = $instance['news_pictures_categories'];
        echo $before_widget;
    ?>
    <div class="apmag-news-picture-wrapper">
        <h1 class="widget-title"><span><?php echo esc_attr( $news_picture_title ); ?></span></h1>
       <?php
            if( !empty( $news_pictures_categories ) ) {
                $inc_cat = array();
                foreach( $news_pictures_categories as $cat_id => $cat_option ){
                    $inc_cat[] = $cat_id;
                }
                $get_cat_ids = implode(",", $inc_cat);
                $news_args = array(
                                'post_type' => 'post',
                                'cat' => $get_cat_ids,
                                'post_status' => 'publish',
                                'posts_per_page' => $news_pictures_posts_count,
                                'order' => 'DESC'
                                );
                $news_args['meta_query'] = array(
                                            array(
                                                'key' => '_thumbnail_id',
                                                'compare' => '!=',
                                                'value' => null
                                            )
                                        );
                $news_query = new WP_Query( $news_args );
                $news_counter = 0;
                $total_posts = $news_query->post_count;
                if( $news_query->have_posts() ) {
                    echo '<div class="post-thumbs-wrapper clearfix">';
                    while( $news_query->have_posts() ) {
                        $news_query->the_post();
                        $news_counter++;
                        if( $news_counter == 1 ) { 
                            $news_image_size = 'accesspress-mag-block-big-thumb'; 
                            $post_zoom = 'top-post non-zoomin';
                            echo '<div class="news-recent">';
                        } else { 
                            $news_image_size = 'accesspress-mag-block-small-thumb'; 
                            $post_zoom = '';
                        }
                        if( $news_counter == 2 ) { echo '<div class="news-grid">'; }
                        $post_image_id = get_post_thumbnail_id();
                        $post_image_path = wp_get_attachment_image_src( $post_image_id, $news_image_size , true );
                        $post_image_alt = get_post_meta( $post_image_id, '_wp_attachment_image_alt', true );
       ?>
                <div class="single-post-wrap">
                    <div class="post-image <?php echo esc_attr( $post_zoom ); ?>">
                        <figure>
                            <a href="<?php the_permalink();?>" class="tooltip" title="<?php the_title(); ?>">
                                <img src="<?php echo esc_url( $post_image_path[0] );?>" alt="<?php echo esc_attr( $post_image_alt ); ?>" />
                            </a>
                        </figure>
                    </div>
                </div><!--.single-post-wrap-->
       <?php
                    if( $news_counter == 1 || $news_counter == $total_posts ) { echo '</div>'; }
                    }
                    echo '</div>';
                    }
            }
        ?>
    </div><!--.apmag-news-picture-wrapper-->

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
     * @uses	accesspress_mag_widgets_updated_field_value()		defined in widget-fields.php
     *
     * @return	array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ( $widget_fields as $widget_field ) {

            extract($widget_field);

            // Use helper function to get updated field values
            $instance[ $accesspress_mag_widgets_name ] = accesspress_mag_widgets_updated_field_value( $widget_field, $new_instance[ $accesspress_mag_widgets_name ] );
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
     * @uses	accesspress_mag_widgets_show_widget_field()		defined in widget-fields.php
     */
    public function form($instance) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            // Make array elements available as variables
            extract($widget_field);
            $accesspress_mag_widgets_field_value = !empty($instance[$accesspress_mag_widgets_name]) ? $instance[$accesspress_mag_widgets_name] : '';
            accesspress_mag_widgets_show_widget_field($this, $widget_field, $accesspress_mag_widgets_field_value);
        }
    }
}
endif;