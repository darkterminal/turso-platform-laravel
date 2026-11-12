<?php

declare(strict_types=1);

namespace Darkterminal\TursoPlatformLaravel;

use Darkterminal\TursoPlatformAPI\Client\Client;
use Darkterminal\TursoPlatformAPI\Client\ClientBuilder;

/**
 * Builds the Turso Platform {@see Client} from config.
 *
 * Lives on the container (`turso-platform`) so the Facade can resolve
 * cleanly. No HTTP code of its own — it only wires the core SDK with
 * runtime values.
 */
final class TursoPlatformManager
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $orgSlug,
        private readonly string $orgId,
        private readonly int $timeout,
    ) {}

    public function client(): Client
    {
        $builder = Client::builder()
            ->withToken($this->token())
            ->withBaseUrl($this->baseUrl)
            ->withTimeout($this->timeout);

        if ($this->orgSlug !== '') {
            $builder = $builder->withOrganization($this->orgSlug);
        }
        if ($this->orgId !== '') {
            $builder = $builder->withOrganizationId($this->orgId);
        }

        return $builder->build();
    }

    /** Token read from config each call so .env changes are reflected without cache flushes. */
    private function token(): string
    {
        $token = getenv('TURSO_PLATFORM_API_TOKEN');
        if (!is_string($token) || $token === '') {
            throw new \Darkterminal\TursoPlatformAPI\Exceptions\ConfigurationException(
                'TURSO_PLATFORM_API_TOKEN is not set. Publish the package config and populate the token.',
            );
        }
        return $token;
    }
}
