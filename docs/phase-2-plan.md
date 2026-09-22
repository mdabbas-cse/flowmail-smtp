# Phase 2 provider configuration plan

**Scope:** Provider schemas, encrypted credentials, active-provider selection, protected REST configuration endpoints, and WordPress admin React forms. No mail hooks, sending, OAuth exchange, logs, or retry.

1. Define provider contracts, registry, manager, and nine isolated provider schema classes. Validate field types, required and conditional fields, provider IDs, and completeness on the server.
2. Add versioned WordPress option repositories and an authenticated-encryption service. Keep provider secrets encrypted and non-autoloaded, return only configured flags to REST, and preserve secrets when replacement inputs are blank.
3. Add protected providers and settings routes under the existing `flowmail-smtp/v1` namespace. Use consistent success/error envelopes and reject activation of incomplete or OAuth-disconnected providers.
4. Add a typed API client and dynamic React provider/settings components. Show secret status without hydrating secret values. Keep OAuth in a visibly disconnected, non-activatable state.
5. Test PHP behavior, frontend rendering and validation, REST permissions, secret redaction, build output, and WordPress activation. Review for no mail interception.
