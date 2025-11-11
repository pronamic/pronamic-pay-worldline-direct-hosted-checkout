<?php
/**
 * Created payment output
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

/**
 * Get hosted checkout status response class
 *
 * @link https://docs.direct.worldline-solutions.com/en/api-reference#tag/HostedCheckout/operation/GetHostedCheckoutApi
 * @link https://github.com/wl-online-payments-direct/sdk-php/blob/c69a6bbb531e3abd9c4d4494660926b1319d93ac/src/OnlinePayments/Sdk/Domain/CreatedPaymentOutput.php#L12
 */
final class CreatedPaymentOutput {
	/**
	 * This object holds the properties related to the payment.
	 *
	 * @var null|PaymentResponse
	 */
	public ?PaymentResponse $payment = null;

	/**
	 * Payment status category.
	 *
	 * @var null|PaymentStatusCategory
	 */
	public ?PaymentStatusCategory $payment_status_category = null;

	/**
	 * From array.
	 *
	 * @param array<string, mixed> $data Data.
	 * @return self
	 */
	public static function from_array( array $data ): self {
		$output = new self();

		if ( \array_key_exists( 'payment', $data ) ) {
			$output->payment = PaymentResponse::from_array( $data['payment'] );
		}

		if ( \array_key_exists( 'paymentStatusCategory', $data ) ) {
			$output->payment_status_category = PaymentStatusCategory::from( $data['paymentStatusCategory'] );
		}

		return $output;
	}
}
