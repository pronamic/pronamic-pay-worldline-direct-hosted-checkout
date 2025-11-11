<?php
/**
 * Get hosted checkout status response
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
 * @link https://github.com/wl-online-payments-direct/sdk-php/blob/c69a6bbb531e3abd9c4d4494660926b1319d93ac/src/OnlinePayments/Sdk/Domain/GetHostedCheckoutResponse.php#L27
 */
final class GetHostedCheckoutResponse {
	/**
	 * Created payment output.
	 *
	 * @var null|CreatedPaymentOutput
	 */
	public ?CreatedPaymentOutput $created_payment_output = null;

	/**
	 * Status.
	 *
	 * @var null|HostedCheckoutStatus
	 */
	public ?HostedCheckoutStatus $status = null;

	/**
	 * From array.
	 *
	 * @param array $data Data.
	 * @return self
	 */
	public static function from_array( $data ): self {
		$response = new self();

		if ( \array_key_exists( 'createdPaymentOutput', $data ) ) {
			$response->created_payment_output = CreatedPaymentOutput::from_array( $data['createdPaymentOutput'] );
		}

		if ( \array_key_exists( 'status', $data ) ) {
			$response->status = HostedCheckoutStatus::from( $data['status'] );
		}

		return $response;
	}
}
