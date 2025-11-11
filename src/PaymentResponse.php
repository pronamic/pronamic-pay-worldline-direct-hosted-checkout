<?php
/**
 * Payment response
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

/**
 * Payment response class
 *
 * @link https://docs.direct.worldline-solutions.com/en/api-reference#tag/HostedCheckout/operation/GetHostedCheckoutApi
 * @link https://github.com/wl-online-payments-direct/sdk-php/blob/c69a6bbb531e3abd9c4d4494660926b1319d93ac/src/OnlinePayments/Sdk/Domain/CreatedPaymentOutput.php#L12
 */
final class PaymentResponse {
	/**
	 * Unique payment transaction identifier.
	 *
	 * @var null|string
	 */
	public ?string $id = null;

	/**
	 * From array.
	 *
	 * @param array $data Data.
	 * @return self
	 */
	public static function from_array( $data ): self {
		$response = new self();

		if ( \array_key_exists( 'id', $data ) ) {
			$response->id = $data['id'];
		}

		return $response;
	}
}
