<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * UFBL Model Class
 * All the database related tasks 
 */
if ( !class_exists( 'UFBL_Model' ) ) {

	class UFBL_Model {

		/**
		 * Model to add form into DB
		 * @global UFBL_Lib object $library_obj
		 * 
		 */
		public static function add_form() {
			global $library_obj;
			//$library_obj->print_array( $_POST );
			$form_title = sanitize_text_field( $_POST['form_title'] );
			$form_title = ($form_title == '') ? __( 'Contact Form', 'ultimate-form-builder-lite' ) : $form_title;
			$form_status = 1;
			$form_detail = serialize( $library_obj->get_default_detail() );
			$created_date = date( 'Y-m-d H:i:s:u' );
			global $wpdb;
			$check = $wpdb->insert(
					UFBL_FORM_TABLE, array(
				'form_title' => $form_title,
				'form_detail' => $form_detail,
				'form_status' => $form_status,
				'form_created' => $created_date,
				'form_modified' => $created_date
					), array(
				'%s', '%s', '%s', '%s', '%s'
					)
			);
			if ( $check == 1 ) {
				$form_id = $wpdb->insert_id;
				$redirect_url = admin_url( 'admin.php?page=ufbl&action=edit-form&form_id=' . $form_id );
				$response_array = array( 'success' => 1, 'form_id' => $form_id, 'redirect_url' => $redirect_url );
			} else {
				$response_array = array( 'success' => 0, 'error_msg' => __( 'Something went wrong.Please try again later', 'ultimate-form-builder-lite' ) );
			}
			die( json_encode( $response_array ) );
		}

		/**
		 * Returns all the forms from the database
		 * @return array
		 */
		public static function get_all_forms() {
			global $wpdb;
			$table_name = UFBL_FORM_TABLE;
			$forms = $wpdb->get_results( "SELECT * FROM $table_name" );
			return $forms;
		}

		/**
		 * Model to change status of the form
		 * 
		 */
		public static function change_form_status() {
			$form_id = sanitize_text_field( $_POST['form_id'] );
			$form_status = sanitize_text_field( $_POST['status'] );
			global $wpdb;
			$check = $wpdb->update(
					UFBL_FORM_TABLE, array(
				'form_status' => $form_status
					), array( 'form_id' => $form_id ), array(
				'%d'
					), array( '%d' )
			);
			$msg = ($check) ? __( 'Status updated.', 'ultimate-form-builder-lite' ) : __( 'Something went wrong.', 'ultimate-form-builder-lite' );
			die( $msg );
		}

		/**
		 * Model to return form settings by form id
		 * @param int $form_id
		 * @return array
		 */
		public static function get_form_detail( $form_id ) {
			global $wpdb;
			$form_table = UFBL_FORM_TABLE;
			$form_row = $wpdb->get_row( "SELECT * FROM $form_table WHERE form_id = $form_id", ARRAY_A );
			return $form_row;
		}

		/**
		 * Model to save form
		 * @return string 
		 */
		public static function save_form() {
			global $library_obj;
			$library_obj->load_core( 'save-form' );
		}

		/**
		 * Model to delete form
		 * @return string;
		 */
		public static function delete_form() {
			//global $library_obj;
			//$library_obj->print_array($_POST);
			$form_id = sanitize_text_field( $_POST['form_id'] );
			global $wpdb;
			$wpdb->delete( UFBL_FORM_TABLE, array( 'form_id' => $form_id ), array( '%d' ) );
			die( 'success' );
		}

		/**
		 * Model to get the form row by form_id
		 * @return array
		 */
		public static function get_form_row( $form_id ) {
			global $wpdb;
			$table_name = UFBL_FORM_TABLE;
			$form_row = $wpdb->get_row( "SELECT * FROM $table_name WHERE form_id = $form_id", ARRAY_A );
			return $form_row;
		}

		/**
		 * 
		 * @param array $form_data
		 * @return void
		 */
		public static function save_to_db( $form_data = array() ) {

			if ( isset( $form_data['form_id'] ) ) {
				$form_id = sanitize_text_field( $form_data['form_id'] );
				unset( $form_data['form_id'] );
				foreach ( $form_data as $key => $val ) {
					if ( !is_array( $val ) ) {
						$form_data[$key] = sanitize_text_field( $val );
					} else {
						$form_data[$key] = array_map( 'sanitize_text_field', $val );
					}
				}
				global $wpdb;
				$created_date = date( 'Y-m-d H:i:s:u' );
				$wpdb->insert(
						UFBL_ENTRY_TABLE, array(
					'form_id' => $form_id,
					'entry_detail' => maybe_serialize( $form_data ),
					'entry_created' => $created_date
						), array(
					'%d',
					'%s',
					'%s'
						)
				);
			}
		}

		/**
		 * Model to return form with id and form title
		 * @return array
		 */
		public static function get_forms() {
			global $wpdb;
			$form_table = UFBL_FORM_TABLE;
			$form_rows = $wpdb->get_results( "select `form_id`,`form_title` from $form_table", 'ARRAY_A' );
			return $form_rows;
		}

		/**
		 * Model to return form entries
		 * @param int $form_id
		 * @return array
		 */
		public static function get_forms_entries( $form_id = NULL,$limit = UFBL_ENTRY_LIMIT,$offset = 0 ) {
			global $wpdb;
			$form_table = UFBL_FORM_TABLE;
			$form_entry_table = UFBL_ENTRY_TABLE;
			if ( $form_id == NULL ) {
				$query = "select $form_entry_table.entry_id, $form_entry_table.entry_detail, $form_entry_table.entry_created, $form_table.form_title, $form_table.form_id  from $form_entry_table inner join $form_table on $form_entry_table.form_id = $form_table.form_id order by $form_entry_table.entry_id desc limit $offset, $limit";
			} else {
				$query = "select $form_entry_table.entry_id, $form_entry_table.entry_detail, $form_entry_table.entry_created, $form_table.form_title, $form_table.form_id  from $form_entry_table inner join $form_table on $form_entry_table.form_id = $form_table.form_id where $form_entry_table.form_id = $form_id  order by $form_entry_table.entry_id desc limit $offset, $limit";
			}
			$form_entry_rows = $wpdb->get_results( $query, 'ARRAY_A' );
			return $form_entry_rows;
		}
		/**
		 * Model to return form entries
		 * @param int $form_id
		 * @return array
		 */
		public static function get_total_form_entries( $form_id = NULL) {
			global $wpdb;
			$form_table = UFBL_FORM_TABLE;
			$form_entry_table = UFBL_ENTRY_TABLE;
			if ( $form_id == NULL ) {
				$query = "select $form_entry_table.entry_id, $form_entry_table.entry_detail, $form_entry_table.entry_created, $form_table.form_title, $form_table.form_id  from $form_entry_table inner join $form_table on $form_entry_table.form_id = $form_table.form_id order by $form_entry_table.entry_id desc";
			} else {
				$query = "select $form_entry_table.entry_id, $form_entry_table.entry_detail, $form_entry_table.entry_created, $form_table.form_title, $form_table.form_id  from $form_entry_table inner join $form_table on $form_entry_table.form_id = $form_table.form_id where $form_entry_table.form_id = $form_id  order by $form_entry_table.entry_id desc";
			}
			$form_entry_rows = $wpdb->get_results( $query, 'ARRAY_A' );
			$total_form_entry_rows = count($form_entry_rows);
			return $total_form_entry_rows;
		}

		/**
		 * Model to delete entry
		 * @param type $entry_id
		 * @return int
		 */
		public static function delete_entry( $entry_id ) {
			global $wpdb;
			if ( $entry_id != '' ) {
				$wpdb->delete( UFBL_ENTRY_TABLE, array( 'entry_id' => $entry_id ), array( '%d' ) );
				die( 'success' );
			}
		}

		/**
		 * Model to return entry detail by entry id
		 * @param int $entry_id
		 * @return array
		 */
		public static function get_entry_detail( $entry_id ) {
			global $wpdb;
			if ( $entry_id != '' ) {
				$form_table = UFBL_FORM_TABLE;
				$entry_table = UFBL_ENTRY_TABLE;
				$entry_row = $wpdb->get_row( "SELECT * FROM $entry_table INNER JOIN $form_table ON $entry_table.form_id = $form_table.form_id WHERE $entry_table.entry_id = $entry_id", 'ARRAY_A' );
				return $entry_row;
			} else {
				return NULL;
			}
		}

		/**
		 * Model to return all the form labels in array
		 * @param int $form_id
		 * @return array 
		 */
		public static function get_form_data( $form_id ) {
			global $wpdb;
			$form_table = UFBL_FORM_TABLE;
			$form_row = $wpdb->get_row( "SELECT form_detail FROM $form_table WHERE form_id = $form_id ", 'ARRAY_A' );
			if ( !empty( $form_row ) ) {
				$form_detail = maybe_unserialize( $form_row['form_detail'] );
				$form_labels = array();
				$form_keys = array();
				$except_field_types = array( 'submit','captcha' );
				foreach ( $form_detail['field_data'] as $field_key => $field_settings ) {
					if ( !in_array( $field_settings['field_type'], $except_field_types ) ) {
						$form_labels[] = (isset( $field_settings['field_label'] ) && $field_settings['field_label'] != '') ? esc_attr( $field_settings['field_label'] ) : __( 'Untitled', 'ultimate-form-builder-lite' ) . ' ' . $field_settings['field_type'];
						$form_keys[] = $field_key;
					}
				}
				$form_data = array( 'form_labels' => $form_labels, 'form_keys' => $form_keys );
			} else {
				$form_data = array( 'form_labels' => array(), 'form_keys' => array() );
			}
			return $form_data;
		}

		/**
		 * Model to copy form
		 * @return void
		 */
		public static function copy_form() {
			$form_id = sanitize_text_field( $_POST['form_id'] );
			$form_title = sanitize_text_field( $_POST['form_title'] );
			$form_row = self::get_form_row( $form_id );
			$form_title = ($form_title == '') ? esc_attr( $form_row['form_title'] ) . '- Copy' : $form_title;
			$form_detail = $form_row['form_detail'];
			$form_status = $form_row['form_status'];
			$created_date = date( 'Y-m-d H:i:s:u' );
			global $wpdb;
			$check = $wpdb->insert(
					UFBL_FORM_TABLE, array(
				'form_title' => $form_title,
				'form_detail' => $form_detail,
				'form_status' => $form_status,
				'form_created' => $created_date,
				'form_modified' => $created_date
					), array(
				'%s', '%s', '%s', '%s', '%s'
					)
			);
			if ( $check == 1 ) {
				$form_id = $wpdb->insert_id;
				$redirect_url = admin_url( 'admin.php?page=ufbl&action=edit-form&form_id=' . $form_id );
				$response_array = array( 'success' => 1, 'form_id' => $form_id, 'redirect_url' => $redirect_url );
			} else {
				$response_array = array( 'success' => 0, 'error_msg' => __( 'Something went wrong.Please try again later', 'ultimate-form-builder-lite' ) );
			}
			die( json_encode( $response_array ) );
		}

	}

}
