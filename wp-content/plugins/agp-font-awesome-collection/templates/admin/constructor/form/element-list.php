<?php
    $args = $params;
    $elementList =  Fac()->getSettings()->getElementList();
    $customElementList =  Fac()->getCustomElements();
    $sliderElementList =  Fac()->getSliderElements();
?>
<div class="fac-constructor-type">
    <h2>Elements</h2>
    <select class="fac-constructor-type-select widefat">
        <option value="0"></option>
        <optgroup label="Basic">            
        <?php 
            foreach( $elementList as $k => $v ):
                if ($k != 'fac_blank') :
                $selected = $args->key == $k || empty($args->key) && empty($k);
        ?>
                <option value="<?php echo $k;?>"<?php selected( $selected );?>><?php echo $v;?></option>
        <?php 
                endif;
            endforeach; 
        ?>
        </optgroup>

        <?php if (!empty($customElementList)) : ?>        
        <optgroup label="Custom">            
        <?php 
            foreach( $customElementList as $k => $v ):
                $selected = $args->key == $k || empty($args->key) && empty($k);
        ?>
                <option value="<?php echo $k;?>"<?php selected( $selected );?>><?php echo $v;?></option>
        <?php 
            endforeach; 
        ?>            
        </optgroup>            
        <?php endif;?>
        
        <?php if (!empty($sliderElementList)) : ?>        
        <optgroup label="Sliders">            
        <?php 
            foreach( $sliderElementList as $k => $v ):
                $selected = $args->key == $k || empty($args->key) && empty($k);
        ?>
                <option value="<?php echo $k;?>"<?php selected( $selected );?>><?php echo $v;?></option>
        <?php 
            endforeach; 
        ?>            
        </optgroup>            
        <?php endif;?>        
    </select>    
</div>
