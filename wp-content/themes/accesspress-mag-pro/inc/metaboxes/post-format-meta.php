<?php
/**
 * Metabox about Post Format 
 * 
 * @package Accesspress Mag Pro
 */

add_action('add_meta_boxes', 'accesspress_mag_post_format_meta');

function accesspress_mag_post_format_meta() {
    
    add_meta_box(
                 'accesspress_mag_post_video',  // $id
                 __( 'Embed video', 'accesspress-mag' ),  // $title
                 'accesspress_mag_post_video_callback',  // $callback
                 'post',  // $page
                 'normal',  // $context
                 'high');  // $priority
                 
    add_meta_box(
                 'accesspress_mag_post_audio',
                 __( 'Embed audio', 'accesspress-mag' ),
                 'accesspress_mag_post_audio_callback',
                 'post',
                 'normal',
                 'high');
                 
    add_meta_box(
                 'accesspress_mag_post_gallery',
                 __( 'Embed gallery', 'accesspress-mag' ),
                 'accesspress_mag_post_gallery_callback',
                 'post',
                 'normal',
                 'high');
    
    add_meta_box(
                 'accesspress_mag_post_quote',
                 __( 'Embed Quote', 'accesspress-mag' ),
                 'accesspress_mag_post_quote_callback',
                 'post',
                 'normal',
                 'high');
}

/*-------------------Function for Post Video meta box----------------------------*/
function accesspress_mag_post_video_callback()
{
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_post_video_nonce' );
    $accesspress_mag_embed_url = get_post_meta($post->ID, 'post_embed_videourl', true);
?>
    <div class="post-embedvideo-wrapper">
        <div class="section-title"><strong><?php _e( 'Embed video url', 'accesspress-mag' );?></strong></div>
        <div class="section-input">
            <input type="text" name="post_embed_videourl" class="post-videourl" value="<?php if( !empty( $accesspress_mag_embed_url ) ){ echo $accesspress_mag_embed_url; }?>" />
            <input class="button" type="button" id="reset-post-embedurl" value="Reset url" />
        </div>
        <span><em><?php _e( 'Please use youtube/vimeo video url ( https://www.youtube.com/watch?v=cXhXy6DIhDY ).', 'accesspress-mag' ); ?></em></span>
    </div>
<?php
}

/*-------------------Function for Post Audio meta box----------------------------*/
function accesspress_mag_post_audio_callback()
{
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_post_audio_nonce' );
    $accesspress_mag_embed_audio_url = get_post_meta($post->ID, 'post_embed_audiourl', true);
?>
    <div class="post-embedaudio-wrapper">
        <div class="section-title"><strong><?php _e( 'Embed audio url', 'accesspress-mag' );?></strong></div>
        <div class="audiourl-sec">
            <input type="text" name="post_embed_audiourl" value="<?php if( !empty( $accesspress_mag_embed_audio_url ) ){ echo $accesspress_mag_embed_audio_url ; } ?>" />
            <input class="button" name="media_upload_button" id="post_audio_upload_button" value="<?php _e( 'Embed audio', 'accesspress-mag' ); ?>" type="button" />
        </div>        
        <input class="button" type="button" id="audiourl_remove" value="Reset url" style="display: <?php if( !empty( $accesspress_mag_embed_audio_url ) ){ echo 'block'; } else { echo 'none'; }?>;"  /> 
    </div>
<?php
}

/*-------------------Function for Post Gallery meta box----------------------------*/
function accesspress_mag_post_gallery_callback()
{
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_post_gallery_nonce' );
    $accesspress_mag_post_images = get_post_meta($post->ID, 'post_images', true);
    $accesspress_mag_post_images_count = get_post_meta( $post->ID, 'image_count', true );
?>
    <div class="post-embedgallery-wrapper">
        <div class="section-title"><strong><?php _e( 'Embed Gallery Images', 'accesspress-mag' );?></strong></div>
        <div class="post_image_section apmag-not-home">
            <?php
                $total_img = 0;
                if( !empty( $accesspress_mag_post_images ) ){                                            
                    $total_img = count( $accesspress_mag_post_images );
                    $img_counter = 0;
                    foreach( $accesspress_mag_post_images as $gallery_image ){                                               
                       $attachment_id = accesspress_mag_get_attachment_id_from_url( $gallery_image );
                       $img_url = wp_get_attachment_image_src($attachment_id,'thumbnail'); 
                    
            ?>
                        <div class="gal-img-block">
                            <div class="gal-img"><img src="<?php echo $img_url[0]; ?>" /><span class="fig-remove">Remove</span></div>
                            <input type="hidden" name="post_images[<?php echo $img_counter; ?>]" class="hidden-media-gallery" value="<?php echo $gallery_image; ?>" />
                        </div>
            <?php
                        $img_counter++;
                    }
                }
            ?>                  
        </div>
        <input id="post_image_count" type="hidden" name="image_count" value="<?php echo $total_img; ?>">
        <a href="javascript:void(0)" class="docopy-post_image button"><?php _e( 'Add Image', 'accesspress-mag' );?></a>
    </div>
<?php
}

/*-------------------Function for Post Quote meta box----------------------------*/
function accesspress_mag_post_quote_callback()
{
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_post_quote_nonce' );
    $accesspress_mag_post_quote = get_post_meta($post->ID, 'post_embed_quote', true);
?>
    <div class="post-embedquote-wrapper">
        <div class="section-title"><strong><?php _e( 'Embed Quote', 'accesspress-mag' );?></strong></div>
        <div class="section-input">
            <textarea name="post_embed_quote" class="post_quote" cols="70" rows="7" placeholder="Enter quote here..." ><?php if( !empty( $accesspress_mag_post_quote ) ){ echo wp_kses_post( $accesspress_mag_post_quote ); }?></textarea>
        </div>
    </div>
<?php
}

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */

/*--------------------Save function for post embed video-------------------------*/

function accesspress_mag_save_post_embed_video( $post_id ) { 
    global $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_post_video_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_post_video_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
        
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }
     
     $prev_url = get_post_meta( $post_id, 'post_embed_videourl', true);
     $new_url = sanitize_text_field($_POST['post_embed_videourl']);
     if ($new_url && $new_url != $prev_url) {  
            update_post_meta($post_id, 'post_embed_videourl', $new_url);  
        } elseif ('' == $new_url && $prev_url) {  
            delete_post_meta($post_id,'post_embed_videourl', $prev_url);  
        }
    
}
add_action('save_post', 'accesspress_mag_save_post_embed_video');  

/*--------------------Save function for post embed audio-------------------------*/

function accesspress_mag_save_post_embed_audio( $post_id ) { 
    global $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_post_audio_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_post_audio_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
        
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }
     
     $prev_audiourl = get_post_meta( $post_id, 'post_embed_audiourl', true);
     $new_audiourl = sanitize_text_field($_POST['post_embed_audiourl']);
     if ($new_audiourl && $new_audiourl != $prev_audiourl) {  
            update_post_meta($post_id, 'post_embed_audiourl', $new_audiourl);  
        } elseif ('' == $new_audiourl && $prev_audiourl) {  
            delete_post_meta($post_id,'post_embed_audiourl', $prev_audiourl);  
        }
    
}
add_action('save_post', 'accesspress_mag_save_post_embed_audio');  

/*-------------------Save post format gallery type--------------------------*/

function accesspress_mag_save_post_image( $post_id ) { 
    global  $post;
    
    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_post_gallery_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_post_gallery_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
        
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    } 
    $image_count = get_post_meta($post->ID, 'image_count', true);
    //Execute this saving function
    $stz_image_count = sanitize_text_field($_POST['image_count']);
   
    if ( $stz_image_count && '' == $stz_image_countt ){
            add_post_meta( $post_id, 'image_count', $stz_image_count );
        }elseif ($stz_image_count && $stz_image_count != $image_count) {  
            update_post_meta($post_id, 'image_count', $stz_image_count);  
        } elseif ('' == $stz_image_count && $image_count) {  
            delete_post_meta($post_id,'image_count');  
        }
    
    $stz_post_image = $_POST['post_images'];
   // var_dump($stz_post_image); die();
    
    update_post_meta($post_id, 'post_images', $stz_post_image);

    }
add_action('save_post', 'accesspress_mag_save_post_image');

/*--------------------Save function for post embed quote-------------------------*/

function accesspress_mag_save_post_embed_quote( $post_id ) { 
    global $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_post_quote_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_post_quote_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
        
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    }
         
     $prev_quote = get_post_meta( $post_id, 'post_embed_quote', true);
     $new_quote = wp_kses_post($_POST['post_embed_quote']);
     if ($new_quote && $new_quote != $prev_quote) {  
            update_post_meta($post_id, 'post_embed_quote', $new_quote);  
        } elseif ('' == $new_quote && $prev_quote) {  
            delete_post_meta($post_id,'post_embed_quote', $prev_quote);  
        }
}
add_action('save_post', 'accesspress_mag_save_post_embed_quote');