<?php
if (!empty($params) && !empty($params['icon'])) :
    $id = !empty($params['id']) ? $params['id'] : 'fac_' . uniqid();
    $icon = !empty($params['icon']) ? $params['icon'] : '';
    $text = !empty($params['text']) ? $params['text'] : '';
    $shape_type = !empty($params['shape_type']) && in_array($params['shape_type'], array('square','rounded','round')) ? $params['shape_type'] : '';
    $shape_bg = !empty($params['shape_bg']) ? $params['shape_bg'] : '';
    $shape_bg_hover = !empty($params['shape_bg_hover']) ? $params['shape_bg_hover'] : '';
    $icon_color = !empty($params['icon_color']) ? $params['icon_color'] : '';    
    $icon_color_hover = !empty($params['icon_color_hover']) ? $params['icon_color_hover'] : '';    
    $text_color = !empty($params['text_color']) ? $params['text_color'] : '';    
    $text_color_hover = !empty($params['text_color_hover']) ? $params['text_color_hover'] : '';    
?>
<style>
    #<?php echo $id;?> .fac-shape {
        <?php if (!empty($icon_color)): echo "color: $icon_color!important;"; endif;?>
        <?php if (!empty($shape_bg)): echo "background: $shape_bg!important;"; endif;?>
    }
    #<?php echo $id;?> .fac-shape:hover {
        <?php if (!empty($icon_color_hover)): echo "color: $icon_color_hover!important;"; endif;?>
        <?php if (!empty($shape_bg_hover)): echo "background: $shape_bg_hover!important;"; endif;?>
    }    
    #<?php echo $id;?> .fac-text {
        <?php if (!empty($text_color)): echo "color: $text_color!important;"; endif;?>
    }
    #<?php echo $id;?> .fac-text:hover {
        <?php if (!empty($text_color_hover)): echo "color: $text_color_hover!important;"; endif;?>
    }    
</style>
<div id="<?php echo $id;?>" class="fac fac-icontext-template">
    <span <?php echo !empty($shape_type) ? ' class="fac-shape fac-' . $shape_type . '"' : '';?>>
        <i class="fa fa-<?php echo $icon?>"></i>
    </span>
    <?php echo (!empty($text)) ? '<span class="fac-text">' . $text . '</span>' : '';?>
</div>
<?php
endif;
