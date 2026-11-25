---
title: Databases and groups
description: Manage Turso databases and groups through the Laravel facade or injected SDK client.
section: Resources
eyebrow: Resources
---

## Databases

Access database operations through `TursoPlatform::databases()`.

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

$databases = TursoPlatform::databases()->list();
$database = TursoPlatform::databases()->get('main');
```

### Common database operations

| Operation | Example |
|---|---|
| List databases | `TursoPlatform::databases()->list()` |
| Create database | `TursoPlatform::databases()->create([...])` |
| Get database | `TursoPlatform::databases()->get('name')` |
| Delete database | `TursoPlatform::databases()->delete('name')` |
| Get configuration | `TursoPlatform::databases()->getConfiguration('name')` |
| Update configuration | `TursoPlatform::databases()->updateConfiguration('name', [...])` |
| List instances | `TursoPlatform::databases()->listInstances('name')` |
| Get instance | `TursoPlatform::databases()->getInstance('name', 'instance')` |
| Create token | `TursoPlatform::databases()->createToken('name')` |
| Invalidate tokens | `TursoPlatform::databases()->invalidateTokens('name')` |
| Usage | `TursoPlatform::databases()->usage('name')` |
| Stats | `TursoPlatform::databases()->stats('name')` |

> Method signatures and payload shapes follow `darkterminal/turso-api-client-php`. Consult the SDK API map when you need exact parameter contracts.

### Database tokens

Create a database token for data-plane access:

```php
$token = TursoPlatform::databases()->createToken('main');
```

Invalidate tokens when credentials must be rotated:

```php
TursoPlatform::databases()->invalidateTokens('main');
```

Treat returned tokens as secrets. Do not log, persist in plaintext, or expose them to clients unnecessarily.

### Delete contract

`databases()->delete()` returns the deleted database name as a string (or the raw array, depending on SDK contract). This package pins `darkterminal/turso-api-client-php:^2.1`, where the scalar-string contract is handled correctly.

## Groups

Access group operations through `TursoPlatform::groups()`.

```php
$groups = TursoPlatform::groups()->list();
$group = TursoPlatform::groups()->get('default');
```

### Common group operations

| Operation | Example |
|---|---|
| List groups | `TursoPlatform::groups()->list()` |
| Create group | `TursoPlatform::groups()->create([...])` |
| Get group | `TursoPlatform::groups()->get('name')` |
| Delete group | `TursoPlatform::groups()->delete('name')` |
| Get configuration | `TursoPlatform::groups()->getConfiguration('name')` |
| Update configuration | `TursoPlatform::groups()->updateConfiguration('name', [...])` |
| Transfer | `TursoPlatform::groups()->transfer('name', [...])` |
| Unarchive | `TursoPlatform::groups()->unarchive('name')` |
| Add location | `TursoPlatform::groups()->addLocation('name', 'ams')` |
| Remove location | `TursoPlatform::groups()->removeLocation('name', 'ams')` |
| Update | `TursoPlatform::groups()->update('name', [...])` |
| Create token | `TursoPlatform::groups()->createToken('name')` |
| Invalidate tokens | `TursoPlatform::groups()->invalidateTokens('name')` |

## Organization scope

Most database and group operations are organization-scoped. Set `TURSO_PLATFORM_ORGANIZATION` to the organization slug. If the organization is missing where required, the SDK will raise a configuration/validation error depending on the operation.

## Error handling

Wrap Platform API calls where failure is expected:

```php
use Darkterminal\TursoPlatformAPI\Exceptions\ApiException;
use Darkterminal\TursoPlatformAPI\Exceptions\ValidationException;

try {
    $db = TursoPlatform::databases()->get('missing-db');
} catch (ValidationException $e) {
    // Invalid input before request.
} catch (ApiException $e) {
    // HTTP/API failure from SDK.
}
```

See [Errors and troubleshooting](errors-troubleshooting.md) for package-specific symptoms.