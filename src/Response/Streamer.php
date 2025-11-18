<?php

namespace TurboEngine\Response;

class Streamer
{
    public static function stream(callable $callback)
    {
        if (ob_get_level() == 0) ob_start();

        $callback();

        ob_flush();
        flush();
    }
}
