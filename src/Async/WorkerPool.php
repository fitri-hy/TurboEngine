<?php

namespace TurboEngine\Async;

class WorkerPool
{
    protected array $workers = [];
    protected array $queue = [];

    public function __construct(protected int $size = 8)
    {
        for ($i = 0; $i < $size; $i++) $this->workers[$i] = null;
    }

    public function push(callable $task)
    {
        $this->queue[] = $task;
        $this->dispatch();
    }

    protected function dispatch()
    {
        foreach ($this->workers as $i => $worker) {
            if (!$worker && $task = array_shift($this->queue)) {
                $pid = pcntl_fork();
                if ($pid == 0) {
                    $task();
                    exit(0);
                } else {
                    $this->workers[$i] = $pid;
                }
            }
        }
    }
}
