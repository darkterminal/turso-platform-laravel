---
title: Tokens, audit logs, and recovery
description: Manage Platform API tokens, inspect audit logs, and restore deleted resources with v3 recovery endpoints.
section: Resources
eyebrow: Resources
---

## Personal API tokens

Use `TursoPlatform::apiTokens()` for personal Platform API tokens.

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

$tokens = TursoPlatform::apiTokens()->list();
$created = TursoPlatform::apiTokens()->create('ci-token');
TursoPlatform::apiTokens()->revoke('token-name');
```

| Operation | Example |
|---|---|
| List tokens | `TursoPlatform::apiTokens()->list()` |
| Create token | `TursoPlatform::apiTokens()->create('name')` |
| Revoke token | `TursoPlatform::apiTokens()->revoke('name')` |

## Organization API tokens

Use `TursoPlatform::organizationApiTokens()` for organization-scoped tokens:

```php
$tokens = TursoPlatform::organizationApiTokens()->list();
TursoPlatform::organizationApiTokens()->revoke('token-name');
```

| Operation | Example |
|---|---|
| List org tokens | `TursoPlatform::organizationApiTokens()->list()` |
| Revoke org token | `TursoPlatform::organizationApiTokens()->revoke('name')` |

Token values returned at creation time are sensitive. Capture them once, store securely, and never write them to logs.

## Audit logs

Use `TursoPlatform::auditLogs()` to inspect organization audit events:

```php
$logs = TursoPlatform::auditLogs()->list(page: 1, pageSize: 100);
```

Audit logs are useful for tracing who created/deleted databases, rotated tokens, changed members, or performed recovery actions. In Laravel, consider wrapping audit log reads in admin-only commands or dashboards.

## Recovery (v3)

Recovery uses v3 organization UUID endpoints. Configure the organization UUID:

```dotenv
TURSO_PLATFORM_ORGANIZATION=acme          # slug
TURSO_PLATFORM_ORGANIZATION_ID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
```

Use the facade:

```php
$deletedDatabases = TursoPlatform::recovery()->listDeletedDatabases();
TursoPlatform::recovery()->restoreDatabase('db-name');

$deletedGroups = TursoPlatform::recovery()->listDeletedGroups();
TursoPlatform::recovery()->restoreGroup('group-name');
```

| Operation | Example |
|---|---|
| List deleted databases | `TursoPlatform::recovery()->listDeletedDatabases()` |
| Restore database | `TursoPlatform::recovery()->restoreDatabase('name')` |
| List deleted groups | `TursoPlatform::recovery()->listDeletedGroups()` |
| Restore group | `TursoPlatform::recovery()->restoreGroup('name')` |

> `TURSO_PLATFORM_ORGANIZATION_ID` must be the organization UUID. The slug is not a valid substitute for v3 recovery. Missing UUID causes `ConfigurationException`.

## Operational caution

Recovery and token revocation are destructive or security-sensitive operations. Protect them behind explicit admin authorization, confirmation flows, and audit logging in your application.