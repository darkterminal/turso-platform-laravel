---
title: Getting started
description: Install Turso Platform for Laravel, configure environment variables, and make your first Platform API call.
section: Introduction
eyebrow: Quick start
---

## Install

```bash
composer require darkterminal/turso-platform-laravel
```

The service provider and facade are auto-discovered by Laravel. No manual registration is required.

## Configure environment

Set the Platform API token:

```dotenv
TURSO_PLATFORM_API_TOKEN=your-platform-api-token
TURSO_PLATFORM_ORGANIZATION=your-org-slug
```

Optional values:

```dotenv
TURSO_PLATFORM_ORGANIZATION_ID=your-org-uuid
TURSO_PLATFORM_BASE_URL=https://api.turso.tech
TURSO_PLATFORM_TIMEOUT=20
```

`TURSO_PLATFORM_ORGANIZATION_ID` is only required for v3 recovery operations. It must be the organization UUID, not the slug.

## Publish config (optional)

```bash
php artisan vendor:publish --provider="Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider" --tag=turso-platform-config
```

This creates `config/turso-platform.php`. The token is intentionally not stored in config; it is read from `TURSO_PLATFORM_API_TOKEN`.

## First call with the facade

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

Route::get('/turso/databases', function () {
    return TursoPlatform::databases()->list();
});
```

## First call with dependency injection

```php
use Darkterminal\TursoPlatformAPI\Client\Client;

Route::get('/turso/user', function (Client $client) {
    return $client->user()->get();
});
```

## Validate the token

```php
$validation = TursoPlatform::auth()->validate();
```

## Next steps

- Read [Configuration](configuration.md) for env/config details.
- Read [Facade and container usage](facade-and-container.md) for integration styles.
- Read [Errors and troubleshooting](errors-troubleshooting.md) before deploying with config cache or recovery operations.