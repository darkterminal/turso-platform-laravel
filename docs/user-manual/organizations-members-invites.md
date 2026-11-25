---
title: Organizations, members, and invites
description: Manage Turso organizations, team members, and invitations from Laravel.
section: Resources
eyebrow: Resources
---

## Organizations

Use `TursoPlatform::organizations()` for organization-level operations.

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

$orgs = TursoPlatform::organizations()->list();
$org = TursoPlatform::organizations()->get();
```

### Available operations

| Operation | Example |
|---|---|
| List organizations | `TursoPlatform::organizations()->list()` |
| Get organization | `TursoPlatform::organizations()->get()` |
| Update organization | `TursoPlatform::organizations()->update([...])` |
| List plans | `TursoPlatform::organizations()->listPlans()` |
| List invoices | `TursoPlatform::organizations()->listInvoices()` |
| Get subscription | `TursoPlatform::organizations()->getSubscription()` |
| Get usage | `TursoPlatform::organizations()->getUsage()` |

The organization slug comes from `TURSO_PLATFORM_ORGANIZATION` unless you pass/configure it explicitly through the SDK client behavior.

## Members

Use `TursoPlatform::members()` to manage organization members.

```php
$members = TursoPlatform::members()->list();
TursoPlatform::members()->add([...]);
```

| Operation | Example |
|---|---|
| List members | `TursoPlatform::members()->list()` |
| Add member | `TursoPlatform::members()->add([...])` |
| Get member | `TursoPlatform::members()->get('username')` |
| Update role | `TursoPlatform::members()->updateRole('username', [...])` |
| Remove member | `TursoPlatform::members()->remove('username')` |

Role values and payload schemas are defined by the SDK. Prefer SDK enums/constants where available instead of hardcoded strings.

## Invites

Use `TursoPlatform::invites()` for organization invitations.

```php
$invites = TursoPlatform::invites()->list();
TursoPlatform::invites()->create([...]);
```

| Operation | Example |
|---|---|
| List invites | `TursoPlatform::invites()->list()` |
| Create invite | `TursoPlatform::invites()->create([...])` |
| Delete invite | `TursoPlatform::invites()->delete('email-or-id')` |

## Access control considerations

These endpoints mutate or expose organization access. In your Laravel app:

- gate routes/actions with Laravel authorization (`can`, policies, middleware);
- audit who can trigger invite/member changes;
- avoid exposing raw Platform API responses directly to untrusted clients unless filtered;
- keep Platform API tokens server-side.

## Related resources

- [API tokens and audit logs](tokens-audit-recovery.md) — token lifecycle and audit trail.
- [Errors and troubleshooting](errors-troubleshooting.md) — 401/403 and config issues.