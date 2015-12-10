<?php
/**
 * Template Name: Home Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package AccessPress Mag
 */

get_header();
?>

    <?php
        $accesspress_mag_theme_option = get_option( 'accesspress-mag-theme' );
        $slider_option = of_get_option( 'slider_option', '1' );
        if( $slider_option == '1' && $accesspress_mag_theme_option !='' ){
    ?>
        <section class="slider-wrapper">
            <div class="apmag-container"> 
                <?php
                    if( wp_is_mobile() ) {
                        do_action('accesspress_mag_slider_mobile');
                    } else {
                        do_action('accesspress_mag_slider');
                    }
                ?>
            </div>                  
        </section>
    <?php 
        } 
    ?>
    <div class="apmag-container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
              <section class="first-block wow fadeInUp clearfix" data-wow-delay="0.5s">
                    <?php 
                        $block1_cat = of_get_option( 'featured_block_1' );
                        if( !empty( $block1_cat ) ):
                                $posts_for_block1 = of_get_option( 'posts_for_block1' );                                
                                $category_info = get_category_by_slug( $block1_cat );
                    ?>
                            <div class="first-block-wrapper">
                                <h3 class="block-cat-name"> 
                                    <span><?php echo esc_attr( $category_info->name ) ; ?></span>
                                </h3>
                            <div class="block-post-wrapper clearfix">
                    <?php
                                //echo '<div class="first-block-wrapper">';
                                //echo '<h3 class="block-cat-name"> <span> '.esc_attr( $category_info->name ).'</span></h3>';                            
                                //echo '<div class="block-post-wrapper clearfix">';
                                $block1_args = array(
                                                    'category_name' => $block1_cat,
                                                    'post_status' => 'publish',
                                                    'posts_per_page' => $posts_for_block1,
                                                    'order' => 'DESC'
                                                    );
                            $block1_query = new WP_Query( $block1_args );
                            $b_counter = 0;
                            $total_posts_block1 = $block1_query->found_posts;
                            if( $block1_query->have_posts() ){
                                while( $block1_query->have_posts() ){
                                    $b_counter++;
                                    $block1_query->the_post();
                                    $b1_image_id = get_post_thumbnail_id();
                                    $b1_big_image_path = wp_get_attachment_image_src( $b1_image_id, 'accesspress-mag-block-big-thumb', true );
                                    $b1_small_image_path = wp_get_attachment_image_src( $b1_image_id, 'accesspress-mag-block-small-thumb', true );
                                    $b1_image_alt = get_post_meta( $b1_image_id, '_wp_attachment_image_alt', true );
                    ?>
                        <?php if( $b_counter==1 ){echo '<div class="toppost-wrapper">';} if( $b_counter > 2 && $b_counter==3 ){ echo '<div class="bottompost-wrapper">'; }?>
                        <div class="single_post clearfix <?php if($b_counter <= 2){echo 'top-post non-zoomin';}?>">
                            <?php if(has_post_thumbnail()): ?>   
                                <div class="post-image"><a href="<?php the_permalink();?>"><img src="<?php if( $b_counter <= 2 ){echo esc_url( $b1_big_image_path[0] );}else{ echo esc_url( $b1_small_image_path[0] ) ;}?>" alt="<?php echo esc_attr($b1_image_alt);?>" /></a>
                                    <?php if( $b_counter <= 2 ):?> <a class="big-image-overlay" href="<?php the_permalink();?>"><i class="fa fa-external-link"></i></a><?php endif ;?>
                                </div>                                
                            <?php endif ; ?>
                                <div class="post-desc-wrapper">
                                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                                    <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                                </div>
                                <?php if( $b_counter <= 2 ):?><div class="post-content"><?php echo '<p>'. accesspress_mag_word_count( get_the_content(), 25 ) .'</p>' ;?></div><?php endif ;?>
                        </div>
                        <?php 
                            if( $b_counter %2 == 0 ){ echo '<div class="clearfix"></div>'; }
                            if( $b_counter > 2 && $b_counter == $total_posts_block1 ){ echo '</div>'; }
                            if( $b_counter ==2 ){ echo '</div>'; }
                        ?>
                    <?php
                                }
                            }
                    ?>
                            </div>
                            </div>
                    <?php
                            //echo '</div>';
                            //echo '</div>';
                        endif;
                        wp_reset_query();
                    ?>
              </section>
                    
              <section class="second-block clearfix wow fadeInLeft" data-wow-delay="0.5s">
                    <?php 
                        $block2_cat = of_get_option('featured_block_2');
                        if(!empty($block2_cat)):
                            $posts_for_block2 = of_get_option('posts_for_block2');
                            $category_info_2 = get_category_by_slug($block2_cat);
                    ?>
                            <div class="second-block-wrapper">
                                <h3 class="block-cat-name"> 
                                    <span><?php echo esc_attr($category_info_2->name); ?></span>
                                </h3>
                            <div class="block-post-wrapper clearfix">
                    <?php
                            /*echo '<div class="second-block-wrapper">';
                            echo '<h3 class="block-cat-name"> <span>'.esc_attr($category_info_2->name).' </span></h3>'; 
                            echo '<div class="block-post-wrapper clearfix">';*/                           
                            $block2_args = array(
                                                'category_name'=>$block2_cat,
                                                'post_status'=>'publish',
                                                'posts_per_page'=>$posts_for_block2,
                                                'order'=>'DESC'
                                                );
                            $block2_query = new WP_Query($block2_args);
                            $b_counter = 0;
                            $total_posts_block2 = $block2_query->found_posts;
                            if($block2_query->have_posts()){
                                while($block2_query->have_posts()){
                                    $b_counter++;
                                    $block2_query->the_post();
                                    $b2_image_id = get_post_thumbnail_id();
                                    $b2_big_image_path = wp_get_attachment_image_src($b2_image_id,'accesspress-mag-block-big-thumb',true);
                                    $b2_small_image_path = wp_get_attachment_image_src($b2_image_id,'accesspress-mag-block-small-thumb',true);
                                    $b2_image_alt = get_post_meta($b2_image_id,'_wp_attachment_image_alt',true);
                    ?>
                                <?php if($b_counter==1){echo '<div class="leftposts-wrapper">';} if($b_counter>1 && $b_counter==2){echo '<div class="rightposts-wrapper">';}?>
                                <div class="single_post clearfix <?php if($b_counter==1){echo 'first-post non-zoomin';}?>">
                                    <?php if(has_post_thumbnail()): ?>   
                                        <div class="post-image"><a href="<?php the_permalink();?>"><img src="<?php if($b_counter <=1){echo esc_url( $b2_big_image_path[0] );}else{ echo esc_url( $b2_small_image_path[0] ) ;}?>" alt="<?php echo esc_attr($b2_image_alt);?>" /></a>
                                            <?php if($b_counter==1):?> <a class="big-image-overlay" href="<?php the_permalink();?>"><i class="fa fa-external-link"></i></a><?php endif ;?>
                                        </div>                                
                                            
                                    <?php endif ; ?>
                                        <div class="post-desc-wrapper">
                                            <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                                            <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                                        </div>
                                        <?php if($b_counter ==1 ):?><div class="post-content"><?php echo '<p>'. accesspress_mag_word_count( get_the_content(), 25 ) .'</p>' ;?></div><?php endif ;?>
                                </div>
                                <?php if($b_counter==1){echo '</div>';} if($b_counter>1 && $b_counter==$total_posts_block2){echo '</div>';}?>                    
                    <?php                    
                                }
                            }
                    ?>
                                </div>
                                </div>
                    <?php
                            /*echo '</div>';
                            echo '</div>';*/
                            endif ;
                    ?>
              </section>

            <?php 
                if ( is_active_sidebar( 'accesspress-mag-homepage-inline-ad' ) ) : ?>
                <div class="homepage-middle-ad wow flipInX" data-wow-delay="1s">
                    <?php dynamic_sidebar( 'accesspress-mag-homepage-inline-ad' ); ?> 
                </div>
            <?php endif; ?>

              <section class="third-block clearfix wow fadeInUp" data-wow-delay="0.5s">
                    <?php 
                        $block3_cat = of_get_option('featured_block_3');
                        if(!empty($block3_cat)):
                                $posts_for_block3 = of_get_option('posts_for_block3');
                                $category_info_3 = get_category_by_slug($block3_cat);
                    ?>
                                <div class="first-block-wrapper">
                                    <h3 class="block-cat-name">
                                        <span><?php echo esc_attr($category_info_3->name); ?></span>
                                    </h3>
                                <div class="block-post-wrapper clearfix">
                    <?php
                                /*echo '<div class="first-block-wrapper">';
                                echo '<h3 class="block-cat-name"><span>'.esc_attr($category_info_3->name).'</span></h3>';
                                echo '<div class="block-post-wrapper clearfix">';*/
                                $block3_args = array(
                                                    'category_name'=>$block3_cat,
                                                    'post_status'=>'publish',
                                                    'posts_per_page'=>$posts_for_block3,
                                                    'order'=>'DESC'
                                                    );
                            $block3_query = new WP_Query($block3_args);
                            $b_counter = 0;
                            $total_posts_block3 = $block3_query->found_posts;
                            if($block3_query->have_posts()){
                                while($block3_query->have_posts()){
                                    $b_counter++;
                                    $block3_query->the_post();
                                    $b3_image_id = get_post_thumbnail_id();
                                    $b3_big_image_path = wp_get_attachment_image_src($b3_image_id,'accesspress-mag-block-big-thumb',true);
                                    $b3_small_image_path = wp_get_attachment_image_src($b3_image_id,'accesspress-mag-block-small-thumb',true);
                                    $b3_image_alt = get_post_meta($b3_image_id,'_wp_attachment_image_alt',true);
                    ?>
                        <?php if($b_counter==1){echo '<div class="toppost-wrapper">';} if($b_counter> 2 && $b_counter==3){echo '<div class="bottompost-wrapper">';}?>
                        <div class="single_post clearfix <?php if($b_counter <= 2){echo 'top-post non-zoomin';}?>">
                            <?php if(has_post_thumbnail()): ?>   
                                <div class="post-image"><a href="<?php the_permalink();?>"><img src="<?php if($b_counter <=2){echo esc_url( $b3_big_image_path[0] );}else{ echo esc_url( $b3_small_image_path[0] ) ;}?>" alt="<?php echo esc_attr($b3_image_alt);?>" /></a>
                                    <?php if($b_counter<=2):?> <a class="big-image-overlay" href="<?php the_permalink();?>"><i class="fa fa-external-link"></i></a><?php endif ;?>
                                </div>                               
                            <?php endif ; ?>
                                <div class="post-desc-wrapper">
                                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                                    <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                                </div>
                                <?php if($b_counter <=2 ):?><div class="post-content"><?php echo '<p>'. accesspress_mag_word_count( get_the_content(), 25) .'</p>' ;?></div><?php endif ;?>
                        </div>
                        <?php 
                            if($b_counter%2==0){echo '<div class="clearfix"></div>';}
                            if($b_counter >2 && $b_counter==$total_posts_block3){echo '</div>';}
                            if($b_counter==2){echo '</div>';}
                        ?>
                    <?php
                                }
                            }
                    ?>
                                </div>
                                </div>
                    <?php
                            /*echo '</div>';
                            echo '</div>';*/
                        endif;
                    ?>
              </section>
              
              <section class="forth-block clearfix wow fadeInRight" data-wow-delay="0.5s">
                    <?php 
                        $block4_cat = of_get_option('featured_block_4');
                        if(!empty($block4_cat)):
                            $posts_for_block4 = of_get_option('posts_for_block4');
                            $category_info_4 = get_category_by_slug($block4_cat);
                    ?>
                            <div class="second-block-wrapper">
                                <h3 class="block-cat-name">
                                    <span><?php echo esc_attr($category_info_4->name);?></span>
                                </h3>
                            <div class="block-post-wrapper clearfix">
                    <?php
                            /*echo '<div class="second-block-wrapper">';
                            echo '<h3 class="block-cat-name"><span>'.esc_attr($category_info_4->name).'</span></h3>';                            
                            echo '<div class="block-post-wrapper clearfix">';*/
                            $block4_args = array(
                                                'category_name'=>$block4_cat,
                                                'post_status'=>'publish',
                                                'posts_per_page'=>$posts_for_block4,
                                                'order'=>'DESC'
                                                );
                            $block4_query = new WP_Query($block4_args);
                            $b_counter = 0;
                            $total_posts_block4 = $block4_query->found_posts;
                            if($block4_query->have_posts()){
                                while($block4_query->have_posts()){
                                    $b_counter++;
                                    $block4_query->the_post();
                                    $b4_image_id = get_post_thumbnail_id();
                                    $b4_big_image_path = wp_get_attachment_image_src($b4_image_id,'accesspress-mag-block-big-thumb',true);
                                    $b4_image_alt = get_post_meta($b4_image_id,'_wp_attachment_image_alt',true);
                    ?>
                                <div class="single_post non-zoomin clearfix">
                                    <?php if(has_post_thumbnail()): ?>   
                                        <div class="post-image">
                                            <a href="<?php the_permalink();?>"><img src="<?php echo esc_url( $b4_big_image_path[0] );?>" alt="<?php echo esc_attr($b4_image_alt);?>" /></a>
                                            <a class="big-image-overlay" href="<?php the_permalink();?>"><i class="fa fa-external-link"></i></a>
                                        </div>                                
                                    <?php endif ; ?>
                                        <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                                        <div class="block-poston"><?php do_action('accesspress_mag_home_posted_on');?></div>
                                </div>
                                <?php if($b_counter%2==0){echo '<div class="clearfix"></div>';}?>                    
                    <?php                    
                                }
                            }
                    ?>
                                </div>
                                </div>
                    <?php
                            /*echo '</div>';
                            echo '</div>';*/
                            endif ;
                    ?>
              </section>      			
		</main><!-- #main -->
	</div><!-- #primary -->
<?php 
wp_reset_query();
$page_sidebar = get_post_meta( $post -> ID, 'accesspress_mag_page_sidebar_layout', true);
    if( $page_sidebar != 'no-sidebar' ){
        get_sidebar( 'home' );
    } 
?>
</div>
<?php get_footer(); ?>