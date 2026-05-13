# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.4.0] - 2026-05-13

### Added

- Added WordPress filters `pronamic_pay_worldline_direct_hosted_checkout_merchant_reference` and `pronamic_pay_worldline_direct_hosted_checkout_descriptor` to allow customization of the merchant reference and descriptor via code.

Full set of changes: [`1.3.0...1.4.0`][1.4.0]

[1.4.0]: https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/compare/v1.3.0...v1.4.0

## [1.3.0] - 2026-03-23

### Added

- Added locale to `hostedCheckoutSpecificInput` to control the language of the Worldline-hosted payment page.
- Added configurable reference fields (`merchantReference`, `descriptor`, `invoiceNumber`) with merge tag support, allowing dynamic values from payment data.

Full set of changes: [`1.2.0...1.3.0`][1.3.0]

[1.3.0]: https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/compare/v1.2.0...v1.3.0

## [1.2.0] - 2026-01-30

### Commits

- Added customer data to Worldline hosted checkout requests (#8) ([7a2ed01](https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/commit/7a2ed018ad1d7e9e533fb1b918f05ce284911d53))
- Fixed incorrect order field names in hosted checkout request ([e4e041c](https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/commit/e4e041ce4fe1f94c6581ece085c031a5bdc91ad0))
- Added Worldline Direct API OpenAPI spec and docs ([d2c4642](https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/commit/d2c464242069ce5706fc9d8b74c93a474012288d))

### Composer

- Changed `wp-pay/core` from `v4.30.0` to `v4.31.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.31.0

Full set of changes: [`1.1.0...1.2.0`][1.2.0]

[1.2.0]: https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/compare/v1.1.0...v1.2.0

## [1.1.0] - 2026-01-27

### Commits

- Updated required PHP to version `8.2`. ([45df8bb](https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/commit/45df8bbf43d0173b208ac121351bdda7139e7b61))
- Added support for variant field ([538a319](https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/commit/538a319863f3fe692c52996428108cb43a23320f))

### Composer

- Changed `php` from `>=8.1` to `>=8.2`.
- Changed `automattic/jetpack-autoloader` from `v5.0.12` to `v5.0.15`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v5.0.15
- Changed `wp-pay/core` from `v4.26.0` to `v4.30.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.30.0

Full set of changes: [`1.0.0...1.1.0`][1.1.0]

[1.1.0]: https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/compare/v1.0.0...v1.1.0

## [1.0.0] - 2024-03-26

- First relase.

[1.0.0]: https://github.com/pronamic/pronamic-pay-worldline-direct-hosted-checkout/releases/tag/v1.0.0
