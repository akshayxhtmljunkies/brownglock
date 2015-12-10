<?php
/**
 * Gallery section for every single page
 * 
 * @package Accesspress Mag Pro
 */
 
global $post;
$image_url = get_post_meta( $post -> ID, 'post_images', true );
if( !empty( $image_url ) )
{
?>
<div class="post_gallery">
    <ul class="gallery-slider">
        <?php
            foreach( $image_url as $gallery_image ){                                               
               $attachment_id = accesspress_mag_get_attachment_id_from_url( $gallery_image );
               $img_url = wp_get_attachment_image_src( $attachment_id, 'singlepost-large' ); 
        ?>
            <li><img src="<?php echo $img_url[0]; ?>" /></li>
        <?php } ?>
    </ul>
</div>
<?php 
} 
?>