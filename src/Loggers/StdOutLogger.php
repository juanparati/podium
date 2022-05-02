<?php

namespace Juanparati\Podium\Loggers;

use Psr\Log\LoggerInterface;

class StdOutLogger implements LoggerInterface
{
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->print('emergency', $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->print('alert', $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->print('critical', $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->print('error', $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->print('warning', $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->print('notice', $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->print('info', $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->print('debug', $message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->print('log', $message, $context);
    }

    protected function print($level, \Stringable|string $message, array $context) : void {
        echo $level . ' ' . $message . (empty($context) ? '' : (' ' . json_encode($context, JSON_PRETTY_PRINT))) . PHP_EOL;
    }
}