<?php

/**
 * Class SPU_GetResponse
 * GetResponse provider
 */
class SPU_getresponse implements SPU_Providers{

	/**
	 * @var provider api
	 */
	private $api;

	/**
	 * @var string provider name
	 */
	public $provider = 'getresponse';

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

		$this->api = new SPU_getresponse_api( $this->integrations[$this->provider]['gr_api'] );

	}

	/**
	 * Pings the GetResponse API to see if we're connected
	 * @return boolean
	 */
	public function is_connected() {

		if( $this->connected !== null ) {
			return $this->connected;
		}

		$this->connected = false;
		$result = $this->api->ping();

		if( $result !== false ) {
			if( isset( $result ) && $result['ping'] === 'pong' ) {
				$this->connected = true;
			} else {
				SPU_Errors::display_error( 'GetResponse Error: ' . $result['message']);
			}
		}

		return $this->connected;
	}


	/**
	 * Get GetResponse Lists from cache or renew
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = false) {
		$lists = array();
		$cached_lists = get_transient( 'spu_gr_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_gr_lists_fallback' );
		}

		if ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) {
			$lists = $this->api->getCampaigns();
			$lists = $this->map_name_to_id( $lists );
			set_transient( 'spu_gr_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_gr_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
		} else {
			if( !empty($cached_lists) )
				$lists = $cached_lists;
		}
		return $lists;
	}



	/**
	 * The function that actually subscribe user to GetResponse
	 *
	 * @param $lead
	 * @param $box_opts
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {


		$data = array(
			'campaign'  => $box_opts['optin_list'],
			'email'     => $lead['email'],
			'action' => 'standard',
			'cycle_day' => 0,
			//'ip' => $_SERVER['REMOTE_ADDR'],
			'customs'   => array(
				array(
					'name' => 'popups_plugin',
					'content' => 'true',
				)
			)
		);
		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$data['name'] = $lead['name'];
		}


		try {
			$this->api->addContact( apply_filters( 'spup/getresponse/subscribe_data', $data, $lead, $box_opts['optin_list'] ) );
		} catch( Exception $e ) {
			return $e->getMessage();
		}
		return true;
	}

	/**
	 * GetResponse returns list->name and we want to make it list->id
	 *
	 * @param $lists
	 *
	 * @return array
	 */
	private function map_name_to_id( $lists ) {
		if( empty( $lists ) )
			return $lists;
		$new_lists = new stdClass();
		foreach( $lists as $k => $list ) {
			$temp_list = new stdClass();
			$temp_list->id = $k;
			$temp_list->name = $list['name'];
			$new_lists->$k = $temp_list;
			unset( $temp_list );
		}
		return $new_lists;
	}
}