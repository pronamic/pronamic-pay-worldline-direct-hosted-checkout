<?php
/**
 * Integration
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2025 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;
use Pronamic\WordPress\Pay\Core\Gateway as PronamicGateway;

/**
 * Integration class
 */
final class Integration extends AbstractGatewayIntegration {
	/**
	 * Construct iDEAL 2.0 integration.
	 *
	 * @param array<string, mixed> $args Arguments.
	 * @return void
	 */
	public function __construct( $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'id'                     => 'worldline-direct-hosted-checkout',
				'name'                   => 'Worldline - Direct - Hosted Checkout',
				'mode'                   => PronamicGateway::MODE_LIVE,
				'url'                    => \__( 'https://worldline.com/', 'pronamic-pay-worldline-direct-hosted-checkout' ),
				'product_url'            => \__( 'https://worldline.com/', 'pronamic-pay-worldline-direct-hosted-checkout' ),
				'manual_url'             => null,
				'dashboard_url'          => null,
				'provider'               => null,
				'app'                    => null,
				'base_url'               => null,
				'client'                 => null,
				'supports'               => [
					'payment_status_request',
				],
			]
		);

		parent::__construct( $args );

		$this->set_mode( $args['mode'] );
	}

	/**
	 * Setup.
	 */
	public function setup() {
		\add_filter( 'pronamic_gateway_configuration_display_value_' . $this->get_id(), [ $this, 'gateway_configuration_display_value' ], 10, 2 );
	}

	/**
	 * Gateway configuration display value.
	 *
	 * @param string $display_value Display value.
	 * @param int    $post_id       Gateway configuration post ID.
	 * @return string
	 */
	public function gateway_configuration_display_value( $display_value, $post_id ) {
		$display_value = (string) $this->get_meta( $post_id, 'worldline_direct_merchant_id' );

		return $display_value;
	}

	/**
	 * Get settings fields.
	 *
	 * @return array<int, array<string, mixed>>>
	 */
	public function get_settings_fields() {
		$fields = parent::get_settings_fields();

		// Merchant ID.
		$fields[] = [
			'section'  => 'general',
			'title'    => \__( 'Merchant ID', 'pronamic-pay-worldline-direct-hosted-checkout' ),
			'meta_key' => '_pronamic_gateway_worldline_direct_merchant_id',
			'type'     => 'text',
			'classes'  => [ 'code' ],
		];

		// API Key.
		$fields[] = [
			'section'  => 'general',
			'title'    => \__( 'API Key', 'pronamic-pay-worldline-direct-hosted-checkout' ),
			'meta_key' => '_pronamic_gateway_worldline_direct_api_key',
			'type'     => 'text',
			'classes'  => [ 'code' ],
		];

		// API Secret.
		$fields[] = [
			'section'  => 'general',
			'title'    => \__( 'API Secret', 'pronamic-pay-worldline-direct-hosted-checkout' ),
			'meta_key' => '_pronamic_gateway_worldline_direct_api_secret',
			'type'     => 'text',
			'classes'  => [ 'code' ],
		];

		// Return.
		return $fields;
	}

	/**
	 * Get config.
	 *
	 * @param int $post_id Post ID.
	 * @return Config
	 */
	public function get_config( $post_id ) {
		$mode = $this->get_mode();

		$merchant   = (string) $this->get_meta( $post_id, 'worldline_direct_merchant_id' );
		$api_key    = (string) $this->get_meta( $post_id, 'worldline_direct_api_key' );
		$api_secret = (string) $this->get_meta( $post_id, 'worldline_direct_api_secret' );

		$config = new Config( $merchant, $api_key, $api_secret );

		return $config;
	}

	/**
	 * Get gateway.
	 *
	 * @param int $post_id Post ID.
	 * @return Gateway
	 */
	public function get_gateway( $post_id ) {
		$gateway = new Gateway( $this->get_config( $post_id ) );

		$gateway->set_mode( $this->mode );

		return $gateway;
	}
}
