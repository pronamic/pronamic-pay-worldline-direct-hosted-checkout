<?php
/**
 * Pronamic Pay - Worldline - Direct - Hosted Checkout
 *
 * @author    Pronamic
 * @copyright 2025 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 *
 * @wordpress-plugin
 * Plugin Name:       Pronamic Pay - Worldline - Direct - Hosted Checkout
 * Plugin URI:        https://wp.pronamic.directory/plugins/pronamic-pay-worldline-direct-hosted-checkout/
 * Description:       This plugin contains the Pronamic Pay integration for the Worldline Open Banking Platform and iDEAL 2.0.
 * Version:           1.2.0
 * Requires at least: 6.2
 * Requires PHP:      8.2
 * Author:            Pronamic
 * Author URI:        https://www.pronamic.eu/
 * Text Domain:       pronamic-pay-worldline-direct-hosted-checkout
 * Domain Path:       /languages/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://wp.pronamic.directory/plugins/pronamic-pay-worldline-direct-hosted-checkout/
 * GitHub URI:        https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoload.
 */
$autoload_path = __DIR__ . '/vendor/autoload_packages.php';

if ( file_exists( $autoload_path ) ) {
	require_once $autoload_path;
}

/**
 * Gateway.
 */
add_filter(
	'pronamic_pay_gateways',
	function ( $gateways ) {
		// Worldline - Direct - Hosted Checkout.
		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout\Integration(
			[
				'id'       => 'worldline-direct-hosted-checkout-test',
				'name'     => 'Worldline - Direct - Hosted Checkout - Test',
				'mode'     => 'test',
				'api_host' => 'payment.preprod.direct.worldline-solutions.com',
			]
		);

		$gateways[] = new \Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout\Integration(
			[
				'id'       => 'worldline-direct-hosted-checkout',
				'name'     => 'Worldline - Direct - Hosted Checkout',
				'mode'     => 'live',
				'api_host' => 'payment.direct.worldline-solutions.com',
			]
		);

		return $gateways;
	}
);
