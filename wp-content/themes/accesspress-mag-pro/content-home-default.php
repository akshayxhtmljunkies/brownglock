<?php
/**
 * Default layout for Home page
 * 
 * @package Accesspress Mag Pro
 */
 
$cat_link_option = of_get_option( 'home_cat_link', '' );
$fallback_image_option = of_get_option( 'fallback_image_option', '1' );
$fallback_image = of_get_option( 'fallback_image', get_template_directory_uri(). '/images/no-image.jpg' );
$apmag_overlay_icon = of_get_option( 'apmag_overlay_icon', 'fa-external-link' );
?>

<section class="first-block wow fadeInUp clearfix" data-wow-delay="0.5s">
    <?php 
        $block1_cat = of_get_option( 'featured_block_1' );
        if( !empty( $block1_cat ) ):
                $posts_for_block1 = of_get_option( 'posts_for_block1' );                                
                $category_info = get_category_by_slug( $block1_cat );
                $category_id = $category_info->cat_ID;
                $category_link = get_category_link( $category_id );
                $category_name = $category_info->name;
     ?>
                <div class="first-block-wrapper">
                    <h2 class="block-cat-name"><span>
                        <?php 
                            if ( $cat_link_option == 1 && !empty( $cat_link_option ) ) {
                        ?>
                            <a href="<?php echo esc_url( $category_link )?>" title="<?php echo esc_attr( $category_name );?>"><?php echo esc_attr( $category_name );?></a>
                        <?php
                            } else {
                                echo esc_attr( $category_name );
                            } 
                        ?>
                    </span></h2>
                    <div class="block-post-wrapper clearfix">
     <?php      
            $block1_args = array(
                                'post_type' => 'post',
                                'category_name' => $block1_cat,
                                'post_status' => 'publish',
                                'posts_per_page' => $posts_for_block1,
                                'order' => 'DESC'
                                );
            $block1_query = new WP_Query( $block1_args );
            $b_counter = 0;
            $total_posts_block1 = $block1_query->found_posts;
            if( $block1_query->have_posts() ) {
                while( $block1_query->have_posts() ) {
                    $b_counter++;
                    $block1_query->the_post();
                    $b1_image_id = get_post_thumbnail_id();
                    $b1_big_image_path = wp_get_attachment_image_src( $b1_image_id, 'accesspress-mag-block-big-thumb', true );
                    $b1_small_image_path = wp_get_attachment_image_src( $b1_image_id, 'accesspress-mag-block-small-thumb', true );
                    $b1_image_alt = get_post_meta( $b1_image_id, '_wp_attachment_image_alt', true );
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
                    if( $b_counter == 1 ){ echo '<div class="toppost-wrapper clearfix">'; } 
                    if( $b_counter > 2 && $b_counter == 3 ){ echo '<div class="bottompost-wrapper">'; }
    ?>
        <div class="single_post clearfix <?php if( $b_counter <= 2 ){ echo 'top-post non-zoomin'; }?>">
            <?php if( has_post_thumbnail() ){ ?>   
                <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php if( $b_counter <=2 ){echo esc_url( $b1_big_image_path[0] ) ;}else{ echo esc_url( $b1_small_image_path[0] );}?>" alt="<?php echo esc_attr( $b1_image_alt );?>" /></a>
                    <?php if( $b_counter <= 2 ):?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php endif ;?>
                    <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $b_counter > 2 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                </div>                                
            <?php } else {
                        if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
            ?>
                <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php _e( 'Fallback Image',  'accesspress-mag' ); ?>" /></a>
                    <?php if( $b_counter <= 2 ):?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php endif ;?>
                    <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $b_counter > 2 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                </div>
            <?php   
                        }
                     }   
            ?>
                <div class="post-desc-wrapper">
                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                    <div class="ratings-wrapper"><?php do_action('accesspress_mag_post_review');?></div>
                    <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>                                    
                </div>
                <?php if( $b_counter <= 2 ):?><div class="post-content"><?php accesspress_mag_homepage_excerpt(); ?></div><?php endif ;?>
        </div>
    <?php 
            if( $b_counter > 2 && $b_counter == $total_posts_block1 ){ echo '</div>'; }
            if( $b_counter == 2){ echo '</div>'; }
                }
            }
    ?>
            </div>
        </div>
    <?php
        endif;
        wp_reset_query();
    ?>
</section>
    
<section class="second-block clearfix wow fadeInLeft" data-wow-delay="0.5s">
    <?php 
        $block2_cat = of_get_option( 'featured_block_2' );
        if( !empty( $block2_cat ) ):
            $posts_for_block2 = of_get_option( 'posts_for_block2' );
            $category_info_2 = get_category_by_slug( $block2_cat );
            $category_id = $category_info_2->cat_ID;
            $category_link = get_category_link( $category_id );
            $category_name = $category_info_2->name;
    ?>
            <div class="second-block-wrapper">
                <h2 class="block-cat-name"><span>
                    <?php 
                        if ( $cat_link_option == 1 && !empty( $cat_link_option ) ) {
                    ?>
                        <a href="<?php echo esc_url( $category_link )?>" title="<?php echo esc_attr( $category_name );?>"><?php echo esc_attr( $category_name );?></a>
                    <?php
                        } else {
                            echo esc_attr( $category_name );
                        } 
                    ?>
                </span></h2>
                <div class="block-post-wrapper clearfix">
    <?php                         
            $block2_args = array(
                                'post_type' => 'post',
                                'category_name' => $block2_cat,
                                'post_status' => 'publish',
                                'posts_per_page' => $posts_for_block2,
                                'order' => 'DESC'
                                );
            $block2_query = new WP_Query( $block2_args );
            $b_counter = 0;
            $total_posts_block2 = $block2_query->found_posts;
            if( $block2_query->have_posts() ) {
                while( $block2_query->have_posts() ) {
                    $b_counter++;
                    $block2_query->the_post();
                    $b2_image_id = get_post_thumbnail_id();
                    $b2_big_image_path = wp_get_attachment_image_src( $b2_image_id, 'accesspress-mag-block-big-thumb', true );
                    $b2_small_image_path = wp_get_attachment_image_src( $b2_image_id, 'accesspress-mag-block-small-thumb', true );
                    $b2_image_alt = get_post_meta( $b2_image_id, '_wp_attachment_image_alt', true );
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
                    if( $b_counter == 1 ){ echo '<div class="leftposts-wrapper">'; } 
                    if( $b_counter > 1 && $b_counter == 2 ){ echo '<div class="rightposts-wrapper">'; }
    ?>
                <div class="single_post clearfix <?php if( $b_counter == 1 ){ echo 'first-post non-zoomin'; }?>">
                    <?php if( has_post_thumbnail() ) { ?>   
                        <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php if( $b_counter <=1 ){ echo esc_url( $b2_big_image_path[0] ); }else{ echo esc_url( $b2_small_image_path[0] ) ;}?>" alt="<?php echo esc_attr( $b2_image_alt );?>" /></a>
                            <?php if( $b_counter == 1 ):?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php endif ;?>
                            <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $b_counter > 1 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>    
                        </div>
                    <?php } else {
                            if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
                    ?>
                        <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php _e( 'Fallback Image',  'accesspress-mag' ); ?>" /></a>
                            <?php if( $b_counter == 1 ):?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php endif ;?>
                            <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $b_counter > 1 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>    
                        </div>
                    <?php
                                }
                            } 
                    ?>
                        <div class="post-desc-wrapper">
                            <?php if( $b_counter == 1 ) { ?>
                            <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                            <?php } else { ?>
                            <h4 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                            <?php } ?>
                            <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                            <div class="ratings-wrapper"><?php do_action( 'accesspress_mag_post_review' );?></div>
                        </div>
                        <?php if( $b_counter == 1 ):?><div class="post-content"><?php accesspress_mag_homepage_excerpt(); ?></div><?php endif ;?>
                </div>
    <?php 
            if( $b_counter == 1 ){ echo '</div>'; } 
            if( $b_counter > 1 && $b_counter == $total_posts_block2 ){ echo '</div>'; }
                }
            }
    ?>
                </div>
            </div>
    <?php
            endif ;
            wp_reset_query();
    ?>
</section>

<?php 
    $home_inline_ad = of_get_option( 'value_homepage_inline_ad' );
    if( !empty( $home_inline_ad ) ) {
        echo '<div class="homepage-middle-ad wow flipInX" data-wow-delay="1s">'.$home_inline_ad.'</div>';                        
    }
?> 

<section class="third-block clearfix wow fadeInUp" data-wow-delay="0.5s">
    <?php 
        $block3_cat = of_get_option( 'featured_block_3' );
        if(!empty($block3_cat)):
                $posts_for_block3 = of_get_option( 'posts_for_block3' );
                $category_info_3 = get_category_by_slug( $block3_cat );
                $category_id = $category_info_3->cat_ID;
                $category_link = get_category_link( $category_id );
                $category_name = $category_info_3->name;
    ?>
                <div class="first-block-wrapper">
                    <h2 class="block-cat-name"><span>
                        <?php 
                            if ( $cat_link_option == 1 && !empty( $cat_link_option ) ) {
                        ?>
                            <a href="<?php echo esc_url( $category_link )?>" title="<?php echo esc_attr( $category_name );?>"><?php echo esc_attr( $category_name );?></a>
                        <?php
                            } else {
                                echo esc_attr( $category_name );
                            } 
                        ?>
                    </span></h2>
                    <div class="block-post-wrapper clearfix">
    <?php
            $block3_args = array(
                                'post_type' => 'post',
                                'category_name' => $block3_cat,
                                'post_status' => 'publish',
                                'posts_per_page' => $posts_for_block3,
                                'order' => 'DESC'
                                );
            $block3_query = new WP_Query( $block3_args );
            $b_counter = 0;
            $total_posts_block3 = $block3_query->found_posts;
            if( $block3_query->have_posts() ){
                while( $block3_query->have_posts() ){
                    $b_counter++;
                    $block3_query->the_post();
                    $b3_image_id = get_post_thumbnail_id();
                    $b3_big_image_path = wp_get_attachment_image_src( $b3_image_id, 'accesspress-mag-block-big-thumb', true );
                    $b3_small_image_path = wp_get_attachment_image_src( $b3_image_id, 'accesspress-mag-block-small-thumb', true );
                    $b3_image_alt = get_post_meta( $b3_image_id, '_wp_attachment_image_alt', true );
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
                    if( $b_counter == 1 ){ echo '<div class="toppost-wrapper clearfix">'; } 
                    if( $b_counter > 2 && $b_counter == 3 ){ echo '<div class="bottompost-wrapper">'; }
    ?>
        <div class="single_post clearfix <?php if( $b_counter <= 2 ){ echo 'top-post non-zoomin'; }?>">
            <?php if( has_post_thumbnail() ) { ?>   
                <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php if( $b_counter <=2 ){ echo esc_url( $b3_big_image_path[0] ); }else{ echo esc_url( $b3_small_image_path[0] ); }?>" alt="<?php echo esc_attr( $b3_image_alt );?>" /></a>
                    <?php if($b_counter<=2):?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php endif ;?>
                    <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $b_counter > 2 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                </div>                               
            <?php } else {
                    if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
            ?>
                <div class="post-image"><a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php _e( 'Fallback Image',  'accesspress-mag' ); ?>" /></a>
                    <?php if($b_counter<=2):?> <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a><?php endif ;?>
                    <?php if( $show_icon == 'on' ){?><span class="format_icon <?php if( $b_counter > 2 ){ echo 'small'; }?>"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                </div> 
            <?php
                        }
                    } 
            ?>
                <div class="post-desc-wrapper">
                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                    <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                    <div class="ratings-wrapper"><?php do_action( 'accesspress_mag_post_review' );?></div>
                </div>
                <?php if($b_counter <=2 ):?><div class="post-content"><?php accesspress_mag_homepage_excerpt(); ?></div><?php endif ;?>
        </div>
        <?php
            if( $b_counter > 2 && $b_counter == $total_posts_block3 ){ echo '</div>'; }
            if( $b_counter == 2 ){ echo '</div>'; }
                }
            }
    ?>
                    </div>
                </div>
    <?php
        endif;
        wp_reset_query();
    ?>
</section>

<section class="forth-block clearfix wow fadeInRight" data-wow-delay="0.5s">
    <?php 
        $block4_cat = of_get_option( 'featured_block_4' );
        if( !empty( $block4_cat ) ):
            $posts_for_block4 = of_get_option( 'posts_for_block4' );
            $category_info_4 = get_category_by_slug( $block4_cat );
            $category_id = $category_info_4->cat_ID;
            $category_link = get_category_link( $category_id );
            $category_name = $category_info_4->name;
    ?>
            <div class="second-block-wrapper">
                <h2 class="block-cat-name"><span>
                    <?php 
                        if ( $cat_link_option == 1 && !empty( $cat_link_option ) ) {
                    ?>
                        <a href="<?php echo esc_url( $category_link )?>" title="<?php echo esc_attr( $category_name );?>"><?php echo esc_attr( $category_name );?></a>
                    <?php
                        } else {
                            echo esc_attr( $category_name );
                        } 
                    ?>
                </span></h2>
                <div class="block-post-wrapper clearfix">
    <?php
            $block4_args = array(
                                'post_type' => 'post',
                                'category_name' => $block4_cat,
                                'post_status' => 'publish',
                                'posts_per_page' => $posts_for_block4,
                                'order' => 'DESC'
                                );
            $block4_query = new WP_Query( $block4_args );
            $b_counter = 0;
            $total_posts_block4 = $block4_query->found_posts;
            if( $block4_query->have_posts() ) {
                while( $block4_query->have_posts() ) {
                    $b_counter++;
                    $block4_query->the_post();
                    $b4_image_id = get_post_thumbnail_id();
                    $b4_big_image_path = wp_get_attachment_image_src( $b4_image_id, 'accesspress-mag-block-big-thumb', true );
                    $b4_image_alt = get_post_meta( $b4_image_id, '_wp_attachment_image_alt', true );
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
                <div class="single_post non-zoomin clearfix">
                    <?php if(has_post_thumbnail()) { ?>   
                        <div class="post-image">
                            <a href="<?php the_permalink();?>"><img src="<?php echo esc_url( $b4_big_image_path[0] );?>" alt="<?php echo esc_attr($b4_image_alt);?>" /></a>
                            <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a>
                            <?php if( $show_icon == 'on' ){?><span class="format_icon"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                        </div>                                
                    <?php } else {
                            if( $fallback_image_option == 1 && !empty( $fallback_image ) ) {
                    ?>
                        <div class="post-image">
                            <a href="<?php the_permalink();?>"><img src="<?php echo esc_url( $fallback_image ); ?>" alt="<?php _e( 'Fallback Image',  'accesspress-mag' ); ?>" /></a>
                            <a class="big-image-overlay" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><i class="fa <?php echo esc_attr( $apmag_overlay_icon );?>"></i></a>
                            <?php if( $show_icon == 'on' ){?><span class="format_icon"><i class="fa <?php echo esc_attr( $post_format_icon );?>"></i></span><?php } ?>
                        </div> 
                    <?php
                                }
                            } 
                    ?>
                        <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                        <div class="block-poston"><?php do_action( 'accesspress_mag_home_posted_on' );?></div>
                        <div class="ratings-wrapper"><?php do_action( 'accesspress_mag_post_review' );?></div>
                </div>
    <?php                 
                }
            }
    ?>
                </div>
            </div>
    <?php
            endif ;
            wp_reset_query();
    ?>
</section>