<?php

namespace TurboEngine\Response;

use TurboEngine\Core\MemoryManager;
use TurboEngine\Core\EventManager;

class ResponseEngine
{
    public function __construct(protected MemoryManager $memory, protected EventManager $events) {}

    public function render(string $content, string $cacheKey = null)
    {
        if ($cacheKey && ($cached = $this->memory->get($cacheKey))) return $cached;

        $compressed = Compressor::gzip($content);

        if ($cacheKey) $this->memory->set($cacheKey, $compressed);

        $this->events->dispatch('response.rendered', $compressed);

        return $compressed;
    }
}
