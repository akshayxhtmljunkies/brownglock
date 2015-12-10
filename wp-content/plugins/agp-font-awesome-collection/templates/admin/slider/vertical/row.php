<?php
    $id = isset($params['id']) ? $params['id'] : NULL;
    $row = isset($params['row']) ? $params['row'] : 0;
    $data = !empty($params['data']) ? $params['data'] : NULL;
    if (!empty($id)) :
?>
<td>
    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_headline";?>">Headline:</label></div>
        <input class="widefat" type="text" value="<?php echo !empty($data['headline']) ? $data['headline'] : '' ;?>" id="<?php echo "{$id}_data_{$row}_headline";?>" name=<?php echo "{$id}_data[{$row}][headline]";?> />            
    </div>
    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_description";?>">Description:</label></div>
        <input class="widefat" type="text" value="<?php echo !empty($data['description']) ? $data['description'] : '' ;?>" id="<?php echo "{$id}_data_{$row}_description";?>" name=<?php echo "{$id}_data[{$row}][description]";?> />        
    </div>
    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_icon";?>">Icon:</label></div>
        <?php
        $selected = !empty($data['icon']) ? $data['icon'] : '';
        $categories = Fac()->getIconRepository()->getAllCategories();
        ?>
        <select class="widefat" style="font-family:FontAwesome, Arial;" id="<?php echo "{$id}_data_{$row}_icon";?>" name=<?php echo "{$id}_data[{$row}][icon]";?>>
            <option value=""></option>                
            <?php 
                foreach ($categories as $category) : 
            ?>
                    <optgroup label="<?php echo $category;?>">            
            <?php
                    $icons = Fac()->getIconRepository()->getAllByCategory($category);
                    foreach ($icons as $icon) : 
            ?>
                <option style="font-family:FontAwesome, Arial;" data-icon="fa-<?php echo $icon->getId(); ?>" value="<?php echo $icon->getId(); ?>"<?php selected($icon->getId(), $selected); ?>>
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
    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_link";?>">Link URL:</label></div>
        <input class="widefat" type="text" value="<?php echo !empty($data['link']) ? $data['link'] : '' ;?>" id="<?php echo "{$id}_data_{$row}_link";?>" name=<?php echo "{$id}_data[{$row}][link]";?> />                
    </div>    


    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_target";?>">Link Target:</label></div>
        <?php
            $selected = !empty($data['target']) ? $data['target'] : '_blank';
            $targets = array(
                '_blank' => 'Blank',                
                '_self' => 'Self',
                '_parent' => 'Parent',
                '_top' => 'Top',        
            );
        ?>
        <select class="widefat" id="<?php echo "{$id}_data_{$row}_target";?>" name=<?php echo "{$id}_data[{$row}][target]";?>>
            <?php
                    foreach ($targets as $targetKey => $targetName) : 
            ?>
                <option value="<?php echo $targetKey; ?>"<?php selected($targetKey, $selected); ?>><?php echo $targetName; ?></option>            
            <?php 
                    endforeach;
            ?>
        </select>                        
    </div>        
    
    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_text_color";?>">Text Color:</label></div>
        <input class="widefat fac-slider-text-color" type="text" value="<?php echo !empty($data['text_color']) ? $data['text_color'] : '' ;?>" id="<?php echo "{$id}_data_{$row}_text_color";?>" name="<?php echo "{$id}_data[{$row}][text_color]";?>" data-row="<?php echo "_{$row}_";?>" />                
    </div>    
    <div class="inside">
        <div class="agp-repeater-label"><label for="<?php echo "{$id}_data_{$row}_background_color";?>">Background Color:</label></div>
        <input class="widefat fac-slider-background-color" type="text" value="<?php echo !empty($data['background_color']) ? $data['background_color'] : '' ;?>" id="<?php echo "{$id}_data_{$row}_background_color";?>" name="<?php echo "{$id}_data[{$row}][background_color]";?>" data-row="<?php echo "_{$row}_";?>" />                
    </div>    
    <div class="inside tbl-actions">
        <a class="button agp-down-row" href="javascript:void(0);">Down</a>            
        <a class="button agp-up-row" href="javascript:void(0);">Up</a>
        <a class="button agp-del-row" href="javascript:void(0);">Delete</a>
    </div>        
</td>
<?php 
    endif;
