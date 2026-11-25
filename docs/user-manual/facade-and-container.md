---
title: Facade and container usage
description: Use the TursoPlatform facade, inject the SDK Client, or resolve the manager.
section: Guide
eyebrow: Usage
---

## Facade

The facade is the most Laravel-idiomatic entry point:

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

$databases = TursoPlatform::databases()->list();
$user = TursoPlatform::user()->get();
```

Facade accessor resolves `turso-platform`, which is a singleton SDK `Client`.

## Container injection

The package aliases `turso-platform` to `Darkterminal\TursoPlatformAPI\Client\Client`, so you can inject the client directly:

```php
use Darkterminal\TursoPlatformAPI\Client\Client;

class TursoController
{
    public function index(Client $client)
    {
        return $client->databases()->list();
    }
}
```

This is useful for controllers, jobs, commands, and services where dependency injection is preferred.

## Manager

`TursoPlatformManager` builds the client from config. It is bound as a singleton:

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatformManager;

$client = app(TursoPlatformManager::class)->client();
```

You normally do not need the manager directly unless you are testing bindings or extending package behavior.

## Resource access

All SDK resource getters are available:

```php
TursoPlatform::user();
TursoPlatform::auth();
TursoPlatform::locations();
TursoPlatform::organizations();
TursoPlatform::members();
TursoPlatform::invites();
TursoPlatform::databases();
TursoPlatform::groups();
TursoPlatform::apiTokens();
TursoPlatform::organizationApiTokens();
TursoPlatform::auditLogs();
TursoPlatform::recovery();
```

## Which style to pick?

| Style | Use when |
|---|---|
| Facade | Quick route/controller code, Blade-adjacent services, simple scripts. |
| Client DI | Application services, jobs, testable classes, clean architecture boundaries. |
| Manager | Package internals, advanced container customization, debugging resolution. |

All styles use the same configured SDK client behavior.