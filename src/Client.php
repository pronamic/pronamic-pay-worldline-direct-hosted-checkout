<?php
/**
 * Client
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Client class
 */
final class Client {
	/**
	 * Construct client.
	 *
	 * @param Config $config Config.
	 */
	public function __construct(
		/**
		 * Config.
		 */
		private readonly Config $config
	) {
	}

	/**
	 * Create string to hash.
	 *
	 * @link https://docs.direct.worldline-solutions.com/en/integration/api-developer-guide/authentication#createstringtohash
	 * @link https://github.com/wl-online-payments-direct/sdk-php/blob/7.0.0/lib/OnlinePayments/Sdk/Authentication/V1HmacAuthenticator.php#L60-L102
	 * @param string                $http_method  HTTP method.
	 * @param string                $endpoint     Endpoint.
	 * @param array<string, string> $headers      Headers.
	 * @return string
	 */
	private function create_string_to_hash( string $http_method, string $endpoint, array $headers ): string {
		$lines = [];

		$lines[] = $http_method;
		$lines[] = $headers['Content-Type'] ?? '';
		$lines[] = $headers['Date'] ?? '';

		$lines[] = $endpoint;

		$value = '';

		foreach ( $lines as $line ) {
			$value .= $line . "\n";
		}

		return $value;
	}

	/**
	 * Send request.
	 *
	 * @param string $method   Request method.
	 * @param string $endpoint Endpoint.
	 * @param mixed  $data     Request data.
	 * @return mixed
	 * @throws \Exception If the request fails.
	 */
	private function request( string $method, string $endpoint, $data = null ) {
		$url = 'https://' . $this->config->api_host . $endpoint;

		$headers = [
			'Content-Type' => ( 'POST' === $method ) ? 'application/json; charset=utf-8' : '',
			'Date'         => \gmdate( \DATE_RFC1123 ),
		];

		$string_to_hash = $this->create_string_to_hash( $method, $endpoint, $headers );

		$hash = \base64_encode( \hash_hmac( 'sha256', $string_to_hash, $this->config->api_secret, true ) );

		$headers['Authorization'] = 'GCS v1HMAC:' . $this->config->api_key . ':' . $hash;

		$options = [
			'method'  => $method,
			'headers' => $headers,
		];

		if ( null !== $data ) {
			$body = \wp_json_encode( $data );

			if ( false === $body ) {
				throw new \Exception( 'Could not encode JSON data.' );
			}

			$options['body'] = $body;
		}

		$response = \wp_remote_request( $url, $options );

		if ( \is_wp_error( $response ) ) {
			throw new \Exception( \sprintf( 'Error: %s', \esc_html( $response->get_error_message() ) ) );
		}

		$response_code = \wp_remote_retrieve_response_code( $response );
		$response_body = \wp_remote_retrieve_body( $response );

		if ( '200' !== (string) $response_code ) {
			throw new \Exception( \sprintf( 'Error: %s', \esc_html( $response_body ) ) );
		}

		$response_data = \json_decode( $response_body, true );

		return $response_data;
	}

	/**
	 * Get webhook URL.
	 *
	 * @param Payment $payment Payment.
	 * @return string
	 */
	private function get_webhook_url( Payment $payment ): string {
		$path = \strtr(
			'<namespace>/webhook/<payment_id>',
			[
				'<namespace>'  => Integration::REST_ROUTE_NAMESPACE,
				'<payment_id>' => $payment->get_id(),
			]
		);

		$url = \rest_url( $path );

		// @phpstan-ignore-next-line
		if ( false === $url ) {
			return '';
		}

		return $url;
	}

	/**
	 * Create hosted checkout.
	 *
	 * @param Payment $payment Payment.
	 * @return CreateHostedCheckoutResponse
	 * @throws \Exception If the request fails.
	 */
	public function create_hosted_checkout( Payment $payment ): CreateHostedCheckoutResponse {
		$endpoint = \strtr(
			'/v2/{merchantId}/hostedcheckouts',
			[
				'{merchantId}' => $this->config->merchant_id,
			]
		);

		$hosted_checkout_specific_input = [
			'returnUrl' => $payment->get_return_url(),
		];

		if ( null !== $this->config->variant ) {
			$hosted_checkout_specific_input['variant'] = $this->config->variant;
		}

		$data = [
			'hostedCheckoutSpecificInput' => $hosted_checkout_specific_input,
			'order'                       => [
				'reference'     => $payment->get_id(),
				'description'   => 'Order ' . $payment->get_id(),
				'amountOfMoney' => [
					'amount'       => $payment->get_total_amount()->get_minor_units(),
					'currencyCode' => $payment->get_total_amount()->get_currency()->get_alphabetic_code(),
				],
			],
			'feedbacks'                   => [
				'webhooksUrls' => [
					$this->get_webhook_url( $payment ),
				],
			],
		];

		$response_data = $this->request( 'POST', $endpoint, $data );

		if ( ! \is_array( $response_data ) ) {
			throw new \Exception( 'Could not create hosted checkout.' );
		}

		return CreateHostedCheckoutResponse::from_array( $response_data );
	}

	/**
	 * Get hosted checkout status.
	 *
	 * @param string $hosted_checkout_id Hosted checkout ID.
	 * @return GetHostedCheckoutResponse
	 * @throws \Exception If the request fails.
	 */
	public function get_hosted_checkout_status( string $hosted_checkout_id ): GetHostedCheckoutResponse {
		$endpoint = \strtr(
			'/v2/{merchantId}/hostedcheckouts/{hostedCheckoutId}',
			[
				'{merchantId}'       => $this->config->merchant_id,
				'{hostedCheckoutId}' => $hosted_checkout_id,
			]
		);

		$response_data = $this->request( 'GET', $endpoint );

		if ( ! \is_array( $response_data ) ) {
			throw new \Exception( 'Could not get hosted checkout.' );
		}

		return GetHostedCheckoutResponse::from_array( $response_data );
	}
}
