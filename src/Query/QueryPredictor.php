<?php

namespace TurboEngine\Query;

use TurboEngine\Core\MemoryManager;

class QueryPredictor
{
    public function __construct(protected MemoryManager $memory) {}

    public function prefetch(array $queries)
    {
        foreach ($queries as $sql) {
            $hash = md5($sql);
            if (!$this->memory->get($hash)) {
                $result = \DB::select($sql);
                $this->memory->set($hash, $result);
            }
        }
    }
}
