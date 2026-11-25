---
title: Overview
description: What Turso Platform for Laravel provides, how it relates to turso-api-client-php, and where to start.
section: Introduction
eyebrow: User manual
---

## What is this package?

**Turso Platform for Laravel** (`darkterminal/turso-platform-laravel`) is a thin Laravel integration for the [Turso Platform PHP SDK](https://github.com/darkterminal/turso-api-client-php). It wires the SDK into Laravel's container and config system so you can manage Turso Platform resources with familiar Laravel ergonomics.

It provides:

- an auto-discovered service provider;
- a publishable config file (`turso-platform-config`);
- a shared `TursoPlatformManager`;
- container bindings for `turso-platform` and `Darkterminal\TursoPlatformAPI\Client\Client`;
- a `TursoPlatform` facade.

It does **not** reimplement the Platform API. HTTP, request validation, response decoding, and exception mapping live in `darkterminal/turso-api-client-php`.

## When to use it

Use this package when your Laravel app needs to automate Turso Platform control-plane operations:

- provision or inspect databases and groups;
- create/rotate database or group tokens;
- manage organizations, members, invites, and API tokens;
- read audit logs or usage/subscription data;
- restore deleted databases/groups through v3 recovery endpoints.

Do not use it as a SQL database driver. For data-plane queries, use a libSQL/Turso database client.

## Quick example

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

$databases = TursoPlatform::databases()->list();
$token = TursoPlatform::databases()->createToken('main');
```

Or inject the SDK client directly:

```php
use Darkterminal\TursoPlatformAPI\Client\Client;

public function index(Client $client)
{
    return $client->organizations()->get();
}
```

## Requirements

- PHP 8.3+
- Laravel 12+
- `darkterminal/turso-api-client-php` ^2.1 (installed by Composer)

## Documentation map

- [Getting started](getting-started.md) — install, env vars, first call.
- [Configuration](configuration.md) — env keys, publish config, token/org/timeout behavior.
- [Facade and container usage](facade-and-container.md) — facade, DI, manager.
- Resource guides — databases/groups, org/members/invites, tokens/audit/recovery.
- [Integrations](integrations.md) — DatabaseUploader and RegionResolver from the SDK.
- [Errors and troubleshooting](errors-troubleshooting.md) — common Laravel/package pitfalls.
- [Facade map](reference/facade-map.md) and [Architecture](reference/architecture.md).