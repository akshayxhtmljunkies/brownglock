<?php
class Fac_MenuIcons {
    
    /**
     * The single instance of the class 
     * 
     * @var Fac_MenuIcons
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
        // add custom menu fields to menu
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'addCustomNavFields' ) );

        // save menu custom fields
        add_action( 'wp_update_nav_menu_item', array( $this, 'updateCustomNavFields'), 10, 3 );   
        
        // edit menu walker
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'editWalker'), 10, 2 );  
        
        // update menu item title
        add_filter( 'walker_nav_menu_start_el', array( $this, 'updateTitle'), 10, 4 ); 
    }
    
    /**
     * Add custom fields to $item nav object
     * in order to be used in custom Walker
     * 
     * @param type $menu_item
     * @return type
     */
    public function addCustomNavFields( $menu_item ) {
        $menu_item->menuIcon = get_post_meta( $menu_item->ID, '_menu_item_menuIcon', true );
        return $menu_item;
    }    

    /**
     * Save menu custom fields
     *
     * @param type $menu_id
     * @param type $menu_item_db_id
     * @param type $args
     */
    public function updateCustomNavFields( $menu_id, $menu_item_db_id, $args ) {
        // Check if element is properly sent
        if ( isset($_REQUEST['menu-item-menuIcon']) && is_array( $_REQUEST['menu-item-menuIcon']) ) {
            $value = $_REQUEST['menu-item-menuIcon'][$menu_item_db_id];
            update_post_meta( $menu_item_db_id, '_menu_item_menuIcon', $value );
        }
    }    
    
    /**
     * Define new Walker edit
     *
     * @param type $walker
     * @param type $menu_id
     * @return string
     */
    public function editWalker( $walker, $menu_id ) {
        if ( class_exists( 'Fac_WalkerNavMenuEdit' ) ) {
            $walker = 'Fac_WalkerNavMenuEdit';
        }
        return $walker;
    }    
    
    /**
     * Update menu item title
     * 
     * @param type $item_output
     * @param type $item
     * @param type $depth
     * @param type $args
     * @return string
     */
    public function updateTitle ($item_output, $item, $depth, $args) {
        if (!empty($item->menuIcon)) {
            $item_output = preg_replace("/(<a.*?>)([^<]*?)</", '$1<i class="fa fa-'.$item->menuIcon.'"></i> $2<', $item_output);     
        }
    	return $item_output;
    }
    
    /**
     * Render custom nav fields
     * 
     * @param type $item_id
     * @param type $item
     * @return type
     */
    public function renderCustomNavFields($item_id, $item) {
        ob_start();
        $categories = Fac()->getIconRepository()->getAllCategories();
        $selected = !empty($item->menuIcon) ? esc_attr( $item->menuIcon ) : '';
    ?>    
        <p class="field-custom description description-wide">
           <label for="edit-menu-item-menuIcon-<?php echo $item_id; ?>">
                <?php _e( 'Menu Icon' ); ?><br />
                <select style="font-family:FontAwesome, Arial;" id="edit-menu-item-menuIcon-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-menuIcon[<?php echo $item_id; ?>]">
                    <option value=""></option>                
                    <?php 
                        foreach ($categories as $category) : 
                    ?>
                            <optgroup label="<?php echo $category;?>">            
                    <?php
                            $icons = Fac()->getIconRepository()->getAllByCategory($category);
                            foreach ($icons as $icon) : 
                    ?>
                        <option style="font-family:FontAwesome, Arial;" data-icon="fa-<?php echo $icon->getId(); ?>" value="<?php echo $icon->getId(); ?>"<?php selected($icon->getId(), $selected); ?>>
                            &#x<?php echo $icon->getUnicode(); ?>; <?php echo $icon->getName(); ?>
                        </option>            
                    <?php 
                            endforeach;
                    ?>
                            </optgroup>
                    <?php                
                        endforeach; 
                    ?>
                </select>
               
           </label>
       </p>
    <?php    
        return ob_get_clean();
    }
    
}
