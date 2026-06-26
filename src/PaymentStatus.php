<?php
/**
 * Payment status.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2026 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout
 */

namespace Pronamic\WordPress\Pay\Gateways\WorldlineDirectHostedCheckout;

/**
 * Payment status enum.
 *
 * @link https://docs.direct.worldline-solutions.com/en/integration/api-developer-guide/statuses
 */
enum PaymentStatus: string {
	case Created = 'CREATED';

	case Cancelled = 'CANCELLED';

	case Rejected = 'REJECTED';

	case RejectedCapture = 'REJECTED_CAPTURE';

	case Redirected = 'REDIRECTED';

	case PendingCapture = 'PENDING_CAPTURE';

	case AuthorizationRequested = 'AUTHORIZATION_REQUESTED';

	case CaptureRequested = 'CAPTURE_REQUESTED';

	case Captured = 'CAPTURED';

	case RefundRequested = 'REFUND_REQUESTED';

	case Refunded = 'REFUNDED';
}
