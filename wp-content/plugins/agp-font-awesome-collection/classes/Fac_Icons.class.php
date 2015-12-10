<?php
class Fac_Icons {
    
    public function getData () {    
        $result = Spyc::YAMLLoad(Fac()->getBaseDir() . '/vendor/agpfontawesome/icons/icons.yml');
        
        $data = array();
        $icons = $result['icons'];
        
        foreach ($icons as $key => $value) {
            $sort[$key]  = $value['categories'][0];
            $name[$key]  = $value['name'];
            $data[$value['id']] = $value;
            
        }
        array_multisort($sort, SORT_ASC, $name, SORT_ASC, $data);
        
        return $data;
    }    
    
    public function getVersion() {
        ob_start();
        include_once(Fac()->getBaseDir() . '/vendor/components/font-awesome/component.json');
        $components = ob_get_clean();
        $components = json_decode($components);
        return $components->version;
        
    }
}
