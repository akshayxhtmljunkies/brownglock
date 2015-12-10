<?php
/**
 * Metabox about Post template with Post/Page sidebar 
 *
 * @package Accesspress Mag Pro
 */
 
add_action('add_meta_boxes', 'accesspress_mag_add_sidebar_layout_box'); 
 
function accesspress_mag_add_sidebar_layout_box()
{   
    add_meta_box(
                 'accesspress_mag_post_settings', // $id
                 __( 'Post settings', 'accesspress-mag' ), // $title
                 'accesspress_mag_post_settings_callback', // $callback
                 'post', // $page
                 'normal', // $context
                 'high'); // $priority

    add_meta_box(
                 'accesspress_mag_page_settings', // $id
                 __( 'Sidebar Layout', 'accesspress-mag' ), // $title
                 'accesspress_mag_page_settings_callback', // $callback
                 'page', // $page
                 'normal', // $context
                 'high'); // $priority
 
}

$accesspress_mag_sidebar_layout = array(
        'global-sidebar' => array(
                        'value'     => 'global-sidebar',
                        'label'     => __( 'Default sidebar', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/default-sidebar.png'
                    ), 
        'left-sidebar' => array(
                        'value'     => 'left-sidebar',
                        'label'     => __( 'Left sidebar', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/left-sidebar.png'
                    ), 
        'right-sidebar' => array(
                        'value' => 'right-sidebar',
                        'label' => __( 'Right sidebar<br/>(default)', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/right-sidebar.png'
                    ),
       
        'no-sidebar' => array(
                        'value'     => 'no-sidebar',
                        'label'     => __( 'No sidebar', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/no-sidebar.png'
                    )   

    );

$accesspress_mag_page_sidebar_layout = array(
        'left-sidebar' => array(
                        'value'     => 'left-sidebar',
                        'label'     => __( 'Left sidebar', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/left-sidebar.png'
                    ), 
        'right-sidebar' => array(
                        'value' => 'right-sidebar',
                        'label' => __( 'Right sidebar<br/>(default)', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/right-sidebar.png'
                    ),
       
        'no-sidebar' => array(
                        'value'     => 'no-sidebar',
                        'label'     => __( 'No sidebar', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/no-sidebar.png'
                    )   

    );

$accesspress_mag_post_template_layout = array(
        'global-template' => array(
                        'value'     => 'global-template',
                        'label'     => __( 'Default Template', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/post_template/post-templates-icons-theme.png',
                        'available'=> 'free'
                    ),
        'default-template' => array(
                        'value'     => 'single',
                        'label'     => __( 'Default Style', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/post_template/post-templates-icons-0.png',
                        'available'=> 'free'
                    ), 
        'style1-template' => array(
                        'value' => 'single-style1',
                        'label' => __( 'Style 1', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/post_template/post-templates-icons-1.png',
                        'available'=> 'free'
                    ),
        'style2-template' => array(
                        'value' => 'single-style2',
                        'label' => __( 'Style 2', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/post_template/post-templates-icons-2.png',
                        'available'=> 'pro'
                    ),
        'style3-template' => array(
                        'value' => 'single-style3',
                        'label' => __( 'Style 3', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/post_template/post-templates-icons-3.png',
                        'available'=> 'pro'
                    ),
        'style4-template' => array(
                        'value' => 'single-style4',
                        'label' => __( 'Style 4', 'accesspress-mag' ),
                        'thumbnail' => get_template_directory_uri() . '/inc/option-framework/images/post_template/post-templates-icons-4.png',
                        'available'=> 'pro'
                    )

    );

/*-------------------Function for Post settings meta box----------------------------*/

function accesspress_mag_post_settings_callback() {
    global $post, $accesspress_mag_post_template_layout, $accesspress_mag_sidebar_layout ;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_post_settings_nonce' );
    $fearured_slider = get_post_meta( $post->ID, 'post_featured_on_slider', true );
?>

<div class="my-post-settings">
    <div class="post-template-wrapper">
        <div class="section-title"><h4><?php _e( 'Post template', 'accesspress-mag' )?></h4></div>
        <?php  
           foreach ($accesspress_mag_post_template_layout as $field) {  
                        $accesspress_mag_post_template_metalayout = get_post_meta( $post->ID, 'accesspress_mag_post_template_layout', true );
        ?>
                                        
                <div class="radio-post-template-wrapper" available="<?php echo $field['available'];?>" style="float:left; margin-right:30px;">
                <label class="description">
                <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="" /></span></br>
                <input type="radio" name="accesspress_mag_post_template_layout" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $accesspress_mag_post_template_metalayout ); if(empty($accesspress_mag_post_template_metalayout) && $field['value']=='global-template'){ echo "checked='checked'";} ?>/>&nbsp;<?php echo $field['label']; ?>
                </label>
                </div>
        <?php 
            } // end foreach 
        ?>
    </div>
    <div class="post-sidebar-wrapper">
        <div class="section-title"><h4><?php _e( 'Post Sidebar', 'accesspress-mag' )?></h4></div>
        <?php  
           foreach ($accesspress_mag_sidebar_layout as $field) {  
                $accesspress_mag_sidebar_metalayout = get_post_meta( $post->ID, 'accesspress_mag_sidebar_layout', true ); 
        ?>
            <div class="radio-image-wrapper" style="float:left; margin-right:30px;">
                <label class="description">
                <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="" /></span></br>
                <input type="radio" name="accesspress_mag_sidebar_layout" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $accesspress_mag_sidebar_metalayout ); if(empty($accesspress_mag_sidebar_metalayout) && $field['value']=='global-sidebar'){ echo "checked='checked'";} ?>/>&nbsp;<?php echo $field['label']; ?>
                </label>
            </div>
        <?php 
            } // end foreach 
        ?>
    </div>
    <div class="sidebar-link">
        <?php _e( 'You can set up the sidebar content', 'accesspress-mag' );?> 
        <a href="<?php echo admin_url('/widgets.php'); ?>"><?php _e( 'here', 'accesspress-mag' );?></a>
    </div>
</div>
<?php
}

/*---------Function for Page sidebar meta box----------------------------*/

function accesspress_mag_page_settings_callback()
{
    global $post, $accesspress_mag_page_sidebar_layout ;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_page_settings_nonce' );
?>
    <div class="my-page-settings">
        <div class="page-sidebar-wrapper">
            <div class="section-title"><h4><?php _e( 'Page Sidebar', 'accesspress-mag' )?></h4></div>
            <?php  
                foreach ($accesspress_mag_page_sidebar_layout as $field) {  
                    $accesspress_mag_page_sidebar_metalayout = get_post_meta( $post->ID, 'accesspress_mag_page_sidebar_layout', true ); 
            ?>  
                <div class="radio-image-wrapper" style="float:left; margin-right:30px;">
                    <label class="description">
                    <span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="" /></span></br>
                    <input type="radio" name="accesspress_mag_page_sidebar_layout" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $accesspress_mag_page_sidebar_metalayout ); if(empty($accesspress_mag_page_sidebar_metalayout) && $field['value']=='right-sidebar'){ echo "checked='checked'";} ?>/>&nbsp;<?php echo $field['label']; ?>
                    </label>
                </div>
            <?php 
                } // end foreach 
            ?>
        </div>
        <div class="sidebar-link">
            <?php _e( 'You can set up the sidebar content', 'accesspress-mag' );?> 
            <a href="<?php echo admin_url('/widgets.php'); ?>"><?php _e( 'here', 'accesspress-mag' );?></a>
        </div>
    </div>
<?php
}

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */

/*-------------------Save function for Post Setting-------------------------*/

function accesspress_mag_save_post_settings( $post_id ) { 
    global $accesspress_mag_post_template_layout, $accesspress_mag_sidebar_layout, $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_post_settings_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_post_settings_nonce' ], basename( __FILE__ ) ) )
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
    
    foreach ($accesspress_mag_post_template_layout as $field) {  
        //Execute this saving function
        $old = get_post_meta( $post_id, 'accesspress_mag_post_template_layout', true); 
        $new = sanitize_text_field($_POST['accesspress_mag_post_template_layout']);
        if ($new && $new != $old) {  
            update_post_meta($post_id, 'accesspress_mag_post_template_layout', $new);  
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id,'accesspress_mag_post_template_layout', $old);  
        }
     } // end foreach  
     
   foreach ($accesspress_mag_sidebar_layout as $field) {  
        //Execute this saving function
        $old = get_post_meta( $post_id, 'accesspress_mag_sidebar_layout', true); 
        $new = sanitize_text_field($_POST['accesspress_mag_sidebar_layout']);
        if ($new && $new != $old) {  
            update_post_meta($post_id, 'accesspress_mag_sidebar_layout', $new);  
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id,'accesspress_mag_sidebar_layout', $old);  
        }
     } // end foreach
}
add_action('save_post', 'accesspress_mag_save_post_settings');

/*-------------------Save function for Page Setting-------------------------*/

function accesspress_mag_save_page_settings( $post_id ) { 
    global $accesspress_mag_sidebar_layout, $post; 

    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_page_settings_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_page_settings_nonce' ], basename( __FILE__ ) ) )
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
    
    foreach ($accesspress_mag_sidebar_layout as $field) {  
        //Execute this saving function
        $old = get_post_meta( $post_id, 'accesspress_mag_page_sidebar_layout', true); 
        $new = sanitize_text_field($_POST['accesspress_mag_page_sidebar_layout']);
        if ($new && $new != $old) {  
            update_post_meta($post_id, 'accesspress_mag_page_sidebar_layout', $new);  
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id,'accesspress_mag_page_sidebar_layout', $old);  
        } 
     } // end foreach 
    
}
add_action('save_post', 'accesspress_mag_save_page_settings');