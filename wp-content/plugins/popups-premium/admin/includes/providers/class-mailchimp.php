<?php

/**
 * Class SPU_Mailchimp
 * Mailchimp provider
 */
class SPU_mailchimp implements SPU_Providers{

	/**
	 * @var provider api
	 */
	private $api;

	/**
	 * @var string provider name
	 */
	public $provider = 'mailchimp';

	/**
	 * @var array saved integrations
	 */
	private $integrations;

	/**
	 * @var Bool to check if connected
	 */
	private $connected;


	/**
	 * Constructor
	 */
	function __construct( ) {

		$this->integrations = get_option('spu_integrations');

		$this->api = new SPU_mailchimp_api( $this->integrations[$this->provider]['mc_api'] );

	}

	/**
	 * Pings the MailChimp API to see if we're connected
	 * @return boolean
	 */
	public function is_connected() {

		if( $this->connected !== null ) {
			return $this->connected;
		}

		$this->connected = false;
		$result = $this->api->call( 'helper/ping' );

		if( $result !== false ) {
			if( isset( $result->msg ) && $result->msg === "Everything's Chimpy!" ) {
				$this->connected = true;
			} else {
				SPU_Errors::display_error( 'MailChimp Error: ' . $result->error );
			}
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

		$cached_lists = get_transient( 'spu_mc_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_mc_lists_fallback' );
		}

		if ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) {
			$lists = $this->api->get_lists();
			set_transient( 'spu_mc_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_mc_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
		} else {
			if( !empty($cached_lists) )
				$lists = $cached_lists;
		}
		return $lists;
	}



	/**
	 * The function that actually subscribe user to MailChimp
	 *
	 * @param $lead
	 * @param $box_opts
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {


		$data = array(
			'id' => $box_opts['optin_list'],
			'email' => array( 'email' => $lead['email']),
			'email_type' => 'html',
			'double_optin' => true,
			'update_existing' => false,
			'replace_interests' => true,
			'send_welcome' => true,
			'merge_vars'    => array()
		);

		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$names = explode( ' ', $lead['name'] );
			if ( isset( $names[0] ) ) {
				$data['merge_vars']['FNAME'] = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data['merge_vars']['LNAME'] = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data['merge_vars']['FNAME'] = $names[0] . ' ' . $names[1];
				$data['merge_vars']['LNAME'] = $names[2];
			}
		}

		// Setup segments if set
		if ( ! empty( $box_opts['optin_list_segments'] ) ) {
			$i = 0;
			foreach ( $box_opts['optin_list_segments'] as $group_id => $segment ) {
				$data['merge_vars']['groupings'][ $i ]['id']     = $group_id;
				$data['merge_vars']['groupings'][ $i ]['groups'] = $segment;
				$i++;
			}
		}

		$response = $this->api->call( 'lists/subscribe', apply_filters('spup/mailchimp/subscribe_data', $data, $lead, $box_opts['optin_list']) );

		if( is_object( $response ) ) {

			if(  isset( $response->error ) )
				return $response->error;
		}
		return true;
	}
}