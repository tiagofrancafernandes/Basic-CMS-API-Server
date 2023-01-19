<?php

return [
    'seeders' => [
        'envs' => [
            'dev' => ['local', 'dev', 'stage', ],
            'prod' => ['production', ],
        ],

        'disabled_seeders' => array_filter(
            explode(
                ';',
                (string) env('PLUS_DISABLED_SEEDERS', '')
            ),
            fn ($item) => (bool) $item
        ),
    ],
    'dusk' => [
        'db_connection' => sprintf(
            '%s_test',
            env('DB_CONNECTION', 'pgsql')
        ),
    ],
];
