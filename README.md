# Laravel Sql Logger

# 版本支持

目前只对 laravel 7.* 版本做了测试

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

在生产环境关闭 ALL QUERY LOG

```
SQL_LOGGER_ALL_QUERY_ENABLED=false
```

SLOW QUERY LOG可以视情况而定，如果团队没有专门的DBA对数据库运行情况进行监控，可以考虑在生产环境输出SLOW QUERY LOG

```
SQL_LOGGER_SLOW_QUERY_ENABLED=true
```

为SQL日志定义专门上的LOG CHANNEL, 在config/logging.php中增加如下配置

```
'sql-logger' => [
    'driver' => 'daily',
    'path' => storage_path('logs/sql.log'),
    'level' => 'debug',
    'days' => 14,
],
'slow-sql-logger' => [
    'driver' => 'daily',
    'path' => storage_path('logs/slow-sql.log'),
    'level' => 'debug',
    'days' => 14,
],
```

并修改环境变量, 这样，ALL QUERY和SLOW QUERY将会分别输出到不同的日志文件中。

```
SQL_LOGGER_ALL_QUERY_LOG_CHANNEL=sql-logger
SQL_LOGGER_SLOW_QUERY_LOG_CHANNEL=slow-sql-logger
```

最后，关于SLOW_QUERY_THRESHOLD的定义，可以根据业务的数据量及对效率的敏感程度决定。

```
SQL_LOGGER_SLOW_QUERY_THRESHOLD=1000
```

# TODO

1. 单元测试
2. 更多Laravel版本的测试与支持

# 参考 
https://github.com/mnabialek/laravel-sql-logger
