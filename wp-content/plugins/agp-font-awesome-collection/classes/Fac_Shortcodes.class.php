<?php
class Fac_Shortcodes {
    
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
        add_action( 'add_meta_boxes', array( $this, 'addMetaboxes' ) );        
        add_action( 'save_post', array( $this, 'saveMetaboxes' ), 1, 2);
    }

    public function addMetaboxes() {
        add_meta_box('fac_shortcodes_name', 'Shortcode Name', array( $this, 'viewNameMetabox' ), 'fac-shortcodes', 'normal', 'default');
        add_meta_box('fac_shortcodes_preview', 'Preview', array( $this, 'viewPreviewMetabox' ), 'fac-shortcodes', 'side', 'default');        
    }
    
    
    public function saveMetaboxes( $post_id, $post ) {

        if ( empty( $_POST['fac_shortcodes_noncename'] ) 
            || !wp_verify_nonce( $_POST['fac_shortcodes_noncename'],  basename(Fac()->getBaseDir()) )
            || !current_user_can( 'edit_post', $post->ID ) ) {
            return $post->ID;
        }
        
        $shortcodes_meta['_name'] = !empty($_POST['_name']) ?  $_POST['_name'] : 'fac_shortcode_' . $post->ID;
        
        foreach ($shortcodes_meta as $key => $value) {
            if( $post->post_type == 'revision' ) return;
                        
            $value = implode(',', (array) $value);            
            if ( !$value ) {
                delete_post_meta($post->ID, $key); 
            } else {
                update_post_meta($post->ID, $key, $value);
            }
            
        }   
    }
    
    public function viewNameMetabox() {
        global $post;
        // Noncename needed to verify where the data originated
        echo '<input type="hidden" name="fac_shortcodes_noncename" id="fac_shortcodes_noncename" value="' . wp_create_nonce( basename(Fac()->getBaseDir()) ) . '" />';
        // Get the location data if its already been entered
        $name = get_post_meta($post->ID, '_name', true);
        $name = !empty($name) ?  $name : 'fac_shortcode_' . $post->ID;
        // Echo out the field
        echo '<input type="text" name="_name" value="' . $name  . '" class="widefat" />';
    }
    
    public function viewPreviewMetabox() {
        global $post;        
        echo apply_filters('the_content', get_post_field('post_content', $post->ID));
    }    
}

