<?php
if (!empty($params) && !empty($params['icon'])) :
    $id = !empty($params['id']) ? $params['id'] : 'fac_' . uniqid();
    $name = !empty($params['name']) ? $params['name'] : '';
    $title = !empty($params['title']) ? $params['title'] : '';
    $icon = !empty($params['icon']) ? $params['icon'] : '';
    $link = !empty($params['link']) ? $params['link'] : '';    
    $target = !empty($params['target']) ? $params['target'] : '';        
    $text = !empty($params['text']) ? $params['text'] : '';    
    $background = !empty($params['background']) ? $params['background'] : '';
    $background_hover = !empty($params['background_hover']) ? $params['background_hover'] : '';
    $border_radius = isset($params['border_radius']) ? $params['border_radius'] : '';
    $border_width = !empty($params['border_width']) ? $params['border_width'] : '';
    $border_color = !empty($params['border_color']) ? $params['border_color'] : '';
    $color = !empty($params['color']) ? $params['color'] : '';
    $color_hover = !empty($params['color_hover']) ? $params['color_hover'] : '';
?>
<style>
    #<?php echo $id;?> a {
        <?php if (!empty($background)): echo "background: $background!important;"; endif;?> 
        <?php if (!empty($border_radius)): echo "border-radius: $border_radius!important;"; endif;?>
        <?php if (!empty($border_width)): echo "border-width: $border_width!important;"; endif;?>
        <?php if (!empty($border_color)): echo "border-color: $border_color!important;"; endif;?>
        <?php if (!empty($color)): echo "color: $color!important;"; endif;?>
    }
    #<?php echo $id;?> a:hover {
        <?php if (!empty($background_hover)): echo "opacity: 1!important; background: $background_hover!important;"; endif;?>         
        <?php if (!empty($color_hover)): echo "color: $color_hover!important;"; endif;?>
    }
</style>
<div id="<?php echo $id;?>" class="fac fac-button-template">
    <a href="<?php echo (!empty($link)) ? $link : '#';?>" 
        class="fac-button<?php echo (!empty($text)) ? ' fac-text' : '';?>" 
        <?php echo (!empty($name)) ? ' id="' . $name . '"'  : '';?>       
        <?php echo (!empty($target)) ? ' target="' . $target . '"'  : '';?>              
        title="<?php echo $title;?>"
    >
             <i class="fa fa-<?php echo $icon;?>"></i>
             <?php echo (!empty($text)) ? '<span>' . $text . '</span>' : '';?>
    </a>    
</div>
<?php
endif;
