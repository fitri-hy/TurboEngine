<?php

namespace TurboEngine\Async;

use TurboEngine\Core\EventManager;

class JobOptimizer
{
    protected array $dependencies = [];

    public function __construct(protected EventManager $events) {}

    public function add(string $jobId, array $dependsOn)
    {
        $this->dependencies[$jobId] = $dependsOn;
    }

    public function schedule(string $jobId, callable $job)
    {
        foreach ($this->dependencies[$jobId] ?? [] as $dep) {
            $this->events->listen("job.$dep.done", fn() => $job());
        }
        $job();
        $this->events->dispatch("job.$jobId.done");
    }
}
