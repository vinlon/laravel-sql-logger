<?php

return [
    'all_query_enabled' => env(SQL_LOGGER_ALL_QUERY_ENABLED, true),

    'slow_query_enabled' => env(SQL_LOGGER_SLOW_QUERY_ENABLED, true),

    'slow_query_threshold' => env(SQL_LOGGER_SLOW_QUERY_THRESHOLD, 1000),

    'all_query_log_channel' => env(SQL_LOGGER_ALL_QUERY_LOG_CHANNEL, null),

    'slow_query_log_channel' => env(SQL_LOGGER_ALL_QUERY_LOG_CHANNEL, null),
];
