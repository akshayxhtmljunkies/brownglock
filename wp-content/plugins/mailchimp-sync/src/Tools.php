<?php

namespace MC4WP\Sync;

use WP_User;

class Tools {

	/**
	 * Returns the translated role of the current user. If that user has
	 * no role for the current blog, it returns false.
	 *
	 * @return string The name of the current role
	 **/
	public function get_user_role( WP_User $user ) {
		global $wp_roles;
		$roles = $user->roles;
		$role = array_shift( $roles );
		return isset( $wp_roles->role_names[$role] ) ? translate_user_role( $wp_roles->role_names[$role] ) : '';
	}

	/**
	 * @param WP_User $user
	 * @param string $name
	 *
	 * @return string|bool
	 */
	public function get_user_field( WP_User $user, $name) {

		$magic_fields = array( 'role' );

		if( in_array( $name, $magic_fields ) ) {
			return $this->get_user_magic_field( $user, $name );
		}

		// does user have this property?
		if( $user->has_prop( $name ) ) {
			// get value and check if it's usable
			$value = $user->get( $name );
			if( ! is_scalar( $value ) || strlen( $value ) === 0 ) {
				return false;
			}

			return $value;
		}

		$value = false;

		/**
		 * Filters the field value that is returned for unknown fields
		 *
		 * @param bool $value
		 * @param string $name
		 * @param WP_User $user
		 */
		return apply_filters( 'mailchimp_sync_get_user_field', $value, $name, $user );
	}

	/**
	 * @param WP_User $user
	 * @param         $field_name
	 *
	 * @return string|bool
	 */
	public function get_user_magic_field( WP_User $user, $field_name ) {
		switch( $field_name ) {
			case 'role':
				return $this->get_user_role( $user );
				break;
		}

		return false;
	}


}