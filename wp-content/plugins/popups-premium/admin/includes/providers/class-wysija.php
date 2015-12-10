<?php

/**
 * Class SPU_wysija
 * Mailchimp provider
 */
class SPU_wysija implements SPU_Providers{

	/**
	 * @var provider api
	 */
	private $api;

	/**
	 * @var string provider name
	 */
	public $provider = 'wysija';

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
		$this->api = new SPU_wysija_api();
	}


	/**
	 * Get Wysija Lists from cache or renew
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = true) {
		$lists = array();

		$cached_lists = get_transient( 'spu_wysija_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_wysija_lists_fallback' );
		}

		if ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) {
			$lists = $this->api->get_lists();
			set_transient( 'spu_wysija_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_wysija_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
		} else {
			if( !empty($cached_lists) )
				$lists = $cached_lists;
		}
		return $lists;
	}



	/**
	 * The function that actually subscribe user to Wysija
	 *
	 * @param $lead
	 * @param $box_opts
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {
		// retrieve error
		global $wysija_msg;

		$data = array(
			'user'      => array(
				'email'     => esc_html( $lead['email'] ),
				'firstname' => '',
				'lastname'  => ''
			),
			'user_list' => array( 'list_ids' => array( $box_opts['optin_list'] ) )
		);

		// Setup name if set
		if ( ! empty( $lead['name'] ) ) {
			$names = explode( ' ', $lead['name'] );
			if ( isset( $names[0] ) ) {
				$data['user']['firstname'] = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data['user']['lastname'] = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data['user']['firstname'] = $names[0] . ' ' . $names[1];
				$data['user']['lastname']  = $names[2];
			}
		}
		try{
			$userHelper = WYSIJA::get( 'user', 'helper' );
			return $userHelper->addSubscriber( $data ) ? true : $wysija_msg['error'];
		} catch( Exception $e ) {
			return $e->getMessage();
		}

		return true;
	}

	/**
	 * Pings the provider API to see if we're connected or connection is valid
	 * @return boolean
	 */
	public function is_connected() {
		return true;
	}
}