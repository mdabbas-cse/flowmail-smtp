# Phase 3 plan — FlowMail SMTP sending

The existing provider registry and encrypted settings remain the source of truth. A provider that implements `SmtpTransportProviderInterface` can supply validated SMTP options to `PHPMailerConfigurator`. Custom SMTP is the first sending provider. Other configured providers return a safe unsupported-transport failure until their real API or OAuth transport exists.

WordPress continues to own `wp_mail()` parsing, recipients, MIME, and attachments. `WordPressMailIntegration` uses `wp_mail` to capture normalized message data, `pre_wp_mail` to reject an unusable selected provider, sender filters for configured defaults, `phpmailer_init` for SMTP, and success/failure actions for structured results. It leaves mail untouched when no provider is active. No Phase 4 persistence is added.

`TestMailService` calls `wp_mail()` through the same path. Its REST route requires `manage_options` and returns only safe status data. The dashboard shows the test form and active-provider warning.

Verification: PHP syntax, Composer validation and tests, PHPCS, frontend typecheck/lint/tests/build, and WordPress integration where the local container is available. Live SMTP delivery requires user-owned SMTP credentials and is outside local automated tests.
