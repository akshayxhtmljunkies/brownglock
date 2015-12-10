<?php

namespace MC4WP\Sync\CLI;

use MC4WP\Sync\Wizard;
use WP_CLI, WP_CLI_Command;
use MC4WP\Sync\ListSynchronizer;

class SyncCommand extends WP_CLI_Command {

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options = $GLOBALS['mailchimp_sync']->options;

		parent::__construct();
	}

	/**
	 * SYnchronize all users with the given role
	 *
	 * @param $args
	 * @param $assoc_args
	 *
	 * @subcommand sync-all
	 */
	public function synchronize_all( $args, $assoc_args ) {

		global $wpdb;

		$wizard = new Wizard( $this->options['list'],  $this->options );
		$user_role = ( isset( $assoc_args['role'] ) ) ? $assoc_args['role'] : '';

		// start by counting all users
		$user_count = $wizard->get_user_count( $user_role );
		WP_CLI::line( "Found $user_count users." );

		// query users in batches of 50
		$processed = 0;
		while( $processed < $user_count ) {

			$batch = $wizard->get_users( $user_role, $processed );

			if( $batch ) {
				$wizard->subscribe_users( $batch );
				$processed += count( $batch );
			}


		}

		WP_CLI::line("Synced $processed users.");

	}

	/**
	 * @param $args
	 * @param $assoc_args
	 * @subcommand sync-user
	 */
	public function synchronize_user( $args, $assoc_args ) {

		$opts =  $this->options;
		$user_id = absint( $args[0] );

		$wizard = new Wizard(  $this->options['list'],  $this->options );
		$result = $wizard->subscribe_users( array( $user_id ) );

		if( $result ) {
			WP_CLI::line( "User successfully synced!" );
		} else {
			WP_CLI::line( "Error while syncing user #" . $user_id );
		}

	}
}
