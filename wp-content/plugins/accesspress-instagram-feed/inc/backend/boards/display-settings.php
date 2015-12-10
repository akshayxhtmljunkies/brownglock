<div class="apsc-boards-tabs" id="apsc-board-display-settings" style="display: none">
    <div class="apsc-tab-wrapper">
        
        <div class="apsc-option-inner-wrapper">
            <div class="apsc-option-field">
                <div class="apsc-option-inner-wrapper">
                    <label style="float:left;"><?php _e('Image Like', 'accesspress-instagram-feed') ?></label>
                    <div class="apsc-option-field"><input type="checkbox" name="instagram[active]" value="1" class="apsc-counter-activation-trigger" <?php checked( $apif_settings['active'], '1'); ?>/><?php _e('Show/Hide', 'accesspress-instagram-feed'); ?></div>
                </div>
            </div>
        </div>
       
        <div class="apsc-option-inner-wrapper">
            <label style="width:30%;"><?php _e('Choose Instagram Themes Layout', 'accesspress-instagram-feed'); ?></label>
            <div class="apsc-option-field">
                <label>
                    <input type="radio" name="instagram[instagram_mosaic]" value="mosaic" <?php if($apif_settings['instagram_mosaic']=='mosaic'){?>checked="checked"<?php }?>/><?php _e('Mosaic layout', 'accesspress-instagram-feed'); ?>
                    <div class="apsc-theme-image"><img src="<?php echo APIF_IMAGE_DIR.'/themes/massonary.png';?>"/></div>
                </label>
                <label>
                    <input type="radio" name="instagram[instagram_mosaic]" value="mosaic_lightview" <?php if($apif_settings['instagram_mosaic']=='mosaic_lightview'){?>checked="checked"<?php }?>/><?php _e('Mosaic LightBox Layout', 'accesspress-instagram-feed'); ?>
                    <div class="apsc-theme-image"><img src="<?php echo APIF_IMAGE_DIR.'/themes/lightbox.png';?>"/></div>
                </label>
                <label>
                    <input type="radio" name="instagram[instagram_mosaic]" value="slider" <?php if($apif_settings['instagram_mosaic']=='slider'){?>checked="checked"<?php }?>/><?php _e('Slider Layout', 'accesspress-instagram-feed'); ?>
                    <div class="apsc-theme-image"><img src="<?php echo APIF_IMAGE_DIR.'/themes/slider.png';?>"/></div>
                </label>                
            </div>
        </div>

    </div>
</div>