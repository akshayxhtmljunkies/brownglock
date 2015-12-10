<?php

/**
 * Class SPU_ccontact
 * Handles Constant Contact Api
 */
class SPU_ccontact {

	/**
	 * @var string provider name
	 */
	private $provider = 'ccontact';

	/**
	 * @var array saved integrations
	 */
	private $integrations;

	/**
	 * @var Bool to check if connected
	 */
	private $connected;
	/**
	 * @var Aweber account class to use later
	 */
	private $account;
	/**
	 * @var Aweber lists
	 */
	private $lists;
	private $lead;
	private $box_opts;


	/**
	 * Constructor
	 */
	function __construct() {

		$this->integrations = get_option('spu_integrations');

		$this->api = new SPU_ccontact_api( $this->integrations[$this->provider]['ccontact_auth'] );
	}

	/**
	 * Pings the Aweber API to see if we're connected
	 * @return boolean
	 */
	public function is_connected() {

		if( $this->connected !== null ) {
			return $this->connected;
		}
		$this->connected = false;

		// Verify we can connect to AWeber
		try {
			$this->lists = $this->api->get_lists();
			$this->connected = true;
		} catch (Exception $e ) {
			SPU_Errors::display_error( sprintf( __( 'Sorry, but Constant Contact was unable to grant access to your account. Constant Contact gave the following response: <em>%s</em>', 'spup' ),
				$e->getMessage()
			) );
			return $this->connected;
		}

		return $this->connected;
	}


	/**
	 * Get Mailchimp Lists from cache or renew
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = false) {
		$lists = array();

		$cached_lists = get_transient( 'spu_ccontact_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_ccontact_lists_fallback' );
		}

		if ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) {
			try {
				$lists   = $this->api->get_lists();
			} catch ( Exception $e ) {
				SPU_Errors::display_error(  sprintf( __( 'Sorry, but Constant Contact was unable to grant access to your account. Constant Contact gave the following response: <em>%s</em>', 'spup' ),
					$e->getMessage()
				) );
				return array();
			}
			set_transient( 'spu_ccontact_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_ccontact_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
		} else {
			if( !empty($cached_lists) )
				$lists = $cached_lists;
		}
		return $lists;
	}



	/**
	 * The function that actually subscribe user to MailChimp
	 *
	 * @param $lead Array
	 * @param $box_opts Array
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {
		// cache to use later
		$this->lead     = $lead;
		$this->box_opts = $box_opts;

		try {
			//Check if contact exists
			$contact = $this->api->get_contact( $lead['email'] );
			if ( ! empty( $contact->results ) ) {
				//if exist update list
				$this->update_existing_contact( $contact->results );
			} else {
				// Or create contact
				$this->create_contact( $contact->results );
			}
		} catch( Exception $e ){
			return $e->getMessage();
		}

		return true;
	}


	/**
	 * Custom update implementation to reduce overhead of adding a subscriber
	 * @param $results
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function update_existing_contact( $results ) {

		$data = $results[0];

		// Check if they are already assigned to lists.
		if ( ! empty( $data->lists ) ) {
			foreach ( $data->lists as $i => $list ) {
				// If they are already assigned to this list, return early.
				if ( isset( $list->id ) && $this->box_opts['optin_list'] == $list->id ) {
					throw new Exception( __('The provided email already exist in the list'));
				}
			}
			// Otherwise, add them to the list.
			$new_list 		  	= new stdClass;
			$new_list->id 	  	= $this->box_opts['optin_list'];
			$new_list->status 	= 'ACTIVE';
			$data->lists[]      = $new_list;
		} else {
			// Add the contact to the list.
			$data->lists      = array();
			$new_list 		  = new stdClass;
			$new_list->id 	  = $this->box_opts['optin_list'];
			$new_list->status = 'ACTIVE';
			$data->lists[]   = $new_list;
		}

		$data = $this->parse_name( $data );

		$data = apply_filters('spup/ccontact/subscribe_data', $data, $this->lead, $this->box_opts['optin_list'] );

		$this->api->contact( $data , 'UPDATE' );

		return true;
	}

	/**
	 * Custom create implementation to reduce overhead of adding a subscriber
	 * @return bool
	 * @throws Exception
	 */
	private function create_contact( ) {

		$data = new stdClass();
		$data->email_addresses                      = array();
		$data->email_addresses[0]['id']             = $this->box_opts['optin_list'];
		$data->email_addresses[0]['status']         = 'ACTIVE';
		$data->email_addresses[0]['confirm_status'] = 'CONFIRMED';
		$data->email_addresses[0]['email_address']  = $this->lead['email'];
		$data->lists                                = array();
		$data->lists[0]['id']                       = $this->box_opts['optin_list'];

		$data = $this->parse_name( $data );

		$data = apply_filters( 'spup/ccontact/subscribe_data', $data, $this->lead, $this->box_opts['optin_list'], null );

		$this->api->contact( $data );

		return true;
	}

	/**
	 * Fill Data with parsed name
	 * @param $data
	 *
	 * @return mixed
	 */
	private function parse_name( $data ) {

		if ( ! empty( $this->lead['name'] ) ) {
			$names = explode( ' ', $this->lead['name'] );
			if ( isset( $names[0] ) ) {
				$data->first_name = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data->last_name = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data->first_name = $names[0] . ' ' . $names[1];
				$data->last_name  = $names[2];
			}
		}
		return $data;
	}
}