<?php

class Fac_PromotionSlider extends WP_Widget {
    
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 'description' => __( "Adds promotion slider to sidebar") );
		parent::__construct('fac_promotion_slider', __('AGP Font Awesome Promotion Slider'), $widget_ops);
	}
    
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if (!empty( $instance['title'])) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        
        if (!empty($instance['slider'])) {
            $slider = $instance['slider'];
            echo do_shortcode( "[$slider]" );    
        }
        
        echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$slider = !empty($instance['slider']) ? $instance['slider'] : '';        
    ?>
        <p><?php $this->renderTitleField($title); ?></p>
        <p><?php $this->renderSldierField($slider); ?></p>        
    <?php    
	}
    
	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
        
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags( $new_instance['title'] ) : '';
        $instance['slider'] = (!empty($new_instance['slider'])) ? strip_tags( $new_instance['slider'] ) : '';
        
		return $instance;
	}    
    
    public function renderTitleField ($title) {
    ?>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">    
    <?php    
    }    
   
    public function renderSldierField ($slider) {
        $selected = !empty($slider) ? $slider : '';
        $sliders = Fac()->getSettings()->getSliderElementList();
    ?>
        <label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php _e( 'Slider:' ); ?></label> 
        <select class="widefat" id="<?php echo $this->get_field_id( 'slider' ); ?>" name="<?php echo $this->get_field_name( 'slider' ); ?>">
            <option value=""></option>                
            <?php 
                foreach ($sliders as $item => $itemName) : 
            ?>
                <option value="<?php echo $item; ?>"<?php selected($item, $selected); ?>>
                    <?php echo $itemName; ?>
                </option>            
            <?php 
                endforeach; 
            ?>
        </select>        
    <?php    
    }            
}

    