<?php
use Agp\FontAwesomeCollection\Core\Agp_SettingsAbstract;

class Fac_Settings extends Agp_SettingsAbstract {
    
    /**
     * The single instance of the class 
     * 
     * @var object 
     */
    protected static $_instance = null;    

    /**
     * Parent Module
     * 
     * @var Fac
     */
    protected static $_parentModule;
    
	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance($parentModule = NULL) {
		if ( is_null( self::$_instance ) ) {
            self::$_parentModule = $parentModule;            
			self::$_instance = new self();
		}
		return self::$_instance;
	}    
    
	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
    }        
    
    /**
     * Constructor 
     * 
     * @param Agp_Module $parentModule
     */
    public function __construct() {
        $config = include ($this->getParentModule()->getBaseDir() . '/config/config.php');        
        parent::__construct($config);
    }
    
    public static function getParentModule() {
       
        return self::$_parentModule;
    }
    
    public function getGlobalSettings () {
        return $this->getSettings('fac-global-settings');
    }    
    
    public static function renderSettingsPage() {
        echo self::getParentModule()->getTemplate('admin/options/layout', self::instance());
    }    
    
    public static function getCustomShortcodesFieldSet() {
        return self::$_parentModule->getCustomElements();
    }
    
    public function getShortcodes() {
        $result = array();

        $shortcodes = $this->objectToArray($this->getConfig()->shortcodes->elements);
        if(!empty($shortcodes)) {
            foreach ($shortcodes as $key => $value) {
               $result[$key] = $this->arrayToObject($value);
            }
            return $result;
        }
    }
    
    public function getShortcodeDefaults($name) {
        $result = array();

        $shortcodes = $this->objectToArray($this->getConfig()->shortcodes->elements);
        if (!empty($shortcodes[$name]['fields'])) {
            foreach( $shortcodes[$name]['fields'] as $key => $item ) {
                if ($item['type'] != 'hidden') {
                    $result[$key] = !empty($item['default']) ? $item['default'] : NULL;
                }
            }
        }

        return $result;
    }
    
    public function getElementNote ( $key ) {
        $shortcodes = $this->objectToArray($this->getConfig()->shortcodes->elements);
        if (!empty($shortcodes[$key]['note'])) {
            return $shortcodes[$key]['note'];
        }
    }
    
    public function getElementList () {
        $result = array();
        $shortcodes = $this->objectToArray($this->getConfig()->shortcodes->elements);
        if (!empty($shortcodes)) {
            foreach( $shortcodes as $key => $item ) {
                if (empty($item['developerOnly'])) {
                    $result[$key] = $item['displayName'];
                }
            }
        }
        return $result;
    }
    
    public function getCustomElementList () {
        global $pagenow;
        $result = array();


        if ( $pagenow == 'post.php' && !empty($_REQUEST['post']) ) {
            if (get_post_type ($_REQUEST['post']) == 'fac-shortcodes') {
                return $result;
            }
        }

        $args = array(
            'post_type' => 'fac-shortcodes',
            'posts_per_page' => -1,
        );
                
        $query = new WP_Query($args);
        
        while ( $query->have_posts() ) : $query->the_post();
            $key = get_post_meta( get_the_ID(), '_name', true );
            if (!empty($key)) {
                $result[$key] = (get_the_title()) ? get_the_title() : $key;    
            }
        endwhile;        

        wp_reset_query();

        return $result;
    }    
    
    public function getSliderElementList () {
        global $pagenow;
        $result = array();


        if ( $pagenow == 'post.php' && !empty($_REQUEST['post']) ) {
            if (get_post_type ($_REQUEST['post']) == 'fac-sliders') {
                return $result;
            }            
        }

        $args = array(
            'post_type' => 'fac-sliders',
            'posts_per_page' => -1,
        );
                
        $query = new WP_Query($args);
        
        while ( $query->have_posts() ) : $query->the_post();
            $key = get_post_meta( get_the_ID(), '_name', true );
            if (!empty($key)) {
                $result[$key] = (get_the_title()) ? get_the_title() : $key;    
            }
        endwhile;        

        wp_reset_query();
        
        return $result;
    }    

}

