---
title: Facade map
description: Mapping between TursoPlatform facade accessors, SDK resource classes, and configuration requirements.
section: Reference
eyebrow: Reference
---

## Resource map

Every `TursoPlatform::*()` accessor maps 1:1 to a resource on the SDK `Client`.

| Facade call | SDK resource | Operations | Notes |
|---|---|---:|---|
| `TursoPlatform::user()` | `Resources\Users` | 1 | Current user. |
| `TursoPlatform::auth()` | `Resources\Auth` | 1 | Token validation. |
| `TursoPlatform::locations()` | `Resources\Locations` | 1 | List available locations. |
| `TursoPlatform::organizations()` | `Resources\Organizations` | 7 | Org details, plans, invoices, subscription, usage. |
| `TursoPlatform::members()` | `Resources\Members` | 5 | Org member management. |
| `TursoPlatform::invites()` | `Resources\Invites` | 3 | Org invites (v2). |
| `TursoPlatform::databases()` | `Resources\Databases` | 12 | Database lifecycle, config, instances, tokens, usage, stats. |
| `TursoPlatform::groups()` | `Resources\Groups` | 13 | Group lifecycle, locations, config, tokens, transfer/unarchive. |
| `TursoPlatform::apiTokens()` | `Resources\ApiTokens` | 3 | Personal Platform API tokens. |
| `TursoPlatform::organizationApiTokens()` | `Resources\OrganizationApiTokens` | 2 | Org-scoped Platform API tokens. |
| `TursoPlatform::recovery()` | `Resources\Recovery` | 4 | v3 deleted DB/group restore; requires org UUID. |
| `TursoPlatform::auditLogs()` | `Resources\AuditLogs` | 1 | Paginated audit logs. |

## Container alternatives

```php
use Darkterminal\TursoPlatformAPI\Client\Client;
use Darkterminal\TursoPlatformLaravel\TursoPlatformManager;

$client = app(Client::class); // aliased from turso-platform
$client = app(TursoPlatformManager::class)->client();
```

## Environment to builder mapping

| Env | Config key | Builder | Required |
|---|---|---|---|
| `TURSO_PLATFORM_API_TOKEN` | env only | `withToken()` | All calls |
| `TURSO_PLATFORM_ORGANIZATION` | `turso-platform.organization` | `withOrganization()` | Most org-scoped calls |
| `TURSO_PLATFORM_ORGANIZATION_ID` | `turso-platform.organization_id` | `withOrganizationId()` | v3 recovery |
| `TURSO_PLATFORM_BASE_URL` | `turso-platform.base_url` | `withBaseUrl()` | Optional |
| `TURSO_PLATFORM_TIMEOUT` | `turso-platform.timeouts.default` | `withTimeout()` | Optional |

## Source of truth for signatures

This package is wiring only. For method signatures, request/response payloads, and SDK contract quirks, use the upstream SDK docs:

- <https://github.com/darkterminal/turso-api-client-php/blob/main/docs/api-map.md>
- <https://github.com/darkterminal/turso-api-client-php>