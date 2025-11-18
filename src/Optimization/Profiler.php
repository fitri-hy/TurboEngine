<?php

namespace TurboEngine\Optimization;

use TurboEngine\Core\MemoryManager;

class Profiler
{
    protected array $stats = [];

    public function __construct(protected MemoryManager $memory) {}

    public function track(string $name, float $time)
    {
        $this->stats[$name] = ($this->stats[$name] ?? 0) + $time;
        $this->memory->set('profiler.stats', $this->stats);
    }

    public function report(): array
    {
        return $this->stats;
    }
}
