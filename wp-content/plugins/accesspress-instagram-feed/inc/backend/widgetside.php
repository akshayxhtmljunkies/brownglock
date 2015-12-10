<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 * Adds AccessPress Instagram Feed Widget
 */
class APIF_SideWidget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'apif_sidewidget', // Base ID
                __('AP : Instagram Feeds', 'accesspress-instagram-feed'), // Name
                array('description' => __('AccessPress Instagram Feeds', 'accesspress-instagram-feed')) // Args
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

        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $instagram_num_img = isset($instance['instagram_num_img']) ? $instance['instagram_num_img']:'12';
        $instance_post = (isset($instance['instance_post']) && $instance['instance_post']==1)?'true':'false';
        $instance_followers = (isset($instance['instance_followers']) && $instance['instance_followers']==1)?'true':'false';
        $instance_following = (isset($instance['instance_following']) && $instance['instance_following']==1)?'true':'false';
        $instance_follow = (isset($instance['instance_follow']) && $instance['instance_follow']==1)?'true':'false';


global $apif_settings, $insta;
    $apif_settings = get_option( 'apif_settings' );
    $username = !empty($apif_settings['username']) ? $apif_settings['username'] : '';
    $user_id = !empty($apif_settings['user_id']) ? $apif_settings['user_id'] : '';
    $social_profile_url = 'https://instagram.com/' . $username;
    $access_token = !empty($apif_settings['access_token']) ? $apif_settings['access_token'] : '';

    $api_url = 'https://api.instagram.com/v1/users/' . $user_id . '?access_token=' . $access_token;
    $connection = wp_remote_get($api_url);           
    $response = json_decode($connection['body'], true);
?>
    <div class="instagram-header">
        <header class="profile clearfix">
            <div class="profile-img">
                <img src="<?php echo $response['data']['profile_picture']; ?>"/>
            </div>
            <div class="profile-name">
                <?php echo $response['data']['full_name']; ?>
            </div>
        </header>
        <header class="profile-follow clearfix">
            <div class="post">                
                <?php echo $response['data']['counts']['media']; ?>
                <span>post</span>
            </div>
            <div class="followers">                
                <?php echo $response['data']['counts']['followed_by']; ?>
                <span>followers</span>
            </div>
            <div class="following">                
                <?php echo $response['data']['counts']['follows']; ?>
                <span>following</span>
            </div>
            <div class="follow">
                <div class="follow-inner">
                    <div class="table-outer">
                        <div class="table-inner">
                             <a href="https://instagram.com/<?php echo $apif_settings['username'];  ?>" target='_blank' title='Follow <?php echo $apif_settings['username'];  ?>' >follow</a>
                            <?php //echo $response['data']['link']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
<?php
         
        $count = $instagram_num_img;
        require_once(APIF_INST_PATH . 'inc/frontend/instagram.php');
        $ins_media = $insta->userMedia();
        $j = 0;
        if(isset($ins_media['meta']['error_message'])){
            ?>
               <h1 class="widget-title-insta"><span><?php echo $ins_media['meta']['error_message']; ?></span></h1> 
            <?php
        } else if (is_array($ins_media['data']) || is_object($ins_media['data'])) 
            {
                    echo '<ul class="instagram-widget clear">';
                    foreach ($ins_media['data'] as $vm)
                    {
                        if ($count == $j) {
                            break;
                        }
                        $j++;
                        $img = $vm['images']['thumbnail']['url'];
            ?> 
                    <li><img src="<?php echo esc_url($img); ?>"></li>
            <?php
                    }
                echo '</ul>';
            }       
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
        $instagram_num_img = isset($instance['instagram_num_img'])?$instance['instagram_num_img']:'';

        $instance_post = isset($instance['instance_post'])?$instance['instance_post']:'';
        $instance_followers = isset($instance['instance_followers'])?$instance['instance_followers']:'';
        $instance_following = isset($instance['instance_following'])?$instance['instance_following']:'';
        $instance_follow = isset($instance['instance_follow'])?$instance['instance_follow']:'';

        ?>
        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'accesspress-instagram-feed'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>

            <label for="<?php echo $this->get_field_id('instagram_num_img'); ?>"><?php _e('Number of Image:', 'accesspress-instagram-feed'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('instagram_num_img'); ?>" name="<?php echo $this->get_field_name('instagram_num_img'); ?>" >
                <?php for($i=1;$i<=21;$i++){
                ?>
                    <option value="<?php echo $i;?>" <?php selected( $instagram_num_img,''.$i ); ?>><?php echo $i; ?></option>
                <?php
                }?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('instance_post'); ?>"><?php _e('Display Instagram Post :', 'accesspress-instagram-feed'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('instance_post'); ?>" name="<?php echo $this->get_field_name('instance_post'); ?>" type="checkbox" value="1" <?php checked($instance_post,true);?>/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('instance_followers'); ?>"><?php _e('Display Followers :', 'accesspress-instagram-feed'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('instance_followers'); ?>" name="<?php echo $this->get_field_name('instance_followers'); ?>" type="checkbox" value="1" <?php checked($instance_followers,true);?>/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('instance_following'); ?>"><?php _e('Display Instagram Following :', 'accesspress-instagram-feed'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('instance_following'); ?>" name="<?php echo $this->get_field_name('instance_following'); ?>" type="checkbox" value="1" <?php checked($instance_following,true);?>/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('instance_follow'); ?>"><?php _e('Display Instagram Follow Button :', 'accesspress-instagram-feed'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('instance_follow'); ?>" name="<?php echo $this->get_field_name('instance_follow'); ?>" type="checkbox" value="1" <?php checked($instance_follow,true);?>/>
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
        $instance['instagram_num_img'] = (!empty($new_instance['instagram_num_img']) ) ? sanitize_text_field($new_instance['instagram_num_img']) : '';

        $instance['instance_post'] = (!empty($new_instance['instance_post']) ) ? sanitize_text_field($new_instance['instance_post']) : '';
        $instance['instance_followers'] = (!empty($new_instance['instance_followers']) ) ? sanitize_text_field($new_instance['instance_followers']) : '';
        $instance['instance_following'] = (!empty($new_instance['instance_following']) ) ? sanitize_text_field($new_instance['instance_following']) : '';
        $instance['instance_follow'] = (!empty($new_instance['instance_follow']) ) ? sanitize_text_field($new_instance['instance_follow']) : '';
        return $instance;
    }
}
// class APS_PRO_Widget
?>