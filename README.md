<p align="center">
  <a href="https://discord.gg/turso">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://i.imgur.com/UhuW3zm.png">
      <source media="(prefers-color-scheme: light)" srcset="https://i.imgur.com/vljWbfr.png">
      <img alt="Shows a black logo in light color mode and a white one in dark color mode." src="https://i.imgur.com/vGCC0I4.png">
    </picture>
  </a>
</p>

<p align="center">
  <img alt="Turso Platform API + Laravel" src="https://i.imgur.com/dpml6YK.png" width="1000">
</p>

# Turso Platform API for Laravel

Laravel integration for `darkterminal/turso-api-client-php` — exposes the Turso Platform API as a pre-configured shared client: publishable config, auto-discovered Service Provider, and a Facade so calls read naturally in Laravel apps.

## Requirements

| Component | Version |
|---|---|
| PHP | 8.3+ |
| Laravel | 12+ |
| `darkterminal/turso-api-client-php` | `^2.0` (auto-installed) |

## Installation

```bash
composer require darkterminal/turso-platform-laravel
```

The Service Provider and Facade are auto-discovered. No manual registration needed.

## Configuration

```dotenv
TURSO_PLATFORM_API_TOKEN=your-platform-api-jwt
TURSO_PLATFORM_ORGANIZATION=your-org-slug       # optional
TURSO_PLATFORM_ORGANIZATION_ID=your-org-uuid    # required for v3 recovery operations
TURSO_PLATFORM_BASE_URL=https://api.turso.tech  # optional override
```

Publish the config if you need to customize:

```bash
php artisan vendor:publish --provider="Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider" --tag=turso-platform-config
```

## Usage

### Via Facade

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

$databases = TursoPlatform::databases()->list();
$token     = TursoPlatform::databases()->createToken('main', 'never', \Darkterminal\TursoPlatformAPI\Enums\Authorization::FULL_ACCESS);
```

### Via container injection

```php
use Darkterminal\TursoPlatformAPI\Client\Client;

public function index(Client $client)
{
    $databases = $client->databases()->list();
    // ...
}
```

### Via manager

```php
use Darkterminal\TursoPlatformLaravel\TursoPlatformManager;

$client = app(TursoPlatformManager::class)->client();
$databases = $client->databases()->list();
```

## Security notes

- `TURSO_PLATFORM_API_TOKEN` is only attached as a Bearer header to requests bound for the configured base URL. It is never attached to upload or resolver hosts (those are separate integrations handled by `darkterminal/turso-api-client-php`).
- See `turso-api-client-php` for the full set of retry/timeout/error behaviors and the contract-aligned exception hierarchy.
