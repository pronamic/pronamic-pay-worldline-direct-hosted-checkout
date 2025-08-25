<?php
/**
 * Config
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
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
	 * Merchant ID.
	 *
	 * @var string
	 */
	public string $merchant_id;

	/**
	 * API Key.
	 *
	 * @var string
	 */
	public string $api_key;

	/**
	 * API Secret.
	 *
	 * @var string
	 */
	public string $api_secret;

	/**
	 * Construct config.
	 *
	 * @param string $merchant_id Merchant ID.
	 * @param string $api_key     API Key.
	 * @param string $api_secret  API Secret.
	 */
	public function __construct(
		string $merchant_id,
		string $api_key,
		string $api_secret
	) {
		$this->merchant_id = $merchant_id;
		$this->api_key     = $api_key;
		$this->api_secret  = $api_secret;
	}

	/**
	 * Serialize to JSON.
	 *
	 * @link https://www.w3.org/TR/json-ld11/#specifying-the-type
	 * @return object
	 */
	public function jsonSerialize(): object {
		return (object) [
			'@type'       => __CLASS__,
			'merchant_id' => $this->merchant_id,
			'api_key'     => $this->api_key,
			'api_secret'  => $this->api_secret,
		];
	}
}
