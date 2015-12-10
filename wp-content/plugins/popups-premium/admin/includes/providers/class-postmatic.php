<?php

/**
 * Class SPU_postmatic
 * Mailchimp provider
 */
class SPU_postmatic implements SPU_Providers{

	/**
	 * @var provider api
	 */
	private $api;

	/**
	 * @var string provider name
	 */
	public $provider = 'postmatic';

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
	}


	/**
	 *  No lists on this provider
	 * @return array
	 *
	 */
	public function get_lists( $force_renewal = true) {
		$none       = new stdClass();
		$none->id   = '';
		$none->name = 'Default';
		$lists = array( $none );
		return $lists;
	}



	/**
	 * The function that actually subscribe user to Postmatic
	 *
	 * @param $lead
	 * @param $box_opts
	 *
	 * @return bool|string
	 */
	public function subscribe($lead, $box_opts ) {
		// retrieve error
		global $postmatic_msg;

		$data = array(
			'user_email'    => $lead['email'] ,
			'firs_tname' => '',
			'last_name'  => ''
		);

		// Setup name if set
		if ( ! empty( $lead['name'] ) ) {
			$names = explode( ' ', $lead['name'] );
			if ( isset( $names[0] ) ) {
				$data['first_name'] = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data['last_name'] = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data['first_name'] = $names[0] . ' ' . $names[1];
				$data['last_name']  = $names[2];
			}
		}
		try{
			$result = Prompt_Api::subscribe( $data );
			if( Prompt_Api::INVALID_EMAIL == $result || Prompt_Api::ALREADY_SUBSCRIBED == $result )
				return $result;
			return true;
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