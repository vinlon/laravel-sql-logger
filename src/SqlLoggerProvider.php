<?php

namespace Vinlon\Laravel\SqlLogger;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class SqlLoggerProvider extends ServiceProvider
{
    private $configPath;

    /**
     * SqlLoggerProvider constructor.
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->configPath = __DIR__ . '/../publishes/config/sql-logger.php';
    }

    public function boot()
    {
        //发布配置文件
        $this->publishes([
            $this->configPath => config_path(Config::CONFIG_NAME . '.php'),
        ], 'config');
    }

    /**
     * @throws BindingResolutionException
     */
    public function register()
    {
        //合并配置
        $this->mergeConfigFrom($this->configPath, Config::CONFIG_NAME);

        if ($this->nothingToDo()) {
            return;
        }

        // 监听 SQL Query 事件
        DB::listen(function (QueryExecuted $query) {
            $this->app->make(LogProcessor::class)->process($query);
        });
    }

    /**
     * 如果All Query Log和Slow Query Log都没有开启，则不做任何事情.
     *
     * @throws BindingResolutionException
     *
     * @return bool
     */
    private function nothingToDo()
    {
        $config = $this->app->make(Config::class);

        return !$config->allQueryEnabled() && !$config->slowQueryEnabled();
    }
}
