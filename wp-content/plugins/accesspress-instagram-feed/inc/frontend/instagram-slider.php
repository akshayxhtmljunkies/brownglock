<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
global $apif_settings, $insta;
    $apif_settings = get_option( 'apif_settings' );
    $username = $apif_settings['username']; // your username
    $access_token = $apif_settings['access_token'];
    $image_like = $apif_settings['active'];
    $count = 10; // number of images to show
    require_once('instagram.php');
    ?>
    <div id="owl-demo" class="owl-carousel">
        <?php
        $ins_media_slider = $insta->userMedia();
        $j = 0;
        if(isset($ins_media['meta']['error_message'])){
            ?>
               <h1 class="widget-title-insta"><span><?php echo $ins_media['meta']['error_message']; ?></span></h1> 
            <?php
        }else if (is_array($ins_media_slider['data']) || is_object($ins_media_slider['data'])) {
            foreach ($ins_media_slider['data'] as $vm):
                if ($count == $j) {
                    break;
                }
                $j++;
                $imgslider = $vm['images']['standard_resolution']['url'];
                ?>
                <div class="item">
                    <img src="<?php echo esc_url($imgslider); ?>" />
                    <?php if ($image_like == '1') : ?>
                        <!-- Image like cound section start -->
                        <span class="instagram_like_count">
                            <p class="instagram_imge_like">
                                <span class="insta like_image">
                                    <i class="fa fa-heart-o fa-2x"></i>
                                </span>
                                <span class="count"><?php echo $likes = $vm['likes']['count']; ?></span>
                            </p>
                        </span>
                        <!-- Image like cound section end -->
                    <?php endif; ?>
                </div>

                <?php
            endforeach;
        }
        ?>
    </div>
