# AGENTS.md

## Project Overview

**Pronamic Pay – Worldline – Direct – Hosted Checkout** is a WordPress plugin that provides integration between WordPress/WooCommerce and **Worldline Direct**, a modern payment platform by Worldline. This plugin is the successor to earlier Ogone and Ingenico integrations and is designed for merchants migrating from legacy payment solutions to Worldline Direct.

### Key Information for AI Agents

- **Project Type**: WordPress Plugin
- **Language**: PHP
- **Purpose**: Payment gateway integration for WordPress (supports WooCommerce, Gravity Forms, Contact Form 7, and other popular plugins)
- **Payment Platform**: Worldline Direct (REST API v2.416.0)
- **Code Standards**: 
  - [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
  - Pronamic WordPress Coding Standards (Pronamic-specific extensions to WCS)

## Architecture

### Plugin Structure

The main plugin file is [pronamic-pay-worldline-direct-hosted-checkout.php](pronamic-pay-worldline-direct-hosted-checkout.php), which follows WordPress plugin conventions.

### Source Code Organization

Source code is located in the [src/](src/) directory:

- **[Client.php](src/Client.php)** - HTTP client for Worldline Direct API communication
- **[Config.php](src/Config.php)** - Configuration management for API credentials and settings
- **[Gateway.php](src/Gateway.php)** - Main payment gateway implementation, handles payment processing
- **[Integration.php](src/Integration.php)** - Pronamic Pay integration adapter
- **[WebhookController.php](src/WebhookController.php)** - Webhook handler for payment status updates
- **Response Classes**:
  - [CreateHostedCheckoutResponse.php](src/CreateHostedCheckoutResponse.php)
  - [GetHostedCheckoutResponse.php](src/GetHostedCheckoutResponse.php)
  - [CreatedPaymentOutput.php](src/CreatedPaymentOutput.php)
  - [PaymentResponse.php](src/PaymentResponse.php)
- **Status Classes**:
  - [HostedCheckoutStatus.php](src/HostedCheckoutStatus.php)
  - [PaymentStatusCategory.php](src/PaymentStatusCategory.php)

### Dependencies

- **Pronamic Pay Framework** (`wp-pay/core`): Manages payment processing lifecycle
- **Pronamic HTTP Client** (`pronamic/wp-http`): HTTP client for API communication
- **Pronamic WP Updater** (`pronamic/pronamic-wp-updater`): Plugin update handling
- **Jetpack Autoloader** (`automattic/jetpack-autoloader`): Composer dependency management
- **WordPress**: Minimum required version as per compatibility

### Language Files

Internationalization (i18n) support via [languages/](languages/) directory with `.pot` template file.

## Worldline Direct API Integration

### Hosted Checkout Flow

The primary payment method uses **Hosted Checkout**, a REST API endpoint that manages the complete payment flow:

1. **Create Hosted Checkout** (`POST /v2/{merchantId}/hostedcheckouts`)
   - Initiates a payment session
   - Returns `hostedCheckoutId` and redirect URL
   - Supports payment product filtering (cards, redirects, SEPA, mobile payments)
   - Configurable result page display

2. **Get Hosted Checkout Status** (`GET /v2/{merchantId}/hostedcheckouts/{hostedCheckoutId}`)
   - Retrieves session status
   - Contains payment details if transaction was created
   - Sessions have maximum 3-hour lifespan

### Request Structure

The API expects structured request bodies with:
- **Order Information**: Amount, currency, customer details, references
- **Payment Method Specific Input**: Card, redirect (iDeal, PayPal), mobile (Apple Pay, Google Pay), SEPA Direct Debit
- **Hosted Checkout Specific Input**: Locale, product filters, return URL, result page settings
- **Fraud Fields**: IP address, product categories for risk assessment

### Response Handling

API responses include:
- `hostedCheckoutId`: Session identifier
- `redirectUrl`: URL for customer redirect
- `merchantReference`: Reference matching request
- `paymentStatusCategory`: Status of any created payment (SUCCESSFUL, UNSUCCESSFUL, etc.)

### Key API Details

- **Authentication**: HMAC-SHA256 signature-based (see [API Authentication](https://docs.direct.worldline-solutions.com/en/integration/api-developer-guide/authentication))
- **Format**: JSON for all payloads
- **Protocol**: REST over HTTPS
- **Error Handling**: Detailed error responses with error codes and messages
- **API Reference**: [Worldline Direct Hosted Checkout API](https://docs.direct.worldline-solutions.com/en/api-reference#tag/HostedCheckout)

### Supported Payment Products

- **Card Payments**: Credit/debit cards (Visa, Mastercard, American Express, etc.)
- **Redirect Payments**: iDeal, PayPal, Bancontact, and other redirect-based methods
- **Mobile Payments**: Apple Pay, Google Pay
- **Direct Debit**: SEPA Direct Debit (771)
- **Alternative Payment Methods**: Depends on merchant configuration

## Implementation Details

### Key Classes and Responsibilities

#### Gateway Class
Implements the main payment processing logic:
- Handles payment creation requests
- Manages hosted checkout redirects
- Processes return URLs and status checks
- Integrates with Pronamic Pay payment status system

#### Client Class
Handles all API communication:
- HTTP requests to Worldline Direct API
- Request signing and authentication
- Response parsing and error handling

#### WebhookController Class
Processes webhook notifications:
- Receives payment status updates from Worldline
- Updates order status in WordPress
- Validates webhook signatures

#### Integration Class
Adapts the payment gateway to Pronamic Pay:
- Payment method registration
- Gateway configuration UI
- Currency and amount handling

### PHP Code Standards

All code follows **WordPress Coding Standards** with **Pronamic-specific extensions**:

- **Files**: Use `snake_case.php` naming
- **Classes**: Use `PascalCase` naming
- **Methods/Functions**: Use `camelCase` naming
- **Constants**: Use `SCREAMING_SNAKE_CASE` naming
- **Properties/Variables**: Use `snake_case` naming with appropriate visibility
- **Comments**: Use PHPDoc-style documentation for all public methods and classes
- **Indentation**: Tabs (WordPress standard)
- **Line Length**: Aim for readability, max 100 characters where practical
- **String Quotes**: Use single quotes in PHP, double quotes for HTML attributes
- **Type Declarations**: Include return types and parameter types where possible (modern PHP)
- **Spacing**: Single blank line between methods/functions

### Pronamic Pay Integration

The plugin extends Pronamic Pay's payment gateway framework:

1. **Payment Flows**:
   - Payment creation and submission
   - Status checking and updates
   - Webhook notification handling
   - Refunds and cancellations

2. **Status Mapping**:
   - Maps Worldline payment states to Pronamic Pay statuses
   - Supports status categories: SUCCESSFUL, UNSUCCESSFUL, PENDING, CREATED, etc.

3. **Order Integration**:
   - Automatic order status updates
   - Transaction ID and reference mapping
   - Payment details persistence

## Configuration

The plugin requires:

1. **Merchant Credentials**:
   - Merchant ID (from Worldline Direct)
   - API Key and Secret (for HMAC-SHA256 signing)

2. **Gateway Settings**:
   - Test/Live environment selection
   - Payment product filtering
   - Currency and locale configuration
   - Webhook URL configuration

3. **WordPress Integration**:
   - Integration with WooCommerce (if applicable)
   - Order payment status hooks
   - Notification email settings

## Webhooks and Notifications

The plugin implements webhook handling for payment status notifications:

- **Endpoint**: `/wp-json/pronamic-pay/v1/webhooks/worldline`
- **Signature Validation**: HMAC-SHA256
- **Supported Events**: Payment status changes (AUTHORIZED, CAPTURED, REFUNDED, etc.)
- **Processing**: Asynchronous update of payment status in WordPress

## Migration from Ogone/Ingenico

For merchants migrating from legacy solutions:

1. Merchants should follow [Worldline Migration Guide](https://docs.direct.worldline-solutions.com/en/migrate/migrate-to-direct/)
2. Hosted Checkout provides modern alternative to legacy `orderstandard.asp`
3. No PCI compliance burden (SAQ-A level)
4. Modular payment flow configuration

## Resources and Documentation

- **Worldline Direct API Reference**: https://docs.direct.worldline-solutions.com/en/api-reference#tag/HostedCheckout
- **Worldline SDK**: https://github.com/wl-online-payments-direct/sdk-php
- **Integration Guide**: https://docs.direct.worldline-solutions.com/en/integration/api-developer-guide/authentication
- **Migration Guide**: https://docs.direct.worldline-solutions.com/en/migrate/migrate-to-direct/
- **Hosted Checkout Migration**: https://docs.direct.worldline-solutions.com/en/migrate/migrate-to-direct/migrate-hosted-checkout
- **Packagist Package**: https://packagist.org/packages/pronamic/pronamic-pay-worldline-direct-hosted-checkout

## Development Notes for AI Agents

When working with this codebase:

1. **Understand the Payment Flow**: Always consider the full hosted checkout flow from creation through status checking
2. **API Communication**: Requests to Worldline require proper HMAC-SHA256 authentication
3. **Webhook Handling**: Payment updates may come asynchronously via webhooks; implement proper error handling and retry logic
4. **WordPress Integration**: Remember this is a WordPress plugin; use WordPress hooks and filters appropriately
5. **Status Mapping**: Always map Worldline statuses correctly to Pronamic Pay statuses
6. **Error Handling**: Include proper error messages and logging for debugging issues
7. **Security**: Protect API credentials, validate webhooks, and sanitize/escape user input per WordPress standards
8. **Testing**: Consider test environment credentials; implement test payment flows
9. **Compatibility**: Maintain compatibility with WordPress versions and Pronamic Pay framework updates
