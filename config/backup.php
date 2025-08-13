<?php

return [
    'backup' => [
       'name' => env('APP_NAME', 'SmartCanteen'),

        'source' => [
            'files' => [
                'include' => [
                    base_path('storage/app/public'),
                ],
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],
                'follow_links' => false,
                'ignore_unreadable_directories' => false,
                'relative_path' => base_path(''),
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
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,
        'default_strategy' => [
            'keep_all_backups_for_days' => 7,
            'keep_daily_backups_for_days' => 16,
            'keep_weekly_backups_for_weeks' => 8,
            'keep_monthly_backups_for_months' => 4,
            'keep_yearly_backups_for_years' => 2,
            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],
    ],
];
