<?php


namespace Vinlon\Laravel\SqlLogger;


class Config
{
    const CONFIG_NAME = 'sql-logger';

    private function getConfig($key, $defaultValue)
    {
        $value = config(self::CONFIG_NAME . '.' . $key, $defaultValue);
        if (trim($value) === '') {
            return $defaultValue;
        } else {
            return $value;
        }
    }

    public function allQueryEnabled()
    {
        return $this->getConfig('all_query_enabled', true);
    }

    public function slowQueryEnabled()
    {
        return $this->getConfig('slow_query_enabled', true);
    }

    public function slowQueryThreshold()
    {
        return $this->getConfig('slow_query_threshold', 1000);
    }

    public function allQueryLogChannel()
    {
        return $this->getConfig('all_query_log_channel', null);
    }

    public function slowQueryLogChannel()
    {
        return $this->getConfig('slow_query_log_channel', null);
    }

    public function usingSameChannel()
    {
        return $this->allQueryLogChannel() == $this->slowQueryLogChannel();
    }
}