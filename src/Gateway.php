<?php
/**
 * Gateway
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

use Pronamic\WordPress\Pay\Core\Gateway as PronamicGateway;
use Pronamic\WordPress\Pay\Core\ModeTrait;
use Pronamic\WordPress\Pay\Core\PaymentMethod;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\Payment;

/**
 * Gateway class
 */
final class Gateway extends PronamicGateway {
	use ModeTrait;

	/**
	 * Config.
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Constructs and initializes an Wordline Open Banking gateway
	 *
	 * @param Config $config Config.
	 */
	public function __construct( Config $config ) {
		parent::__construct();

		$this->config = $config;

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		$this->supports = [
			'payment_status_request',
		];

		$ideal_payment_method = new PaymentMethod( PaymentMethods::IDEAL );
		$ideal_payment_method->set_status( 'active' );

		$this->register_payment_method( $ideal_payment_method );
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

		foreach  ( $lines as $line ) {
        	$value .= $line . "\n";
		}

		return $value;
    }

	/**
	 * Start
	 *
	 * @see PronamicGateway::start()
	 *
	 * @param Payment $payment Payment.
	 */
	public function start( Payment $payment ) {
		$client = new Client( $this->config );

		$endpoint = \strtr(
			'/v2/{merchantId}/hostedcheckouts',
			[
				'{merchantId}' => $this->config->merchant_id,
			]
		);

		$url = 'https://' . $this->config->api_host . $endpoint;

		$http_method  = 'POST';

		$headers = [
			'Content-Type' => ( 'POST' === $http_method ) ? 'application/json; charset=utf-8' : '',
			'Date'         => \gmdate( \DATE_RFC1123 ),
		];

		$string_to_hash = self::create_string_to_hash( $http_method, $endpoint, $headers );

		$hash = \base64_encode( \hash_hmac( 'sha256', $string_to_hash, $this->config->api_secret, true ) );

		$headers['Authorization'] = 'GCS v1HMAC:' . $this->config->api_key . ':' . $hash;

		$test = \wp_remote_request(
			$url,
			[
				'method'  => $http_method,
				'headers' => $headers,
				'body'    => \json_encode(
					[
						'hostedCheckoutSpecificInput' => [
							'returnUrl' => $payment->get_return_url(),
						],
						'order' => [
							'reference'     => $payment->get_id(),
							'description'   => 'Order ' . $payment->get_id(),
							'amountOfMoney' => [
								'amount'       => $payment->get_total_amount()->get_minor_units(),
								'currencyCode' => $payment->get_total_amount()->get_currency()->get_alphabetic_code(),
							],
						],
					]
				),
			]
		);

		if ( is_wp_error( $test ) ) {
			throw new \Exception( 'Error: ' . $test->get_error_message() );
		}

		$response_code = \wp_remote_retrieve_response_code( $test );
		$response_body = \wp_remote_retrieve_body( $test );

		if ( '200' !== (string) $response_code ) {
			throw new \Exception( 'Error: ' . $response_body );
		}

		$response_data = \json_decode( $response_body, true );

		if ( ! isset( $response_data['redirectUrl'] ) ) {
			throw new \Exception( 'Error: No redirectUrl in response.' );
		}

		$redirect_url = $response_data['redirectUrl'];

		$payment->set_action_url( $redirect_url );

		$payment->save();
	}

	/**
	 * Update status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \Exception Throws an execution if private key cannot be read.
	 */
	public function update_status( Payment $payment ) {
		$client = new Client( $this->config );
	}
}
