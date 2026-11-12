<?php

declare(strict_types=1);

use Darkterminal\TursoPlatformLaravel\TursoPlatformManager;

it('builds a shared Client from config with org set', function () {
    putenv('TURSO_PLATFORM_API_TOKEN=test-token-for-good-path');

    $manager = new TursoPlatformManager(
        baseUrl: 'https://api.turso.tech',
        orgSlug: 'acme',
        orgId: 'd0b1a2c3-0000-4000-8000-000000000001',
        timeout: 20,
    );

    $client = $manager->client();

    expect($client)->toBeInstanceOf(\Darkterminal\TursoPlatformAPI\Client\Client::class)
        ->and($client->configuration()->organization)->toBe('acme')
        ->and($client->configuration()->organizationId)->toBe('d0b1a2c3-0000-4000-8000-000000000001')
        ->and($client->configuration()->baseUrl)->toBe('https://api.turso.tech')
        ->and($client->configuration()->timeout)->toBe(20);
});

it('throws a clear exception when token is missing', function () {
    putenv('TURSO_PLATFORM_API_TOKEN=');

    $manager = new TursoPlatformManager(
        baseUrl: 'https://api.turso.tech',
        orgSlug: '',
        orgId: '',
        timeout: 20,
    );

    expect(fn () => $manager->client())
        ->toThrow(\Darkterminal\TursoPlatformAPI\Exceptions\ConfigurationException::class);
});
