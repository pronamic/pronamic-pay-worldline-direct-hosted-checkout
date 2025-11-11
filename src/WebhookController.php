<?php
/**
 * Webhook controller
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

use Pronamic\WordPress\Pay\Plugin;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Webhook controller class
 *
 * @link https://docs.direct.worldline-solutions.com/en/integration/api-developer-guide/webhooks
 */
class WebhookController {
	/**
	 * Setup.
	 *
	 * @return void
	 */
	public function setup() {
		\add_action( 'rest_api_init', $this->rest_api_init( ... ) );
	}

	/**
	 * REST API init.
	 *
	 * @link https://docs.direct.worldline-solutions.com/en/integration/api-developer-guide/webhooks
	 * @link https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
	 * @link https://developer.wordpress.org/reference/hooks/rest_api_init/
	 *
	 * @return void
	 */
	private function rest_api_init() {
		\register_rest_route(
			Integration::REST_ROUTE_NAMESPACE,
			'/webhook/(?P<payment_id>\d+)',
			[
				'methods'             => 'POST',
				'callback'            => $this->rest_api_webhook( ... ),
				'args'                => [
					'payment_id' => [
						'description' => \__( 'Payment ID.', 'pronamic-pay-worldline-direct-hosted-checkout' ),
						'type'        => 'string',
						'required'    => true,
					],
				],
				'permission_callback' => fn() => true,
			]
		);
	}

	/**
	 * REST API webhook handler.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return object
	 */
	private function rest_api_webhook( WP_REST_Request $request ) {
		/**
		 * Result.
		 *
		 * @link https://developer.wordpress.org/reference/functions/wp_send_json_success/
		 */
		$response = new WP_REST_Response(
			[
				'success' => true,
			]
		);

		$response->add_link( 'self', rest_url( $request->get_route() ) );

		/**
		 * Payment.
		 */
		$payment_id = $request->get_param( 'payment_id' );

		if ( empty( $payment_id ) ) {
			return $response;
		}

		$payment = \get_pronamic_payment( $payment_id );

		if ( null === $payment ) {
			return $response;
		}

		// Add note.
		$note = \__( 'Webhook requested by Worldline.', 'pronamic-pay-worldline-direct-hosted-checkout' );

		$payment->add_note( $note );

		// Log webhook request.
		\do_action( 'pronamic_pay_webhook_log_payment', $payment );

		// Update payment.
		Plugin::update_payment( $payment, false );

		return $response;
	}
}
