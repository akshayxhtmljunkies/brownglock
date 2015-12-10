<?php

interface SPU_Providers {

	/**
	 * Initialize provider class and provider api
	 */
	function __construct();

	/**
	 * Pings the provider API to see if we're connected or connection is valid
	 * @return boolean
	 */
	public function is_connected();

	/**
	 * Get providers Lists from cache or renew
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = false);

	/**
	 * The function that actually subscribe user to provider
	 *
	 * @param $lead array of data passed to provider
	 * @param $box_opts array of popup options
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts );
}