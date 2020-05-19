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

    /** @var float $startTime */
    private $startTime;


    /**
     * @param  string  $message
     * @param  int  $level
     * @param  bool  $withPeak
     */
    protected function log(string $message, int $level = Logger::INFO, bool $withPeak = false): void
    {
        $message = implode(' ', [$this->getScriptStats($withPeak), $message]);

        /**
         * @see \Illuminate\Console\Command::comment()
         * @see \Illuminate\Console\Command::info()
         * @see \Illuminate\Console\Command::warn()
         * @see \Illuminate\Console\Command::error()
         * @see \Illuminate\Console\Command::alert()
         */
        $this->{$this->getLogLevelText($level)}($message);

        Log::channel($this->getLogChannel())->log($this->getLogLevelText($level, false), $message);
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

        return 'info';
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


    /**
     * Return stats in the format of [9,999.9MB|mm:ss].
     *
     * @param  bool  $withPeak
     *
     * @return string
     */
    private function getScriptStats($withPeak = false): string
    {
        if ($withPeak) {
            return "[{$this->getMemoryUsage()}|{$this->getMemoryPeakUsage()}|{$this->getRunningTime()}]";
        }

        return "[{$this->getMemoryUsage()}|{$this->getRunningTime()}]";
    }


    /**
     * Return script elapse time in the format of mm:ss. E.g.: 500s = '08:20' / 1000s = 16:40.
     *
     * @return string
     */
    private function getRunningTime(): string
    {
        if (empty($this->startTime)) {
            $this->startTime = microtime(true);
        }
        $sinceStartTime = microtime(true) - $this->startTime;

        return str_pad(floor($sinceStartTime / 60), 2, '0', STR_PAD_LEFT)
            .gmdate(":s", $sinceStartTime % 60);
    }


    /**
     * Return peak memory usage in the format of 9,999.9MB. E.g.: 1234.5678 = '1,234.5MB'.
     *
     * @return string
     */
    private function getMemoryPeakUsage(): string
    {
        $memoryPeakUsage = memory_get_peak_usage() / 1024 / 1024; // MB

        return number_format($memoryPeakUsage, 1).'MB';
    }


    /**
     * Return memory usage in the format of 9,999.9MB. E.g.: 1234.5678 = '1,234.5MB'.
     *
     * @return string
     */
    private function getMemoryUsage(): string
    {
        $memoryUsage = memory_get_usage() / 1024 / 1024; // MB

        return number_format($memoryUsage, 1)."MB";
    }
}
