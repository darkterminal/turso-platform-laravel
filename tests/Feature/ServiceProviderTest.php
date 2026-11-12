<?php

declare(strict_types=1);

use Darkterminal\TursoPlatformAPI\Client\Client;
use Darkterminal\TursoPlatformLaravel\Tests\TestCase;
use Darkterminal\TursoPlatformLaravel\TursoPlatformServiceProvider;

it('service provider is auto-discovered', function () {
    expect(array_key_exists(
        TursoPlatformServiceProvider::class,
        $this->app->getLoadedProviders(),
    ))->toBeTrue();
});

it('binding turso-platform resolves to a shared Client', function () {
    putenv('TURSO_PLATFORM_API_TOKEN=test-token-for-resolution');
    // force fresh resolution
    $this->app->forgetInstance('turso-platform');
    $this->app->forgetInstance(TursoPlatformManager::class);
    $this->app->forgetInstance(\Darkterminal\TursoPlatformLaravel\TursoPlatformManager::class);

    $a = $this->app->make('turso-platform');
    $b = $this->app->make('turso-platform');

    expect($a)->toBeInstanceOf(Client::class)->and($a)->toBe($b);
});

it('resolves the documented config keys from the published file', function () {
    expect(config('turso-platform.base_url'))->toBeString();
    expect(config('turso-platform.organization'))->toBeString();
    expect(config('turso-platform.organization_id'))->toBeString();
});
