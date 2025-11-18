<?php

namespace TurboEngine\ML;

use TurboEngine\Core\MemoryManager;

class TrafficPredictor
{
    public function __construct(protected MemoryManager $memory) {}

    public function predict(array $history): array
    {
        $next = [];
        foreach ($history as $endpoint => $hits) {
            $next[$endpoint] = end($hits) * 1.05; // simple linear prediction
        }
        return $next;
    }
}
