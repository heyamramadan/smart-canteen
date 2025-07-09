<?php

return [
    'backup' => [
        'name' => 'SmartCanteen',

        'source' => [
            'files' => [
                'include' => [
                    base_path('storage/app/public'),
                ],
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],
            ],
            'databases' => [
                'mysql',
            ],
        ],

        'destination' => [
            'disks' => [
                'local',
                // 's3',
            ],
        ],
    ],

    'cleanup' => [
        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],
    ],
];
