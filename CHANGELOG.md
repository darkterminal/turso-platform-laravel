# Changelog

All notable changes to this project are documented in this file. The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Changed

- `darkterminal/turso-api-client-php` constraint raised from `^2.0` to `^2.1` (pulls in the v2.1.0 `deleteDatabase` scalar-string contract fix).

### Fixed

- `TursoPlatformManager::token()` — `getenv()` returns `string|false`, so the `?? ''` fallback never caught a missing token and `false` leaked into `withToken()`. The manager now throws `ConfigurationException` with the documented message when `TURSO_PLATFORM_API_TOKEN` is missing or empty. (Found via PHPStan level 6.)

### Added

- PHPStan (`level 6`) with `phpstan.neon`; `composer analyse` script; CI (`.github/workflows/tests.yml`) now runs pest + PHPStan on every matrix leg (PHP 8.3/8.4/8.5 × Laravel 12).
- `art/TursoPlatformAPIforLaravel.png` — package banner; README now references the local file instead of the external imgur banner.

## [1.0.0] — Released 2026-11-12

Initial public release.

Laravel integration package for the [Turso Platform API PHP SDK](https://github.com/darkterminal/turso-api-client-php) (`darkterminal/turso-api-client-php` v2).

### Added

- `TursoPlatformServiceProvider` — auto-discovered, merges `turso-platform` config, registers publishable `turso-platform-config` tag, binds shared `Client` as `turso-platform` (aliased to the core `Client::class`).
- `TursoPlatformManager` — builds the shared client from `TURSO_PLATFORM_*` env/config values at first resolution (no eager HTTP; nothing runs until you call). The token is read directly from the environment (`getenv()`) so config caching does not affect it.
- `TursoPlatform` Facade — fluent access to every SDK resource.
- Publishable `config/turso-platform.php` (`TURSO_PLATFORM_ORGANIZATION`, `TURSO_PLATFORM_ORGANIZATION_ID`, `TURSO_PLATFORM_BASE_URL`, `TURSO_PLATFORM_TIMEOUT`).
- Pest + Orchestra Testbench suite (7 tests / 14 assertions), CI matrix PHP 8.3/8.4/8.5 × Laravel 12.
