<?php

use Hyn\Tenancy\Database\Connection;

return [
    'models' => [
        'hostname' => \Hyn\Tenancy\Models\Hostname::class,
        'website' => \Hyn\Tenancy\Models\Website::class,
    ],

    'middleware' => [
        \Hyn\Tenancy\Middleware\EagerIdentification::class,
        \Hyn\Tenancy\Middleware\HostnameActions::class,
    ],

    'website' => [
        'disable-random-id' => false,
        'random-id-generator' => Hyn\Tenancy\Generators\Uuid\ShaGenerator::class,
        'uuid-limit-length-to-32' => env('LIMIT_UUID_LENGTH_32', false),
        'disk' => null,
        'auto-create-tenant-directory' => true,
        'auto-rename-tenant-directory' => true,
        'auto-delete-tenant-directory' => true,
        'cache' => 10,
    ],

    'hostname' => [
        'default' => env('TENANCY_DEFAULT_HOSTNAME'),
        'auto-identification' => env('TENANCY_AUTO_HOSTNAME_IDENTIFICATION', true),
        'early-identification' => env('TENANCY_EARLY_IDENTIFICATION', true),
        'abort-without-identified-hostname' => env('TENANCY_ABORT_WITHOUT_HOSTNAME', false),
        'cache' => 10,
        'update-app-url' => false,
    ],

    'db' => [
        'default' => env('TENANCY_DEFAULT_CONNECTION', 'mysql'),
        'system-connection-name' => env('TENANCY_SYSTEM_CONNECTION_NAME', 'mysql'),
        'tenant-connection-name' => env('TENANCY_TENANT_CONNECTION_NAME', 'mysql'),
        'tenant-division-mode' => env('TENANCY_DATABASE_DIVISION_MODE', 'database'),
        'password-generator' => Hyn\Tenancy\Generators\Database\DefaultPasswordGenerator::class,
        'tenant-migrations-path' => database_path('migrations/tenant'),
        'tenant-seed-class' => false,
        'auto-create-tenant-database' => true,
        'auto-create-tenant-database-user' => true,
        'auto-rename-tenant-database' => true,
        'auto-delete-tenant-database' => env('TENANCY_DATABASE_AUTO_DELETE', false),
        'auto-delete-tenant-database-user' => env('TENANCY_DATABASE_AUTO_DELETE_USER', false),
        'force-tenant-connection-of-models' => [
        ],
        'force-system-connection-of-models' => [
        ],
    ],

    'routes' => [
        'path' => base_path('routes/tenants.php'),
        'replace-global' => false,
    ],

    'folders' => [
        'config' => [
            'enabled' => true,
            'blacklist' => ['database', 'tenancy', 'webserver'],
        ],
        'routes' => [
            'enabled' => true,
            'prefix' => null,
        ],
        'trans' => [
            'enabled' => true,
            'override-global' => true,
            'namespace' => 'tenant',
        ],
        'vendor' => [
            'enabled' => true,
        ],
        'media' => [
            'enabled' => true,
        ],
        'views' => [
            'enabled' => true,
            'namespace' => null,
            'override-global' => true,
        ],
    ],
];
