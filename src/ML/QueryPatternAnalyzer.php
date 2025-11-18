<?php

namespace TurboEngine\ML;

use TurboEngine\Core\MemoryManager;

class QueryPatternAnalyzer
{
    public function __construct(protected MemoryManager $memory) {}

    public function analyze(array $queries)
    {
        $patterns = [];
        foreach ($queries as $sql) {
            $pattern = preg_replace('/\d+/', '{num}', $sql);
            $patterns[$pattern] = ($patterns[$pattern] ?? 0) + 1;
        }
        $this->memory->set('query.patterns', $patterns);
        return $patterns;
    }
}
