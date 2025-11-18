<?php

namespace TurboEngine\Helpers;

class Logger
{
    public static function log(string $level, string $message)
    {
        $file = storage_path('logs/turbo.log');
        $log = "[".date('Y-m-d H:i:s')."] [$level] $message\n";

        if (config('turboengine.logging.async') && function_exists('pcntl_fork')) {
            if (pcntl_fork() === 0) {
                file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
                exit(0);
            }
        } else {
            file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
        }
    }
}
