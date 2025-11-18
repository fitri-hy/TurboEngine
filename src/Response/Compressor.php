<?php

namespace TurboEngine\Response;

class Compressor
{
    public static function gzip(string $content): string
    {
        return gzencode($content, 9);
    }

    public static function brotli(string $content): string
    {
        if (function_exists('brotli_compress')) return brotli_compress($content);
        return $content;
    }
}
