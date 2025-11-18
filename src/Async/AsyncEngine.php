<?php

namespace TurboEngine\Async;

use TurboEngine\Core\MemoryManager;
use TurboEngine\Core\EventManager;

class AsyncEngine
{
    public function __construct(protected MemoryManager $memory, protected EventManager $events)
    {
        $this->events->listen('job.enqueue', [$this, 'enqueue']);
    }

    public function enqueue(callable $job)
    {
        $pid = pcntl_fork();
        if ($pid == 0) {
            $job();
            exit(0);
        }
    }
}
