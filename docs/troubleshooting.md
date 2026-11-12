# Troubleshooting — Turso Platform API for Laravel

Only symptoms observed in this package, in its CI runs, or in the turso-api-client-php CI/live proofs are listed. Deeper runtime behaviour (retries, backoff, response mapping) is documented by the SDK: see its [troubleshooting](https://github.com/darkterminal/turso-api-client-php/blob/main/docs/troubleshooting.md).

## `ConfigurationException: TURSO_PLATFORM_API_TOKEN is not set`

Thrown by `TursoPlatformManager::token()` when the env var is missing, empty, or non-string. The manager reads the variable directly with `getenv()` (not via `config()`) on purpose:

- Laravel's `config:cache` does not affect it — `env()` inside config files is resolved at build time, while `getenv()` sees the process environment at call time;
- edits to `.env` between boot and resolution are honored without re-creating the container.

Resolution: set `TURSO_PLATFORM_API_TOKEN` in your environment (`.env`, systemd unit, Docker compose, CI secret). The linked config file intentionally does NOT contain the token — there is nothing to publish for it.

> Historical note: a v1.0.0 bug made the "missing token" path undetectable (`getenv() ?? ''` never triggered for `false`), leaking `false` into `withToken()`. Fixed in the QA pass released after 1.0.0 (PHPStan level 6 finding).

## `ConfigurationException` when calling `TursoPlatform::recovery()`

The v3 recovery operations hit `/v3/organizations/{organizationId}/…` where **`organizationId` is a UUID, not the organization slug** used everywhere else. Set **both**:

```dotenv
TURSO_PLATFORM_ORGANIZATION=softwarefreestylengineer          # slug — most resources
TURSO_PLATFORM_ORGANIZATION_ID=0b3f…-…                        # UUID — v3 recovery only
```

## 401/403 on Turso Cloud calls

The platform token and database-scoped JWTs are different credentials with different endpoints:

- this SDK sends its bearer to `TURSO_PLATFORM_BASE_URL` (default `https://api.turso.tech`) only;
- database JWTs belong to the `turso-api-client-php` upload/Hrana flows, not here.

Use a **Platform API token** from the Turso dashboard. Errors surface as typed exceptions carrying `statusCode`/`body`/`errorCode`; tokens are never printed to logs or exception traces (SDK guarantee, tested).

## Fatal: class `TursoPlatformServiceProvider` not found / provider not registered

Auto-discovery requires the composer manifest to ship `extra.laravel.providers` (it does — `TursoPlatformServiceProvider`). If auto-discovery fails after install or an upgrade:

```bash
composer dump-autoload
php artisan package:discover --ansi
```

and confirm:

```bash
php artisan about    # or: php artisan list | grep turso
```

## Publishing the config: wrong tag / provider not found

Correct invocation:

```bash
php artisan vendor:publish --provider="Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider" --tag=turso-platform-config
```

The publish tag is `turso-platform-config` (note: dashes, not underscores) and the provider class is `Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider`. On success, the file is written to `config/turso-platform.php`.

## `env()` removed from your config by hand and things break

`config/turso-platform.php` evaluates `env()` at config-build time. With `php artisan config:cache`, that is a snapshot. That is fine for the non-secret keys (`base_url`, organization slug/UUID, timeout) — the *token* is not in config at all, so caching is safe for it. Do not try to move the token into a published config: the manager deliberately refuses to read it from `config()`.

## Empty 2xx responses decoded as `[]`

Several endpoints (token rotation, configuration PATCH) return no body. The SDK accepts empty 2xx bodies as `[]` — this is intended, never an error. Don't add client-side guards that treat `[]` as failure.

## `Databases::delete()` returns a string, not an object

Contract fact: `DELETE …/databases/{name}` replies `{"database": "<name>"}` where `database` is a scalar string. Since v2.1.0 `databases()->delete()` returns `string`; earlier v2.0.0 signatures expected `array` and raised a `TypeError`. This package pins `darkterminal/turso-api-client-php:^2.1` accordingly.

## SQLSTATE / PDO errors when testing uploads or dump helpers locally

See the SDK's troubleshooting page for the `SQLSTATE[HY000] [14] unable to open database file` class of failure on CI runners and the dump pre-flight checklist (WAL, `page_size=4096`, `auto_vacuum=0`, UTF-8) used by `Integrations\DatabaseUploader` in live proofs.

## Verifying an install end to end without hitting the network

```bash
composer install
composer test      # pest, 7 tests / 14 assertions
composer analyse   # phpstan, level 6
```

CI runs both on PHP 8.3/8.4/8.5 × Laravel 12 on every push/PR. **Live** Turso Cloud proofs (e.g., `docker/upload-proof/`) are intentionally NOT part of CI and require `TURSO_PLATFORM_API_TOKEN` on the host.
