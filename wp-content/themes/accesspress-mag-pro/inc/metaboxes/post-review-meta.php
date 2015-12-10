<?php
/**
 * Metabox about Post Review stystem 
 * 
 * @package Accesspress Mag Pro
 */

add_action('add_meta_boxes', 'accesspress_mag_post_review');

function accesspress_mag_post_review() {
    add_meta_box(
                 'accesspress_mag_product_review', // $id
                 __( 'Product review', 'accesspress-mag' ), // $title
                 'accesspress_mag_product_review_callback', // $callback
                 'post', // $page
                 'normal', // $context
                 'high'); // $priority
}

/*---------Function for Product Review meta box----------------------------*/

function accesspress_mag_product_review_callback()
{
    global $post ;
    wp_nonce_field( basename( __FILE__ ), 'accesspress_mag_product_review_nonce' );
?>
<div class="my_meta_control td-not-portfolio td-not-home">
<?php
        $accesspress_mag_review_option = get_post_meta( $post->ID, 'product_review_option', true ); 
        $star_rating = get_post_meta( $post->ID, 'star_rating', true ); 
        $percent_rating = get_post_meta( $post->ID, 'percent_rating', true );
        $point_rating = get_post_meta( $post->ID, 'points_rating', true );
        $star_review_count = get_post_meta( $post->ID, 'star_review_count', true );
        $precent_review_count = get_post_meta( $post->ID, 'percent_review_count', true );
        $point_review_count = get_post_meta( $post->ID, 'points_review_count', true );
        $accesspress_mag_rate_description = get_post_meta( $post->ID, 'product_rate_description', true );
    ?>

    <p class="apmag_help_section apmag-help-select">
        <span class="apmag_custom_label"><?php _e( 'Is product review? :','accesspress-mag' );?></span>        

        <div class="apmag-select-review-option">
            <select id="reviewSelector" name="product_review_option" class="apmag-panel-dropdown">
                <option value="norate" <?php selected( $accesspress_mag_review_option, 'norate' ); ?>><?php _e( 'No', 'accesspress-mag' ); ?></option>
                <option value="rate_stars" <?php selected( $accesspress_mag_review_option, 'rate_stars' ); ?>><?php _e( 'Stars', 'accesspress-mag' ); ?></option>
                <option value="rate_percent"<?php selected( $accesspress_mag_review_option, 'rate_percent' ); ?>><?php _e( 'Percentages', 'accesspress-mag' ); ?></option>
                <option value="rate_point"<?php selected( $accesspress_mag_review_option, 'rate_point' ); ?>><?php _e( 'Points', 'accesspress-mag' ); ?></option>
            </select>
        </div>
    </p>
    
    <div class="rating_type rate_Stars">
        <div><strong><?php _e( 'Add star ratings for this product:', 'accesspress-mag' );?></strong></div>
        <div class="product_reivew_section apmag-not-home">
            <?php 
            $count = 0;
            if( !empty( $star_rating ) ){
            foreach ( $star_rating as $key => $value ) {
                if( !empty( $key['feature_name'] ) || !empty( $value['feature_star'] ) ) {
                $count++;
            ?>

            <div class="review_section_group">               
                <span class="apmag_custom_label"><?php _e( 'Feature Name:', 'accesspress-mag' );?></span>
                <input style="width: 200px;" type="text" name="star_ratings[<?php echo $count; ?>][feature_name]" value="<?php echo $value['feature_name']; ?>"/>
                <select name="star_ratings[<?php echo $count; ?>][feature_star]">
                    <option value=""><?php _e( 'Select rating', 'accesspress-mag' );?></option>
                    <option value="5"<?php selected( $value['feature_star'], 5 ); ?>><?php _e( '5 stars', 'accesspress-mag' );?></option>
                    <option value="4.5"<?php selected( $value['feature_star'], 4.5 ); ?>><?php _e( '4.5 stars', 'accesspress-mag' );?></option>
                    <option value="4"<?php selected( $value['feature_star'], 4 ); ?>><?php _e( '4 stars', 'accesspress-mag' );?></option>
                    <option value="3.5"<?php selected( $value['feature_star'], 3.5 ); ?>><?php _e( '3.5 stars', 'accesspress-mag' );?></option>
                    <option value="3"<?php selected( $value['feature_star'], 3 ); ?>><?php _e( '3 stars', 'accesspress-mag' );?></option>
                    <option value="2.5"<?php selected( $value['feature_star'], 2.5 ); ?>><?php _e( '2.5 stars', 'accesspress-mag' );?></option>
                    <option value="2"<?php selected( $value['feature_star'], 2 ); ?>><?php _e( '2 stars', 'accesspress-mag' );?></option>
                    <option value="1.5"<?php selected( $value['feature_star'], 1.5 ); ?>><?php _e( '1.5 stars', 'accesspress-mag' );?></option>
                    <option value="1"<?php selected( $value['feature_star'], 1 ); ?>><?php _e( '1 star', 'accesspress-mag' );?></option>
                    <option value="0.5"<?php selected( $value['feature_star'], 0.5 ); ?>><?php _e( '0.5 star', 'accesspress-mag' );?></option>
                </select>
                <a href="javascript:void(0)" class="delete-review-stars button">Delete</a>
            </div> 

            <?php
                        }
                    } 
                }
            ?>           
        </div>
        <input id="post_star_review_count" type="hidden" name="star_review_count" value="<?php echo $count; ?>" />
        <a href="javascript:void(0)" class="docopy-revirew-stars button"><?php _e( 'Add rating category', 'accesspress-mag' );?></a>
    </div>
    
    <div class="rating_type rate_Percentages">
        <div><strong><?php _e( 'Add percent ratings for this product:', 'accesspress-mag' )?></strong></div>
        <div class="precent_review_section apmag-not-home">
            <?php 
                $p_count = 0;
                if(!empty($percent_rating)){
                foreach ($percent_rating as $key => $value) {
                    $p_count++;
            ?>
                <div class="reivew_percent_group">
                    <span class="apmag_custom_label"><?php _e('Featured Name:', 'accesspress-mag') ;?></span>
                        <input style="width: 200px;" type="text" name="percent_ratings[<?php echo $p_count; ?>][feature_name]" value="<?php echo $value['feature_name']; ?>"/>
                    <?php _e( '- Percent: ', 'accesspress-mag' );?>
                    <input style="width: 100px;" type="number" min="1" max="100" name="percent_ratings[<?php echo $p_count; ?>][feature_percent]" value="<?php echo $value['feature_percent']; ?>" step="1"/>
                    <a href="javascript:void(0)" class="delete-review-percents button">Delete</a>
                </div>
            <?php
                    } 
                }
            ?>
        </div>
        <input id="post_precent_review_count" type="hidden" name="percent_review_count" value="<?php echo $p_count; ?>" />
            <a href="javascript:void(0)" class="docopy-review_percents button">Add rating category</a>
    </div>


    <div class="rating_type rate_Points">
        <div><strong><?php _e( 'Add points ratings for this product: ', 'accesspress-mag' ); ?></strong></div>
        <div class="point_review_section apmag-not-home">
            <?php 
                $count_p = 0;
                if(!empty($point_rating)){
                foreach ($point_rating as $key => $value) {
                    $count_p++;
            ?>
            <div class="reivew_point_group">
                <span class="td_custom_label"><?php _e( 'Featured Name:', 'accesspress-mag' );?></span>
                    <input style="width: 200px;" type="text" name="points_ratings[<?php echo $count_p ;?>][feature_name]" value="<?php echo $value['feature_name'];?>"/>
                <?php _e( '- Points: ', 'accesspres-mag' );?>
                <input style="width: 100px;" type="number" min="0.2" max="10" name="points_ratings[<?php echo $count_p;?>][feature_points]" value="<?php echo $value['feature_points'];?>" step="0.1"/>
                <a href="javascript:void(0)" class="delete-review-points button">Delete</a>
            </div>
            <?php
                    } 
                }
            ?>
            </div>
            <input id="post_points_review_count" type="hidden" name="points_review_count" value="<?php echo $count_p; ?>" />
            <a href="javascript:void(0)" class="docopy-review_points button">Add rating category</a>    
    </div>
    
    <div class="review_desc">
        <div><strong><?php _e( 'Review description:', 'accesspress-mag' );?></strong></div>
        <p class="apmag_help_section">
            <textarea style="width: 500px; height: 100px;" type="text" name="product_rate_description"><?php if(!empty($accesspress_mag_rate_description)){echo $accesspress_mag_rate_description;} ?></textarea>
        </p>
    </div>

</div>
<?php     
}

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */

/*--------------------Save function for product review-------------------------*/

function accesspress_mag_save_product_review( $post_id ) { 
    global  $post;
    
    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'accesspress_mag_product_review_nonce' ] ) || !wp_verify_nonce( $_POST[ 'accesspress_mag_product_review_nonce' ], basename( __FILE__ ) ) )
        return;

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
        return;
        
    if ( 'page' == $_POST[ 'post_type' ] ) {  
        if (!current_user_can( 'edit_page', $post_id ) )  
            return $post_id;  
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {  
            return $post_id;  
    } 
    
    //Execute this saving function
    $accesspress_mag_allowed_textarea = array(
                                'a' => array(
                                    'href' => array(),
                                    'title' => array()
                                ),
                                'br' => array(),
                                'em' => array(),
                                'strong' => array(),
                            );
    $post_review_option = get_post_meta( $post->ID, 'product_review_option', true ); 
    
    /*----Star value------*/
    $post_star_feature_rate_name = get_post_meta( $post->ID, 'star_rating_feature_name', true );
    $star_rating = get_post_meta( $post->ID, 'star_rating', true );
    $star_review_count = get_post_meta( $post->ID, 'star_review_count', true );
    /*----Percent value----*/
    $post_percent_feature_name = get_post_meta( $post->ID, 'percent_ratings_feature_name', true );
    $post_percent_rating = get_post_meta( $post->ID, 'percent_rating', true );
    $percent_review_count = get_post_meta( $post->ID, 'percent_review_count', true );
    
    /*-------Points value--------*/
    $post_points_feature_name = get_post_meta( $post->ID, 'points_ratings_feature_name', true );
    $post_points_rating = get_post_meta( $post->ID, 'points_rating', true );
    $points_review_count = get_post_meta( $post->ID, 'points_review_count', true );
    
    $post_rate_description = get_post_meta( $post->ID, 'product_rate_description', true );
    
    $stz_review_option = sanitize_text_field( $_POST[ 'product_review_option' ] );
    /*----Star sanitize------*/
    $stz_star_rating = $_POST['star_ratings'];
    $stz_star_feature_rate_name = sanitize_text_field( $_POST[ 'star_rating_feature_name' ] );
    $stz_star_review_count = sanitize_text_field( $_POST[ 'star_review_count' ] );
    
    /*-----Percent Sanitize--------*/
    $stz_percent_rating =  $_POST['percent_ratings'];
    $stz_percent_feature_name = sanitize_text_field( $_POST[ 'percent_rating_feature_name' ] );
    $stz_percent_review_count = sanitize_text_field( $_POST[ 'percent_review_count' ] );
    
    /*-----Points Sanitize--------*/
    $stz_points_rating =  $_POST['points_ratings'];
    $stz_points_feature_name = sanitize_text_field( $_POST[ 'points_rating_feature_name' ] );
    $stz_points_review_count = sanitize_text_field( $_POST[ 'points_review_count' ] );
    
    $stz_rate_description = wp_kses( $_POST[ 'product_rate_description' ], $accesspress_mag_allowed_textarea );
    
    
        //if ( $product_rating && '' == $product_rating ){
        //    add_post_meta( $post_id, 'product_rating', $stz_product_rating );
        //}elseif ($product_rating && $stz_product_rating != $product_rating) {
            update_post_meta($post_id, 'star_rating', $stz_star_rating);
            
            update_post_meta($post_id, 'percent_rating', $stz_percent_rating);
            
            update_post_meta($post_id, 'points_rating', $stz_points_rating);
              
        //} elseif ('' == $stz_product_rating && $product_rating) {  
        //delete_post_meta($post_id,'product_rating');  
        //}

        
         //update data for Review Option 
        if ( $stz_review_option && '' == $stz_review_option ){
            add_post_meta( $post_id, 'product_review_option', $stz_review_option );
        }elseif ($stz_review_option && $stz_review_option != $post_review_option) {  
            update_post_meta($post_id, 'product_review_option', $stz_review_option);  
        } elseif ('' == $stz_review_option && $post_review_option) {  
            delete_post_meta($post_id,'product_review_option', $post_review_option);  
        }
                
        //update data for star count
        if ( $stz_star_review_count && '' == $stz_star_review_count ){
            add_post_meta( $post_id, 'star_review_count', $stz_star_review_count );
        }elseif ($stz_star_review_count && $stz_star_review_count != $star_review_count) {  
            update_post_meta($post_id, 'star_review_count', $stz_star_review_count);  
        } elseif ('' == $stz_star_review_count && $star_review_count) {  
            delete_post_meta($post_id,'star_review_count');  
        }
        
        //update data for Star review feature name
        if ( $stz_star_feature_rate_name && '' == $stz_star_feature_rate_name ){
            add_post_meta( $post_id, 'star_rating_feature_name', $stz_star_feature_rate_name );
        }elseif ($stz_star_feature_rate_name && $stz_star_feature_rate_name != $post_star_feature_rate_name) {  
            update_post_meta($post_id, 'star_rating_feature_name', $stz_star_feature_rate_name);  
        } elseif ('' == $stz_star_feature_rate_name && $post_star_feature_rate_name) {  
            delete_post_meta($post_id,'star_rating_feature_name', $post_star_feature_rate_name);  
        }
        
        /*//update data for star rating
        if ( $stz_star_rate_value && '' == $stz_star_rate_value ){
            add_post_meta( $post_id, 'star_rate_value', $stz_star_rate_value );
        }elseif ($stz_star_rate_value && $stz_star_rate_value != $post_star_rate_value) {  
            update_post_meta($post_id, 'star_rate_value', $stz_star_rate_value);  
        } elseif ('' == $stz_star_rate_value && $post_star_rate_value) {  
            delete_post_meta($post_id,'star_rate_value', $post_star_rate_value);  
        }*/
        
        //update data for percent count
        if ( $stz_percent_review_count && '' == $stz_percent_review_count ){
            add_post_meta( $post_id, 'percent_review_count', $stz_percent_review_count );
        }elseif ($stz_percent_review_count && $stz_percent_review_count != $percent_review_count) {  
            update_post_meta($post_id, 'percent_review_count', $stz_percent_review_count);  
        } elseif ('' == $stz_percent_review_count && $percent_review_count) {  
            delete_post_meta($post_id,'percent_review_count');  
        }
                         
        //update data for Percent rating feature name
        if ( $stz_percent_feature_name && '' == $stz_percent_feature_name ){
            add_post_meta( $post_id, 'percent_rating_feature_name', $stz_percent_feature_name );
        }elseif ($stz_percent_feature_name && $stz_percent_feature_name != $post_percent_feature_name) {  
            update_post_meta($post_id, 'percent_rating_feature_name', $stz_percent_feature_name);  
        } elseif ('' == $stz_percent_feature_name && $post_percent_feature_name) {  
            delete_post_meta($post_id,'percent_rating_feature_name', $post_percent_feature_name);  
        }
        
        //update data for points count
        if ( $stz_points_review_count && '' == $stz_points_review_count ){
            add_post_meta( $post_id, 'points_review_count', $stz_points_review_count );
        }elseif ($stz_points_review_count && $stz_points_review_count != $points_review_count) {  
            update_post_meta($post_id, 'points_review_count', $stz_points_review_count);  
        } elseif ('' == $stz_points_review_count && $points_review_count) {  
            delete_post_meta($post_id,'points_review_count');  
        }
                         
        //update data for point rating feature name
        if ( $stz_points_feature_name && '' == $stz_points_feature_name ){
            add_post_meta( $post_id, 'points_rating_feature_name', $stz_points_feature_name );
        }elseif ($stz_points_feature_name && $stz_points_feature_name != $post_points_feature_name) {  
            update_post_meta($post_id, 'points_rating_feature_name', $stz_points_feature_name);  
        } elseif ('' == $stz_points_feature_name && $post_points_feature_name) {  
            delete_post_meta($post_id,'points_rating_feature_name', $post_points_feature_name);  
        }
        
        
        //update data for Reveiw descriptions
        if ( $stz_rate_description && '' == $stz_rate_description ){
            add_post_meta( $post_id, 'product_rate_description', $stz_rate_description );
        }elseif ($stz_rate_description && $stz_rate_description != $post_rate_description) {  
            update_post_meta($post_id, 'product_rate_description', $stz_rate_description);  
        } elseif ('' == $stz_rate_description && $post_rate_description) {  
            delete_post_meta($post_id,'product_rate_description', $post_rate_description);  
        }
    }
add_action('save_post', 'accesspress_mag_save_product_review');