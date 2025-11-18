<?php

namespace TurboEngine\Helpers;

use TurboEngine\Core\MemoryManager;

class MetricsHelper
{
    public function __construct(protected MemoryManager $memory) {}

    public function record(string $metric, float $value)
    {
        $stats = $this->memory->get('metrics', []);
        $stats[$metric][] = $value;
        $this->memory->set('metrics', $stats);
    }

    public function get(string $metric): array
    {
        return $this->memory->get('metrics.'.$metric, []);
    }
}
