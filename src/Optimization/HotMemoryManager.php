<?php

namespace TurboEngine\Optimization;

use TurboEngine\Core\MemoryManager;

class HotMemoryManager
{
    public function __construct(protected MemoryManager $memory) {}

    public function prioritize(array $keys)
    {
        foreach ($keys as $key) {
            if ($value = $this->memory->get($key)) {
                $this->memory->set("hot.$key", $value);
            }
        }
    }
}
