<?php

namespace TurboEngine\Response;

use TurboEngine\Core\MemoryManager;

class PredictiveRenderer
{
    public function __construct(protected MemoryManager $memory) {}

    public function preload(array $fragments)
    {
        foreach ($fragments as $key => $content) {
            $this->memory->set("view.$key", $content);
        }
    }
}
