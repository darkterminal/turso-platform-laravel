---
title: Integrations
description: Use standalone SDK integrations alongside the Laravel package for database uploads and edge-aware region resolution.
section: Guide
eyebrow: SDK integrations
---

## Why integrations live in the SDK

`darkterminal/turso-api-client-php` ships standalone integrations that are useful outside the core Platform API resource model. This Laravel package does not duplicate them; you can use them directly because the SDK is installed as a dependency.

## DatabaseUploader

`Integrations\DatabaseUploader` handles SQLite file upload flows to Turso. Use it when you need to seed or migrate a local SQLite database into Turso.

```php
use Darkterminal\TursoPlatformAPI\Integrations\DatabaseUploader;

$uploader = DatabaseUploader::create(
    baseUrl: config('turso-platform.base_url'),
    organization: config('turso-platform.organization'),
    token: env('TURSO_PLATFORM_API_TOKEN'),
);

$result = $uploader->upload('/path/to/database.sqlite', 'target-db');
```

> Check the SDK documentation for the exact constructor/factory signature and upload options in your installed version. The upload flow may involve database creation, dump handling, or multipart upload depending on SDK behavior.

### Upload caveats

The SDK troubleshooting notes call out SQLite dump requirements for upload-related flows:

- WAL mode considerations;
- `page_size=4096`;
- `auto_vacuum=0`;
- UTF-8 text encoding.

Validate dumps before upload and test against a non-production database first.

## RegionResolver

`Integrations\RegionResolver` helps resolve edge/client location to a Turso location. This is useful when provisioning databases near users or selecting a primary location.

```php
use Darkterminal\TursoPlatformAPI\Integrations\RegionResolver;

$resolver = RegionResolver::create();
$location = $resolver->resolveFromRequest($request);
```

In Laravel, you can wire this in a service class or controller and pair it with `TursoPlatform::locations()->list()` if you need to validate/choose from available Turso locations.

## Using SDK classes in Laravel

Because the SDK is a Composer dependency, you can import any SDK class directly:

```php
use Darkterminal\TursoPlatformAPI\Client\Client;
use Darkterminal\TursoPlatformAPI\Exceptions\ApiException;
use Darkterminal\TursoPlatformAPI\Integrations\DatabaseUploader;
```

Keep Laravel-specific wiring (config, env, container, authorization) in your app; keep Platform API contracts in the SDK.