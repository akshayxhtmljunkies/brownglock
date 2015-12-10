<?php
/**
 * Metabox about Extra options
 * 
 * @package Accesspress Mag Pro
 */

add_action('add_meta_boxes', 'accesspress_mag_post_assorted_meta');

function accesspress_mag_post_assorted_meta() {
    add_meta_box(
                 'accesspress_mag_post_assorted',  // $id
                 __( 'Miscellaneous Meta Fields', 'accesspress-mag' ),  // $title
                 'accesspress_mag_post_assorted_callback',  // $callback
                 'post',  // $page
                 'normal',  // $context
                 'high');  // $priority
}

/*---------------------------------- Function Miscellaneous--------------------------------------------------------*/

function accesspress_mag_post_assorted_callback() {
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_post_assorted_nonce' );
    $fearured_slider = get_post_meta( $post->ID, 'post_featured_on_slider', true );
?>
    <div class="featured-slider-meta-wrap">
        <div class="section-title"><h4><?php _e( 'Featured in slider', 'accesspress-mag' );?></h4></div>
        <input type="checkbox" name="post_featured_on_slider" value="1" <?php checked( true, $fearured_slider ); ?> />
        <em class="f13"><?php _e( 'Checked option for featured post in slider', 'accesspress-mag' );?></em></td>
    </div>
    <div class="source-section">
        <div class="section-title"><h4><?php _e( 'Article source section', 'accesspress-mag' );?></h4></div>
        <?php 
            $accesspress_mag_post_source_name = get_post_meta($post->ID, 'post_source_name', true);
            $accesspress_mag_post_source_url = get_post_meta($post->ID, 'post_source_url', true); 
            $accesspress_mag_post_via_name = get_post_meta($post->ID, 'post_via_name', true); 
            $accesspress_mag_post_via_url = get_post_meta($post->ID, 'post_via_url', true);  
        ?>
        <div class="single-source-field">
            <span class="field-label"><?php _e( 'Source Name :', 'accesspress-mag' );?></span>
            <input type="text" name="post_source_name" value="<?php if(!empty($accesspress_mag_post_source_name)){echo $accesspress_mag_post_source_name;}?>" />
            <span class="field-info"><?php _e( ' Name of the source', 'accesspress-mag' );?></span>
        </div>
        <div class="single-source-field">
            <span class="field-label"><?php _e( 'Source URL :', 'accesspress-mag' );?></span>
            <input type="text" name="post_source_url" value="<?php if(!empty($accesspress_mag_post_source_url)){echo $accesspress_mag_post_source_url;}?>" />
            <span class="field-info"><?php _e( ' URL of the source', 'accesspress-mag' );?></span>
        </div>
        <div class="single-source-field">
            <span class="field-label"><?php _e( 'Via Name :', 'accesspress-mag' );?></span>
            <input type="text" name="post_via_name" value="<?php if(!empty($accesspress_mag_post_via_name)){echo $accesspress_mag_post_via_name;}?>" />
        </div>
        <div class="single-source-field">
            <span class="field-label"><?php _e( 'Via Url :', 'accesspress-mag' );?></span>
            <input type="text" name="post_via_url" value="<?php if(!empty($accesspress_mag_post_via_url)){echo $accesspress_mag_post_via_url;}?>" />
        </div>
        
    </div>
<?php
}

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */

/*-------------------Save function for Post Setting-------------------------*/

function accesspress_mag_save_post_assorted( $post_id ) { 
    global $post;

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_post_assorted_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_post_assorted_nonce' ], basename( __FILE__ ) ) )
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
    
    $post_featured_slider = get_post_meta( $post->ID, 'post_featured_on_slider', true );
       $post_source_name = get_post_meta($post->ID, 'post_source_name', true);
       $post_source_url = get_post_meta($post->ID, 'post_source_url', true); 
       $post_via_name = get_post_meta($post->ID, 'post_via_name', true); 
       $post_via_url = get_post_meta($post->ID, 'post_via_url', true);
       
       // If the checkbox has not been checked, we void it
    	if ( ! isset( $_POST['post_featured_on_slider'] ) )
    		$_POST['post_featured_on_slider'] = null;
    	// We verify if the input is a boolean value
        
   	   $_POST['post_featured_on_slider'] = ( $_POST['post_featured_on_slider'] == 1 ? 1 : 0 );
       
       $stz_featured_slider =$_POST['post_featured_on_slider'] ; 
       $stz_source_name = sanitize_text_field($_POST['post_source_name']);  
       $stz_source_url = esc_url($_POST['post_source_url']);
       $stz_via_name = sanitize_text_field($_POST['post_via_name']);
       $stz_via_url = esc_url($_POST['post_via_url']); 
   
   //update data for Featured slider
        if ( $stz_featured_slider && '' == $stz_featured_slider ){
            add_post_meta( $post_id, 'post_featured_on_slider', $stz_featured_slider );
        }elseif ($stz_featured_slider && $stz_featured_slider != $post_featured_slider) {  
            update_post_meta($post_id, 'post_featured_on_slider', $stz_featured_slider);  
        } elseif ('' == $stz_featured_slider && $post_featured_slider) {  
            delete_post_meta($post_id,'post_featured_on_slider', $post_featured_slider);  
        }
   //update data for source name
        if ( $stz_source_name && '' == $stz_source_name ){
            add_post_meta( $post_id, 'post_source_name', $stz_source_name );
        }elseif ($stz_source_name && $stz_source_name != $post_source_name) {  
            update_post_meta($post_id, 'post_source_name', $stz_source_name);  
        } elseif ('' == $stz_source_name && $post_source_name) {  
            delete_post_meta($post_id,'post_source_name', $post_source_name);  
        }
   //update data for source url
        if ( $stz_source_url && '' == $stz_source_url ){
            add_post_meta( $post_id, 'post_source_url', $stz_source_url );
        }elseif ($stz_source_url && $stz_source_url != $post_source_url) {  
            update_post_meta($post_id, 'post_source_url', $stz_source_url);  
        } elseif ('' == $stz_source_url && $post_source_url) {  
            delete_post_meta($post_id,'post_source_url', $post_source_url);  
        }
    //update data for via name
        if ( $stz_via_name && '' == $stz_via_name ){
            add_post_meta( $post_id, 'post_via_name', $stz_via_name );
        }elseif ($stz_via_name && $stz_via_name != $post_via_name) {  
            update_post_meta($post_id, 'post_via_name', $stz_via_name);  
        } elseif ('' == $stz_via_name && $post_via_name) {  
            delete_post_meta($post_id,'post_via_name', $post_via_name);  
        }
   //update data for via url
        if ( $stz_via_url && '' == $stz_via_url ){
            add_post_meta( $post_id, 'post_via_url', $stz_via_url );
        }elseif ($stz_via_url && $stz_via_url != $post_via_url) {  
            update_post_meta($post_id, 'post_via_url', $stz_via_url);  
        } elseif ('' == $stz_via_url && $post_via_url) {  
            delete_post_meta($post_id,'post_via_url', $post_via_url);  
        }
}
add_action('save_post', 'accesspress_mag_save_post_assorted');