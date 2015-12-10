<?php
/**
 * Define required field for widget
 * 
 * @package Accesspress Mag Pro
 */
function accesspress_mag_widgets_show_widget_field($instance = '', $widget_field = '', $athm_field_value = '') {
    // Store Posts in array
    $accesspress_mag_postlist[0] = array(
        'value' => 0,
        'label' => '--choose--'
    );
    $arg = array('posts_per_page' => -1);
    $accesspress_mag_posts = get_posts($arg);
    foreach ($accesspress_mag_posts as $accesspress_mag_post) :
        $accesspress_mag_postlist[$accesspress_mag_post->ID] = array(
            'value' => $accesspress_mag_post->ID,
            'label' => $accesspress_mag_post->post_title
        );
    endforeach;

    extract($widget_field);

    switch ($accesspress_mag_widgets_field_type) {

        // Standard text field
        case 'text' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <input class="widefat" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="text" value="<?php echo esc_attr($athm_field_value) ; ?>" />

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;
            
        //title    
        case 'title' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <input class="widefat" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="text" value="<?php echo esc_attr($athm_field_value) ; ?>" />

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;
        
        //title    
        case 'ap_info' :
            ?>
            <p>
                <span class="wid-section-info"><h3><?php echo $accesspress_mag_widgets_title; ?> :</h3></span>                
                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Standard url field
        case 'url' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <input class="widefat" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="text" value="<?php echo $athm_field_value; ?>" />

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Textarea field
        case 'textarea' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <textarea class="widefat" rows="<?php echo $accesspress_mag_widgets_row; ?>" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>"><?php echo $athm_field_value; ?></textarea>
            </p>
            <?php
            break;
        
       
        //iframe Textarea
        case 'iframe_textarea' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <textarea class="widefat" rows="<?php echo $accesspress_mag_widgets_row; ?>" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>"><?php echo $athm_field_value; ?></textarea>
            </p>
            <?php
            break;


        // Checkbox field
        case 'checkbox' :
            ?>
            <p>
                <input id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="checkbox" value="1" <?php checked('1', $athm_field_value); ?>/>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?></label>

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;
        
        //Multi checkboxes
        case 'multicheckboxes':
            if( isset( $accesspress_mag_mulicheckbox_title ) ) {
            ?>
                <label><?php echo esc_attr( $accesspress_mag_mulicheckbox_title ); ?>:</label>
            <?php
            }
            foreach ( $accesspress_mag_widgets_field_options as $athm_option_name => $athm_option_title) {
                if( isset( $athm_field_value[$athm_option_name] ) ) {
                    $athm_field_value[$athm_option_name] = 1;
                }else{
                    $athm_field_value[$athm_option_name] = 0;
                }
                
            ?>
                <p>
                    <input id="<?php echo $instance->get_field_id($athm_option_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name).'['.$athm_option_name.']'; ?>" type="checkbox" value="1" <?php checked('1', $athm_field_value[$athm_option_name]); ?>/>
                    <label for="<?php echo $instance->get_field_id($athm_option_name); ?>"><?php echo $athm_option_title; ?></label>
                </p>
            <?php
				}
                if (isset($accesspress_mag_widgets_description)) {
            ?>
                    <small><em><?php echo $accesspress_mag_widgets_description; ?></em></small>
            <?php
                }
        break;

        // Radio fields
        case 'radio' :
            ?>
            <p>
                <?php
                echo $accesspress_mag_widgets_title;
                echo '<br />';
                foreach ($accesspress_mag_widgets_field_options as $athm_option_name => $athm_option_title) {
                    ?>
                    <input id="<?php echo $instance->get_field_id($athm_option_name); ?>" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="radio" value="<?php echo $athm_option_name; ?>" <?php checked($athm_option_name, $athm_field_value); ?> />
                    <label for="<?php echo $instance->get_field_id($athm_option_name); ?>"><?php echo $athm_option_title; ?></label>
                    <br />
                <?php } ?>

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select field
        case 'select' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <select name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" class="widefat">
                    <?php foreach ($accesspress_mag_widgets_field_options as $athm_option_name => $athm_option_title) { ?>
                        <option value="<?php echo $athm_option_name; ?>" id="<?php echo $instance->get_field_id($athm_option_name); ?>" <?php selected($athm_option_name, $athm_field_value); ?>><?php echo $athm_option_title; ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        case 'number' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label><br />
                <input name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="number" step="1" min="1" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" value="<?php echo $athm_field_value; ?>" class="small-text" />

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        // Select field
        case 'selectpost' :
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label>
                <select name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" class="widefat">
                    <?php foreach ($accesspress_mag_postlist as $accesspress_mag_single_post) { ?>
                        <option value="<?php echo $accesspress_mag_single_post['value']; ?>" id="<?php echo $instance->get_field_id($accesspress_mag_single_post['label']); ?>" <?php selected($accesspress_mag_single_post['value'], $athm_field_value); ?>><?php echo $accesspress_mag_single_post['label']; ?></option>
                    <?php } ?>
                </select>

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <?php
            break;

        case 'upload' :

            $output = '';
            $id = $instance->get_field_id($accesspress_mag_widgets_name);
            $class = '';
            $int = '';
            $value = $athm_field_value;
            $name = $instance->get_field_name($accesspress_mag_widgets_name);

            if ($value) {
                $class = ' has-file';
            }
            $output .= '<div class="sub-option widget-upload">';
            $output .= '<label for="'.$instance->get_field_id($accesspress_mag_widgets_name).'">'.$accesspress_mag_widgets_title.'</label><br/>';
            $output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="' . $name . '" value="' . $value . '" placeholder="' . __('No file chosen', 'accesspress-mag') . '" />' . "\n";
            if (function_exists('wp_enqueue_media')) {
                if (( $value == '')) {
                    $output .= '<input id="upload-' . $id . '" class="upload-button button" type="button" value="' . __('Upload', 'accesspress-mag') . '" />' . "\n";
                } else {
                    $output .= '<input id="remove-' . $id . '" class="remove-file button" type="button" value="' . __('Remove', 'accesspress-mag') . '" />' . "\n";
                }
            } else {
                $output .= '<p><i>' . __( 'Upgrade your version of WordPress for full media support.', 'accesspress-mag' ) . '</i></p>';
            }

            $output .= '<div class="screenshot team-thumb" id="' . $id . '-image">' . "\n";

            if ($value != '') {
                $remove = '<a class="remove-image">Remove</a>';
                $attachment_id = accesspress_mag_get_attachment_id_from_url($value);
                $image_array = wp_get_attachment_image_src( $attachment_id, 'medium', true );
                $image = preg_match('/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value);
                if ($image) {
                    $output .= '<img src="' . $image_array[0] . '" alt="" />' . $remove;
                } else {
                    $parts = explode("/", $value);
                    for ($i = 0; $i < sizeof($parts); ++$i) {
                        $title = $parts[$i];
                    }

                    // No output preview if it's not an image.
                    $output .= '';

                    // Standard generic output if it's not an image.
                    $title = __('View File', 'accesspress-mag');
                    $output .= '<div class="no-image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">' . $title . '</a></span></div>';
                }
            }
            $output .= '</div></div>' . "\n";
            echo $output;
            break;

            case 'icon' :
             add_thickbox();
            ?>
            <p>
                <label for="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>"><?php echo $accesspress_mag_widgets_title; ?>:</label><br />
                <span class="icon-receiver"><i class="<?php echo $athm_field_value; ?>"></i></span>
                <input class="hidden-icon-input" name="<?php echo $instance->get_field_name($accesspress_mag_widgets_name); ?>" type="hidden" id="<?php echo $instance->get_field_id($accesspress_mag_widgets_name); ?>" value="<?php echo $athm_field_value; ?>" />

                <?php if (isset($accesspress_mag_widgets_description)) { ?>
                    <br />
                    <small><?php echo $accesspress_mag_widgets_description; ?></small>
                <?php } ?>
            </p>
            <div id="ap-font-awesome-list">
                <?php 
                    $icon_class_array = array( 
                             'glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th', 'th-list', 'check', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'cog', 'trash-o', 'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox', 'play-circle-o', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones', 
                             'volume-off', 'volume-down', 'volume-up','qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print', 'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-right','align-justify', 'list', 'outdent', 'indent', 'video-camera', 'picture-o', 'pencil', 'map-marker', 'adjust', 'tint', 'pencil-square-o', 'share-square-o', 'check-square-o', 'arrows', 
                             'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward', 'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle', 'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban', 'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'share', 'expand', 'compress', 'plus', 
                             'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down', 'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart', 'twitter-square', 'facebook-square', 'camera-retro', 'key', 'cogs', 'comments', 
                             'thumbs-o-up', 'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link', 'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o', 'phone-square', 'twitter', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn', 'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 
                             'hand-o-down', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter', 'briefcase', 'arrows-alt', 'users', 'link', 'cloud', 'flask', 'scissors', 'files-o', 'paperclip', 'floppy-o', 'square', 'bars', 'list-ul', 'list-ol', 'strikethrough', 'underline','table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square', 'google-plus', 
                             'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'sort', 'sort-desc', 'sort-asc', 'envelope', 'linkedin', 'undo', 'gavel', 'tachometer', 'comment-o', 'comments-o', 'bolt', 'sitemap', 'umbrella', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md', 'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o', 'ambulance', 
                             'medkit', 'fighter-jet', 'beer', 'square', 'plus-square', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down', 'desktop', 'laptop', 'tablet', 'mobile', 'circle-o', 'quote-left', 'quote-right', 'spinner', 'circle', 'reply', 'github-alt', 'folder-o', 'folder-open-o', 'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 
                             'flag-checkered', 'terminal', 'code', 'reply-all', 'star-half-o', 'location-arrow', 'crop', 'code-fork', 'chain-broken', 'question', 'exclamation', 'superscript', 'info', 'subscript', 'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher', 'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-circle-down', 'html5', 'css3', 
                             'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v', 'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'vlevel-up', 'level-down', 'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'caret-square-o-down', 'caret-square-o-up', 'caret-square-o-right', 'eur', 'gbp', 'usd', 'inr', 'jpy', 'rub', 'krw', 'btc', 'file', 'file-text', 'sort-alpha-asc', 
                             'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square', 'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn', 'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up', 'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 
                             'dribbble', 'skype', 'foursquare', 'trello', 'female', 'male', 'gittip', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo', 'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'try', 'plus-square-o', 'space-shuttle', 'slack', 'envelope-square', 'wordpress', 'openid', 'university', 'graduation-cap', 'yahoo', 'google', 
                             'reddit', 'reddit-square', 'stumbleupon-circle', 'stumbleupon', 'delicious', 'digg', 'pied-piper', 'pied-piper-alt', 'drupal', 'joomla', 'language', 'fax', 'building', 'child', 'paw', 'spoon', 'cube', 'cubes', 'behance', 'behance-square', 'steam', 'steam-square', 'recycle', 'car', 'taxi', 'tree', 'spotify', 'deviantart', 'soundcloud', 'database', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'file-powerpoint-o', 'file-image-o', 
                             'file-archive-o', 'file-audio-o', 'file-video-o', 'file-code-o', 'vine', 'codepen', 'jsfiddle', 'life-ring', 'circle-o-notch', 'rebel', 'empire', 'git-square', 'git', 'hacker-news', 'tencent-weibo', 'qq', 'weixin', 'paper-plane', 'paper-plane-o', 'history', 'circle-thin', 'header', 'paragraph', 'sliders', 'share-alt', 'share-alt-square', 'bomb', 'futbol-o', 'tty', 'binoculars', 'plug', 'slideshare', 'twitch', 'yelp', 'newspaper-o', 
                             'wifi', 'calculator', 'paypal', 'google-wallet', 'cc-visa', 'cc-mastercard', 'cc-discover', 'cc-amex', 'cc-paypal', 'cc-stripe', 'bell-slash', 'bell-slash', 'bell-slash-o', 'trash', 'copyright', 'at', 'eyedropper', 'paint-brush', 'birthday-cake', 'area-chart', 'pie-chart', 'line-chart', 'lastfm', 'lastfm-square', 'toggle-off', 'toggle-on', 'bicycle', 'bus', 'ioxhost', 'angellist', 'cc', 'ils', 'meanpath' 
                            );
                ?>
                <ul class="ap-font-group">
                    <!-- <li><i class="fa fa-glass"></i></li> -->
                    <?php foreach( $icon_class_array as $count => $class ) {
                        echo '<li><i class="fa fa-'. $class .'"></i></li>';
                    }?>
                </ul>
            </div>
            <?php
            break;
    }
}

function accesspress_mag_widgets_updated_field_value($widget_field, $new_field_value) {

    extract($widget_field);

    // Allow only integers in number fields
    if ($accesspress_mag_widgets_field_type == 'number') {
        return absint($new_field_value);

        // Allow some tags in textareas
    } elseif ($accesspress_mag_widgets_field_type == 'textarea') {
        // Check if field array specifed allowed tags
        if (!isset($accesspress_mag_widgets_allowed_tags)) {
            // If not, fallback to default tags
            $accesspress_mag_widgets_allowed_tags = '<p><strong><em><a>';
        }
        
        return strip_tags($new_field_value, $accesspress_mag_widgets_allowed_tags);

        // No allowed tags for all other fields
    } elseif ($accesspress_mag_widgets_field_type == 'iframe_textarea') {
        // Check if field array specifed allowed tags
        if (!isset($accesspress_mag_widgets_allowed_tags)) {
            // If not, fallback to default tags
            $accesspress_mag_widgets_allowed_tags = '<iframe>';
        }
        
        return strip_tags($new_field_value, $accesspress_mag_widgets_allowed_tags);

        // No allowed tags for all other fields
    } 
    
    elseif ($accesspress_mag_widgets_field_type == 'url') {
        return esc_url_raw($new_field_value);
    }
    elseif ($accesspress_mag_widgets_field_type == 'title') {
        return wp_kses_post($new_field_value);
    }
    elseif ($accesspress_mag_widgets_field_type == 'multicheckboxes') {
        return $new_field_value;
    }
    else {
        return strip_tags($new_field_value);
    }
}

/**
 * Enqueue scripts for file uploader
 */
function optionsframework_media_scriptss($hook) {

    if (function_exists('wp_enqueue_media'))
        wp_enqueue_media();

    wp_register_script('of-media-uploader', OPTIONS_FRAMEWORK_DIRECTORY . 'js/media-uploader.js', array('jquery'), 1.70);
    wp_enqueue_script('of-media-uploader');
    wp_localize_script('of-media-uploader', 'optionsframework_l10n', array(
        'upload' => __('Upload', 'accesspress-mag'),
        'remove' => __('Remove', 'accesspress-mag')
    ));

    wp_enqueue_style( 'accesspress-mag-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
    //wp_enqueue_style( 'ap-admin-css', get_template_directory_uri() . '/inc/css/ap-admin.css' );
    //wp_enqueue_script('ap-admin-js', get_template_directory_uri() . '/inc/js/ap-admin.js', array('jquery'));
    //wp_enqueue_script('ap-admin-js');
}

add_action('admin_enqueue_scripts', 'optionsframework_media_scriptss');

