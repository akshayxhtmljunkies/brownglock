<?php

namespace MailChimp\Sync\Admin;

use WP_User;

class FieldMapper {

	/**
	 * @var array
	 */
	public $rules = array();

	/**
	 * @var array
	 */
	public $mailchimp_fields = array();

	/**
	 * @var array
	 */
	public $user_fields = array();

	/**
	 * @param array $rules
	 * @param array $mailchimp_fields
	 */
	public function __construct( array $rules, array $mailchimp_fields = array() ) {
		// reset array index
		$this->rules = array_values( $rules );

		// add empty mapping rule to end of array
		$this->rules[] = array(
			'user_field' => '',
			'mailchimp_field' => ''
		);

		$this->mailchimp_fields = $mailchimp_fields;
		$this->user_fields = $this->get_current_user_fields();
	}

	/**
	 * Combines all fields and sorts 'em
	 *
	 * @return array
	 */
	public function get_current_user_fields() {
		$default_fields = $this->get_current_user_default_fields();
		$custom_fields = $this->get_current_user_custom_fields();
		$magic_fields = $this->get_magic_fields();

		$meta = array_merge( $custom_fields, $default_fields, $magic_fields );
		sort( $meta );
		return $meta;
	}

	/**
	 * An array of "magic" fields for which the value will be calculated by the plugin
	 *
	 * @return array
	 */
	protected function get_magic_fields() {
		return array(
			'role',
		);
	}

	/**
	 * Default fields, each user has these.
	 *
	 * @return array
	 */
	protected function get_current_user_default_fields() {
		$user = wp_get_current_user();
		$hidden_fields = array( 'user_pass', 'user_status', 'spam', 'deleted', 'user_activation_key' );
		$fields = array();

		foreach( $user->data as $field => $value ) {

			// don't use fields which should be hidden
			if( in_array( $field, $hidden_fields ) ) {
				continue;
			}

			$fields[] = $field;
		}

		return $fields;
	}

	/**
	 * Gets all custom fields for the currently logged-in user.
	 *
	 * @return array
	 */
	protected function get_current_user_custom_fields() {
		$user = wp_get_current_user();
		return $this->get_user_custom_fields( $user );
	}

	/**
	 * Guesses al custom fields for a given user
	 *
	 * @param WP_User $user
	 *
	 * @return array
	 */
	protected function get_user_custom_fields( WP_User $user ) {

		$meta = array_map(
			function( $a ){ return $a[0]; },
			get_user_meta( $user->ID )
		);

		$hidden_fields = array(
			'show_admin_bar_front',
			'use_ssl',
			'comment_shortcuts',
			'dismissed_wp_pointers',
			'show_welcome_panel',
			'rich_editing',
			'admin_color'
		);

		$fields = array();

		foreach( $meta as $key => $value ) {
			// only use scalar values
			// ignore fields starting with wp_ or _
			// don't use fields which are in our array of hidden fields
			if( ! is_scalar( $value )
			    || strpos( $key, 'wp_' ) === 0
			    || strpos( $key, '_' ) === 0
			    || strpos( $key, 'mailchimp_sync_' ) === 0
			    || is_serialized( $value )
				|| in_array( $key, $hidden_fields )) {
				continue;
			}

			$fields[] = $key;
		}

		return $fields;
	}

}