<?php

declare(strict_types=1);

namespace Darkterminal\TursoPlatformLaravel;

/**
 * Turso Platform Facade.
 *
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Databases databases()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Groups groups()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Organizations organizations()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Members members()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Invites invites()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\ApiTokens apiTokens()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\OrganizationApiTokens organizationApiTokens()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Locations locations()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Auth auth()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Users user()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\Recovery recovery()
 * @method static \Darkterminal\TursoPlatformAPI\Resources\AuditLogs auditLogs()
 *
 * @see \Darkterminal\TursoPlatformAPI\Client\Client
 */
final class TursoPlatform extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'turso-platform';
    }
}
