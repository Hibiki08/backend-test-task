<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;

class Connector
{
    public function __construct(private Redis $redis) {}

    /**
     * @throws ConnectorException
     */
    public function get(string $key): mixed
    {
        try {
            return unserialize($this->redis->get($key));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, mixed $value, int $ttl = 86400)
    {
        try {
            $this->redis->setex($key, $ttl, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    public function has(string $key): bool
    {
        return (bool)$this->redis->exists($key);
    }
}
