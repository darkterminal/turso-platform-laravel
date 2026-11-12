# Facade map — Turso Platform API for Laravel

Every `TursoPlatform::*()` call maps 1:1 to a resource class in `darkterminal/turso-api-client-php` (`Client::*()`).
Method-level signatures, parameter contracts and return shapes are documented in the SDK's API map:

https://github.com/darkterminal/turso-api-client-php/blob/main/docs/api-map.md

## Resource inventory (53 operations, all implemented and test-covered in the SDK)

| Facade call | SDK resource class | Operations |
|---|---|---|
| `TursoPlatform::user()` | `Resources\Users` | 1 — `user()->get()` |
| `TursoPlatform::auth()` | `Resources\Auth` | 1 — `auth()->validate()` |
| `TursoPlatform::locations()` | `Resources\Locations` | 1 — `locations()->list()` |
| `TursoPlatform::organizations()` | `Resources\Organizations` | 7 — list, get, update, listPlans, listInvoices, getSubscription, getUsage |
| `TursoPlatform::members()` | `Resources\Members` | 5 — list, add, get, updateRole, remove |
| `TursoPlatform::invites()` | `Resources\Invites` | 3 — list, create, delete (v2) |
| `TursoPlatform::databases()` | `Resources\Databases` | 12 — list, create, get, delete, configuration get/update, instances list/get, createToken, invalidateTokens, usage, stats |
| `TursoPlatform::groups()` | `Resources\Groups` | 13 — list, create, get, delete, configuration get/update, transfer, unarchive, addLocation, removeLocation, update, createToken, invalidateTokens |
| `TursoPlatform::apiTokens()` | `Resources\ApiTokens` | 3 — personal: list, create, revoke |
| `TursoPlatform::organizationApiTokens()` | `Resources\OrganizationApiTokens` | 2 — org-scoped: list, revoke |
| `TursoPlatform::recovery()` | `Resources\Recovery` | 4 — v3: listDeletedDatabases, restoreDatabase, listDeletedGroups, restoreGroup (requires `TURSO_PLATFORM_ORGANIZATION_ID`) |
| `TursoPlatform::auditLogs()` | `Resources\AuditLogs` | 1 — list (page / page_size) |

## Container injection alternatives

```php
use Darkterminal\TursoPlatformAPI\Client\Client;

public function index(Client $client)          // aliased from 'turso-platform'
{
    return $client->databases()->list();
}
```

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatformManager;

$client = app(TursoPlatformManager::class)->client();
```

Functionally identical to `TursoPlatform::databases()` etc. The Facade is a thin accessor for `turso-platform`; the alias lets `Client` be injected directly in controllers and jobs.

## Environment → builder mapping

| Env | Config key | Builder call | Required for |
|---|---|---|---|
| `TURSO_PLATFORM_API_TOKEN` | *(not in config — read via `getenv()`)* | `withToken()` | all calls (else `ConfigurationException`) |
| `TURSO_PLATFORM_ORGANIZATION` | `turso-platform.organization` | `withOrganization()` | databases/groups/members/invites/tokens/audit logs |
| `TURSO_PLATFORM_ORGANIZATION_ID` | `turso-platform.organization_id` | `withOrganizationId()` | v3 recovery operations only |
| `TURSO_PLATFORM_BASE_URL` | `turso-platform.base_url` | `withBaseUrl()` | optional (default `https://api.turso.tech`) |
| `TURSO_PLATFORM_TIMEOUT` | `turso-platform.timeouts.default` | `withTimeout()` | optional (default `20`) |

## Contract quirks to know before using the facade

- `databases()->delete()` returns `string` (deleted DB name) or verbatim array — contract envelope is `{"database": "<string-name>"}` since v2.1.0; v2.0.0 threw a `TypeError`.
- v3 recovery (`recovery()`) throws `ConfigurationException` unless `TURSO_PLATFORM_ORGANIZATION_ID` (**UUID**) is set — the slug is not a valid substitute.
- 204/empty-2xx endpoints decode as `[]`, not an exception.
- Validation failures and auth errors surface as typed SDK exceptions (`ValidationException`, `AuthenticationException`, …) — there is no retry logic in this package; retries/timeouts belong in the SDK's HTTP layer.

## Making the SDK fail before an HTTP call

SDK resources validate input client-side (e.g., empty token names, path separators in names, non-HTTPS base URLs), so invalid input throws `ValidationException` before any request is made. That is covered by SDK tests — 78 tests / 167 assertions.
