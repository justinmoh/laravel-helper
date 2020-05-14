<?php

namespace Justinmoh\LaravelHelper\Traits;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;

/**
 * Allows output to console at log to log file at the same time. Specify log
 * channel with `setLogChannel()` method if necessary.
 *
 * Trait ConsoleLogger
 * @package Justinmoh\LaravelHelper\Traits
 *
 * @mixin \Illuminate\Console\Command
 */
trait ConsoleLogger
{
    /** @var string $loggerChannel */
    private $loggerChannel;


    /**
     * @param  string  $message
     * @param  int  $level
     */
    protected function log($message, int $level = Logger::INFO): void
    {
        $this->line($message, $this->getLogLevelText($level));

        Log::channel($this->getLogChannel())
            ->log($this->getLogLevelText($level, false), $message);
    }


    /**
     * @param  int  $monologLevelConstant
     * @param  bool  $forConsole
     *
     * @return string
     */
    private function getLogLevelText(int $monologLevelConstant, $forConsole = true): string
    {
        switch ($monologLevelConstant) {
            case Logger::DEBUG:
                return $forConsole ? 'comment' : 'debug';
            case Logger::INFO:
                return 'info';
            case Logger::NOTICE:
                return $forConsole ? 'info' : 'notice';
            case Logger::WARNING:
                return $forConsole ? 'warn' : 'warning';
            case Logger::ERROR:
                return 'error';
            case Logger::CRITICAL:
                return $forConsole ? 'alert' : 'critical';
            case Logger::ALERT:
                return 'alert';
            case Logger::EMERGENCY:
                return $forConsole ? 'alert' : 'emergency';
        }

        return '';
    }


    /**
     * @param  string  $laravelLogChannel
     */
    protected function setLogChannel(string $laravelLogChannel): void
    {
        $this->loggerChannel = $laravelLogChannel;
    }


    /**
     * @return string|null
     */
    private function getLogChannel()
    {
        return $this->loggerChannel;
    }
}
