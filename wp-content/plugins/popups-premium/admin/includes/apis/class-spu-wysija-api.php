<?php

/**
 * Class SPU_wysija_api
 * Handle all api calls
 */
class SPU_wysija_api {

	function __construct( ) {
		// nothing here
	}

	/**
	 * Get Wysija Lists
	 * @return array|bool
	 */
	public function get_lists( ) {

		$lists = array();
		try {
			$w_lists = WYSIJA::get('list','model');
			$lists_a = $w_lists->get(array('list_id','name'),array('is_enabled'=>'1'));

			if( is_array( $lists_a) ) {
				foreach( $lists_a as $l ) {
					$list       = new stdClass();
					$list->id   = $l['list_id'];
					$list->name = $l['name'];
					$lists[]    = $list;
				}
			}
			return $lists;
		} catch( Exception $e ) {

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
		return false;
	}

}