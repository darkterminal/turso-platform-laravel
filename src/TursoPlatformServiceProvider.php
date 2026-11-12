<?php

declare(strict_types=1);

namespace Darkterminal\TursoPlatformLaravel;

use Darkterminal\TursoPlatformAPI\Client\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Turso Platform service provider.
 *
 * - Publishes the `turso-platform` config publish tag.
 * - Binds a shared Client as `turso-platform` (aliased to the core Client).
 * - Config is read lazily by the manager, so .env edits between boot and
 *   resolution are still honored.
 */
final class TursoPlatformServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/turso-platform.php', 'turso-platform');

        // Config is bound lazily — read at first resolution, not at boot,
        // so edits between boot and call still take effect.
        $this->app->singleton(TursoPlatformManager::class, fn ($app) => new TursoPlatformManager(
            baseUrl: (string) $app['config']['turso-platform.base_url'],
            orgSlug: (string) $app['config']['turso-platform.organization'],
            orgId: (string) $app['config']['turso-platform.organization_id'],
            timeout: (int) $app['config']['turso-platform.timeouts.default'],
        ));

        $this->app->singleton('turso-platform', fn ($app) => $app->make(TursoPlatformManager::class)->client());
        $this->app->alias('turso-platform', Client::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/turso-platform.php' => config_path('turso-platform.php'),
        ], 'turso-platform-config');
    }
}
