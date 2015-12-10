<?php

namespace MC4WP\Sync\CLI;

use WP_CLI;

class CommandProvider {

	/**
	 * Register commands
	 */
	public function register() {
		WP_CLI::add_command( 'mailchimp-sync', 'MC4WP\\Sync\\CLI\\SyncCommand' );
	}

}