<?php
/**
 * Sponsers Widget
 * 
 * @package Accesspress Mag Pro
 */
 
add_action( 'widgets_init', 'register_apmag_sponser_widget' );

function register_apmag_sponser_widget() {
    register_widget( 'accesspress_mag_sponsers' );
}

if( !class_exists( 'accesspress_mag_sponsers' ) ):
class Accesspress_Mag_Sponsers extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'accesspress_mag_sponsers', 'AP-Mag : Sponsers Widget', array(
            'description' => __( 'A widget that shows multiple sponsers images.', 'accesspress-mag' )
                )
        );
    }
    
    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'sponsers_title' => array(
                'accesspress_mag_widgets_name' => 'sponsers_title',
                'accesspress_mag_widgets_title' => __( 'Widget Title', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'title',
            ),
            'sponsers_item_info_1' => array(
                'accesspress_mag_widgets_name' => 'sponsers_item_info_1',
                'accesspress_mag_widgets_title' => __( 'Sponser 1', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'ap_info',
            ),
            'sponsers_name_1' => array(
                'accesspress_mag_widgets_name' => 'sponsers_name_1',
                'accesspress_mag_widgets_title' => __( 'Sponser Name', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'text',
            ),
            'sponsers_logo_1' => array(
                'accesspress_mag_widgets_name' => 'sponsers_logo_1',
                'accesspress_mag_widgets_title' => __( 'Sponser Logo', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'upload',
            ),
            'sponsers_url_1' => array(
                'accesspress_mag_widgets_name' => 'sponsers_url_1',
                'accesspress_mag_widgets_title' => __( 'Sponser Link', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'url',
            ),
            'sponsers_item_info_2' => array(
                'accesspress_mag_widgets_name' => 'sponsers_item_info_2',
                'accesspress_mag_widgets_title' => __( 'Sponser 2', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'ap_info',
            ),
            'sponsers_name_2' => array(
                'accesspress_mag_widgets_name' => 'sponsers_name_2',
                'accesspress_mag_widgets_title' => __( 'Sponser Name', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'text',
            ),
            'sponsers_logo_2' => array(
                'accesspress_mag_widgets_name' => 'sponsers_logo_2',
                'accesspress_mag_widgets_title' => __( 'Sponser Logo', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'upload',
            ),
            'sponsers_url_2' => array(
                'accesspress_mag_widgets_name' => 'sponsers_url_2',
                'accesspress_mag_widgets_title' => __( 'Sponser Link', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'url',
            ),
            'sponsers_item_info_3' => array(
                'accesspress_mag_widgets_name' => 'sponsers_item_info_3',
                'accesspress_mag_widgets_title' => __( 'Sponser 3', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'ap_info',
            ),
            'sponsers_name_3' => array(
                'accesspress_mag_widgets_name' => 'sponsers_name_3',
                'accesspress_mag_widgets_title' => __( 'Sponser Name', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'text',
            ),
            'sponsers_logo_3' => array(
                'accesspress_mag_widgets_name' => 'sponsers_logo_3',
                'accesspress_mag_widgets_title' => __( 'Sponser Logo', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'upload',
            ),
            'sponsers_url_3' => array(
                'accesspress_mag_widgets_name' => 'sponsers_url_3',
                'accesspress_mag_widgets_title' => __( 'Sponser Link', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'url',
            ),
            'sponsers_item_info_4' => array(
                'accesspress_mag_widgets_name' => 'sponsers_item_info_4',
                'accesspress_mag_widgets_title' => __( 'Sponser 4', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'ap_info',
            ),
            'sponsers_name_4' => array(
                'accesspress_mag_widgets_name' => 'sponsers_name_4',
                'accesspress_mag_widgets_title' => __( 'Sponser Name', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'text',
            ),
            'sponsers_logo_4' => array(
                'accesspress_mag_widgets_name' => 'sponsers_logo_4',
                'accesspress_mag_widgets_title' => __( 'Sponser Logo', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'upload',
            ),
            'sponsers_url_4' => array(
                'accesspress_mag_widgets_name' => 'sponsers_url_4',
                'accesspress_mag_widgets_title' => __( 'Sponser Link', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'url',
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
        $sponser_widget_title = $instance['sponsers_title'];
        echo $before_widget;
?>
        <div class="apmag-sponser-wrapper clearfix">
            <h1 class="widget-title"><span><?php echo esc_attr( $sponser_widget_title ); ?></span></h1>
            <div class="sponser-content-wrapper">
                <?php 
                    for( $wc=1; $wc<=4; $wc++ ){
                        $sponsers_name = $instance['sponsers_name_'.$wc];
                        $sponsers_logo = $instance['sponsers_logo_'.$wc];
                        $sponsers_url = $instance['sponsers_url_'.$wc];
                        if( !empty( $sponsers_logo ) ) {
                            $logo_id = accesspress_mag_get_attachment_id_from_url( $sponsers_logo );
                            $logo_path = wp_get_attachment_image_src( $logo_id, 'thumbnail', true );
                            $logo_alt = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
                            if( !empty( $sponsers_url ) ) {
                ?>
                    <a href="<?php echo esc_url( $sponsers_url ); ?>">
                        <img src="<?php echo esc_url( $logo_path[0] ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" title="<?php echo esc_attr( $sponsers_name ); ?>" />
                    </a>
                <?php        
                            }
                            else {
                ?>
                    <img src="<?php echo esc_url( $logo_path[0] ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" title="<?php echo esc_attr( $sponsers_name ); ?>" />
                <?php
                            }
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