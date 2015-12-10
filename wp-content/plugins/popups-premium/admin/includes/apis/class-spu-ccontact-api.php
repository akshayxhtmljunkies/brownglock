<?php

/**
 * Class SPU_ccontact_api
 * Handle all api calls
 */
class SPU_ccontact_api {

	private $access_token;
	private $api_key;

	function __construct( $access_token ) {
		$this->access_token = $access_token;
		$this->api_key      = 'n4emv3whbr6exxu4hs2x456d';
	}

	/**
	 * Get Wysija Lists
	 * @return array|bool
	 * @throws Exception
	 */
	public function get_lists( ) {

		$lists = array();

		$request = wp_remote_get( 'https://api.constantcontact.com/v2/lists?api_key=' . $this->api_key . '&access_token=' . $this->access_token );
		$lists   = json_decode( wp_remote_retrieve_body( $request ) );
		if( isset( $lists[0]->error_key) )
			throw new Exception( $lists[0]->error_key . ' - ' . $lists[0]->error_message );

		return $lists;
	}

	public function get_contact( $email ) {
		$contact    = false;
		// Check to see if the lead already exists in Constant Contact.
		$request    = wp_remote_get( 'https://api.constantcontact.com/v2/contacts?api_key=' . $this->api_key . '&access_token=' . $this->access_token . '&email=' . $email );
		$contact    = json_decode( wp_remote_retrieve_body( $request ) );

		if( is_array( $contact ) && isset( $contact[0]->error_key) )
			throw new Exception( $contact[0]->error_key . ' - ' . $contact[0]->error_message );

		return $contact;
	}

	/**
	 * Main function that perform the api call to update or create a new contact
	 * @param $data array of contact information to be created / updated
	 * @param string $method
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function contact( $data, $method = "CREATE" ) {

		$args['body']                      = json_encode( $data );
		$args['method']                    = $method == 'CREATE' ? 'POST' : 'PUT' ;
		$args['headers']['Content-Type']   = 'application/json';
		$args['headers']['Content-Length'] = strlen( $args['body'] );

		if( 'UPDATE' == $method ) {
			$request    = wp_remote_request( 'https://api.constantcontact.com/v2/contacts/' . $data->id . '?api_key=' . $this->api_key . '&access_token=' . $this->access_token . '&action_by=ACTION_BY_VISITOR', $args );
		} else {
			$request    = wp_remote_post( 'https://api.constantcontact.com/v2/contacts?api_key=' . $this->api_key . '&access_token=' . $this->access_token . '&action_by=ACTION_BY_VISITOR', $args );
		}
		$code       = wp_remote_retrieve_response_code( $request );

		// Check valid codes
		if( '200' != $code && '201' != $code )
			throw new Exception( 'API error - Response code ' . $code );

		$contact    = json_decode( wp_remote_retrieve_body( $request ) );

		if ( is_array( $contact ) && isset( $contact[0]->error_key ) )
			throw new Exception( $contact[0]->error_key . ' - ' . $contact[0]->error_message );

		return $contact;
	}

}