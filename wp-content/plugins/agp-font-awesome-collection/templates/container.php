<?php
if (!empty($params) && !empty($params['shortcode'])) :
    $id = !empty($params['id']) ? $params['id'] : 'fac_' . uniqid();
    $text_align = !empty($params['text_align']) ? $params['text_align'] : '';
    $shortcode = !empty($params['shortcode']) ? $params['shortcode'] : '';
?>
<style>
    #<?php echo $id;?> {
        <?php if (!empty($text_align)): echo "text-align: $text_align!important;"; endif;?> 
        display: block!important; 
        width: 100%!important;
    }
</style>

<span id="<?php echo $id;?>">
    <?php echo do_shortcode("[$shortcode]"); ?>
</span>
<?php
endif;
