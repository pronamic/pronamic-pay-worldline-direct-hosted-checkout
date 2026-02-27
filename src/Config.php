<?php
/**
 * Config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2026 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

use JsonSerializable;
use Pronamic\WordPress\Pay\Core\GatewayConfig;

/**
 * Config class
 */
final class Config extends GatewayConfig implements JsonSerializable {
	/**
	 * Variant.
	 *
	 * You can force the use of a custom template by specifying it in the variant field.
	 * This allows you to test out the effect of certain changes to your payment pages in a controlled manner.
	 * Please note that you need to specify the filename of the template or customization.
	 *
	 * @var string|null
	 */
	public ?string $variant = null;

	/**
	 * Merchant Reference.
	 *
	 * The merchant reference format that will be sent to Worldline.
	 * Supports merge tags: {payment_id}, {order_id}.
	 *
	 * @var string|null
	 */
	public ?string $merchant_reference = null;

	/**
	 * Descriptor.
	 *
	 * The descriptor that will be shown to customers on their bank statements.
	 * Supports merge tags: {payment_id}, {order_id}.
	 *
	 * @var string|null
	 */
	public ?string $descriptor = null;

	/**
	 * Construct config.
	 *
	 * @param string $api_host    API Host.
	 * @param string $merchant_id Merchant ID.
	 * @param string $api_key     API Key.
	 * @param string $api_secret  API Secret.
	 */
	public function __construct(
		/**
		 * API host.
		 */
		public string $api_host,
		/**
		 * Merchant ID.
		 */
		public string $merchant_id,
		/**
		 * API Key.
		 */
		public string $api_key,
		/**
		 * API Secret.
		 */
		public string $api_secret
	) {
	}

	/**
	 * Serialize to JSON.
	 *
	 * @link https://www.w3.org/TR/json-ld11/#specifying-the-type
	 * @return object
	 */
	public function jsonSerialize(): object {
		return (object) [
			'@type'              => self::class,
			'api_host'           => $this->api_host,
			'merchant_id'        => $this->merchant_id,
			'api_key'            => $this->api_key,
			'api_secret'         => $this->api_secret,
			'variant'            => $this->variant,
			'merchant_reference' => $this->merchant_reference,
			'descriptor'         => $this->descriptor,
		];
	}
}
