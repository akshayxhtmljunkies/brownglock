<?php

class EventsTracker {

	private $box_id;
	private $referrer;
	private $user_agent;

	/**
	 * @param $data
	 */
	public function __construct( $data )
	{

		$this->box_id       = empty( $data['box_id'] ) ? '' : $data['box_id'];
		$this->post_id      = empty( $data['post_id'] ) ? '' : $data['post_id'];
		$this->user_agent  	= empty( $data['user_agent'] ) ? '' : str_replace( site_url(), '' ,$data['user_agent'] );
		$this->referrer  	= empty( $data['referrer'] ) ? '' : $data['referrer'];
		$this->conversion	= $data['conversion'] == 'false' ? false : true ;

		$this->track();
	}


	/**
	 * Track the event
	 */
	private function track() {
		global $wpdb;
		$wpdb->insert(
			$wpdb->prefix.'spu_hits_logs',
			array(
				'box_id'    => $this->box_id,
				'post_id'   => $this->post_id,
				'ua'        => $this->user_agent,
				'referrer'  => $this->referrer,
				'hit_type'  => $this->conversion,
				'hit_date'  => current_time( 'mysql' ),
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
		if( ! $this->conversion ){

			$counter = get_post_meta( $this->box_id, 'spup_hit_counter', true );
			update_post_meta( $this->box_id, 'spup_hit_counter', (int) $counter + 1 );
			
		}
	}
}