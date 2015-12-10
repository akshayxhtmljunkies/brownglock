<?php 
    $args = $params;
    $label = !empty($args->fields['fields'][$args->field]['label']) ? $args->fields['fields'][$args->field]['label'] : '';
    $class = !empty($args->fields['fields'][$args->field]['class']) ? $args->fields['fields'][$args->field]['class'] : ''; 
    $note = !empty($args->fields['fields'][$args->field]['note']) ? $args->fields['fields'][$args->field]['note'] : '';
    $atts = !empty($args->fields['fields'][$args->field]['atts']) ? $args->fields['fields'][$args->field]['atts'] : '';
    if (is_array($atts)) {
        $atts_s = '';
        foreach ($atts as $key => $value) {
            $atts_s .= $key . '="' . $value . '"';
        }
        $atts = $atts_s;
    }
    
    $value = esc_attr($args->data[$args->field]);
?>
<tr>
    <input <?php echo $atts;?><?php echo !empty($class) ? ' class="'.$class.'"': '';?> type="hidden" id="<?php echo "{$args->key}[{$args->field}]"; ?>" name="<?php echo "{$args->key}[{$args->field}]"; ?>" value="<?php echo $value;?>">                
</tr>    
