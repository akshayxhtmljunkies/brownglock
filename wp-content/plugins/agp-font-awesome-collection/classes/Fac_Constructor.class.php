<?php
class Fac_Constructor {
    
    /**
     * The single instance of the class 
     * 
     * @var object 
     */
    protected static $_instance = null;    

	/**
	 * Main Instance
	 *
     * @return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
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
        add_action( 'admin_footer-post.php', array( $this, 'createForm' ) );       
        add_action( 'admin_footer-post-new.php', array( $this, 'createForm' ) );       
    }
    
    public function createForm() {
        echo Fac()->getTemplate('admin/constructor/constructor', array('key' => 'fac_blank'));
    }
    
}

