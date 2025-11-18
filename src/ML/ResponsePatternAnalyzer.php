<?php

namespace TurboEngine\ML;

use TurboEngine\Core\MemoryManager;

class ResponsePatternAnalyzer
{
    public function __construct(protected MemoryManager $memory) {}

    public function analyze(array $responses)
    {
        $patterns = [];
        foreach ($responses as $key => $content) {
            $hash = md5(substr($content, 0, 50));
            $patterns[$hash] = ($patterns[$hash] ?? 0) + 1;
        }
        $this->memory->set('response.patterns', $patterns);
        return $patterns;
    }
}
