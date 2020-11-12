<?php


namespace Vinlon\Laravel\SqlLogger;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class SqlLoggerProvider extends ServiceProvider
{
    private $configPath;

    /**
     * @var LogProcessor
     */
    private $logProcessor;

    /**
     * @var Config
     */
    private $config;

    /**
     * SqlLoggerProvider constructor.
     * @param Application $app
     * @param LogProcessor $logProcessor
     * @param Config $config
     */
    public function __construct(Application $app, LogProcessor $logProcessor, Config $config)
    {
        parent::__construct($app);
        $this->configPath = __DIR__ . '/publishes/config/sql-logger.php';
        $this->logProcessor = $logProcessor;
        $this->config = $config;
    }


    public function boot()
    {
        //发布配置文件
        $this->publishes([
            $this->configPath => configPath(Config::CONFIG_NAME . '.php'),
        ], 'config');
    }

    public function register()
    {
        //合并配置
        $this->mergeConfigFrom($this->configPath, Config::CONFIG_NAME);

        if ($this->nothingToDo()) {
            return;
        }

        // 监听 SQL Query 事件
        DB::listen(function (QueryExecuted $query) {
            $this->logProcessor->process($query);
        });
    }

    /**
     * 如果All Query Log和Slow Query Log都没有开启，则不做任何事情
     * @return bool
     */
    private function nothingToDo()
    {
        return !$this->config->allQueryEnabled() && !$this->config->slowQueryEnabled();
    }
}