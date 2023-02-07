<?php

namespace App\Middlewares;

use Exception;

/**
 * @method get(mixed $arguments = [])
 * @method post(mixed $arguments = [])
 * @method files(mixed $arguments = [])
 * @method cookie()
 * @method server()
 */
class RequestMiddleware
{

    private array $GetMethods = [
        'get',
        'post',
        'files',
        'server',
        'cookie',
        'headers'
    ];

    private array $SetMethods = ['get', 'post', 'files'];

    /**
     * Access php://input arrays
     *
     * @return array
     * @throws \Exception
     */
    public function input(): array
    {
        $Json = file_get_contents('php://input');
        $Content = json_decode($Json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data');
        }

        return $Content;
    }

    /**
     * Magic method to access superglobal arrays
     *
     * @param string $name
     * @param array $arguments
     *
     * @return array
     */
    public function __call(string $name, array $arguments): array
    {
        if (in_array($name, $this->GetMethods) === false) {
            return [];
        }

        if (!empty($arguments) && in_array($name, $this->SetMethods)) {
            $this->set($name, $arguments[0]);
        }

        return $GLOBALS['_' . strtoupper($name)];
    }

    /**
     * Set values in superglobal arrays
     *
     * @param string $name
     * @param mixed $value
     */
    private function set(string $name, mixed $value): void
    {
        $GLOBALS['_' . strtoupper($name)] = $value;
    }

}