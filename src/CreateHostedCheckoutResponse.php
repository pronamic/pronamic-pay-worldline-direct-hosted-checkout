<?php
/**
 * Create hosted checkout response
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2026 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

/**
 * Create hosted checkout response class
 *
 * @link https://docs.direct.worldline-solutions.com/en/api-reference#tag/HostedCheckout/operation/CreateHostedCheckout
 */
final class CreateHostedCheckoutResponse {
	/**
	 * Hosted checkout ID.
	 *
	 * @var null|string
	 */
	public ?string $hosted_checkout_id = null;

	/**
	 * Return MAC.
	 *
	 * @var null|string
	 */
	public ?string $return_mac = null;

	/**
	 * Redirect URL.
	 *
	 * @var null|string
	 */
	public ?string $redirect_url = null;

	/**
	 * From array.
	 *
	 * @param array<string, mixed> $data Data.
	 * @return self
	 */
	public static function from_array( array $data ): self {
		$response = new self();

		if ( isset( $data['hostedCheckoutId'] ) ) {
			$response->hosted_checkout_id = $data['hostedCheckoutId'];
		}

		if ( isset( $data['RETURNMAC'] ) ) {
			$response->return_mac = $data['RETURNMAC'];
		}

		if ( isset( $data['redirectUrl'] ) ) {
			$response->redirect_url = $data['redirectUrl'];
		}

		return $response;
	}
}
