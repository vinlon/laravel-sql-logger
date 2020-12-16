# Laravel Sql Logger

# 版本支持

- laravel 6.*
- laravel 7.*

# 使用方法

1. 引入 package 

    ```
    composer require vinlon/laravel-sql-logger
    ```

2. 发布config文件 (Laravel 版本 > 5.5)

    先执行如下命令

    ```
    php artisan vendor:publish 
    ```

    然后选择 Provider: Vinlon\Laravel\SqlLogger\SqlLoggerProvider  对应的序号

    在应用程序的config目录下，将生成sql-logger.php文件（注：一般情况下，此文件不需要做任何修改，配置的调整通过环境变量实现）


3. 默认配置

    安装完成后，默认同时开启All Query和Slow Query日志，使用logger中定义的默认channel. SQL执行时间超过 1 秒钟（1000ms） 会被定义为慢查询

4. 配置建议

    ```
    # ALL QUERY LOG 只在 debug 模式下开启
    SQL_LOGGER_ALL_QUERY_ENABLED="${APP_DEBUG}"

    # SLOW QUERY LOG 可以视情况而定，如果团队没有专门的DBA对数据库运行情况进行监控，可以考虑在生产环境开启
    SQL_LOGGER_SLOW_QUERY_ENABLED=true

    # 根据业务数据量及对SQL执行效率的敏感程度决定
    SQL_LOGGER_SLOW_QUERY_THRESHOLD=1000

    # 为 SQL 日志定义专门的 LOG CHANNEL
    SQL_LOGGER_ALL_QUERY_LOG_CHANNEL=sql-logger
    SQL_LOGGER_SLOW_QUERY_LOG_CHANNEL=slow-sql-logger
    ```

    Log Channel示例 (config/logging.php)

    ```
    'sql-logger' => [
        'driver' => 'daily',
        'path' => storage_path('logs/sql.log'),
        'level' => 'debug',
        'days' => 14,
    ]
    ```

# TODO

1. 单元测试
2. 更多Laravel版本的测试与支持

# 参考 
https://github.com/mnabialek/laravel-sql-logger
https://github.com/overtrue/laravel-query-logger
