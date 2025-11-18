<?php

namespace TurboEngine\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    public static function set(string $key, $value, ?int $ttl = null)
    {
        $driver = config('turboengine.cache.driver', 'disk');

        Cache::store($driver)->put($key, $value, $ttl ?? config('turboengine.cache.ttl'));
    }

    public static function get(string $key, $default = null)
    {
        $driver = config('turboengine.cache.driver', 'disk');
        return Cache::store($driver)->get($key, $default);
    }
}
