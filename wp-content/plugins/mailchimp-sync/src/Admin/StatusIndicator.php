<?php

namespace MC4WP\Sync\Admin;

use MC4WP\Sync\Wizard;

class StatusIndicator {

	/**
	 * @var string $list_id The ID of the list to check against
	 */
	private $list_id;

	/**
	 * @var bool Boolean indicating whether all users are subscribed to the selected list
	 */
	public $status = false;

	/**
	 * @var int Percentage of users subscribed to list
	 */
	public $progress = 0;

	/**
	 * @var int Number of registered WP users
	 */
	public $user_count = 0;

	/**
	 * @var int Number of WP Users on the selected list (according to local meta value)
	 */
	public $subscriber_count = 0;

	/**
	 * @var string
	 */
	public $user_role = '';

	/**
	 * @param        $list_id
	 * @param string $user_role
	 */
	public function __construct( $list_id, $user_role = '' ) {
		$this->list_id   = $list_id;
		$this->user_role = $user_role;
		$this->wizard = new Wizard( $list_id );
	}

	/**
	 *
	 */
	public function check() {
		$this->user_count = $this->wizard->get_user_count( $this->user_role );
		$this->subscriber_count = $this->get_subscriber_count();
		$this->status = ( $this->user_count === $this->subscriber_count );
		$this->progress = ( $this->user_count > 0 ) ? ceil( $this->subscriber_count / $this->user_count * 100 ) : 0;
	}


	/**
	 * @return int
	 */
	public function get_subscriber_count() {
		global $wpdb;

		$sql = "SELECT COUNT(u.ID) FROM $wpdb->users u INNER JOIN $wpdb->usermeta um1 ON um1.user_id = u.ID";

		if( '' !== $this->user_role ) {
			$sql .= " AND um1.meta_key = %s";
			$sql .= " INNER JOIN $wpdb->usermeta um2 ON um2.user_id = um1.user_id WHERE um2.meta_key = %s AND um2.meta_value LIKE %s";

			$query = $wpdb->prepare( $sql, 'mailchimp_sync_' . $this->list_id, $wpdb->prefix . 'capabilities', '%%' . $this->user_role . '%%' );
		} else {
			$sql .= " WHERE um1.meta_key = %s";
			$query = $wpdb->prepare( $sql, 'mailchimp_sync_' . $this->list_id );
		}

		// now get number of users with meta key
		$subscriber_count = $wpdb->get_var( $query );
		return (int) $subscriber_count;
	}



}