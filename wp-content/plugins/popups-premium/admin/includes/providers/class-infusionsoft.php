<?php

/**
 * Class SPU_infusion
 * Handles Infusion Soft Api
 */
class SPU_infusion {

	/**
	 * @var string provider name
	 */
	private $provider = 'infusionsoft';

	/**
	 * @var array saved integrations
	 */
	private $integrations;

	/**
	 * @var Bool to check if connected
	 */
	private $connected;
	/**
	 * @var Infusionsoft account class to use later
	 */
	private $account;


	/**
	 * Constructor
	 */
	function __construct() {

		$this->integrations = get_option('spu_integrations');

		if ( ! class_exists( 'iSDK' ) ) {
			require plugin_dir_path( __FILE__ ) . '../vendors/infusionsoft/isdk.php';
		}
		try {
			$this->api = new iSDK();
			$this->api->cfgCon( $args['om-subdomain'], $args['om-api-key'], 'throw' );
		} catch ( iSDKException $e ) {
			SPU_Errors::display_error( sprintf( __( 'Sorry, but InfusionSoft was unable to grant access to your account. InfusionSoft gave the following response: <em>%s</em>', 'spup' ),
				$e->getMessage()
			) );
		}
	}

	/**
	 * Pings the Infusionsoft API to see if we're connected
	 * @return boolean
	 */
	public function is_connected() {

		$this->connected = true; // we already ping on class creation

		return $this->connected;
	}


	/**
	 * Get  Lists from cache or renew
	 *
	 * @param bool $force_renewal
	 *
	 * @return mixed
	 *
	 */
	public function get_lists( $force_renewal = false) {
		$lists = array();

		$cached_lists = get_transient( 'spu_infusionsoft_lists' );

		// if empty try older one
		if( empty($cached_lists) ) {
			$cached_lists = get_transient( 'spu_infusionsoft_lists_fallback' );
		}

		if ( ( true === $force_renewal || false === $cached_lists || empty( $cached_lists ) ) && isset( $this->account ) ) {
			try {
				// Query Infusionsoft for available tags.
				$page    = 0;
				while ( true ) {
					$res = $this->api->dsQuery(
						'ContactGroup',
						1000,
						$page,
						array( 'Id' => '%' ),
						array( 'Id', 'GroupName' )
					);
					$lists = array_merge( $lists, $res );
					if ( count( $res ) < 1000 ) {
						break;
					}
					$page ++;
				}
			} catch ( Exception $e ) {
				SPU_Errors::display_error(  sprintf( __( 'Sorry, but Infusionsoft  was unable to grant access to your account. Infusionsoft  gave the following response: <em>%s</em>', 'spup' ),
					$e->getMessage()
				) );
				return array();
			}
			set_transient( 'spu_infusionsoft_lists', $lists, ( 24 * 3600 ) ); // 1 day
			set_transient( 'spu_infusionsoft_lists_fallback', $lists, ( 24 * 3600 * 7 ) ); // 1 week
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
			'Email'     => $lead['email']
		);

		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$names = explode( ' ', $lead['name'] );
			if ( isset( $names[0] ) ) {
				$data['FirstName'] = $names[0];
			}
			if ( isset( $names[1] ) ) {
				$data['LastName'] = $names[1];
			}
			if ( isset( $names[2] ) ) {
				$data['FirstName'] = $names[0] . ' ' . $names[1];
				$data['LastName'] = $names[2];
			}
		}
		// Setup name if set
		if ( !empty( $lead['name'] ) ) {
			$data['name'] = $lead['name'];
		}

		try{
			$response = $this->add_lead( $box_opts['optin_list'], apply_filters('spup/infusionsoft/subscribe_data', $data ) );
		} catch ( Exception $e ) {
			return $e->getMessage();
		}

		if ( ! $response )
			return false;

		return true;
	}


	/**
	 * Custom create implementation to reduce overhead of adding a subscriber
	 * to Infusionsoft .
	 *
	 * @param $list_id int list we want to subscribe
	 * @param $data Array lead data
	 *
	 * @return bool
	 */
	protected function add_lead( $list_id, $data ) {

		// Add the new contact to Infusionsoft, first checking to see if they already exist.
		try {
			$bool = $this->api->findByEmail( $data['Email'], array( 'Id' ) );
			if ( isset( $bool[0] ) && ! empty( $bool[0]['Id'] ) ) {
				$contact_id = $bool[0]['Id'];
				$this->api->updateCon( $contact_id, $data );
				$group_add = $this->api->grpAssign( $bool[0]['Id'], $list_id );
			} else {
				$contact_id = $this->api->addCon( $data );
				$group_add  = $this->api->grpAssign( $contact_id, $list_id );
			}
		} catch ( iSDKException $e ) {
			return 	$e->getMessage();
		}

		// Get any selected sequences.
		$sequences = isset( $data['segments'] ) ? (array) $data['segments'] : array();
		// Return early if no sequences were selected.
		if ( empty( $sequences ) ) {
			return true;
		}

		// Assign the contact to each selected sequence.
		foreach ( $sequences as $seq_id ) {
			try {
				$campaign_added = $this->api->campAssign( $contact_id, $seq_id );
			} catch ( iSDKException $e ) {
				return 	$e->getMessage();
			}
		}

		return true;
	}
}