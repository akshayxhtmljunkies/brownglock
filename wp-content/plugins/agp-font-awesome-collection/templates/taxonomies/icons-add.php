<?php
    $icon = !empty($params['icon']) ? $params['icon'] : '';
    $position = !empty($params['position']) ? $params['position'] : 'left';
    
    $categories = Fac()->getIconRepository()->getAllCategories();
    $positions = array(
        'left' => 'Before Name',
        'right' => 'After Name',
    )
?>
<div class="form-field">
    <label for="fac_tax_meta[icon]"><?php _e('Icon'); ?></label>
    <select style="font-family:FontAwesome, Arial;" name="fac_tax_meta[icon]" id="fac_tax_meta[icon]">
        <option value=""></option>                
        <?php 
            foreach ($categories as $category) : 
        ?>
                <optgroup label="<?php echo $category;?>">            
        <?php
                $icons = Fac()->getIconRepository()->getAllByCategory($category);
                foreach ($icons as $ic) : 
        ?>
            <option style="font-family:FontAwesome, Arial;" data-icon="fa-<?php echo $ic->getId(); ?>" value="<?php echo $ic->getId(); ?>"<?php selected($ic->getId(), $icon); ?>>
                &#x<?php echo $ic->getUnicode(); ?>; <?php echo $ic->getName(); ?>
            </option>            
        <?php 
                endforeach;
        ?>
                </optgroup>
        <?php                
            endforeach; 
        ?>
    </select>        
    <p class="description">The Font Awesome Icon</p>        
</div>
<div class="form-field">
    <label for="fac_tax_meta[position]"><?php _e('Icon Position'); ?></label>
    <select id="fac_tax_meta[position]" name="fac_tax_meta[position]" >
        <?php 
            foreach( $positions as $k => $v ):
                $selected = !empty($position) && $position == $k;
        ?>
                <option value="<?php echo $k; ?>"<?php selected( $selected );?>><?php echo $v;?></option>
        <?php 
            endforeach; 
        ?>
    </select>        
    <p class="description">The Font Awesome Icon position in the "Name" field</p>        
</div>
