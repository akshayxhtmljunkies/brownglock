<?php
$args = $params;
if (!empty($args->fields)):
    $fields = !empty($args->fields) ? $args->fields : NULL;
    
    if (!empty($fields)) :
        ?>
        <section class="fac-constructor-section">
            <?php        
                foreach ($fields as $fk => $fv) :
                    if (!empty($fv['type'])) :
                    ?>
                    <div class="fac-constructor-field">
                    <?php
                        $args->field = $fk;
                        echo $args->settings->getParentModule()->getTemplate('admin/constructor/form/fields/' . $fv['type'] , $args);
                    ?>
                    </div>
                    <?php
                    endif;                    
                endforeach;                
            ?>
        </section>
        <?php 
    endif;
endif;