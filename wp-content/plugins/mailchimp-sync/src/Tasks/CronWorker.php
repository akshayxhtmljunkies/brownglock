<?php


namespace MC4WP\Sync;

/**
 * Class Worker
 * @package MC4WP\Sync
 */
class CronWorker implements Worker {

	/**
	 * Nothing here
	 */
	public function add_hooks() {}

	/**
	 * @param string $event
	 * @param array $args
	 * @return bool
	 */
	public function assign( $event, array $args = array() ) {

		// we've already scheduled this
		if( wp_next_scheduled( $event, $args ) !== false ) {
			return false;
		}

		wp_schedule_single_event( time() + 1, $event, $args );
		return true;
	}

	/**
	 * This is handled by WP_Cron internally
	 * @see wp_cron
	 */
	public function work() {}
}