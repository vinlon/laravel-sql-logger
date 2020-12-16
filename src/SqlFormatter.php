<?php

namespace Vinlon\Laravel\SqlLogger;

use DateTimeInterface;

/**
 * Class SqlFormatter
 * Borrow from https://github.com/mnabialek/laravel-sql-logger/.
 */
class SqlFormatter
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $bindings;

    /**
     * SqlFormatter constructor.
     */
    public function __construct(string $sql, array $bindings)
    {
        $this->sql = $sql;
        $this->bindings = $bindings;
    }

    /**
     * Replace bindings.
     *
     * @return string
     */
    public function format()
    {
        $sql = $this->sql;
        foreach ($this->formatBindings($this->bindings) as $key => $binding) {
            $regex = is_numeric($key) ? $this->getGeneralRegex() : $this->getNamedParameterRegex($key);
            $sql = preg_replace($regex, $this->getDisplayValue($binding), $sql, 1);
        }

        return $sql;
    }

    /**
     * Get final value that will be displayed in query.
     *
     * @param mixed $value
     *
     * @return int|string
     */
    private function getDisplayValue($value)
    {
        if (null === $value) {
            return 'null';
        }
        if (is_bool($value)) {
            return (int) $value;
        }

        return is_numeric($value) ? $value : "'" . $value . "'";
    }

    /**
     * Get regex to be used for named parameter with given name.
     *
     * @return string
     */
    private function getNamedParameterRegex(string $name)
    {
        if (':' == mb_substr($name, 0, 1)) {
            $name = mb_substr($name, 1);
        }

        return $this->wrapRegex($this->notInsideQuotes('\:' . preg_quote($name), false));
    }

    /**
     * Format bindings values.
     *
     * @return array
     */
    private function formatBindings(array $bindings)
    {
        foreach ($bindings as $key => $binding) {
            if ($binding instanceof DateTimeInterface) {
                $bindings[$key] = $binding->format('Y-m-d H:i:s');
            } elseif (is_string($binding)) {
                $bindings[$key] = str_replace("'", "\\'", $binding);
            }
        }

        return $bindings;
    }

    /**
     * Get regex to be used to replace bindings.
     *
     * @return string
     */
    private function getGeneralRegex()
    {
        return $this->wrapRegex(
            $this->notInsideQuotes('?')
            . '|' .
            $this->notInsideQuotes('\:\w+', false)
        );
    }

    /**
     * Wrap regex.
     *
     * @return string
     */
    private function wrapRegex(string $regex)
    {
        return '#' . $regex . '#ms';
    }

    /**
     * Create partial regex to find given text not inside quotes.
     *
     * @param bool $quote
     *
     * @return string
     */
    private function notInsideQuotes(string $string, $quote = true)
    {
        if ($quote) {
            $string = preg_quote($string);
        }

        return
            // double quotes - ignore "" and everything inside quotes for example " abc \"err "
            '(?:""|"(?:[^"]|\\")*?[^\\\]")(*SKIP)(*F)|' . $string .
            '|' .
            // single quotes - ignore '' and everything inside quotes for example ' abc \'err '
            '(?:\\\'\\\'|\'(?:[^\']|\\\')*?[^\\\]\')(*SKIP)(*F)|' . $string;
    }
}
