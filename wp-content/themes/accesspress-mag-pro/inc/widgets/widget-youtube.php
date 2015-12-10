<?php

/**
 * Widget Youtube which show custom playlist
 * 
 * @package Accesspress Mag Pro
 */

add_action( 'widgets_init', 'register_apmag_youtube_list_widget' );

function register_apmag_youtube_list_widget() {
    register_widget( 'accesspress_mag_youtube_list' );
}

class Accesspress_mag_youtube_list extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'accesspress_mag_youtube_list', 'AP-Mag :  Youtube Video Lists', array(
            'description' => __( 'A widget display number of youtube video in list view.', 'accesspress-mag' )
                )
        );
    }
    
    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'youtube_list_title' => array(
                'accesspress_mag_widgets_name' => 'youtube_list_title',
                'accesspress_mag_widgets_title' => __( 'Title', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'title',
            ),
            'youtube_videos_ids' => array(
                'accesspress_mag_widgets_name' => 'youtube_videos_ids',
                'accesspress_mag_widgets_title' => __( 'Youtube Video Ids', 'accesspress-mag' ),
                'accesspress_mag_widgets_description' => __( "Add youtube id's separated by comma (ex: xrt27dZ7DOA, u8--jALkijM, HusniLw9i68)", 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'text',
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
        $youtube_playlist_title = $instance['youtube_list_title'];
        $youtube_video_ids = $instance['youtube_videos_ids'];
        echo $before_widget;
?>
    <div class="apmag-youtube-lists-wrapper clearfix fullwidth-content">
        <div class="apmag-youtube-list video-playlist-wrapper">
            <h1 class="widget-title"><span><?php echo esc_attr( $youtube_playlist_title ); ?></span></h1>
            <div class="list-conent-wrapper clearfix">
                <?php 
                    if( !empty( $youtube_video_ids ) ) {
                        $http = (!empty($_SERVER['HTTPS'])) ? "https" : "http";
                        $seperate_id = explode( ', ', $youtube_video_ids );
                        $video_counter = 0;  
                        foreach( $seperate_id as $key => $value ) {
                            $video_counter++;
                            $video_url = $http . '://www.youtube.com/watch?v=' . $value;
                            $show_option = ( $video_counter == 1 ) ? "block" : "none" ;
                ?>
                <div class="apmag-youtube-video-play ytvideo_<?php echo esc_attr( $value );?> video-wrap" style="display: <?php echo esc_attr( $show_option );?>">
                    <?php echo wp_oembed_get( $video_url ); ?>
                </div>
                <?php } ?>
                <div class="apmag-playlist-container">
                    <div class="apmag-video-control">
                        
                    </div>
                    <div class="apmag-video-playlist-wrapper">
                    <?php
                        foreach( $seperate_id as $key => $value ) {
                            $response = wp_remote_get('https://www.googleapis.com/youtube/v3/videos?id='. $value .'&part=id,contentDetails,snippet&key=AIzaSyA_Ze-UVGvIN6t94Glex1rYUM3SFB7Kz8o', array(
    							'sslverify' => false
    						));
                            if (is_wp_error($response)) {
                                break;
                            }
                
                			$data = wp_remote_retrieve_body($response);
                
                            if (is_wp_error($data)) {
                                break;
                            }
                
                			$obj = json_decode($data, true);
                            $video_thumb = $obj['items'][0]['snippet']['thumbnails']['default']['url'];
                            $video_title = $obj['items'][0]['snippet']['title'];
                            $video_duration = covtime( $obj['items'][0]['contentDetails']['duration'] );
                    ?>
                        <div class="apmag-click-video-thumb" data-id="apmag_<?php echo esc_attr( $value )?>">
                            <figure class="list-thumb-figure">
                                <img src="<?php echo esc_url( $video_thumb ); ?>" alt="<?php echo esc_attr( $video_title );?>" title="<?php echo esc_attr( $video_title );?>" />
                            </figure>
                            <div class="list-thumb-details">
                                <span class="thumb-title"><?php echo esc_attr( $video_title ); ?></span>
                                <span class="thumb-time"><?php echo $video_duration ; ?></span>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
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