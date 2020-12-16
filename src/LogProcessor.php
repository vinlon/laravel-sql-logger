<?php

namespace Vinlon\Laravel\SqlLogger;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class LogProcessor
{
    /**
     * @var Config
     */
    private $config;

    /**
     * LogProcessor constructor.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 记录SQL Query Log.
     */
    public function process(QueryExecuted $query)
    {
        try {
            if ($this->isSlowQuery($query)) {
                $this->writeSlowQueryLog($query);
                if (!$this->config->usingSameChannel()) {
                    $this->writeAllQueryLog($query);
                }
            } else {
                $this->writeAllQueryLog($query);
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * 判断是否是慢查询.
     *
     * @return bool
     */
    private function isSlowQuery(QueryExecuted $query)
    {
        return $query->time >= $this->config->slowQueryThreshold();
    }

    /**
     * 记录 Slow Query Log.
     */
    private function writeSlowQueryLog(QueryExecuted $query)
    {
        if ($this->config->slowQueryEnabled()) {
            Log::channel($this->config->slowQueryLogChannel())
                ->warning($this->getLineMessage($query, 'SLOW_QUERY'))
            ;
        }
    }

    /**
     * 记录 All Query Log.
     */
    private function writeAllQueryLog(QueryExecuted $query)
    {
        if ($this->config->allQueryEnabled()) {
            Log::channel($this->config->allQueryLogChannel())
                ->debug($this->getLineMessage($query, 'QUERY'))
            ;
        }
    }

    /**
     * 拼接日志信息.
     *
     * @param $tag
     *
     * @return string
     */
    private function getLineMessage(QueryExecuted $query, $tag)
    {
        $sqlFormatter = new SqlFormatter($query->sql, $query->bindings);

        return sprintf(
            '[%s][%s ms] %s',
            $tag,
            $query->time,
            $sqlFormatter->format()
        );
    }
}
