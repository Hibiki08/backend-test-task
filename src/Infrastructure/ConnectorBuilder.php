<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use Redis;
use RedisException;

class ConnectorBuilder
{
    public function __construct(
        private string $host,
        private int $port = 6379,
        private ?string $password = null,
        private ?int $dbindex = null,
    ) {}

    public function build(): Connector
    {
        $redis = new Redis();

        try {
            $isConnected = $redis->isConnected();
            
            if (!$isConnected) {
                $redis->connect($this->host, $this->port);
            }

            $redis->ping('Pong');
        } catch (RedisException) {
             throw new ConnectorException('Redis connection error', $e->getCode(), $e);
        }

        if ($this->password) {
            $redis->auth($this->password);
        }

        if ($this->dbindex) {
            $redis->select($this->dbindex);
        }

        return new Connector($redis);
    }
}
