<?php
/**
 * Transporter loader.
 *
 * @package APIAPI\Transporter_Requests
 * @since 1.0.0
 */

if ( ! function_exists( 'apiapi_register_transporter_requests' ) ) {

	/**
	 * Registers the transporter for the Requests library.
	 *
	 * It is stored in a global if the API-API has not yet been loaded.
	 *
	 * @since 1.0.0
	 */
	function apiapi_register_transporter_requests() {
		if ( function_exists( 'apiapi_manager' ) ) {
			apiapi_manager()->transporters()->register( 'requests', 'APIAPI\Transporter_Requests\Transporter_Requests' );
		} else {
			if ( ! isset( $GLOBALS['_apiapi_transporters_loader'] ) ) {
				$GLOBALS['_apiapi_transporters_loader'] = array();
			}

			$GLOBALS['_apiapi_transporters_loader']['requests'] = 'APIAPI\Transporter_Requests\Transporter_Requests';
		}
	}

	apiapi_register_transporter_requests();

}
