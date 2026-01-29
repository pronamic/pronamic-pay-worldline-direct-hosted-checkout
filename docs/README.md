# Documentation

## Worldline Direct API Specification

This folder contains the OpenAPI specification for the Worldline Direct API (v2.416.0).

### Files

- `worldline-direct-api-v2.yaml` - OpenAPI 3.0.0 specification for Worldline Direct REST API

### Purpose

This specification is included for reference and verification purposes during development. It helps ensure compatibility with the Worldline Direct API and can be used to:

- Verify API endpoint implementations
- Check request/response structure compliance
- Validate payment product configurations
- Document supported features and capabilities

### Source

- **Official Documentation**: https://docs.direct.worldline-solutions.com/en/api-reference
- **Specification URL**: https://payment.preprod.direct.worldline-solutions.com/v1/public-contract-definition.yaml
- **API Version**: 2.416.0

### Updating

To update the specification, download the latest version from the official Worldline Direct source:

```bash
curl -o docs/worldline-direct-api-v2.yaml \
  https://payment.preprod.direct.worldline-solutions.com/v1/public-contract-definition.yaml
```

### Related Files

- Main API client: [src/Client.php](../src/Client.php)
- Integration configuration: [src/Config.php](../src/Config.php)
- Response handlers: [src/GetHostedCheckoutResponse.php](../src/GetHostedCheckoutResponse.php), [src/CreateHostedCheckoutResponse.php](../src/CreateHostedCheckoutResponse.php)
