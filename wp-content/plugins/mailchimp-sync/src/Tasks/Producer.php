<?php

namespace MC4WP\Sync;

use WP_User;

/**
 * Class Scheduler
 * @package MC4WP\Sync
 *
 * @todo This is just WP_CRON adaptation, hooks should be moved into other class which can use several adapters (WP CRON, custom scheduler, etc.)
 */
class Producer {

	/**
	 * Constructor
	 * @param Worker $worker
	 */
	public function __construct( Worker $worker ) {
		$this->worker = $worker;
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		$this->worker->add_hooks();

		add_action( 'user_register', array( $this, 'schedule_subscribe' ), 99 );
		add_action( 'profile_update', array( $this, 'schedule_update' ), 99 );
		add_action( 'delete_user', array( $this, 'schedule_unsubscribe' ), 99 );
		add_action( 'updated_user_meta', array( $this, 'schedule_update_from_meta' ), 99, 2 );
	}

	/**
	 * @param string $event
	 * @param WP_User|int $user
	 * @return bool
	 */
	public function schedule( $event, $user ) {

		// do not schedule event if this change was causd by MailChimp in the first place
		if( defined( 'MC4WP_SYNC_DOING_WEBHOOK' ) && MC4WP_SYNC_DOING_WEBHOOK ) {
			return false;
		}

		$event_name = ListSynchronizer::EVENT_PREFIX . $event;
		$args = array( $user );
		$this->worker->assign( $event_name, $args );

		return true;
	}

	/**
	 * @param $user_id
	 */
	public function schedule_subscribe( $user_id ) {
		$this->schedule( 'subscribe_user', $user_id );
	}

	/**
	 * @param $user_id
	 */
	public function schedule_update( $user_id ) {
		$this->schedule( 'update_subscriber', $user_id );
	}

	/**
	 * @param $user_id
	 */
	public function schedule_unsubscribe( $user_id ) {
		$this->schedule( 'unsubscribe_user', $user_id );
	}

	/**
	 * @param $meta_id
	 * @param $user_id
	 */
	public function schedule_update_from_meta( $meta_id, $user_id ) {
		$this->schedule( 'update_subscriber', $user_id );
	}

}

