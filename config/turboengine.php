<?php

return [
    'cache' => [
        'driver' => env('TURBO_CACHE_DRIVER', 'redis'), // ram, redis, disk
        'ttl' => 300,
    ],
    'workers' => [
        'pool_size' => 16,
        'max_queue' => 2000,
    ],
    'predictive' => [
        'query_prefetch' => true,
        'response_preload' => true,
        'traffic_prediction' => true,
    ],
    'logging' => [
        'level' => 'info',
        'async' => true,
    ],
];
