<?php
/*
....
*/
require_once dirname( __FILE__ ).'/base.addon.php';

if( !class_exists( 'CPCFF_WooCommerce' ) )
{
    class CPCFF_WooCommerce extends CPCFF_BaseAddon
    {
        /************* ADDON SYSTEM - ATTRIBUTES AND METHODS *************/
		protected $addonID = "addon-woocommerce-20150309";
		protected $name = "CFF - WooCommerce";
		protected $description;
		
		/************************ ADDON CODE *****************************/
        /************************ ATTRIBUTES *****************************/
        
        private $form = array(); // Form data
        private $first_time = true; // Control attribute to avoid read multiple times the form associated to the product
        
        /************************ CONSTRUCT *****************************/
        
        function __construct()
        {
			$this->description = __("The add-on allows integrate the forms with WooCommerce products", 'calculated-fields-form');
			
            // Check if the plugin is active
			if( !$this->addon_is_active() ) return;

			// Check if WooCommerce is active in the website
            $active_plugins = (array) get_option( 'active_plugins', array() );

            if ( is_multisite() )
            {
                $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
            }

            if( !( in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) )
            {
                return;
            }    

            // Load resources, css and js
            add_action( 'woocommerce_before_single_product', array( &$this, 'enqueue_scripts' ), 10 );
            
			// Addon display
            add_action('woocommerce_before_add_to_cart_button', array(&$this, 'display_form'), 10);
            
            // Corrects the form options
            add_filter( 'cpcff_get_option', array( &$this, 'get_form_options' ), 10, 3 );
                
            // Filters for cart actions
			add_filter('woocommerce_add_cart_item_data', array(&$this, 'add_cart_item_data'), 10, 2);
			add_filter('woocommerce_get_item_data', array(&$this, 'get_cart_item_data'), 10, 2);
			add_filter('woocommerce_get_cart_item_from_session', array(&$this, 'get_cart_item_from_session'), 10, 2);
            add_filter('woocommerce_add_cart_item', array(&$this, 'add_cart_item'), 10, 1);
			add_action('woocommerce_add_order_item_meta', array(&$this, 'add_order_item_meta'), 10, 3);
            
            // Filters for the Calculated Fields Form
            add_action( 'cpcff_redirect', array( &$this, 'cpcff_redirect'), 10 );

			// The init hook
			add_action( 'admin_init', array( &$this, 'init_hook' ), 1 );
            
        } // End __construct
        
        /************************ PRIVATE METHODS *****************************/
        /**
         * Check if the add-on can be applied to the product
         */
        private function apply_addon( $id = false )
        {
            global $post;
            
            $this->form = array();
            
            if( $id ) $post_id = $id;
            elseif( isset( $_REQUEST[ 'woocommerce_cpcff_product' ] ) ) $post_id = $_REQUEST[ 'woocommerce_cpcff_product' ];
            elseif( isset( $post ) ) $post_id = $post->ID;

            if( isset( $post_id ) )
            {
                $tmp = get_post_meta( $post_id, 'woocommerce_cpcff_form', true );
                if( !empty( $tmp ) ) $this->form[ 'id' ] = $tmp;
            }    
            
            return !empty( $this->form );
            
        }
        
        /************************ PUBLIC METHODS  *****************************/
        
		public function add_cart_item_data( $cart_item_meta, $product_id ) {
			if( !isset( $cart_item_meta[ 'cp_cff_form_data' ] ) && isset( $_SESSION[ 'cp_cff_form_data' ] ) ) 
            {
                $cart_item_meta[ 'cp_cff_form_data' ] = $_SESSION[ 'cp_cff_form_data' ];	
            }
            return $cart_item_meta;
            
        } // End add_cart_item_data
        
        public function get_cart_item_from_session( $cart_item, $values ) {
			if( isset( $values[ 'cp_cff_form_data' ] ) ) {
				$cart_item['cp_cff_form_data'] = $values['cp_cff_form_data'];
                $this->add_cart_item( $cart_item );
			}
			return $cart_item;
            
		} // End get_cart_item_from_session
        
		function get_cart_item_data( $values, $cart_item ) {
			global $wpdb;

			// Adjust price if required based in the cpcff_data
			if( isset($cart_item[ 'cp_cff_form_data' ] ) )    
            {
                $data_id = $cart_item[ 'cp_cff_form_data' ];
                if( !empty( $data_id ) )
                {
					$data = $wpdb->get_var( $wpdb->prepare( "SELECT data FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." WHERE id=%d", $data_id ) );
					$data = preg_replace( array( "/\n+/", "/:+\s*/" ), array( "\n", ":" ), $data );
					$data_arr = explode( "\n", $data );
					foreach( $data_arr as $data_item )
					{
						if( !empty( $data_item ) )
						{
							$data_item = explode( ":", $data_item );
							if( count($data_item) == 2 )
							{
								$values[] = array( 
												'name' 	=> stripcslashes( $data_item[ 0 ] ),
												'value' => stripcslashes( $data_item[ 1 ] )
											);
							}	
						}	
					}
				}    
            }
			unset( $_SESSION[ 'cp_cff_form_data' ] );
			return $values;
        } // End add_cart_item
		
        //Helper function, used when an item is added to the cart as well as when an item is restored from session.
		function add_cart_item( $cart_item ) {
			global $wpdb;

			// Adjust price if required based in the cpcff_data
			if( isset($cart_item[ 'cp_cff_form_data' ] ) )    
            {
                $tmp = get_post_meta( $cart_item[ 'product_id' ], 'woocommerce_cpcff_calculate_price', true );
                if( !empty( $tmp ) )
                {
					$minimum_price = get_post_meta( $cart_item[ 'product_id' ], 'woocommerce_cpcff_minimum_price', true );
                    $data_id = $cart_item[ 'cp_cff_form_data' ];
                    $data = $wpdb->get_var( $wpdb->prepare( "SELECT paypal_post FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." WHERE id=%d", $data_id ) );
                    $paypal_data = unserialize( $data );
                    $price = preg_replace( '/[^\d\.\,]/', '', $paypal_data[ 'final_price' ] );
                    $cart_item[ 'data' ]->price = ( !empty( $minimum_price ) ) ? max( $price, $minimum_price ) : $price;
				}    
            }
            return $cart_item;
            
		} // End add_cart_item
        
        /**
         * Avoid redirect the Calculated Fields Form to the thanks page.
         */
        function cpcff_redirect()
        {
			if( isset( $_REQUEST[ 'product' ] ) || isset( $_REQUEST[ 'woocommerce_cpcff_product' ] ) ) return false;
            return true;
        }
        
        public function add_order_item_meta( $item_id, $values, $cart_item_key )
        {
            global $wpdb;
            $data_id = $values[ 'cp_cff_form_data' ];

            if( $this->apply_addon( $values[ 'data' ]->id ) )
            {
			    $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." WHERE id=%d", $data_id ) );
				
				if( !empty( $data->paypal_post ) && ( $dataArr = @unserialize( $data->paypal_post ) ) !== false )
				{
					foreach( $dataArr as $fieldname => $value )
					{
						if( strpos( $fieldname, '_url' ) !== false )
						{
							$_fieldname = str_replace( '_url', '', $fieldname );
							$_value     = $dataArr[ $_fieldname ];
							$_values 	= explode( ',', $_value );
							$_replacement = array();	
							
							if( count( $_values ) == count( $value ) )
							{
								foreach( $_values as $key => $_fileName )
								{
									$_fileName = trim( $_fileName );
									$_replacement[] = '<a href="'.$value[ $key ].'" target="_blank">'.$_fileName.'</a>';
								}
							}
							if( !empty( $_replacement ) )
							{
								$data->data = str_replace( $_value, implode( ', ', $_replacement ) , $data->data );
							}	
						}	
					}
				}
				
                $metadata = preg_replace( "/\n+/", "<br />", $data->data );
                wc_add_order_item_meta( $item_id, __( 'Data' ), $metadata, true );
            }    
            
        } // End add_order_item_meta
        
        /**
         * Display the form associated to the product
         */
        public function display_form()
        {
            global $post, $woocommerce;
            
            if ( $this->apply_addon() ) {
                
				$product = null;
				if (function_exists('get_product')) {
					$product = get_product($post->ID);
				} else {
					$product = new WC_Product($post->ID);
				}

                $form_content = cp_calculatedfieldsf_filter_content( $this->form );
                
				// Initialize form fields
				if(
					!empty( $_SESSION[ 'cp_cff_form_data' ] ) && 
					!empty( $_REQUEST[ 'cp_calculatedfieldsf_id' ] ) &&
					!empty( $_REQUEST[ 'cp_calculatedfieldsf_pform_psequence' ] ) 
				)
				{
					global $wpdb;
					$result = $wpdb->get_row( $wpdb->prepare( "SELECT form_data.paypal_post AS paypal_post FROM ".CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME." AS form_data WHERE form_data.id=%d AND form_data.formid=%d", $_SESSION[ 'cp_cff_form_data' ],  $_REQUEST[ 'cp_calculatedfieldsf_id' ] ) );

					if( !is_null( $result ) )
					{
						$arr = array();
						$submitted_data = unserialize( $result->paypal_post );
						foreach( $submitted_data as $key => $val )
						{
							if( preg_match( '/^fieldname\d+$/', $key ) )
							{
								$arr[ $key.$_REQUEST[ 'cp_calculatedfieldsf_pform_psequence' ]] = $val;
							}
						}
				?>
						<script>
							cpcff_default  = ( typeof cpcff_default != 'undefined' ) ? cpcff_default : {};
							cpcff_default[ 'form_structure<?php echo $_REQUEST[ 'cp_calculatedfieldsf_pform_psequence' ]; ?>' ] = <?php echo json_encode( $arr ); ?>;
						</script>
				<?php   
					}		
				}	
				unset( $_SESSION[ 'cp_cff_form_data' ] );
                // Remove the form tags
                if( preg_match( '/<form[^>]*>/', $form_content, $match ) )
                {
                    $form_content = str_replace( $match[ 0 ], '', $form_content);
                    $form_content = preg_replace( '/<\/form>/', '', $form_content);
                }
                
                $tmp = get_post_meta( $post->ID, 'woocommerce_cpcff_calculate_price', true );
                $request_cost = ( !empty( $tmp ) ) ? cp_calculatedfieldsf_get_option( 'request_cost', false, $this->form[ 'id' ] ) : false;

                echo '<div class="cpcff-woocommerce-wrapper">'
                     .$form_content
                     .( ( method_exists( $woocommerce, 'nonce_field' ) ) ? $woocommerce->nonce_field('add_to_cart') : '' )
                     .'<input type="hidden" name="woocommerce_cpcff_product" value="'.$post->ID.'" />'
                     .( ( $request_cost ) ? '<input type="hidden" name="woocommerce_cpcff_field" value="'.$request_cost.'" /><input type="hidden" name="woocommerce_cpcff_form" value="'.$this->form[ 'id' ].'">' : '' )
                     .'</div>';
                
                $add_to_cart_value = '';
				if ($product->is_type('variable')) :
					$add_to_cart_value = 'variation';
				elseif ($product->has_child()) :
					$add_to_cart_value = 'group';
				else :
					$add_to_cart_value = $product->id;
				endif;
                
                if (!function_exists('get_product')) {
					//1.x only
					if( method_exists( $woocommerce, 'nonce_field' ) ) $woocommerce->nonce_field('add_to_cart');
					echo '<input type="hidden" name="add-to-cart" value="' . $add_to_cart_value . '" />';
				} else {
					echo '<input type="hidden" name="add-to-cart" value="' . $post->ID . '" />';
				}
			}
            
			echo '<div class="clear"></div>';
            
        } // End display_form
        
        /**
         * Enqueue all resources: CSS and JS files, required by the Addon
         */ 
        public function enqueue_scripts()
        {
            if( $this->apply_addon() )
            {
                wp_enqueue_style ( 'cpcff_wocommerce_addon_css', plugins_url('/woocommerce.addon/css/styles.css', __FILE__) );
                wp_enqueue_script( 'cpcff_wocommerce_addon_js', plugins_url('/woocommerce.addon/js/scripts.js',  __FILE__), array( 'jquery' ) );
            }    
            
        } // End enqueue_scripts
        
        /**
         * Corrects the form options
         */
        public function get_form_options( $value, $field, $id )
        {
            if( $this->apply_addon() )
            {
                switch( $field )
                {
                    case 'fp_return_page':
                        return $_SERVER[ 'REQUEST_URI' ];
                    case 'cv_enable_captcha':
                        return 0;
                    break;    
                    case 'cache':
                        return '';
                    case 'enable_paypal':
                        return 0;
                }
            }
            return $value;    
            
        } // End get_form_options
        
        /************************ METHODS FOR PRODUCT PAGE  *****************************/
        
        public function init_hook()
        {
            add_meta_box('cpcff_woocommerce_metabox', __("Calculated Fields Form", 'calculated-fields-form'), array(&$this, 'metabox_form'), 'product', 'normal', 'high');
            add_action('save_post', array(&$this, 'save_data'));
        } // End init_hook
        
        public function metabox_form()
        {
            global $post;
            
            $id = get_post_meta( $post->ID, 'woocommerce_cpcff_form', true );
            $active = get_post_meta( $post->ID, 'woocommerce_cpcff_calculate_price', true );
            $minimum_price = get_post_meta( $post->ID, 'woocommerce_cpcff_minimum_price', true );
				
            ?>
            <table class="form-table">
				<tr>
					<td>
						<?php _e('Enter the ID of the form', 'calculated-fields-form');?>:
					</td>
                    <td>
                        <input type="text" name="woocommerce_cpcff_form" value="<?php print( ( !empty( $id ) ) ? $id : '' ); ?>" />
                    </td>
                </tr>
                <tr>
					<td style="white-space:nowrap;">
						<?php _e('Calculate the product price through the form', 'calculated-fields-form');?>:
					</td>
                    <td style="width:100%;">
                        <input type="checkbox" name="woocommerce_cpcff_calculate_price" <?php print( ( !empty( $active ) ) ? 'checked' : '' ); ?> />
					</td>	
				</tr>
				<tr>
					<td>
						<?php _e('Minimum price allowed (numbers only)', 'calculated-fields-form');?>:
					</td>
					<td>	
						 <input type="text" name="woocommerce_cpcff_minimum_price" value="<?php print( ( !empty( $minimum_price ) ) ? $minimum_price : '' ); ?>">
                    </td>
                </tr>
            </table>    
			<?php	
            
        } // End metabox_form
        
        public function save_data()
        {
            global $post;
            
            if( !empty( $post ) && is_object( $post ) && $post->post_type == 'product' )
            {
                delete_post_meta( $post->ID, 'woocommerce_cpcff_form' );
                delete_post_meta( $post->ID, 'woocommerce_cpcff_calculate_price' );
                delete_post_meta( $post->ID, 'woocommerce_cpcff_minimum_price' );
                
                if( isset( $_REQUEST[ 'woocommerce_cpcff_form' ] ) )
                {
                    add_post_meta( $post->ID, 'woocommerce_cpcff_form', $_REQUEST[ 'woocommerce_cpcff_form' ], true );
                    add_post_meta( $post->ID, 'woocommerce_cpcff_minimum_price', trim( $_REQUEST[ 'woocommerce_cpcff_minimum_price' ] ), true );
                    add_post_meta( 
                        $post->ID, 
                        'woocommerce_cpcff_calculate_price', 
                        ( empty( $_REQUEST[ 'woocommerce_cpcff_calculate_price' ] ) ) ? false : true, 
                        true 
                    );
                }
            }
        }
    } // End Class
    
    // Main add-on code
    $cpcff_woocommerce_obj = new CPCFF_WooCommerce();
    
	// Add addon object to the objects list
	global $cpcff_addons_objs_list;
	$cpcff_addons_objs_list[ $cpcff_woocommerce_obj->get_addon_id() ] = $cpcff_woocommerce_obj;
}
?>