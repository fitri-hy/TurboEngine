<?php

namespace TurboEngine\Query;

use TurboEngine\Core\MemoryManager;

class QueryProfiler
{
    protected array $stats = [];

    public function __construct(protected MemoryManager $memory) {}

    public function track(string $sql, float $time)
    {
        $hash = md5($sql);
        $this->stats[$hash]['count'] = ($this->stats[$hash]['count'] ?? 0) + 1;
        $this->stats[$hash]['time'] = ($this->stats[$hash]['time'] ?? 0) + $time;

        $this->memory->set('query.profiler', $this->stats);
    }

    public function hotspot(): array
    {
        arsort(array_column($this->stats, 'count'));
        return $this->stats;
    }
}
