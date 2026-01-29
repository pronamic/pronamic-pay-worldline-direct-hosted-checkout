# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Added customer data to hosted checkout requests for improved payment information in Worldline interface:
  - Personal information (first name, surname)
  - Contact details (email address)
  - Billing address (street, house number, city, postal code, country code)
  - Locale
  - Device IP address

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
