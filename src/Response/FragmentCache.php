<?php

namespace TurboEngine\Response;

use TurboEngine\Core\MemoryManager;
use TurboEngine\Helpers\CacheHelper;

class FragmentCache
{
    public function __construct(protected MemoryManager $memory) {}

    public function get(string $key)
    {
        return CacheHelper::get($key);
    }

    public function set(string $key, $content, $ttl = null)
    {
        CacheHelper::set($key, $content, $ttl);
    }
}
