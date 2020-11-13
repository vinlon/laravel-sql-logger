# Laravel Sql Logger

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


3. 添加配置项

```
## SQL Logger
SQL_LOGGER_ALL_QUERY_ENABLED=true
SQL_LOGGER_SLOW_QUERY_ENABLED=true
SQL_LOGGER_ALL_QUERY_LOG_CHANNEL=sql-logger
SQL_LOGGER_SLOW_QUERY_LOG_CHANNEL=slow-sql-logger
SQL_LOGGER_SLOW_QUERY_THRESHOLD=50
```


# 版本支持
目前只测试了 laravel 7.* 版本



# 参考 
https://github.com/mnabialek/laravel-sql-logger
