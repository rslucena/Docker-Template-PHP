<?php

namespace Root\Application\Service;

use Exception;
use Redis;

/**
 * @method get(string $key)
 * @method set(string $key, mixed $value, int $timeout = 1)
 * @method del(int|string|array $keys)
 * @method exists(string $key)
 */
class RedisService
{

    private mixed $Redis;
    private bool $Connected = false;

    /**
     * Building Redis class
     *
     * @throws \Exception
     */
    function __construct()
    {
        $this->Redis = new Redis();
        $this->connect();
        $this->authUser();
    }

    /**
     * Checks if a connection
     * exists and is active
     *
     * @throws \Exception
     */
    private function connect(): void
    {
        $this->Connected = $this->Redis->isConnected();
        if (!$this->Connected) {
            try {
                $this->Connected = $this->Redis->connect(getenv('REDIS_SERVER'), getenv('REDIS_PORT'));
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }
    }

    /**
     * Creates or checks if a connection
     * exists with the service
     *
     * @throws \Exception
     */
    private function authUser(): void
    {
        if ($this->Connected) {
            try {
                $this->Connected = $this->Redis->auth(['default', getenv('REDIS_PASS')]);
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @package \Redis
     *
     */
    public function __call(string $method, array $arguments)
    {
        return call_user_func_array(array($this->Redis, $method), $arguments);
    }

}