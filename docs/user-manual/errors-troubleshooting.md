---
title: Errors and troubleshooting
description: Common configuration, auth, recovery, and Laravel integration issues for Turso Platform for Laravel.
section: Reference
eyebrow: Reference
---

## ConfigurationException: token not set

Symptom:

```text
ConfigurationException: TURSO_PLATFORM_API_TOKEN is not set
```

Cause: `TursoPlatformManager` reads `TURSO_PLATFORM_API_TOKEN` via `getenv()` at client resolution time. Missing or empty value throws a clear SDK `ConfigurationException`.

Fix:

- set `TURSO_PLATFORM_API_TOKEN` in `.env`, process env, container secrets, or CI secrets;
- do not put the token in `config/turso-platform.php`;
- after changing env in local dev, clear/reload as needed; config cache does not store this token.

## Recovery requires organization UUID

Symptom: calling `TursoPlatform::recovery()` fails with configuration error.

Cause: v3 recovery endpoints require organization UUID, not slug.

Fix:

```dotenv
TURSO_PLATFORM_ORGANIZATION=acme
TURSO_PLATFORM_ORGANIZATION_ID=00000000-0000-0000-0000-000000000000
```

## 401 / 403 from Platform API

Cause: invalid/expired Platform API token, wrong token type, or insufficient permissions.

Fix:

- confirm you are using a **Platform API token**;
- do not use database JWTs against `api.turso.tech`;
- check token scope/permissions in Turso dashboard;
- inspect the SDK exception's status/body/error code if surfaced.

## Provider/facade not found

Symptom: class not found or package not auto-discovered.

Fix:

```bash
composer dump-autoload
php artisan package:discover --ansi
```

Confirm `darkterminal/turso-platform-laravel` is installed and `extra.laravel.providers` is present in its `composer.json`.

## Config publish tag mismatch

Correct command:

```bash
php artisan vendor:publish --provider="Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider" --tag=turso-platform-config
```

The tag is `turso-platform-config`.

## Empty 2xx response

Some Platform API endpoints return empty bodies on success. The SDK decodes these as `[]`. Do not treat `[]` as an application error unless the endpoint contract says otherwise.

## Delete database return type

`databases()->delete()` returns a string (deleted DB name) in the current SDK contract. This package requires `darkterminal/turso-api-client-php:^2.1`; older SDK behavior may differ.

## Local verification

```bash
composer install
composer test
composer analyse
```

CI runs Pest and PHPStan on PHP 8.3/8.4/8.5 with Laravel 12. Live Turso Cloud proofs require real credentials and are not part of default CI.