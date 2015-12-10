<?php
/**
 * Adds settings to the permalinks admin settings page.
 *
 * @class       Vibe_CustomTypes_Admin_Permalink_Settings
 * @author      VibeThemes
 * @category    Admin
 * @package     Vibe customtypes/Admin
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Vibe_CustomTypes_Admin_Permalink_Settings' ) ) :

/**
 * Vibe_CustomTypes_Admin_Permalink_Settings Class
 */
class Vibe_CustomTypes_Admin_Permalink_Settings {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		$this->settings_init();
		$this->settings_save();
	}

	/**
	 * Init our settings.
	 */
	public function settings_init() {
		add_settings_section( 'vibe-customtypes-permalink', __( 'Courses permalink base', 'vibe-customtypes' ), array( $this, 'settings' ), 'permalink' );
	}

	/**
	 * Show a slug input box.
	 */
	public function course_category_slug_input() {
		$permalinks = get_option( 'vibe_course_permalinks' );
		?>
		<input name="vibe_course_category_slug" type="text" class="regular-text code" value="<?php if ( isset( $permalinks['course_category_base'] ) ) echo esc_attr( $permalinks['course_category_base'] ); ?>" placeholder="<?php echo _x('course-category', 'slug', 'vibe-customtypes') ?>" />
		<?php
	}



	public function courses_uri(){
		$bp_pages = get_option('bp-pages');
		if(isset($bp_pages) && is_array($bp_pages) && isset($bp_pages['course'])){
		  	$courses_page_id = $bp_pages['course'];
			return $courses_page_id;
		}
		return 0;
	}

	/**
	 * Show the settings.
	 */
	public function settings() {
		echo wpautop( __( 'These settings control the permalinks used for courses. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'vibe-customtypes' ) );

		$permalinks = get_option( 'vibe_course_permalinks' );
		$course_permalink = $permalinks['course_base'];
		$quiz_permalink = $permalinks['quiz_base'];
		$unit_permalink = $permalinks['unit_base'];
		
		$courses_page_id   = $this->courses_uri();

		$base_slug      = urldecode( ( $courses_page_id > 0 && get_post( $courses_page_id ) ) ? get_page_uri( $courses_page_id ) : _x( 'course', 'default-slug', 'vibe-customtypes' ) );
		$course_base   = BP_COURSE_SLUG;


		$structures = array(
			0 => '/' . trailingslashit( $course_base ),
			1 => '/' . trailingslashit( $base_slug ),
			2 => '/' . trailingslashit( $base_slug ) . trailingslashit( '%course-cat%' )
		);
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label><input name="course_permalink" type="radio" value="<?php echo esc_attr( $structures[0] ); ?>" class="base_to_go_course" <?php checked( $structures[0], $course_permalink ); ?> /> <?php _e( 'Course', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/<?php echo BP_COURSE_SLUG; ?>/sample-course/</code></td>
				</tr>
				<tr>
					<th><label><input name="course_permalink" type="radio" value="<?php echo esc_attr( $structures[1] ); ?>" class="base_to_go_course" <?php checked( $structures[1], $course_permalink ); ?> /> <?php _e( 'Course Directory', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ); ?>/sample-course/</code></td>
				</tr>
				<!--tr>
					<th><label><input name="course_permalink" type="radio" value="<?php echo esc_attr( $structures[2] ); ?>" class="base_to_go_course" <?php checked( $structures[2], $course_permalink ); ?> /> <?php _e( 'Course Directory / Category', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $base_slug ) .'/'. trailingslashit( '%course-cat%' ); ?>/sample-course/</code></td>
				</tr-->
				<tr>
					<th><label><input name="course_permalink" id="vibe_course_custom_selection" type="radio" value="custom" class="tog" <?php checked( in_array( $course_permalink, $structures ), false ); ?> />
						<?php _e( 'Custom Base', 'vibe-customtypes' ); ?></label></th>
					<td>
						<input name="course_permalink_structure" id="vibe_course_permalink_structure" type="text" value="<?php echo esc_attr( $course_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'vibe-customtypes' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table">	
			<tbody>
				<tr><th><h3><?php _e('Quiz Permalinks','vibe-customtypes'); ?></h3></th></tr>
				<tr>
					<th><label><input name="quiz_permalink" type="radio" value="<?php echo esc_attr( '/'.WPLMS_QUIZ_SLUG ); ?>" class="base_to_go_quiz" <?php checked( WPLMS_QUIZ_SLUG, $quiz_permalink ); ?> /> <?php _e( 'Quiz', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/<?php echo WPLMS_QUIZ_SLUG; ?>/sample-quiz/</code></td>
				</tr>
				<!--tr>
					<th><label><input name="quiz_permalink" type="radio" value="<?php echo esc_attr( '/%quizcourse%'); ?>" class="base_to_go_quiz" <?php checked( '/%quizcourse%', $quiz_permalink ); ?>/> <?php _e( 'Quiz Course', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/%quizcourse%/sample-quiz/</code></td>
				</tr-->
				<tr>
					<th><label><input name="quiz_permalink" id="vibe_quiz_custom_selection" type="radio" value="custom" class="tog" />
						<?php _e( 'Custom Base', 'vibe-customtypes' ); ?></label></th>
					<td>
						<input name="quiz_permalink_structure" id="vibe_quiz_permalink_structure" type="text" value="<?php echo esc_attr( $quiz_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'vibe-customtypes' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="form-table">	
			<tbody>
				<tr><th><h3><?php _e('Unit Permalinks','vibe-customtypes'); ?></h3></th></tr>
				<tr>
					<th><label><input name="unit_permalink" type="radio" value="<?php echo esc_attr( '/'.WPLMS_UNIT_SLUG ); ?>" class="base_to_go_unit" <?php checked( WPLMS_UNIT_SLUG, $unit_permalink ); ?>/> <?php _e( 'Unit', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/<?php echo WPLMS_UNIT_SLUG; ?>/sample-unit/</code></td>
				</tr>
				<!--tr>
					<th><label><input name="unit_permalink" type="radio" value="<?php echo esc_attr( '/%unitcourse%'); ?>" class="base_to_go_unit" <?php checked( '/%unitcourse%', $unit_permalink ); ?>/> <?php _e( 'Unit Course', 'vibe-customtypes' ); ?></label></th>
					<td><code><?php echo esc_html( home_url() ); ?>/%unitcourse%/sample-unit/</code></td>
				</tr-->
				<tr>
					<th><label><input name="unit_permalink" id="vibe_unit_custom_selection" type="radio" value="custom" class="tog" />
						<?php _e( 'Custom Base', 'vibe-customtypes' ); ?></label></th>
					<td>
						<input name="unit_permalink_structure" id="vibe_unit_permalink_structure" type="text" value="<?php echo esc_attr( $unit_permalink ); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'vibe-customtypes' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery( function() {
				jQuery('input.base_to_go_course').change(function() { 
					jQuery('#vibe_course_permalink_structure').val( jQuery( this ).val() );
				});
				jQuery('#vibe_course_permalink_structure').focus( function(){
					jQuery('#vibe_course_permalink_structure').click();
				} );

				jQuery('input.base_to_go_quiz').change(function() {
					jQuery('#vibe_quiz_permalink_structure').val( jQuery( this ).val() );
				});
				jQuery('input.base_to_go_unit').change(function() {
					jQuery('#vibe_unit_permalink_structure').val( jQuery( this ).val() );
				});
				
				jQuery('#vibe_quiz_permalink_structure').focus( function(){
					jQuery('#vibe_quiz_permalink_structure').click();
				} );
				jQuery('#vibe_unit_permalink_structure').focus( function(){
					jQuery('#vibe_unit_permalink_structure').click();
				} );
			} );
		</script>
		<?php
	}

	/**
	 * Save the settings.
	 */
	public function settings_save() {

		if ( ! is_admin() ) {
			return;
		}

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page
		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) && isset( $_POST['course_permalink'] ) ) {
			// Cat and tag bases
			$vibe_course_category_slug  = sanitize_text_field( $_POST['vibe_course_category_slug'] );

			$permalinks = get_option( 'vibe_course_permalinks' );

			if ( ! $permalinks ) {
				$permalinks = array();
			}

			// Product base
			$course_permalink = sanitize_text_field( $_POST['course_permalink'] );
			$quiz_permalink = sanitize_text_field( $_POST['quiz_permalink'] );
			$unit_permalink = sanitize_text_field( $_POST['unit_permalink'] );

			if ( $course_permalink == 'custom' ) {
				// Get permalink without slashes
				$course_permalink = trim( sanitize_text_field( $_POST['course_permalink_structure'] ), '/' );

				// This is an invalid base structure and breaks pages
				if ( '%course-cat%' == $course_permalink ) {
					$course_permalink = _x( 'course', 'slug', 'vibe-customtypes' ) . '/' . $course_permalink;
				}

				// Prepending slash
				$course_permalink = '/' . $course_permalink;
			} elseif ( empty( $course_permalink ) ) {
				$course_permalink = false;
			}

			if ( $quiz_permalink == 'custom' ) {
				$quiz_permalink = trim( sanitize_text_field( $_POST['quiz_permalink_structure'] ), '/' );
				$quiz_permalink = '/' . $quiz_permalink;
			} elseif ( empty( $quiz_permalink ) ) {
				$quiz_permalink = false;
			}

			if ( $unit_permalink == 'custom' ) {
				$unit_permalink = trim( sanitize_text_field( $_POST['unit_permalink_structure'] ), '/' );
				$unit_permalink = '/' . $unit_permalink;
			} elseif ( empty( $unit_permalink ) ) {
				$unit_permalink = false;
			}

			$permalinks['course_base'] = untrailingslashit( $course_permalink );
			$permalinks['quiz_base'] = untrailingslashit( $quiz_permalink );
			$permalinks['unit_base'] = untrailingslashit( $unit_permalink );

			$courses_page_id   = $this->courses_uri();
			$courses_permalink = ( $courses_page_id > 0 && get_post( $courses_page_id ) ) ? get_page_uri( $courses_page_id ) : _x( 'shop', 'default-slug', 'vibe-customtypes' );

			if ( $courses_page_id && trim( $permalinks['course_base'], '/' ) === $courses_permalink ) {
				$permalinks['use_verbose_page_rules'] = true;
			}

			update_option( 'vibe_course_permalinks', $permalinks );
		}
	}
}

endif;

add_action('admin_init','initiate_vibe_permalinks');
function initiate_vibe_permalinks(){
	return new Vibe_CustomTypes_Admin_Permalink_Settings();	
}


//add_filter('post_type_link', 'wplms_unit_permalinks', 10, 3);
//add_filter('post_type_link', 'wplms_quiz_permalinks', 10, 3);

function wplms_unit_permalinks($permalink, $post, $leavename){
	$post_id = $post->ID;
	if($post->post_type != 'unit' || empty($permalink) || in_array($post->post_status, array('draft', 'pending', 'auto-draft')))
		return $permalink;
	global $wpdb;
	$course_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key= 'vibe_course_curriculum' AND meta_value LIKE %s LIMIT 1;", "%{$post_id}%" ) );
	if(is_numeric($course_id)){
		$slug = get_post_field('post_name',$course_id);
	}
	 
	if(empty($slug)) { $slug = WPLMS_UNIT_SLUG; }
	 
	$permalink = str_replace('%unitcourse%', $slug, $permalink);
	 
	return $permalink;
}


function wplms_quiz_permalinks($permalink, $post, $leavename){
	$post_id = $post->ID;
	if($post->post_type != 'quiz' || empty($permalink) || in_array($post->post_status, array('draft', 'pending', 'auto-draft')))
		return $permalink;
	global $wpdb;
	$course_id =  get_post_meta($post_id,'vibe_quiz_course',true);
	if(is_numeric($course_id)){
		$slug = get_post_field('post_name',$course_id);
	}
	 
	if(empty($slug)) { $slug = WPLMS_QUIZ_SLUG; }
	 
	$permalink = str_replace('%quizcourse%', $slug, $permalink);
	 
	return $permalink;
}
