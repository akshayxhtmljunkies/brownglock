<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 * Adds AccessPress Instagram Feed Widget
 */
class APIF_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'apif_widget', // Base ID
                __('AP : Instagram Masonry', 'accesspress-instagram-feed'), // Name
                array('description' => __('AccessPress Instagram Widget', 'accesspress-instagram-feed')) // Args
        );
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

        echo $args['before_widget']; ?>
        <div class='apif-widget-wrapper'>
        <?php 
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        if(isset($instance['layout']) && $instance['layout'] == 'layout-1')
        {
            echo do_shortcode('[ap_instagram_widget]');
        }
        else if(isset($instance['layout']) && $instance['layout'] == 'layout-2')
        {
            echo do_shortcode('[ap_instagram_mosaic_lightview]');
        }
        else if(isset($instance['layout']) && $instance['layout'] == 'layout-3')
        {
            echo do_shortcode('[ap_instagram_slider]');
        }
        else
        {
            echo do_shortcode('[ap_instagram_feed]');
        }
        ?>
        </div>
        <?php 
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = isset($instance['title'])?$instance['title']:'';
        $layout = isset($instance['layout'])?$instance['layout']:'';
        ?>
        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'accesspress-instagram-feed'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Layout :', 'accesspress-instagram-feed'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>" >
                <option value="">Default</option>
                <?php for($i=1;$i<=3;$i++){

                  if($i == '1'){ $name = 'Mosaic'; } else if($i == '2'){ $name = 'Mosaic LightBox'; }
                            else if($i == '3') { $name = 'Slider'; }
                    ?>
                    <option value="layout-<?php echo $i;?>" <?php selected($layout,'layout-'.$i);?>><?php echo $name; ?> Layout</option>
                    <?php
                }?>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? sanitize_text_field($new_instance['title']) : '';
        $instance['layout'] = (!empty($new_instance['layout']) ) ? sanitize_text_field($new_instance['layout']) : '';
        return $instance;
    }
}
// class APS_PRO_Widget
?>