---
title: Architecture
description: Internal package structure, container wiring, and boundaries between the Laravel package and the core SDK.
section: Reference
eyebrow: Reference
---

## Design boundary

Turso Platform for Laravel is a thin integration layer. It owns Laravel wiring; it does not own Platform API HTTP behavior.

| Concern | This package | `darkterminal/turso-api-client-php` |
|---|---:|---:|
| Service provider / facade / config publish | Yes | No |
| Env/config to SDK builder mapping | Yes | No |
| HTTP, retries, request/response mapping | No | Yes |
| Typed exceptions and API contracts | No | Yes |
| Standalone integrations | No | Yes |

## Namespace layout

```text
Darkterminal\TursoPlatformLaravel
├── TursoPlatformServiceProvider.php
├── TursoPlatformManager.php
└── TursoPlatform.php
```

## Container pipeline

```text
register()
  ├─ mergeConfigFrom(config/turso-platform.php, 'turso-platform')
  ├─ singleton(TursoPlatformManager::class)
  └─ singleton('turso-platform', fn ($app) => $app->make(TursoPlatformManager::class)->client())
       └─ alias('turso-platform', Client::class)

boot()
  └─ publishes(config/turso-platform.php, tag: turso-platform-config)
```

## Resolution behavior

- No HTTP calls at boot.
- Client is built lazily when resolved.
- `TursoPlatformManager` reads token from `getenv('TURSO_PLATFORM_API_TOKEN')` at resolution time.
- Missing token throws SDK `ConfigurationException`.
- Organization slug, organization UUID, base URL, and timeout come from config.

## Quality gates

- Pest: 7 tests / 14 assertions in package.
- PHPStan: level 6.
- CI matrix: PHP 8.3/8.4/8.5 × Laravel 12.

## Why the boundary matters

Keeping HTTP/contracts in the SDK means bug fixes and new Platform API coverage land in one place. The Laravel package stays small, predictable, and easy to audit.