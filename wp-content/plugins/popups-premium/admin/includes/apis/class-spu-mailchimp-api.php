<?php

/**
 * Class SPU_mailchimp_api
 * Handle all api calls
 */
class SPU_mailchimp_api {


	/**
	 * @var Object that store last API response
	 */
	private $last_response;

	/**
	 * @var string API endpoint
	 */
	private $api_url = 'https://api.mailchimp.com/2.0/';

	private $api_key;

	/**
	 * @param $api_key
	 */
	function __construct( $api_key ) {

		$dash = strpos( $api_key, '-' );
		if( $dash !== false ) {
			$this->api_url = 'https://' . substr( $api_key, $dash + 1 ) . '.api.mailchimp.com/2.0/';
		}
		$this->api_key = $api_key;
	}

	/**
	 * Calls the MailChimp API
	 *
	 * @uses WP_HTTP
	 *
	 * @param string $method
	 * @param array $data
	 *
	 * @return object
	 */
	public function call( $method, array $data = array() ) {

		// do not make request when no api key was provided.
		if( empty( $this->api_key ) ) {
			SPU_Errors::display_error( 'Mailchimp Error: Empty api key' );
			return false;
		}

		$data['apikey'] = $this->api_key;
		$url = $this->api_url . $method . '.json';

		$response = wp_remote_post( $url, array(
				'body' => $data,
				'timeout' => 15,
				'headers' => array('Accept-Encoding' => ''),
				'sslverify' => false
			)
		);

		if( is_wp_error( $response ) ) {
			// show error message to admins
			SPU_Errors::display_error( 'Mailchimp Error: ' . $response->get_error_message() );
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$response = json_decode( $body );

		// store response
		$this->last_response = $response;

		return $response;
	}

	/**
	 * Get Mailchimp Lists from API
	 * @param array $list_ids
	 *
	 * @return array|bool
	 */
	public function get_lists( $list_ids = array() ) {

		$args = array(
			'limit' => 100
		);

		$lists = array();

		// set filter if the $list_ids parameter was set
		if( is_array($list_ids) && !empty( $list_ids ) ) {
			$args['filters'] = array(
				'list_id' => implode( ',', $list_ids )
			);
		}

		$result = $this->call( 'lists/list', $args );

		if( is_object( $result ) && isset( $result->data ) ) {
			foreach ( $result->data as $list ) {

				$lists["{$list->id}"] = (object) array(
					'id'                 => $list->id,
					'name'               => $list->name,
					'subscriber_count'   => $list->stats->member_count,
					'merge_vars'         => array(),
					'interest_groupings' => array()
				);

				// get interest groupings
				$groupings_data = $this->get_list_groupings( $list->id );
				if ( $groupings_data ) {
					$lists["{$list->id}"]->interest_groupings = array_map( array(
						$this,
						'strip_unnecessary_grouping_properties'
					), $groupings_data );
				}

			}

			// get merge vars for all lists at once
			$merge_vars_data = $this->get_lists_with_merge_vars( array_keys( $lists ) );
			if ( $merge_vars_data ) {
				foreach ( $merge_vars_data as $list ) {
					// add merge vars to list
					$lists["{$list->id}"]->merge_vars = array_map( array(
						$this,
						'strip_unnecessary_merge_vars_properties'
					), $list->merge_vars );

				}
			}
			return $lists;
		}
		return false;
	}

	/**
	 * Get groupings for each list
	 * @param $list_id
	 *
	 * @return bool|array
	 */
	private function get_list_groupings( $list_id ) {
		$result = $this->call( 'lists/interest-groupings', array( 'id' => $list_id ) );

		if( is_array( $result ) ) {
			return $result;
		}
		return false;
	}

	/**
	 * Build the group array object which will be stored in cache
	 *
	 * @param $group
	 *
	 * @return object
	 */
	public function strip_unnecessary_group_properties( $group ) {
		return (object) array(
			'name' => $group->name
		);
	}

	/**
	 * Build the groupings array object which will be stored in cache
	 *
	 * @param $grouping
	 *
	 * @return object
	 */
	public function strip_unnecessary_grouping_properties( $grouping ) {
		return (object) array(
			'id' => $grouping->id,
			'name' => $grouping->name,
			'groups' => array_map( array( $this, 'strip_unnecessary_group_properties' ), $grouping->groups ),
			'form_field' => $grouping->form_field
		);
	}

	/**
	 * Build the merge_var array object which will be stored in cache
	 *
	 * @param $merge_var
	 *
	 * @return object
	 */
	public function strip_unnecessary_merge_vars_properties( $merge_var ) {
		$array = array(
			'name' => $merge_var->name,
			'field_type' => $merge_var->field_type,
			'req' => $merge_var->req,
			'tag' => $merge_var->tag
		);

		if ( isset( $merge_var->choices ) ) {
			$array['choices'] = $merge_var->choices;
		}

		return (object) $array;

	}

	/**
	 * Get merge vars from lists
	 * @param $list_ids
	 *
	 * @return bool|array
	 */
	private function get_lists_with_merge_vars( $list_ids ) {
		$result = $this->call( 'lists/merge-vars', array('id' => $list_ids ) );

		if( is_object( $result ) && isset( $result->data ) ) {
			return $result->data;
		}

		return false;
	}
}