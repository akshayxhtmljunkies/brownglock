<?php
/**
 * Tabbed Widget ( Recent, Popular, Comments )
 * 
 * @package Accesspress Mag Pro
 */
 
add_action( 'widgets_init', 'register_apmag_tabbed_widget' );

function register_apmag_tabbed_widget() {
    register_widget( 'accesspress_mag_tabbed' );
}

if( !class_exists( 'accesspress_mag_tabbed' ) ):
class Accesspress_Mag_Tabbed extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'accesspress_mag_tabbed', 'AP-Mag : Tabbed Widget', array(
            'description' => __( 'A widget that shows posts of Recent, Popular( most views ) and Comments.', 'accesspress-mag' )
                )
        );
    }
    
    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(  
            
            'tabbed_recent_post_show' => array(
                'accesspress_mag_widgets_name' => 'tabbed_recent_post_show',
                'accesspress_mag_widgets_title' => __( 'Show Recent Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'checkbox',
            ),
            
            'tabbed_popular_post_show' => array(
                'accesspress_mag_widgets_name' => 'tabbed_popular_post_show',
                'accesspress_mag_widgets_title' => __( 'Show Popular Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'checkbox',
            ),
            
            'tabbed_comments_show' => array(
                'accesspress_mag_widgets_name' => 'tabbed_comments_show',
                'accesspress_mag_widgets_title' => __( 'Show Comments', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'checkbox',
            ),
                      
            'tabbed_posts_count' => array(
                'accesspress_mag_widgets_name' => 'tabbed_posts_count',
                'accesspress_mag_widgets_title' => __( 'Number of Posts', 'accesspress-mag' ),
                'accesspress_mag_widgets_field_type' => 'select',
                'accesspress_mag_widgets_field_options' => array( '3' => '3', '4' => '4', '5' => '5', '6' => '6')
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
        $tabbed_recent_show = $instance['tabbed_recent_post_show'];
        $tabbed_popular_show = $instance['tabbed_popular_post_show'];
        $tabbed_comments_show = $instance['tabbed_comments_show'];
        $tabbed_posts_count = $instance['tabbed_posts_count'];
        echo $before_widget; ?>
        <div class="apmag-tabbed-widget" id="tabs">
           <ul class="widget-tabs clearfix" id="apmag-widget-tab">
                <?php if( !empty( $tabbed_recent_show ) ) { ?>
                <li class="tabs recent-tabs">
                    <a href="#recent"><i class="fa fa-history"></i>Recent</a>
                </li>
                <?php } ?>
                <?php if( !empty( $tabbed_popular_show ) ) { ?>
                <li class="tabs popular-tabs">
                    <a href="#popular"><i class="fa fa-star"></i>Popular</a>
                </li>
                <?php } ?>
                <?php if( !empty( $tabbed_comments_show ) ) { ?>
                <li class="tabs comments-tabs">
                    <a href="#comments"><i class="fa fa-comment"></i>Comments</a>
                </li>
                <?php } ?>
           </ul>
           <?php if( !empty( $tabbed_recent_show ) ) { ?>
           <div id="recent" class="apmage-tabbed-section">
                <?php 
                    $recent_args = array(
                                    'post_type'=>'post',
                                    'post_status'=>'publish',
                                    'posts_per_page'=>$tabbed_posts_count,
                                    'order'=>'DESC'
                                    );
                    $recent_query = new WP_Query( $recent_args );
                    if( $recent_query->have_posts() ) {
                        while( $recent_query->have_posts() ) {
                            $recent_query->the_post();
                            $image_id = get_post_thumbnail_id();
                            $image_path = wp_get_attachment_image_src( $image_id, 'accesspress-mag-block-small-thumb', true );
                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
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
                    <div class="signle-recent-article clearfix">
                        <?php if( has_post_thumbnail() ) { ?>
                        <figure class="single-thumb">
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>"><img src="<?php echo esc_url( $image_path[0] );?>" alt="<?php echo esc_attr( $image_alt );?>" /></a>
                            <?php if( $show_icon == 'on' ){?><span class="format_icon small"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                        </figure>
                        <?php } ?>
                        <div class="post-desc-wrapper">
                            <h4 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                            <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                        </div>
                    </div>
                <?php
                        }
                    }
                    wp_reset_query();
                ?>
           </div>
           <?php } ?>
           <?php if( !empty( $tabbed_popular_show ) ) { ?>
           <div id="popular" class="apmage-tabbed-section">
                <?php
                    $popular_args = array(
                                    'post_type'=>'post',
                                    'post_status'=>'publish',
                                    'posts_per_page'=>$tabbed_posts_count,
                                    'meta_key' => 'post_views_count',
                                    'orderby' => 'meta_value_num',
                                    'order'=>'DESC'
                                    );
                    $popular_query = new WP_Query( $popular_args );
                    if( $popular_query->have_posts() ) {
                        while( $popular_query->have_posts() ) {
                            $popular_query->the_post();
                            $image_id = get_post_thumbnail_id();
                            $image_path = wp_get_attachment_image_src( $image_id, 'accesspress-mag-block-small-thumb', true );
                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
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
                            }                ?>
                    <div class="signle-popular-article clearfix">
                        <?php if( has_post_thumbnail() ) { ?>
                        <figure class="single-thumb">
                            <a href="<?php the_permalink();?>" title="<?php the_title();?>"><img src="<?php echo esc_url( $image_path[0] );?>" alt="<?php echo esc_attr( $image_alt );?>" /></a>
                            <?php if( $show_icon == 'on' ){?><span class="format_icon small"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                        </figure>
                        <?php } ?>
                        <div class="post-desc-wrapper">
                            <h4 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                            <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                        </div>
                    </div>
                <?php
                        }
                    }
                    wp_reset_query();
                ?>
           </div>
           <?php } ?>
           <?php if( !empty( $tabbed_comments_show ) ) { ?>  
           <div id="comments" class="apmage-tabbed-section">
                <?php 
                    $apmag_comments = get_comments( array( 'number' => $tabbed_posts_count ) );
                    foreach($apmag_comments as $comment  ) {
                ?>
                    <li><p><strong>
                        <?php
                            $title = get_the_title($comment->comment_post_ID);
                            echo get_avatar( $comment, '45' );
                            echo strip_tags($comment->comment_author); ?></strong>&nbsp;commented on <a href="<?php echo get_permalink($comment->comment_post_ID); ?>" rel="external nofollow" title="<?php echo $title; ?>"> <?php echo $title; ?></a>: <?php echo wp_html_excerpt( $comment->comment_content, 50 ); ?> ...
                    </p></li>
                <?php                        
                    }
                ?>
           </div>
           <?php } ?>       
          
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