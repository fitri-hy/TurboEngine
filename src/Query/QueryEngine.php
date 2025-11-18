<?php

namespace TurboEngine\Query;

use TurboEngine\Core\MemoryManager;
use TurboEngine\Core\EventManager;

class QueryEngine
{
    protected array $hotQueries = [];

    public function __construct(protected MemoryManager $memory, protected EventManager $events) {}

    public function execute(string $sql)
    {
        $hash = md5($sql);

        if (isset($this->hotQueries[$hash])) {
            return $this->hotQueries[$hash];
        }

        if ($cached = $this->memory->get($hash)) return $cached;

        $result = \DB::select($sql);

        $this->memory->set($hash, $result);
        $this->hotQueries[$hash] = $result;

        $this->events->dispatch('query.executed', $sql);

        return $result;
    }
}
