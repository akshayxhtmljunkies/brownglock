<?php

/**
 * Class SPU_getresponse_api
 * Wrapper that Handle all api calls
 */
class SPU_getresponse_api {

	private $api_key;
	private $client;

	/**
	 * GetResponse API URL
	 * @var string
	 * @access private
	 */
	private $api_url = 'http://api2.getresponse.com';

	function __construct( $key ) {
		$this->api_key      = $key;
		# initialize JSON-RPC client
		$this->client = new jsonRPCClient($this->api_url);
	}

	function ping() {
		$response = $this->client->ping( $this->api_key );
		return $response;
	}

	function getCampaigns() {
		$response = $this->client->get_campaigns( $this->api_key );
		return $response;
	}

	function addContact( $data ){
		$response = $this->client->add_contact(
			$this->api_key,
			$data
		);
		return $response;
	}
}