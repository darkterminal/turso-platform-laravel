<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Turso Platform API Configuration
    |--------------------------------------------------------------------------
    |
    | This config corresponds to the `TURSO_PLATFORM_*` environment variables.
    | Everything here is optional — values not set explicitly will fall back
    | to sensible defaults compatible with Turso Cloud.
    |
    */

    // Organization slug. Example: 'softwarefreestylengineer'.
    // Required for organization-scoped resources (databases, groups, ...).
    'organization' => env('TURSO_PLATFORM_ORGANIZATION', ''),

    // Organization ID (UUID). Required only for `recovery()` v3 operations.
    'organization_id' => env('TURSO_PLATFORM_ORGANIZATION_ID', ''),

    // Platform API base URL.
    'base_url' => env('TURSO_PLATFORM_BASE_URL', 'https://api.turso.tech'),

    // Timeouts for the underlying HTTP client (seconds).
    'timeouts' => [
        'default' => env('TURSO_PLATFORM_TIMEOUT', 20),
    ],
];
