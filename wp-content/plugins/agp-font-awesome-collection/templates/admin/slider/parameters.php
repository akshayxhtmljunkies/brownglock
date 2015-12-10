<?php
if (!empty($params['post'])) :
    $post = $params['post'];
    $name = !empty($params['name']) ?  $params['name'] : 'fac_slider_' . $post->ID;
    $type = !empty($params['type']) ?  $params['type'] : 'default';
    $sliderTypes = !empty($params['sliderTypes']) ?  $params['sliderTypes'] : array();
    $nonce = wp_create_nonce( basename(Fac()->getBaseDir()) );
?>
<input type="hidden" name="fac_slider_noncename" id="fac_slider_noncename" value="<?php echo $nonce; ?>" />
<table class="widefat" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td>
                <label for="fac_slider_parameters_name"><strong>Shortcode Name</strong></label>
                <input type="text" id="fac_slider_parameters_name" name="_name" value="<?php echo $name; ?>" class="widefat" />                
            </td>    
        </tr>            
        <tr>
            <td>
                <label for="fac_slider_parameters_type"><strong>Slider Type</strong></label>                    
                <select class="widefat" id="fac_slider_parameters_type" name="_type" >
                    <?php 
                        foreach( $sliderTypes as $k => $v ):
                            $selected = !empty($type) && $type == $k;
                    ?>
                            <option value="<?php echo $k; ?>"<?php selected( $selected );?>><?php echo $v;?></option>
                    <?php 
                        endforeach; 
                    ?>
                </select>                
                
            </td>            
        </tr>
    </tbody>
</table>
<?php 
endif;