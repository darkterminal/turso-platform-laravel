# Architecture — Turso Platform API for Laravel

Thin wiring layer over `darkterminal/turso-api-client-php`: there is no HTTP code here. The package merges a publishable config, binds a shared core `Client`, and exposes it under both a Laravel-idiomatic `turso-platform` binding (auto-aliased to the core `Client::class`) and a `TursoPlatform` Facade.

## Namespace tree

```
Darkterminal\TursoPlatformLaravel
├── TursoPlatformServiceProvider.php   # auto-discovered via composer extra.laravel.providers
├── TursoPlatformManager.php           # builds the shared Client from config at first resolution
└── TursoPlatform.php                  # Facade; accessor 'turso-platform'; @method docblocks for every resource
```

## Container pipeline

```
register()
  ├─ mergeConfigFrom(config/turso-platform.php → 'turso-platform')
  ├─ singleton(TursoPlatformManager::class)   # closure receives config values as constructor args
  └─ singleton('turso-platform', fn ($app) => $app->make(TursoPlatformManager::class)->client())
       └─ alias('turso-platform', Client::class)   # constructor injection of Client works

boot()
  └─ publishes(config → config_path('turso-platform.php'), 'turso-platform-config')
```

- **Nothing HTTP happens at boot.** The binding is lazy; the core SDK issues its first request on first resource call.
- **Token is read directly from the environment** (`getenv()`), per resolution, so edits between boot and call are honored and Laravel config caching does not affect the token value.
- `TursoPlatformManager::client()` builds a `Client::builder()` each time a new shared client is created (singletons memoize it) and throws `ConfigurationException` (from the SDK) when the token is missing/empty — see `docs/troubleshooting.md`.
- Optional values from config: `organization` (slug → `withOrganization`), `organization_id` (UUID → `withOrganizationId`, only for v3 recovery), `base_url`, `timeouts.default`.
- The Facade docblock enumerates all 12 resources; the concrete behaviour lives in the SDK.

## Wiring vs the SDK

| Concern | This package | `darkterminal/turso-api-client-php` |
|---|---|---|
| Service Provider / Facade / publish tag | ✔ (`turso-platform-config`) | ✖ |
| Environment → builder mapping | ✔ (`base_url`, slug, org UUID, timeout) | ✖ |
| HTTP, retries, exceptions, contract mapping | ✖ (delegated) | ✔ |
| Contract quirks (scalar-string `deleteDatabase` envelope, empty-2xx bodies, v3 recovery requiring `organizationId`) | ✖ (documented via SDK) | ✔ |

## Quality gates

- `pest` — 7 tests / 14 assertions (green on PHP 8.3/8.4/8.5 × Laravel 12 in CI).
- `phpstan` — level 6 (`phpstan.neon`; `treatPhpDocTypesAsCertain: false` intentional).
- CI (`.github/workflows/tests.yml`) — matrix PHP 8.3/8.4/8.5 × Laravel 12: composer install, pest, PHPStan.

## Versioning

Semantic Versioning. v1.0.0 (2026-11-12) was the initial public release. `composer.json` requires `darkterminal/turso-api-client-php:^2.1`.
