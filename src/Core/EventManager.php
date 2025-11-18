<?php

namespace TurboEngine\Core;

class EventManager
{
    protected array $listeners = [];

    public function __construct(protected MemoryManager $memory) {}

    public function listen(string $event, callable $callback)
    {
        $this->listeners[$event][] = $callback;
    }

    public function dispatch(string $event, $payload = null)
    {
        if (!isset($this->listeners[$event])) return;
        foreach ($this->listeners[$event] as $listener) {
            if (function_exists('pcntl_fork') && pcntl_fork() === 0) {
                $listener($payload);
                exit(0);
            } else {
                $listener($payload);
            }
        }
    }
}
