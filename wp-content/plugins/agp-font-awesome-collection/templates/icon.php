<?php
if (!empty($params) && !empty($params['icon'])) :
    $id = !empty($params['id']) ? $params['id'] : 'fac_' . uniqid();
    $icon = !empty($params['icon']) ? $params['icon'] : '';
    $color = !empty($params['color']) ? $params['color'] : '';
    $color_hover = !empty($params['color_hover']) ? $params['color_hover'] : '';
    $font_size = $params['font_size'];
?>
<style>
    #<?php echo $id;?> {
        <?php if (!empty($color)): echo "color: $color!important;"; endif;?>
        <?php if (!empty($font_size)): echo "font-size: $font_size!important;"; endif;?>
    }
    #<?php echo $id;?>:hover {
        <?php if (!empty($color_hover)): echo "color: $color_hover!important;"; endif;?>
    }
</style>
<i id="<?php echo $id;?>" class="fa fa-<?php echo $icon?>"></i>
<?php
endif;
