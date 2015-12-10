<?php
class Fac_TaxonomyIcons {
    
    /**
     * Taxonomies
     * 
     * @var array
     */
    private $taxonomies;
    /**
     * The single instance of the class 
     * 
     * @var Fac_Slider 
     */
    protected static $_instance = null;    

	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self(dirname(dirname(__FILE__)));
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
     */
    public function __construct() {
        add_action( 'plugins_loaded' , array( $this, 'pluginsLoaded' )); 
        add_action( 'init' , array( $this, 'init' ), 999); 
    }
    
    public function init () {
        $this->taxonomies = get_taxonomies( array( 'public'   => true ), 'names', 'and' );

        if (!empty($this->taxonomies) && is_array($this->taxonomies)) {
            foreach ($this->taxonomies as $key => $taxonomy) {
                add_action( "{$taxonomy}_edit_form_fields", array( $this, 'viewTaxIconMetabox' ) );
                add_action( "{$taxonomy}_add_form_fields", array( $this, 'viewTaxIconMetabox' ) );
                add_action( "edited_{$taxonomy}", array( $this, 'saveTaxIconMetabox' ) );
                add_action( "create_{$taxonomy}", array( $this, 'saveTaxIconMetabox' ) );
            }
        }        
    }

    public function hookEscHtml($safe_text, $text) {
        if (strpos($text, '<i class="fa fa-') !== FALSE/* && is_admin()*/) {
            return html_entity_decode($safe_text);
        }
        return $safe_text;
    }    
    
    public function hookViewTerms($html) {
        return html_entity_decode($html);
    }        
    
    
    public function pluginsLoaded () {
        add_filter( "get_term",  array( $this, 'getTerm' ));                 
        add_filter( "get_terms",  array( $this, 'getTerms' )); 
        add_filter( "get_object_terms",  array( $this, 'getTerms' ));
    }    
    
    public function getTerm ($term) {
        if (is_array($term) || is_object($term)) {
            if (is_array($term)) {
                $term_id = $term['term_id'];
                $name = $term['name'];    
            } else {
                $term_id = $term->term_id;
                $name = $term->name;        
            }

            if (!empty($term_id)) {
                $data = $this->getTaxIcon($term_id);

                if (!empty($data['icon'])) {
                    $ic = '<i class="fa fa-' . $data['icon'] .'"></i>';
                    if (!empty($data['position'])) {
                        switch ($data['position']) {
                            case 'left':
                                $name = "$ic $name";
                                break;
                            case 'right':
                                $name = "$name $ic";
                                break;
                            default:
                                break;
                        }
                    }

                    if (is_array($term)) {
                        $term['fa_name'] = html_entity_decode($name);    
                    } else {
                        $term->fa_name = html_entity_decode($name);    
                    }
                }                    
            }
        }  
        
        return $term;
    }
    
    public function getTerms ($terms) {
        if (!empty($terms) && is_array($terms)) {
            foreach ($terms as $key => $term) {        
                $terms[$key] = $this->getTerm($term);
            }
        }
        
        return $terms;
    }

    
    public function viewTaxIconMetabox ($tag) {
        if (!empty($tag->term_id)) {
            $data = $this->getTaxIcon($tag->term_id);
            $atts = array(
                'icon' => !empty($data['icon']) ? $data['icon'] : '',
                'position' => !empty($data['position']) ? $data['position'] : 'left',
            );
            $template = 'icons-edit';
        } else {
            $atts = array();            
            $template = 'icons-add';
        }
        echo Fac()->getTemplate("taxonomies/{$template}", $atts);
    }    
    
    public function saveTaxIconMetabox ($term_id) {
        if ( isset( $_POST['fac_tax_meta'] ) ) {
            $this->setTaxIcon($term_id, $_POST['fac_tax_meta']);
        }
    }
    
    public function getTaxIcon ($term_id) {
        return get_option( "fac_tax_{$term_id}" );
    }    

    public function setTaxIcon ($term_id, $value) {
        if (!empty($value) && is_array($value)) {
            update_option( "fac_tax_{$term_id}", $value );
        }
    }        
    
}
