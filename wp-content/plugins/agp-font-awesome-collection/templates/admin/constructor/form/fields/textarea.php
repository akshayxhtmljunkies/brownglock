<?php 
    $args = $params;
    $label = !empty($args->fields[$args->field]['label']) ? $args->fields[$args->field]['label'] : '';
    $class = !empty($args->fields[$args->field]['class']) ? $args->fields[$args->field]['class'] : ''; 
    $note = !empty($args->fields[$args->field]['note']) ? $args->fields[$args->field]['note'] : '';
    
    $value = esc_attr($args->fields[$args->field]['default']);
?>
<label for="<?php echo "params[{$args->field}]"; ?>"><?php echo $label;?></label>
<textarea rows="6" <?php echo !empty($class) ? ' class="'.$class.'"': '';?> id="<?php echo "params[{$args->field}]"; ?>" name="<?php echo "params[{$args->field}]"; ?>"><?php echo $value;?></textarea>        
<?php if (!empty($note)): ?><a href="javascript:void(0);" class="fac-hint"><i class="fa fa-question-circle"></i></a><p class="fac-note"><?php echo $note;?></p><?php endif;?>
