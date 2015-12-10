<?php
    $id = !empty($params['id']) ? $params['id'] : '';
    $headline = !empty($params['headline']) ? $params['headline'] : '';
    $description = !empty($params['description']) ? $params['description'] : '';
    $icon = !empty($params['icon']) ? $params['icon'] : '';
    $link = !empty($params['link']) ? $params['link'] : '';    
    $target = !empty($params['target']) ? $params['target'] : '_blank';    
    $text_color = !empty($params['text_color']) ? $params['text_color'] : '#fff';    
    $background_color = !empty($params['background_color']) ? $params['background_color'] : '';        
    
    $styles = array();
    if (!empty($background_color)) {
        $styles[] = "background: $background_color"; 
    }
    if (!empty($text_color)) {
        $styles[] = "color: $text_color!important"; 
    }                
    $styles = implode('; ', $styles);        
?>
<?php 
if (!empty($icon) || !empty($headline) || !empty($description) ): 
?>
<div id="<?php echo $id;?>" class="fac-promotion-main-section"<?php echo (!empty($styles)) ? ' style="' . $styles . '"' : '';?>>
    <?php if (!empty($link)) : ?>
    <a href="<?php echo $link; ?>" title="<?php echo $headline; ?>" target="<?php echo $target;?>"<?php echo (!empty($styles)) ? ' style="' . $styles . '"' : '';?>>
    <?php endif;?>
    
    <?php if (!empty($icon) || !empty($headline)): ?>
    <div class="fac-promotion-preview<?php echo empty($icon) ? ' fac-noicon' : '';?>">
        <div class="fpp-inner">
            <?php if (!empty($icon)): ?>
                <i class="fa fa-<?php echo $icon; ?>"></i>
            <?php endif; ?>    
    
            <?php if (!empty($headline)): ?>
                <h3 class="fac-headline"<?php echo (!empty($styles)) ? ' style="' . $styles . '"' : '';?>><?php echo $headline; ?></h3>
            <?php endif; ?>           
        </div>
    </div>
    <?php endif;?>
    
    <?php if (!empty($description)): ?>
    <div class="fac-promotion-content"<?php if (!empty($icon) || !empty($headline)): ?> style="display: none;"<?php endif;?>>    
        <div class="fpp-inner">
            <?php if (!empty($headline)): ?>
                <h3 class="fac-headline"<?php echo (!empty($styles)) ? ' style="' . $styles . '"' : '';?>><?php echo $headline; ?></h3>
            <?php endif; ?>        
                
            <p class="fac-description"><?php echo $description; ?></p>         
        </div>
    </div>            
    <?php endif; ?>                
    
    <?php if (!empty($link)) : ?>
    </a>
    <?php endif;?>
</div>       
<?php 
endif;
