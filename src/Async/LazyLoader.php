<?php

namespace TurboEngine\Async;

use TurboEngine\Core\MemoryManager;

class LazyLoader
{
    public function __construct(protected MemoryManager $memory) {}

    public function load(string $key, callable $loader)
    {
        if (!$this->memory->get($key)) {
            $value = $loader();
            $this->memory->set($key, $value);
        }
        return $this->memory->get($key);
    }
}
