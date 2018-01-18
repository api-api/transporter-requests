<?php
/**
 * Transporter_Requests class
 *
 * @package APIAPI\Transporter_Requests
 * @since 1.0.0
 */

namespace APIAPI\Transporter_Requests;

use APIAPI\Core\Transporters\Transporter;
use APIAPI\Core\Request\Request;
use APIAPI\Core\Request\Method;
use APIAPI\Core\Exception\Request_Transport_Exception;

if ( ! class_exists( 'APIAPI\Transporter_Requests\Transporter_Requests' ) ) {

	/**
	 * Transporter implementation for the Requests library.
	 *
	 * @since 1.0.0
	 */
	class Transporter_Requests extends Transporter {
		/**
		 * Sends a request and returns the response.
		 *
		 * @since 1.0.0
		 *
		 * @param Request $request The request to send.
		 * @return array The returned response as an array with 'headers', 'body',
		 *               and 'response' key. The array does not necessarily
		 *               need to include all of these keys.
		 *
		 * @throws Request_Transport_Exception Thrown when the request cannot be sent.
		 */
		public function send_request( Request $request ) {
			$url     = $request->get_uri();
			$headers = $request->get_headers();
			$data    = $request->get_params();
			$type    = $request->get_method();
			$options = array();

			if ( ! empty( $data ) ) {
				if ( Method::GET === $type ) {
					$options['data_format'] = 'query';
				} else {
					$options['data_format'] = 'body';

					if ( 0 === strpos( $request->get_header( 'content-type' ), 'application/json' ) ) {
						$data = json_encode( $data );
						if ( ! $data ) {
							throw new Request_Transport_Exception( sprintf( 'The request to %s could not be sent as the data could not be JSON-encoded.', $url ) );
						}
					}
				}
			}

			try {
				$requests_response = \Requests::request( $url, $headers, $data, $type, $options );
			} catch ( \Requests_Exception $e ) {
				throw new Request_Transport_Exception( sprintf( 'The request to %1$s could not be sent: %2$s', $url, $e->getMessage() ) );
			}

			$response_data = array(
				'headers'  => $requests_response->headers->getAll(),
				'body'     => $requests_response->body,
				'response' => array(
					'code'    => (int) $requests_response->status_code,
					'message' => self::get_status_message( $requests_response->status_code ),
				),
			);

			return $response_data;
		}
	}

}
