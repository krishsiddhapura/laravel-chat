<?php
/**
 * @see https://github.com/pionl/laravel-chunk-upload
 */

return [
    // ...

    'default' => [
        'store' => 'file',
        'encrypt' => false,
        'cleanup_days' => 1,
        'max_file_size' => null,
        'chunk_size' => 1024 * 1024 * 5, // Set your default chunk size in bytes (e.g., 1MB)
    ],

    'storage' => [
        'chunks' => 'chunks',
        'disk' => 'local',
    ],
    'clear' => [
        'timestamp' => '-3 HOURS',
        'schedule' => [
            'enabled' => true,
            'cron' => '25 * * * *',
        ],
    ],
    'chunk' => [
        'name' => [
            'use' => [
                'session' => true,
                'browser' => false,
            ],
        ],
    ],
    'handlers' => [
        'custom' => [],
        'override' => [],
    ],
];
