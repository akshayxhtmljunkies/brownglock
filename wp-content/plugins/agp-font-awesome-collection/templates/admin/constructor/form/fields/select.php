<?php 
    $args = $params;
    $label = !empty($args->fields[$args->field]['label']) ? $args->fields[$args->field]['label'] : '';
    $class = !empty($args->fields[$args->field]['class']) ? $args->fields[$args->field]['class'] : ''; 
    $note = !empty($args->fields[$args->field]['note']) ? $args->fields[$args->field]['note'] : '';    
    
    $list = $args->fieldSet[$args->fields[$args->field]['fieldSet']];
?>
<label for="<?php echo "params[{$args->field}]"; ?>"><?php echo $label;?></label>
<select<?php echo !empty($class) ? ' class="'.$class.'"': '';?> id="<?php echo "params[{$args->field}]"; ?>" name="<?php echo "params[{$args->field}]"; ?>" >
    <?php 
        foreach( $list as $k => $v ):
            $selected = !empty($args->data[$args->field]) && $args->data[$args->field] == $k;
    ?>
            <option value="<?php echo $k; ?>"<?php selected( $selected );?>><?php echo $v;?></option>
    <?php 
        endforeach; 
    ?>
</select>
<?php if (!empty($note)): ?><a href="javascript:void(0);" class="fac-hint"><i class="fa fa-question-circle"></i></a><p class="fac-note"><?php echo $note;?></p><?php endif;?>