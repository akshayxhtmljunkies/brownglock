<?php
use Agp\FontAwesomeCollection\Core\Agp_Entity;

class Fac_IconEntity extends Agp_Entity {

    private $name;
    private $unicode;
    private $created;
    private $categories;
    
    public function __construct($data) {
        $default = array(
            'ID' => $data['id'],
            'name' => NULL,
            'unicode' => NULL,
            'created' => NULL,
            'categories' => NULL,
        );

        parent::__construct($data, $default); 
    }

    public function hasCategory($category = NULL) {
        
        if (!empty($category)) {
            $categories = array();
            
            if (is_array($category)) {
                $categories = $category;
            } else {
                $categories[] = $category;
            }
            
            $result = FALSE;
            foreach ($categories as $item) {
                $result = $result || in_array($item, $this->categories);
            }
            
            return $result;
        }
        
    }
    
    public function getName() {
        return $this->name;
    }

    public function getUnicode() {
        return $this->unicode;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getCategories() {
        return $this->categories;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setUnicode($unicode) {
        $this->unicode = $unicode;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function setCategories($categories) {
        $this->categories = $categories;
        return $this;
    }

    
}
