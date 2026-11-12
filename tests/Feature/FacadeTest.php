<?php

declare(strict_types=1);

use Darkterminal\TursoPlatformLaravel\Tests\TestCase;
use Darkterminal\TursoPlatformLaravel\TursoPlatform;

it('facade accessor points at the turso-platform binding', function () {
    putenv('TURSO_PLATFORM_API_TOKEN=test-token-for-resolution-unauthenticated');

    $this->app->forgetInstance('turso-platform');
    $this->app->forgetInstance(\Darkterminal\TursoPlatformLaravel\TursoPlatformManager::class);

    expect(TursoPlatform::getFacadeRoot())->toBeInstanceOf(\Darkterminal\TursoPlatformAPI\Client\Client::class);
});

it('facade type is not registered manually (auto-discovery only)', function () {
    expect(class_exists(TursoPlatform::class))->toBeTrue();
});
