<?php

namespace TurboEngine\Optimization;

use TurboEngine\Core\MemoryManager;

class TrafficAnalyzer
{
    public function __construct(protected MemoryManager $memory) {}

    public function analyze(array $hits)
    {
        $trend = array_count_values($hits);
        arsort($trend);
        $this->memory->set('traffic.trend', $trend);
        return $trend;
    }
}
