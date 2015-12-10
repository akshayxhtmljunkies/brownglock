<?php

/**
 * Class SPU_aweber
 * Handles Aweber Api
 */
class SPU_aweber {

	/**
	 * @var string provider name
	 */
	private $provider = 'aweber';

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
	 * Constructor
	 */
	function __construct() {

		$this->integrations = get_option('spu_integrations');
		// Load the AWeber API.
		if ( ! class_exists( 'AWeberAPI' ) ) {
			require plugin_dir_path( __FILE__ ) . '../vendors/aweber/aweber_api.php';
		}
		list( $auth_key, $auth_token, $req_key, $req_token, $oauth ) = explode( '|', $this->integrations[$this->provider]['aweber_auth'] );
		$this->api = new AWeberAPI( $auth_key, $auth_token );
		$this->api->adapter->user->requestToken = $req_key;
		$this->api->adapter->user->tokenSecret  = $req_token;
		$this->api->adapter->user->verifier     = $oauth;
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
			$this->account = $this->api->getAccount($this->integrations[ $this->provider ]['access_token'], $this->integrations[ $this->provider ]['access_token_secret']);
			$this->connected = true;
		} catch ( AWeberException $e ) {
			SPU_Errors::display_error( sprintf( __( 'Sorry, but AWeber was unable to grant access to your account. AWeber gave the following response: <em>%s</em>', 'spup' ),
				$e->getMessage()
			) );
			return $this->connected;
		}

		return $this->connected;
	}

	/**
	 * Authentificate with aweber and return access tokens
	 * @return array
	 */
	public function authentificate(){
		// Retrieve an access token
		try {
			list( $this->access_token, $this->access_token_secret ) = $this->api->getAccessToken();
			$this->integrations[ $this->provider ]['access_token']        = $this->access_token;
			$this->integrations[ $this->provider ]['access_token_secret'] = $this->access_token_secret;
			update_option( 'spu_integrations', $this->integrations );

		} catch ( AWeberException $e ) {
			SPU_Errors::display_error( sprintf( __( 'Sorry, but AWeber was unable to verify your authorization token. AWeber gave the following response: <em>%s</em>', 'spup' ),
				$e->getMessage()
			) );

		}

		return array( $this->integrations[ $this->provider ]['access_token'], $this->integrations[ $this->provider ]['access_token_secret'] );
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

		$cached_lists = get_transient( 'spu_aweber_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_aweber_lists_fallback' );
		}

		if ( ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) && isset( $this->account ) ) {
			try {
				$lists   = $this->account->loadFromUrl( '/accounts/' . $this->account->id . '/lists' );
			} catch ( AWeberException $e ) {
				SPU_Errors::display_error(  sprintf( __( 'Sorry, but AWeber was unable to grant access to your account. AWeber gave the following response: <em>%s</em>', 'spup' ),
					$e->getMessage()
				) );
				return array();
			}
			set_transient( 'spu_aweber_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_aweber_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
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


		$data = array(
			'email' => $lead['email']
		);

		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$data['name'] = $lead['name'];
		}

		try{
			$response = $this->add_lead( $box_opts['optin_list'], apply_filters('spup/aweber/subscribe_data', $data ) );
		} catch ( AWeberAPIException $e ) {
			return $e->getMessage();
		}

		if ( ! $response )
			return false;

		return true;
	}


	/**
	 * Custom create implementation to reduce overhead of adding a subscriber
	 * to AWeber.
	 *
	 * @param $list_id int list we want to subscribe
	 * @param $data Array lead data
	 *
	 * @return bool
	 */
	protected function add_lead( $list_id, $data ) {
		// connect to aweber and get account
		if (! $this->is_connected() )
			return false;

		// Prepare variables.
		$url  = '/accounts/' . $this->account->id . '/lists/' . $list_id . '/subscribers';
		$data = array_merge( array( 'ws.op' => 'create' ), $data );

		// Make the request.
		$ret  = $this->api->adapter->request( 'POST', $url, $data, array( 'return' => 'headers' ) );

		// If we receive a proper response, the request succeeded.
		if ( isset( $ret['Status-Code'] ) && 201 == $ret['Status-Code'] ) {
			return true;
		}
		// Otherwise, the request failed.
		return false;
	}
}