<?php

namespace TurboEngine\Core;

class MemoryManager
{
    protected array $pool = [];

    public function __construct(protected ConfigManager $config) {}

    public function set(string $key, $value, ?int $ttl = null)
    {
        $expire = $ttl ?? $this->config->get('cache.ttl', 300);
        $this->pool[$key] = [
            'value' => $value,
            'expires' => time() + $expire
        ];
    }

    public function get(string $key, $default = null)
    {
        if (!isset($this->pool[$key])) return $default;
        if ($this->pool[$key]['expires'] < time()) {
            unset($this->pool[$key]);
            return $default;
        }
        return $this->pool[$key]['value'];
    }

    public function compact()
    {
        foreach ($this->pool as $key => $entry) {
            if ($entry['expires'] < time()) unset($this->pool[$key]);
        }
    }
}
