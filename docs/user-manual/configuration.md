---
title: Configuration
description: Environment variables, published config, token behavior, and builder mapping.
section: Guide
eyebrow: Configuration
---

## Environment variables

| Env | Config key | Default | Used for |
|---|---|---:|---|
| `TURSO_PLATFORM_API_TOKEN` | none (read from env at resolution) | required | Bearer token for Platform API requests. |
| `TURSO_PLATFORM_ORGANIZATION` | `turso-platform.organization` | `''` | Organization slug for org-scoped resources. |
| `TURSO_PLATFORM_ORGANIZATION_ID` | `turso-platform.organization_id` | `''` | Organization UUID for v3 recovery resources. |
| `TURSO_PLATFORM_BASE_URL` | `turso-platform.base_url` | `https://api.turso.tech` | Platform API base URL. |
| `TURSO_PLATFORM_TIMEOUT` | `turso-platform.timeouts.default` | `20` | HTTP timeout in seconds. |

## Published config

`config/turso-platform.php`:

```php
return [
    'organization' => env('TURSO_PLATFORM_ORGANIZATION', ''),
    'organization_id' => env('TURSO_PLATFORM_ORGANIZATION_ID', ''),
    'base_url' => env('TURSO_PLATFORM_BASE_URL', 'https://api.turso.tech'),
    'timeouts' => [
        'default' => env('TURSO_PLATFORM_TIMEOUT', 20),
    ],
];
```

Publish it with:

```bash
php artisan vendor:publish --provider="Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider" --tag=turso-platform-config
```

## Token handling

The manager reads `TURSO_PLATFORM_API_TOKEN` via `getenv()` when building the client. It does not read the token from the published config.

This is intentional:

- config caching does not hide token changes in the same way;
- token is not duplicated into config files;
- missing/empty token throws `Darkterminal\TursoPlatformAPI\Exceptions\ConfigurationException` with a clear message.

## Organization slug vs UUID

- **Slug** (`TURSO_PLATFORM_ORGANIZATION`) is for most v1/v2 resources: databases, groups, members, invites, audit logs, org-scoped API tokens, etc.
- **UUID** (`TURSO_PLATFORM_ORGANIZATION_ID`) is for v3 recovery only: `recovery()->listDeletedDatabases()`, `restoreDatabase()`, `listDeletedGroups()`, `restoreGroup()`.

If recovery is called without the UUID, the SDK throws `ConfigurationException`.

## Builder mapping

The service provider wires config into `TursoPlatformManager`, which builds the SDK client:

```php
Client::builder()
    ->withToken(getenv('TURSO_PLATFORM_API_TOKEN'))
    ->withBaseUrl($baseUrl)
    ->withTimeout($timeout)
    ->withOrganization($orgSlug)      // when set
    ->withOrganizationId($orgId)      // when set
    ->build();
```

## Config cache

`php artisan config:cache` is safe for the non-secret config keys. Keep the token in the environment. Do not move the token into config files.