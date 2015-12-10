<?php

namespace MC4WP\Sync;

use MC4WP\Sync\CLI\CommandProvider;
use MC4WP\Sync\Webhook;

final class Plugin {

	/**
	 * @const VERSION
	 */
	const VERSION = MAILCHIMP_SYNC_VERSION;

	/**
	 * @const FILE
	 */
	const FILE = MAILCHIMP_SYNC_FILE;

	/**
	 * @const DIR
	 */
	const DIR = MAILCHIMP_SYNC_DIR;

	/**
	 * @const OPTION_NAME Option name
	 */
	const OPTION_NAME = 'mailchimp_sync';

	/**
	 * @var array
	 */
	public $options = array();

	/**
	 * Constructor
	 */
	public function __construct() {	}

	/**
	 * @var ListSynchronizer
	 */
	public $list_synchronizer;

	/**
	 * @var Webhook\Listener;
	 */
	public $webhooks;

	/**
	 * Let's go...
	 *
	 * Runs at `plugins_loaded` priority 30.
	 */
	public function init() {

		// load plugin options
		$this->options = $options = $this->load_options();

		// Load area-specific code
		if( ! is_admin() ) {
			// @todo make this optional
			$this->webhooks = new Webhook\Listener( $this->options );
			$this->webhooks->add_hooks();
		} elseif( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$ajax = new AjaxListener( $this->options );
			$ajax->add_hooks();
		} else {
			$admin = new Admin\Manager( $this->options, $this->list_synchronizer );
			$admin->add_hooks();
		}

		// if a list was selected, initialise the ListSynchronizer class
		if( $this->options['list'] != '' && $this->options['enabled'] ) {

			// @todo make this filterable (wait for DI container in core?)
			$worker = ( $options['worker_type'] === 'shutdown' ) ? new ShutdownWorker() : new CronWorker();
			$scheduler = new Producer( $worker );
			$scheduler->add_hooks();

			$this->list_synchronizer = new ListSynchronizer( $this->options['list'], $this->options['role'], $this->options );
			$this->list_synchronizer->add_hooks();
		}

		if( defined( 'WP_CLI' ) && WP_CLI ) {
			$commands = new CommandProvider();
			$commands->register();
		}
	}

	/**
	 * @return array
	 */
	private function load_options() {

		$options = (array) get_option( self::OPTION_NAME, array() );

		$defaults = array(
			'list' => '',
			'double_optin' => 0,
			'send_welcome' => 0,
			'role' => '',
			'enabled' => 1,
			'field_mappers' => array(),
			'worker_type' => 'shutdown'
		);

		$options = array_merge( $defaults, $options );

		/**
		 * Filters MailChimp Sync options
		 *
		 * @param array $options
		 */
		return (array) apply_filters( 'mailchimp_sync_options', $options );
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

}