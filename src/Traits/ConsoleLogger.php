<?php

namespace Justinmoh\LaravelHelper\Traits;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;

/**
 * Trait ConsoleLogger
 * @package Justinmoh\LaravelHelper\Traits
 *
 * @mixin \Illuminate\Console\Command
 */
trait ConsoleLogger
{
    /**
     * @param  string  $message
     * @param  int  $level
     */
    protected function log($message, int $level = Logger::INFO): void
    {
        $this->line($message, $this->getLogLevelText($level));
        Log::channel($this->loggerChannel)->log($this->getLogLevelText($level, false), $message);
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
                return 'warning';
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
}
