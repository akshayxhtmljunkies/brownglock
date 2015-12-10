<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
global $apif_settings, $insta;
$apif_settings = get_option( 'apif_settings' );
$username = $apif_settings['username']; // your username
$access_token = $apif_settings['access_token'];
$image_like = $apif_settings['active'];
$count = 7; // number of images to show
require_once('instagram.php');
?>
<section id="main" class="thumb-view">
    <div class="row masonry for-mosaic isotope ifgrid">
        <?php
        $ins_media_masaic = $insta->userMedia();
        $i = 1;
        $j = 0;
        if(isset($ins_media['meta']['error_message'])){
            ?>
               <h1 class="widget-title-insta"><span><?php echo $ins_media['meta']['error_message']; ?></span></h1>
            <?php
        }else if (is_array($ins_media_masaic['data']) || is_object($ins_media_masaic['data'])) {
            foreach ($ins_media_masaic['data'] as $vm):
                if ($count == $j) {
                    break;
                }
                $j++;
                $img = $vm['images']['standard_resolution']['url'];
                ?>
                <?php
                if ($i <= 2 || $i == 6 || $i == 7) {
                    $masonary_class = 'grid-small';
                    $image_url = APIF_IMAGE_DIR . '/image-square.png';
                    $image = $vm['images']['low_resolution']['url'];
                } elseif ($i == 4 || $i == 5) {
                    $masonary_class = 'grid-medium';
                    $image_url = APIF_IMAGE_DIR . '/image-rect.png';
                    $image = $vm['images']['low_resolution']['url'];
                } elseif ($i == 3) {
                    $masonary_class = 'grid-large';
                    $image_url = APIF_IMAGE_DIR . '/image-square.png';
                    $image = $vm['images']['standard_resolution']['url'];
                }
                $link = $vm["link"];
                $flow_icon = APIF_IMAGE_DIR . '/sc-icon.png';
                ?>
                <div class="masonry_elem columns isotope-item element-itemif <?php echo esc_attr($masonary_class); ?>">
                    <div class="thumb-elem large-mosaic-elem small-mosaic-elem  hovermove large-mosaic-elem">
                        <header class="thumb-elem-header">
                            <div class="featimg">
                                <a class="example-image-link" href="<?php echo esc_url($img); ?>" data-lightbox="example-set">
                                    <img class="the-thumb" src="<?php echo esc_url($image); ?>">
                                    <img class="transparent-image" src="<?php echo esc_url($image_url); ?>">
                                </a>
                            </div>
                            <a href="<?php echo $link; ?>" target="_blank" class="image-hover">
                                <span class="follow">Follow Me</span>
                                <span class="follow_icon">
                                    <img src="<?php echo $flow_icon; ?>"/>
                                </span>
                            </a>
                        </header>
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
                </div>
                <?php
                $i++;
            endforeach;
        }
        ?>
    </div>
</section>
