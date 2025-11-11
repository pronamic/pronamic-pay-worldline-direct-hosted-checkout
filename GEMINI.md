# Gemini Agent Instructions

This file provides context and instructions for the Gemini AI agent to ensure it can assist effectively with this project while adhering to its conventions and standards.

## About This Project

This is a WordPress plugin that integrates the Worldline Direct Hosted Checkout payment method with the Pronamic Pay plugin.

- **Project Type:** WordPress Plugin
- **Main Language:** PHP
- **License:** GPL-2.0-or-later

## Key Technologies

- WordPress
- [Pronamic Pay](https://www.pronamic.nl/plugins/pronamic-pay/)
- [Worldline Direct Hosted Checkout](https://developer.worldline-solutions.com/gateway/en-us/hosted-checkout/about.html)
- PHP (version 8.1+)
- Composer for dependency management

## Project Structure

- `pronamic-pay-worldline-direct-hosted-checkout.php`: The main plugin entry file.
- `src/`: Contains all PHP source code, following PSR-4 autoloading.
- `vendor/`: Composer dependencies.
- `composer.json`: Defines project metadata, dependencies, and scripts.
- `phpcs.xml.dist`: Configuration for PHP_CodeSniffer.
- `phpstan.neon.dist`: Configuration for PHPStan.
- `rector.php`: Configuration for Rector.

## Development Workflow

### Dependencies

Install PHP dependencies using Composer:

```bash
composer install
```

### Coding Standards

This project uses `PHP_CodeSniffer` to enforce coding standards. Check for violations by running:

```bash
composer phpcs
```

### Static Analysis

This project uses `PHPStan` for static analysis. Run it with:

```bash
composer phpstan
```

### Building

The project can be built using the provided build script, which may handle tasks like creating a distributable version or updating assets.

```bash
composer build
```

## Instructions for Gemini

- **Adhere to Conventions:** Strictly follow the existing coding style, naming conventions, and architectural patterns found in the `src/` directory.
- **Use Existing Tools:** Utilize the defined Composer scripts for testing and validation (`composer phpcs`, `composer phpstan`).
- **Dependencies:** Do not add new dependencies to `composer.json` without explicit instruction.
- **Testing:** When adding new features or fixing bugs, please add or update corresponding tests if a testing framework is present.
- **Commits:** When asked to commit, follow the style of recent commit messages.
