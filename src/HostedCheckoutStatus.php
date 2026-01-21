<?php
/**
 * Hosted checkout status
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2026 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

/**
 * Hosted checkout status enum
 *
 * @link https://docs.direct.worldline-solutions.com/en/api-reference#tag/HostedCheckout/operation/GetHostedCheckoutApi
 * @link https://github.com/wl-online-payments-direct/sdk-php/blob/c69a6bbb531e3abd9c4d4494660926b1319d93ac/src/OnlinePayments/Sdk/Domain/GetHostedCheckoutResponse.php#L27
 */
enum HostedCheckoutStatus: string {
	case PaymentCreated = 'PAYMENT_CREATED';

	case InProgress = 'IN_PROGRESS';

	case CancelledByConsumer = 'CANCELLED_BY_CONSUMER';
}
