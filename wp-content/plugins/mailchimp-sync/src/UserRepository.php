<?php

namespace MC4WP\Sync;

use WP_User;
use WP_User_Query;

/**
 * Class UserRepository
 *
 * @package MC4WP\Sync
 * @property ListSynchronizer $synchronizer
 */
class UserRepository {

	/**
	 * @var string
	 */
	protected $mailchimp_list_id = '';

	/**
	 * @param $mailchimp_list_id
	 */
	public function __construct( $mailchimp_list_id = '' ) {
		$this->mailchimp_list_id = $mailchimp_list_id;
	}

	/**
	 * @param string $id
	 *
	 * @return WP_User|null;
	 */
	public function get_user_by_mailchimp_id( $id ) {
		return $this->get_first_user(
			array(
				'meta_key'     => $this->synchronizer->meta_key,
				'meta_value'   => $id,
				'limit' => 1
			)
		);
	}

	/**
	 * @param $role
	 *
	 * @return WP_User|null
	 */
	public function get_first_user_with_role( $role ) {
		return $this->get_first_user(
			array(
				'role' => $role
			)
		);
	}

	/**
	 * @return WP_User
	 */
	public function get_current_user() {
		return wp_get_current_user();
	}

	/**
	 * @param array $args
	 *
	 * @return null|WP_User
	 */
	public function get_first_user( $args = array() ) {
		$args['limit'] = 1;
		$users = get_users( $args );

		if( ! is_array( $users ) || empty( $users ) ) {
			return null;
		}

		return $users[0];
	}

	/**
	 * @param $value
	 *
	 * @return ListSynchronizer|null
	 */
	public function __get( $value ) {
		switch( $value ) {
			case 'synchronizer':
				return  new ListSynchronizer( $this->mailchimp_list_id );
				break;
		}

		return null;
	}

}