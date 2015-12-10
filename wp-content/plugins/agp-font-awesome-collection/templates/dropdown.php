<?php
if (!empty($params) ) :
    $name = !empty($params['name']) ? $params['name'] : '';
    $selected = !empty($params['icon']) ? $params['icon'] : '';
    
    if (!empty($name)) :
        $categories = Fac()->getIconRepository()->getAllCategories();
?>
<div class="fac fac-dropdown-template">
    <select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
        <option value=""></option>                
        <?php 
            foreach ($categories as $category) : 
        ?>
                <optgroup label="<?php echo $category;?>">            
        <?php
                $icons = Fac()->getIconRepository()->getAllByCategory($category);
                foreach ($icons as $icon) : 
        ?>
            <option data-icon="fa-<?php echo $icon->getId(); ?>" value="<?php echo $icon->getId(); ?>"<?php selected($icon->getId(), $selected); ?>>
                &#x<?php echo $icon->getUnicode(); ?>; <?php echo $icon->getName(); ?>
            </option>            
        <?php 
                endforeach;
        ?>
                </optgroup>
        <?php                
            endforeach; 
        ?>
    </select>
</div>
<?php
    endif;
endif;