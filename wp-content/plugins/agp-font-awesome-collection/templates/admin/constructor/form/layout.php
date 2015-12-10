<?php 
    $obj = Fac()->getSettings();    
    $key = !empty($params['key']) ? $params['key'] : '';    
    $args = new stdClass();
    $args->key = $key;
    $args->settings =$obj ;
    $args->fieldSet = Fac()->getSettings()->getRecursiveCallable( !empty($obj->getConfig()->fieldSet) ? $obj->objectToArray($obj->getConfig()->fieldSet) : NULL );
    $args->fields = !empty($args->settings->getConfig()->shortcodes->elements->$key->fields) ? $obj->objectToArray($args->settings->getConfig()->shortcodes->elements->$key->fields) : NULL;    
?>
<h1>Font Awesome Constructor</h1>
<div class="fac-constructor-wrapper">
    <div class="faccw-column">
        <?php echo Fac()->getTemplate('admin/constructor/form/element-list', $args); ?>
        <?php echo Fac()->getTemplate('admin/constructor/form/element-params', $args); ?>     
    </div>
    
    <div class="faccw-column faccw-preview">
        <div class="faccw-preview-area">
            <?php echo Fac()->getTemplate('admin/constructor/form/element-preview', $args); ?>
            <div class="fac-constructor-controls">
                <a class="fac-constructor-apply button button-primary" href="javascript:void(0);" >Insert</a>
                <a class="fac-constructor-preview-button button button-primary" href="javascript:void(0);" >Preview</a>
            </div>         
        </div>
    </div>    
</div>


