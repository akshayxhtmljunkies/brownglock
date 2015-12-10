<?php

namespace MC4WP\Sync;

class AjaxListener {

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var array
	 */
	protected $allowed_actions = array(
		'get_users',
		'subscribe_users',
		'get_user_count'
	);

	/**
	 * @var Wizard
	 */
	protected $wizard;

	/**
	 * Constructor
	 * @param array $options
	 */
	public function __construct( array $options ) {
		$this->options = $options;
		$this->wizard = new Wizard( $this->options['list'], $this->options );
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'wp_ajax_mcs_wizard', array( $this, 'route' ) );
		add_action( 'wp_ajax_mcs_autocomplete_user_field', array( $this, 'autocomplete_user_field' ) );
	}

	/**
	 * Route the AJAX call to the correct method
	 */
	public function route() {

		// make sure user is allowed to make the AJAX call
		if( ! current_user_can( 'manage_options' )
		    || ! isset( $_REQUEST['mcs_action'] ) ) {
			die( '-1' );
		}

		// check if method exists and is allowed
		if( in_array( $_REQUEST['mcs_action'], $this->allowed_actions ) ) {
			$this->{$_REQUEST['mcs_action']}();
			exit;
		}

		die( '-1' );
	}

	/**
	 * Get user count
	 */
	protected function get_user_count() {
		$role = ( isset( $_REQUEST['role'] ) ) ? $_REQUEST['role'] : '';
		$this->respond( $this->wizard->get_user_count( $role ) );
	}

	/**
	 * Responds with an array of all user ID's
	 */
	protected function get_users() {

		$offset = ( isset( $_REQUEST['offset'] ) ? absint( $_REQUEST['offset'] ) : 0 );
		$role = ( isset( $_REQUEST['role'] ) ) ? $_REQUEST['role'] : '';
		$users = $this->wizard->get_users( $role, $offset );

		// send response
		$this->respond( $users );
	}

	/**
	 * Subscribes the provided user ID's
	 * Returns the updates progress
	 */
	protected function subscribe_users() {

		// make sure `user_ids` is an array
		$user_ids = $_REQUEST['user_ids'];
		if( ! is_array( $user_ids ) ) {
			$user_ids = sanitize_text_field( $user_ids );
			$user_ids = explode( ',', $user_ids );
		}

		$result = $this->wizard->subscribe_users( $user_ids );

		if( $result ) {
			$this->respond( array( 'success' => true ) );
		}

		// send response
		$this->respond(
			array(
				'success' => $result,
				'error' =>  $this->wizard->get_error()
			)
		);
	}

	/**
	 * Autocomplete user meta key
	 */
	public function autocomplete_user_field() {
		global $wpdb;
		$query = sanitize_text_field( $_GET['q'] );

		// query database
		$sql = $wpdb->prepare( "SELECT meta_key FROM $wpdb->usermeta um WHERE um.meta_key LIKE '%s' GROUP BY um.meta_key", '%' . $query . '%' );
		$meta_key_values = $wpdb->get_col( $sql );

		// query custom fields
		$custom_values = array( 'role', 'user_login', 'ID' );
		$custom_values = array_filter( $custom_values, function( $value ) use( $query ) { return strpos( $value, $query ) !== false; });

		// combine sources
		$values = $meta_key_values + $custom_values;

		// TODO: filter response

		$this->respond( join( PHP_EOL, $values ) );
	}

	/**
	 * Send a JSON response
	 *
	 * @param $data
	 */
	private function respond( $data ) {

		// clear output, some plugins might have thrown errors by now.
		if( ob_get_level() > 0 ) {
			ob_end_clean();
		}

		wp_send_json( $data );
		exit;
	}

}