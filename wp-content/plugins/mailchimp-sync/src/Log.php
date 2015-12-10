<?php

namespace MC4WP\Sync;

/**
 * Class Log
 *
 * @package MC4WP\Sync
 */
class Log {

	/**
	 * @var string
	 */
	protected $file_path = '';

	/**
	 * @var string
	 */
	protected $file_name = 'mailchimp-sync.log';

	/**
	 * @var bool
	 */
	public $enabled = true;

	/**
	 * Constructor
	 *
	 * @param bool $enabled
	 */
	public function __construct( $enabled = true ) {
		$this->enabled = $enabled;
		$this->file_path = $this->determine_file_path();
	}

	/**
	 * Enable logging
	 */
	public function enable() {
		$this->enabled = true;
	}

	/**
	 * Disable logging
	 */
	public function disable() {
		$this->enabled = false;
	}

	/**
	 * Determine the full path of the log file.
	 *
	 * @return string
	 */
	protected function determine_file_path() {
		$upload_dir = wp_upload_dir();

		if( isset( $upload_dir['basedir'] ) ) {
			return $upload_dir['basedir'] . '/' . $this->file_name;
		}

		return dirname( MAILCHIMP_SYNC_FILE ) . '/' . $this->file_name;
	}

	/**
	 * @return string
	 */
	public function read() {
		if( file_exists( $this->file_path ) ) {
			return file_get_contents( $this->file_path );
		}

		return '';
	}

	/**
	 * @param string $text
	 */
	public function write( $text ) {

		if( $this->enabled ) {
			// add timestamp to text
			$text = sprintf( '[%s] %s', date( 'Y-m-d H:i:s' ), $text );
			error_log( $text, 3, $this->file_path );
		}

	}

	/**
	 * Write a new line to the log file.
	 *
	 * @param string $text
	 */
	public function write_line( $text ) {
		$this->write( $text . PHP_EOL );
	}

	/**
	 * Deletes the log file
	 */
	public function clear() {
		if( file_exists( $this->file_path ) ) {
			unlink( $this->file_path );
		}
	}
}