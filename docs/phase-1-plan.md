# Phase 1 implementation plan

**Goal:** Produce an installable FlowMail SMTP foundation with a migrated log table, capability-protected REST base, and buildable WordPress admin app.

1. Add PSR-4 Composer metadata and a guarded WordPress entry point. Activation and `plugins_loaded` invoke an idempotent schema migrator.
2. Register one `manage_options` REST bootstrap endpoint and WordPress admin menu/submenu entries. Only enqueue compiled assets on the plugin pages; pass REST URL and nonce to the app.
3. Build a scoped React/TypeScript Vite app with Dashboard, Mailers, Mail Logs, and Settings shell pages. Fetch the plugin version through an API service and test navigation and response handling.
4. Run PHP syntax checks, PHP unit tests, TypeScript checks, frontend tests, ESLint, and a production Vite build. Verify migration SQL format and asset filenames against bootstrap references.

Phase 1 ends with no mail-routing hooks. The later phases in [architecture.md](architecture.md) own credentials, transports, logs, and retries.
