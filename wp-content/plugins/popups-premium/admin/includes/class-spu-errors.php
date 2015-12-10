<?php

/**
 * Class SPU_Errors
 * Handle error notices
 */
class SPU_Errors {

	/**
	 * @var string error message
	 */
	protected static $error_message;

	/**
	 * Save error to class and hook error box action
	 * @param $string
	 *
	 * @return bool
	 */
	public static function display_error( $string ) {
		if( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		self::$error_message = $string;
		add_action( 'spu/integrations_page/before', array( 'SPU_Errors' , 'error_box' ) );
		return true;
	}

	/**
	 * Prints an error box before content in integrations page
	 */
	public static function error_box() {
		?>
		<div class="error">
			<p><?php _e( self::$error_message, 'spup' ); ?></p>
		</div>
	<?php
	}

}
