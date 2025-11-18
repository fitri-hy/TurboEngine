<?php

namespace TurboEngine\Optimization;

use TurboEngine\Core\MemoryManager;

class AutoTuner
{
    public function __construct(protected MemoryManager $memory) {}

    public function tune(): void
    {
        // Auto-tune TTL cache berdasarkan hit count
        $stats = $this->memory->get('query.profiler', []);
        foreach ($stats as $hash => $data) {
            $count = $data['count'] ?? 0;
            $avgTime = $data['time'] / max(1, $count);

            if ($count > 50 && $avgTime < 0.05) {
                // Hot query, naikkan TTL
                $this->memory->set("cache.ttl.$hash", 900);
            } elseif ($count > 20) {
                // Sedang
                $this->memory->set("cache.ttl.$hash", 600);
            } else {
                // Rendah
                $this->memory->set("cache.ttl.$hash", 300);
            }
        }
    }
}
