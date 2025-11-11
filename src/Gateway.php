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
use Pronamic\WordPress\Pay\Payments\PaymentStatus as PronamicStatus;

/**
 * Gateway class
 */
final class Gateway extends PronamicGateway {
	use ModeTrait;

	/**
	 * Constructs and initializes an Wordline Open Banking gateway
	 *
	 * @param Config $config Config.
	 */
	public function __construct(
		/**
		 * Config.
		 */
		protected Config $config
	) {
		parent::__construct();

		$this->set_method( self::METHOD_HTTP_REDIRECT );

		$this->supports = [
			'payment_status_request',
			'webhook',
			'webhook_log',
			'webhook_no_config',
		];

		// iDEAL.
		$ideal_payment_method = new PaymentMethod( PaymentMethods::IDEAL );
		$ideal_payment_method->set_status( 'active' );

		$this->register_payment_method( $ideal_payment_method );

		// Bancontact.
		$payment_method_bancontact = new PaymentMethod( PaymentMethods::BANCONTACT );
		$ideal_payment_method->set_status( 'active' );

		$this->register_payment_method( $payment_method_bancontact );
	}

	/**
	 * Start
	 *
	 * @see PronamicGateway::start()
	 *
	 * @param Payment $payment Payment.
	 * @throws \Exception If the request fails.
	 */
	public function start( Payment $payment ) {
		$client = new Client( $this->config );

		$response_data = $client->create_hosted_checkout( $payment );

		if ( ! isset( $response_data['redirectUrl'] ) ) {
			throw new \Exception( 'Error: No redirectUrl in response.' );
		}

		$redirect_url = $response_data['redirectUrl'];

		$payment->set_meta( 'worldline_return_mac', $response_data['RETURNMAC'] );
		$payment->set_meta( 'worldline_hosted_checkout_id', $response_data['hostedCheckoutId'] );

		$payment->set_action_url( $redirect_url );

		$payment->save();
	}

	/**
	 * Update status of the specified payment
	 *
	 * @param Payment $payment Payment.
	 * @return void
	 * @throws \Exception Throws an exception if the request fails.
	 */
	public function update_status( Payment $payment ) {
		$hosted_checkout_id = (string) $payment->get_meta( 'worldline_hosted_checkout_id' );

		if ( '' === $hosted_checkout_id ) {
			return;
		}

		$client = new Client( $this->config );

		$response = $client->get_hosted_checkout( $hosted_checkout_id );

		switch ( $response->status ) {
			case HostedCheckoutStatus::CancelledByConsumer:
				$payment->set_status( PronamicStatus::CANCELLED );

				break;
			case HostedCheckoutStatus::InProgress:
				$payment->set_status( PronamicStatus::OPEN );

				break;
			case HostedCheckoutStatus::PaymentCreated:
				$payment->set_status( PronamicStatus::OPEN );

				switch ( $response->created_payment_output?->payment_status_category ) {
					case PaymentStatusCategory::Rejected:
						$payment->set_status( PronamicStatus::EXPIRED );

						break;
					case PaymentStatusCategory::Successful:
						$payment->set_status( PronamicStatus::SUCCESS );

						break;
					case PaymentStatusCategory::StatusUnknown:
						$payment->set_status( PronamicStatus::OPEN );

						break;
				}

				break;
		}

		$payment->set_transaction_id( $response->created_payment_output?->payment?->id );

		$payment->save();
	}
}
