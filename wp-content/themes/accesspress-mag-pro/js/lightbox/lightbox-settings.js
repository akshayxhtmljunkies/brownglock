/**
 *  Setting about lightbox
 * 
 * @package AccessPress Mag
 */

jQuery(document).ready(function($){
    /*Gallery item*/
       $('.gallery-item a').each(function() {
            $(this).addClass('fancybox-gallery').attr('data-lightbox-gallery', 'gallery');
        });
    
        $(".fancybox-gallery").nivoLightbox();
});