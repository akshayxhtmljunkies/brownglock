<?php


namespace MC4WP\Sync;

/**
 * Class ShutdownWorker
 * @package MC4WP\Sync
 */
class ShutdownWorker implements Worker {

	/**
	 * @var array
	 */
	protected $tasks = array();

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		// hook into the various user related actions
		add_action( 'shutdown', array( $this, 'work' ) );
	}

	/**
	 * @param string $event
	 * @param array $args
	 * @return bool
	 */
	public function assign( $event, array $args = array() ) {

		$task = array_merge( array( $event ), $args );

		if( in_array( $task, $this->tasks ) ) {
			return false;
		}

		$this->tasks[] = $task;
		return true;
	}

	/**
	 * Fire a `do_action` for each task
	 */
	public function work() {
		foreach( $this->tasks as $task ) {
			call_user_func_array( 'do_action', $task );
		}
	}
}