<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Connection Pooling Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for database connection pooling
    | and optimization to improve performance and reduce connection overhead.
    |
    */

    'mysql' => [
        'pool_size' => env('DB_POOL_SIZE', 10),
        'max_connections' => env('DB_MAX_CONNECTIONS', 100),
        'min_connections' => env('DB_MIN_CONNECTIONS', 5),
        'connection_timeout' => env('DB_CONNECTION_TIMEOUT', 30),
        'idle_timeout' => env('DB_IDLE_TIMEOUT', 300),
        'max_lifetime' => env('DB_MAX_LIFETIME', 3600),
    ],

    'redis' => [
        'pool_size' => env('REDIS_POOL_SIZE', 10),
        'max_connections' => env('REDIS_MAX_CONNECTIONS', 50),
        'min_connections' => env('REDIS_MIN_CONNECTIONS', 2),
        'connection_timeout' => env('REDIS_CONNECTION_TIMEOUT', 5),
        'idle_timeout' => env('REDIS_IDLE_TIMEOUT', 60),
        'max_lifetime' => env('REDIS_MAX_LIFETIME', 1800),
    ],

    'performance' => [
        'enable_query_logging' => env('DB_QUERY_LOGGING', false),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000), // milliseconds
        'enable_query_caching' => env('DB_QUERY_CACHING', true),
        'cache_ttl' => env('DB_CACHE_TTL', 300), // seconds
        'enable_connection_pooling' => env('DB_CONNECTION_POOLING', true),
        'enable_prepared_statements' => env('DB_PREPARED_STATEMENTS', true),
    ],

    'monitoring' => [
        'enable_performance_monitoring' => env('DB_PERFORMANCE_MONITORING', true),
        'log_slow_queries' => env('DB_LOG_SLOW_QUERIES', true),
        'log_connection_usage' => env('DB_LOG_CONNECTION_USAGE', false),
        'alert_on_high_connection_usage' => env('DB_ALERT_HIGH_CONNECTIONS', true),
        'high_connection_threshold' => env('DB_HIGH_CONNECTION_THRESHOLD', 80), // percentage
    ],
];






























