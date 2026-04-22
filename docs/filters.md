# WordPress Filters

This document describes the WordPress filters available in the Pronamic Pay - Worldline - Direct - Hosted Checkout plugin.

## Order Reference Filters

### `pronamic_pay_worldline_direct_hosted_checkout_merchant_reference`

Filters the merchant reference before sending it to Worldline Direct.

**Parameters:**
- `$merchant_reference` (string) - The merchant reference value after merge tag replacement.
- `$payment` (\Pronamic\WordPress\Pay\Payments\Payment) - The payment object.

**Return:** (string) The filtered merchant reference. Callbacks must return a string and should keep within Worldline's field limits.

**Example:**

```php
add_filter( 'pronamic_pay_worldline_direct_hosted_checkout_merchant_reference', function ( $merchant_reference, $payment ) {
	// Add a custom prefix to the merchant reference
	return 'CUSTOM-' . $merchant_reference;
}, 10, 2 );
```

### `pronamic_pay_worldline_direct_hosted_checkout_descriptor`

Filters the descriptor (payment description shown on bank statements) before sending it to Worldline Direct.

**Parameters:**
- `$descriptor` (string) - The descriptor value after merge tag replacement.
- `$payment` (\Pronamic\WordPress\Pay\Payments\Payment) - The payment object.

**Return:** (string) The filtered descriptor. Callbacks must return a string and should keep within Worldline's field limits.

**Example:**

```php
add_filter( 'pronamic_pay_worldline_direct_hosted_checkout_descriptor', function ( $descriptor, $payment ) {
	// Customize the descriptor with shop name
	$shop_name = get_bloginfo( 'name' );

	return $shop_name . ' - ' . $descriptor;
}, 10, 2 );
```

## Notes

- Both filters are applied **after** merge tag replacement, so the values already contain the replaced payment ID and order ID if applicable.
- The `$payment` object provides access to all payment information, allowing you to create dynamic references based on order data, customer information, etc.
- Be mindful of character limits for these fields as specified in the Worldline Direct API documentation.
