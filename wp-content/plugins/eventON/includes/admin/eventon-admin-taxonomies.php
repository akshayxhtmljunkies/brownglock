<?php
/**
 * Admin taxonomy functions
 *
 *
 * @author 		Ashan Jay
 * @category 	Admin
 * @package 	eventon/Admin/Taxonomies
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class eventon_taxonomies{

	function __construct(){
		add_action( 'admin_init', array($this,'eventon_taxonomy_admin' ));
		add_action( 'event_type_pre_add_form', array($this, 'event_type_description' ));
		add_action( 'admin_init', array($this, 'eventon_add_tax') );

		// event type 1
		add_filter( 'manage_edit-event_type_columns', array($this,'event_type_edit_columns'),5 );
		add_filter( 'manage_event_type_custom_column', array($this,'event_type_custom_columns'),5,3 );

		// event type 2
		add_filter( 'manage_edit-event_type_2_columns', array($this,'event_type_edit_columns'),5 );
		add_filter( 'manage_event_type_2_custom_column', array($this,'event_type_custom_columns'),5,3 );

		add_action( 'event_type_add_form_fields', array($this,'evo_tax_add_new_meta_field_et1'), 10, 2 );

		add_action( 'event_type_edit_form_fields', array($this,'evo_tax_edit_new_meta_field_et1'), 10, 2 );
		add_action( 'edited_event_type', array($this,'evo_tax_save_new_meta_field_et1'), 10, 2 ); 
		add_action( 'create_event_type', array($this,'evo_tax_save_new_meta_field_et1'), 10, 2 );

		// event location
			add_filter("manage_edit-event_location_columns", array($this,'eventon_evLocation_theme_columns')); 
			add_filter("manage_event_location_custom_column", array($this,'eventon_manage_evLocation_columns'), 10, 3);
			add_action( 'event_location_add_form_fields', array($this,'eventon_taxonomy_add_new_meta_field'), 10, 2 );
	 		add_action( 'event_location_edit_form_fields', array($this,'eventon_taxonomy_edit_meta_field'), 10, 2 );
	 		add_action( 'edited_event_location', array($this,'evo_save_taxonomy_custom_meta'), 10, 2 );  
			add_action( 'create_event_location', array($this,'evo_save_taxonomy_custom_meta'), 10, 2 );

		// event organizer
			add_filter("manage_edit-event_organizer_columns", array($this,'eventon_evorganizer_theme_columns'));  
			add_filter("manage_event_organizer_custom_column", array($this,'eventon_manage_evorganizer_columns'), 10, 3); 
			add_action( 'event_organizer_add_form_fields', array($this,'eventon_taxonomy_add_new_meta_field_org'), 10, 2 );
			add_action( 'event_organizer_edit_form_fields', array($this,'eventon_taxonomy_edit_meta_field_org'), 10, 2 );
			add_action( 'edited_event_organizer', array($this,'evo_save_taxonomy_custom_meta'), 10, 2 );  
			add_action( 'create_event_organizer', array($this,'evo_save_taxonomy_custom_meta'), 10, 2 );

	}

	// other settings
		function eventon_taxonomy_admin(){
			global $pagenow;
			if($pagenow =='edit-tags.php' && !empty($_GET['taxonomy']) 
				&& ($_GET['taxonomy']=='event_location' || 
				$_GET['taxonomy']=='event_organizer' )
				&& !empty($_GET['post_type']) 
				&& $_GET['post_type']=='ajde_events'){
				wp_enqueue_media();
			}
		}
		function event_type_description() {
			echo wpautop( __( 'Event categories for events can be managed here. To change the order of categories on the front-end you can drag and drop to sort them. To see more categories listed click the "screen options" link at the top of the page.', 'eventon' ) );
		}

	// event types
		function eventon_add_tax(){
			$options = get_option('evcal_options_evcal_1');
			for($x=3; $x<6; $x++){
				if(!empty($options['evcal_ett_'.$x]) && $options['evcal_ett_'.$x]=='yes'){
					add_filter( "manage_edit-event_type_{$x}_columns", array($this,'event_type_edit_columns'),5 );
					add_filter( "manage_event_type_{$x}_custom_column", array($this,'event_type_custom_columns'),5,3 );
				}
			}
		}
		function event_type_edit_columns($defaults){
		    $defaults['event_type_id'] = __('ID');
		    return $defaults;
		} 
		function event_type_custom_columns($value, $column_name, $id){
			if($column_name == 'event_type_id'){
				$t_id = (int)$id;
				$term_meta = get_option( "evo_et_taxonomy_$t_id" );
				$term_color = ($term_meta['et_color'])? '<span class="evoterm_color" style="background-color:#'.$term_meta['et_color'].'"></span>':false;

				echo $t_id.$term_color;
			}
		}

	// add term page
		function evo_tax_add_new_meta_field_et1() {
			// this will add the custom meta field to the add new term page
			?>
			<div class="form-field" id='evo_et1_color'>				
				<p class='evo_et1_color_circle' hex='bbbbbb'></p>
				<label for="term_meta[et_color]"><?php _e( 'Color', 'eventon' ); ?></label>
				<input type="hidden" name="term_meta[et_color]" id="term_meta[et_color]" value="">
				<p class="description"><?php _e( 'Pick a color','eventon' ); ?></p>
			</div>
			
		<?php
		}
	// Edit term page
		function evo_tax_edit_new_meta_field_et1($term) {		 
			// put the term ID into a variable
			$t_id = $term->term_id;
		 
			// retrieve the existing value(s) for this meta field. This returns an array
			$term_meta = get_option( "evo_et_taxonomy_$t_id" ); ?>
			<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[et_color]"><?php _e( 'Color', 'eventon' ); ?></label></th>
				<td id='evo_et1_color'>
					<?php $__this_value = esc_attr( $term_meta['et_color'] ) ? esc_attr( $term_meta['et_color'] ) : ''; ?>
					<p class='evo_et1_color_circle' hex='<?php echo $__this_value;?>' style='background-color:#<?php echo $__this_value;?>'></p>
					<input type="hidden" name="term_meta[et_color]" id="term_meta[et_color]" value="<?php echo $__this_value;?>">
					<p class="description"><?php _e( 'Pick a color','eventon' ); ?></p>
				</td>
			</tr>
		<?php
		}
	// Save extra taxonomy fields callback function.
		function evo_tax_save_new_meta_field_et1( $term_id ) {
			if ( isset( $_POST['term_meta'] ) ) {
				$t_id = $term_id;
				$term_meta = get_option( "evo_et_taxonomy_$t_id" );
				$cat_keys = array_keys( $_POST['term_meta'] );
				foreach ( $cat_keys as $key ) {
					if ( isset ( $_POST['term_meta'][$key] ) ) {
						$term_meta[$key] = $_POST['term_meta'][$key];
					}
				}
				// Save the option array.
				update_option( "evo_et_taxonomy_$t_id", $term_meta );
			}
		}  
		
	//	TAXONOMY - event location
		// remove some columns		
			function eventon_evLocation_theme_columns($theme_columns) {
			    $new_columns = array(
			        'cb' => '<input type="checkbox" />',
			        //'id' => __('ID','eventon'),
			        'name' => __('Name','eventon'),
			        'event_location_details' => __('Info','eventon'),
			        //'event_location' => __('Address','eventon'),
			        //'ev_lonlat' => __('Lon/Lat','eventon'),
			        'posts' => __('Count','eventon'),
					//      'description' => __('Description'),
			        'slug' => __('Slug'),
			    );			    
			    //return array_merge($theme_columns, $new_columns);
			    return $new_columns;
			}

		// Add event location address field 
			function eventon_manage_evLocation_columns($out, $column_name, $term_id) {
			    //$theme = get_term($term_id, 'event_location');
			    switch ($column_name) {
			        case 'event_location_details': 
			        	$term_meta = get_option( "taxonomy_$term_id" );
			        	$term = get_term_by('id', $term_id, 'event_location'); 

			        	$ADDRESS = esc_attr( $term_meta['location_address'] ) ? esc_attr( $term_meta['location_address'] ) : '-';

			        	$lon = (!empty($term_meta['location_lon']))? esc_attr( $term_meta['location_lon'] ) : '-';
			        	$lat = (!empty($term_meta['location_lat']))? esc_attr( $term_meta['location_lat'] ) : '-';	

			        	$out = "<p><b>".__('ADDRESS','eventon').':</b> '.$ADDRESS."<br/>
			        	<b>LAT/LON:</b> {$lat}/{$lon}<br/>
			        	<b>ID: </b>{$term_id} <br/>
			        	<a href='".get_site_url().'/event-location/'.$term->slug."'>VIEW</a>
			        	</p>";
			        break;
			        case 'event_location': 
			        	$term_meta = get_option( "taxonomy_$term_id" );
			        	$out = "<p>".esc_attr( $term_meta['location_address'] ) ? esc_attr( $term_meta['location_address'] ) : ''."</p>";
			        break;
			        case 'ev_lonlat': 
			        	$term_meta = get_option( "taxonomy_$term_id" );
			        	$lon = (!empty($term_meta['location_lon']))? esc_attr( $term_meta['location_lon'] ) : '-';
			        	$lat = (!empty($term_meta['location_lat']))? esc_attr( $term_meta['location_lat'] ) : '-';			        	
			        	$out = "<p>{$lon} / {$lat}</p>";
			        break;
			        case 'id': $out = $term_id; break;		        
			 
			        default:
			            break;
			    }
			    return $out;    
			}
		// add term page
			function eventon_taxonomy_add_new_meta_field() {
				// this will add the custom meta field to the add new term page
				?>
				<div class="form-field">
					<label for="term_meta[location_address]"><?php _e( 'Location Address', 'eventon' ); ?></label>
					<input type="text" name="term_meta[location_address]" id="term_meta[location_address]" value="">
					<p class="description"><?php _e( 'Enter a location address','eventon' ); ?></p>
				</div>
				<div class="form-field">
					<label for="term_meta[location_lat]"><?php _e( 'Latitude', 'eventon' ); ?></label>
					<input type="text" name="term_meta[location_lat]" id="term_meta[location_lat]" value="">
					<p class="description"><?php _e( '(Optional) latitude for address','eventon' ); ?></p>
				</div>
				<div class="form-field">
					<label for="term_meta[location_lon]"><?php _e( 'Longitude', 'eventon' ); ?></label>
					<input type="text" name="term_meta[location_lon]" id="term_meta[location_lon]" value="">
					<p class="description"><?php _e( '(Optional) longitude for address','eventon' ); ?></p>
				</div>
				
				<div class="form-field evo_metafield_image">
					<label for="term_meta[evo_loc_img]"><?php _e( 'Image', 'eventon' ); ?></label>
					
					<input style='width:auto' class="custom_upload_image_button button <?php echo 'chooseimg';?>" data-txt='<?php echo __('Remove Image','eventon');?>' type="button" value="<?php _e('Choose Image','eventon');?>" /><br/>
					<span class='evo_loc_image_src image_src'><img src='' style='display:none'/></span>
					
					<input class='evo_loc_img evo_meta_img' type="hidden" name="term_meta[evo_loc_img]" id="term_meta[evo_loc_img]" value="">
					<p class="description"><?php _e( '(Optional) Location Image','eventon' ); ?></p>
				</div>
			<?php
			}
		
		// Edit term page
			function eventon_taxonomy_edit_meta_field($term) {
			 
				// put the term ID into a variable
				$t_id = $term->term_id;
			 
				// retrieve the existing value(s) for this meta field. This returns an array
				$term_meta = get_option( "taxonomy_$t_id" ); ?>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="term_meta[location_address]"><?php _e( 'Location Address', 'eventon' ); ?></label></th>
					<td>
						<input type="text" name="term_meta[location_address]" id="term_meta[location_address]" value="<?php echo esc_attr( $term_meta['location_address'] ) ? esc_attr( stripslashes($term_meta['location_address']) ) : ''; ?>">
						<p class="description"><?php _e( 'Enter a location address','eventon' ); ?></p>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="term_meta[location_lat]"><?php _e( 'Latitude', 'eventon' ); ?></label></th>
					<td>
						<input type="text" name="term_meta[location_lat]" id="term_meta[location_lat]" value="<?php echo esc_attr( $term_meta['location_lat'] ) ? esc_attr( $term_meta['location_lat'] ) : ''; ?>">
						<p class="description"><?php _e( '(Optional) latitude for address','eventon' ); ?></p>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="term_meta[location_lon]"><?php _e( 'Longitude', 'eventon' ); ?></label></th>
					<td>
						<input type="text" name="term_meta[location_lon]" id="term_meta[location_lon]" value="<?php echo esc_attr( $term_meta['location_lon'] ) ? esc_attr( $term_meta['location_lon'] ) : ''; ?>">
						<p class="description"><?php _e( '(Optional) longitude for address','eventon' ); ?></p>
					</td>
				</tr>
				
				<tr class="form-field">
					<th scope="row" valign="top"><label for="term_meta[evo_loc_img]"><?php _e( 'Image', 'eventon' ); ?></label></th>
					<td class='evo_metafield_image'>
						<?php 
							if(!empty($term_meta['evo_loc_img'])){
								$img_url = wp_get_attachment_image_src($term_meta['evo_loc_img'],'medium');
							}else{ $img_url=false;}

							$__button_text = (!empty($term_meta['evo_loc_img']))? __('Remove Image','eventon'): __('Choose Image','eventon');
							$__button_text_not = (empty($term_meta['evo_loc_img']))? __('Remove Image','eventon'): __('Choose Image','eventon');
							$__button_class = (!empty($term_meta['evo_loc_img']))? 'removeimg':'chooseimg';
						?>
						
						<input style='width:auto' class="custom_upload_image_button button <?php echo $__button_class;?>" data-txt='<?php echo $__button_text_not;?>' type="button" value="<?php echo $__button_text;?>" /><br/>
						<span class='evo_loc_image_src image_src'><img src='<?php echo $img_url[0];?>' style='<?php echo !empty($term_meta['evo_loc_img'])?'':'display:none';?>'/></span>
						
						<input class='evo_loc_img evo_meta_img' type="hidden" name="term_meta[evo_loc_img]" id="term_meta[evo_loc_img]" value="<?php echo !empty( $term_meta['evo_loc_img'] ) ? esc_attr( $term_meta['evo_loc_img'] ) : ''; ?>">
						<p class="description"><?php _e( '(Optional) Location Image','eventon' ); ?></p>
					</td>
				</tr>
			<?php
			}
				
	// Event Organizer
		// remove some columns
			function eventon_evorganizer_theme_columns($theme_columns) {
			    $new_columns = array(
			        'cb' => '<input type="checkbox" />',
			        'id' => __('ID','eventon'),
			        'name' => __('Name','eventon'),
			        'contact' => __('Contact Info','eventon'),
			//      'description' => __('Description'),
			        'slug' => __('Slug')
			        );
			    return $new_columns;
			}
		// Add event organizer columns
			function eventon_manage_evorganizer_columns($out, $column_name, $term_id) {
			    //$theme = get_term($term_id, 'event_location');
			    switch ($column_name) {
			        case 'contact': 
			        	$term_meta = get_option( "taxonomy_$term_id" );
			        	$out = "<p>".esc_attr( $term_meta['evcal_org_contact'] ) ? esc_attr( $term_meta['evcal_org_contact'] ) : ''."</p>";
			        break;
			        case 'id': 
			        	$out = $term_id;
			        break;
			       
			        default:
			            break;
			    }
			    return $out;    
			}
		// add term page
			function eventon_taxonomy_add_new_meta_field_org() {
				// this will add the custom meta field to the add new term page
				?>
				<div class="form-field">
					<label for="term_meta[evcal_org_contact]"><?php _e( 'Contact Information', 'eventon' ); ?></label>
					<input type="text" name="term_meta[evcal_org_contact]" id="term_meta[evcal_org_contact]" value="">
					<p class="description"><?php _e( 'Enter Organizer Contact Information','eventon' ); ?></p>
				</div>
				<div class="form-field evo_metafield_image">
					<label for="term_meta[evo_org_img]"><?php _e( 'Image', 'eventon' ); ?></label>
					
					<input style='width:auto' class="custom_upload_image_button button <?php echo 'chooseimg';?>" data-txt='<?php echo __('Remove Image','eventon');?>' type="button" value="<?php _e('Choose Image','eventon');?>" /><br/>
					<span class='evo_org_image_src image_src'><img src='' style='display:none'/></span>
					
					<input class='evo_org_img evo_meta_img' type="hidden" name="term_meta[evo_org_img]" id="term_meta[evo_org_img]" value="">
					<p class="description"><?php _e( '(Optional) Organizer Image','eventon' ); ?></p>
				</div>
				
			<?php
			}
		// Edit term
			function eventon_taxonomy_edit_meta_field_org($term) {
		 
				// put the term ID into a variable
				$t_id = $term->term_id;
			 
				// retrieve the existing value(s) for this meta field. This returns an array
				$term_meta = get_option( "taxonomy_$t_id" ); ?>
				<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[evcal_org_contact]"><?php _e( 'Contact Information', 'eventon' ); ?></label></th>
					<td>
						<input type="text" name="term_meta[evcal_org_contact]" id="term_meta[evcal_org_contact]" value="<?php echo esc_attr( $term_meta['evcal_org_contact'] ) ? esc_attr( $term_meta['evcal_org_contact'] ) : ''; ?>">
						<p class="description"><?php _e( 'Enter Organizer Contact Information','eventon' ); ?></p>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="term_meta[evo_org_img]"><?php _e( 'Image', 'eventon' ); ?></label></th>
					<td class='evo_metafield_image'>
						<?php 
							if(!empty($term_meta['evo_org_img'])){
								$img_url = wp_get_attachment_image_src($term_meta['evo_org_img'],'medium');
							}else{ $img_url=false;}

							$__button_text = (!empty($term_meta['evo_org_img']))? __('Remove Image','eventon'): __('Choose Image','eventon');
							$__button_text_not = (empty($term_meta['evo_org_img']))? __('Remove Image','eventon'): __('Choose Image','eventon');
							$__button_class = (!empty($term_meta['evo_org_img']))? 'removeimg':'chooseimg';
						?>						
						<input style='width:auto' class="custom_upload_image_button button <?php echo $__button_class;?>" data-txt='<?php echo $__button_text_not;?>' type="button" value="<?php echo $__button_text;?>" /><br/>
						<span class='evo_org_image_src image_src'><img src='<?php echo $img_url[0];?>' style='<?php echo !empty($term_meta['evo_org_img'])?'':'display:none';?>'/></span>
						
						<input class='evo_org_img evo_meta_img' type="hidden" name="term_meta[evo_org_img]" id="term_meta[evo_org_img]" value="<?php echo !empty( $term_meta['evo_org_img'] ) ? esc_attr( $term_meta['evo_org_img'] ) : ''; ?>">
						<p class="description"><?php _e( '(Optional) Organizer Image','eventon' ); ?></p>
					</td>
				</tr>
				
			<?php
			}
		
	// Save extra taxonomy fields callback function.
		function evo_save_taxonomy_custom_meta( $term_id ) {
			if ( isset( $_POST['term_meta'] ) ) {
				$t_id = $term_id;
				$term_meta = get_option( "taxonomy_$t_id" );
				$cat_keys = array_keys( $_POST['term_meta'] );
				foreach ( $cat_keys as $key ) {
					if ( isset ( $_POST['term_meta'][$key] ) ) {
						$term_meta[$key] = $_POST['term_meta'][$key];
					}
				}
				// Save the option array.
				update_option( "taxonomy_$t_id", $term_meta );
			}
		}  
}
new eventon_taxonomies();
	
?>