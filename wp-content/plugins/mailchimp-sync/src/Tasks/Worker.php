<?php

namespace MC4WP\Sync;

interface Worker {

	/**
	 * @return void
	 */
	public function add_hooks();

	/**
	 * @param       $event
	 * @param array $args
	 *
	 * @return mixed
	 */
	public function assign( $event, array $args );

	/**
	 * @return mixed
	 */
	public function work();
}