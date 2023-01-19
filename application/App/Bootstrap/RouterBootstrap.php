<?php

namespace Root\Application\Bootstrap;

use Exception;

/**
 * @method get(string $Slug, callable $Function, array $Middlewares = [])
 * @method post(string $Slug, callable $Function, array $Middlewares = [])
 * @method put(string $Slug, callable $Function, array $Middlewares)
 * @method delete(string $Slug, callable $Function, array $Middlewares)
 * @method options(string $Slug, callable $Function, array $Middlewares)
 */
class RouterBootstrap
{

    private array $Routes;

    public function __construct()
    {
        $this->Routes = [
            'GET' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => [],
            'OPTIONS' => []
        ];
    }

    /**
     * Add a new route
     *
     * @param string $Method
     * @param array $Arguments [string $slug, callable $function, array $middlewares]
     *
     * @throws Exception
     */
    public function __call(string $Method, array $Arguments): void
    {

        $Slug = $Arguments[0];
        $Function = $Arguments[1];
        $Middlewares = $Arguments[2] ?? [];

        $Method = strtoupper($Method);
        if (!in_array($Method, array_keys($this->Routes))) {
            throw new Exception('Invalid method.');
        }

        $this->Routes[$Method][$Slug] = [
            'function' => $Function,
            'middlewares' => $Middlewares
        ];
    }

    /**
     * Match a request
     *
     * @param string $Method
     * @param string $URI
     *
     * @return mixed
     * @throws \Exception
     */
    public function match(string $Method, string $URI):mixed
    {

        if (!in_array($Method, array_keys($this->Routes))) {
            throw new Exception('Invalid method.');
        }

        foreach ($this->Routes[$Method] as $Route => $Details) {

            $Route = preg_replace('/{[a-zA-Z0-9]+}/', '([a-zA-Z0-9]+)', $Route);

            if (preg_match("#^$Route$#", $URI, $matches)) {

                $Function = $Details['function'];
                $Middlewares = $Details['middlewares'];

                foreach($Middlewares as $middleware){
                    if(class_exists($middleware)){
                        $middleware = new $middleware;
                        if( $middleware->handle() === false ){
                            http_response_code(403);
                            throw new Exception('You must be logged in to access this page.');
                        }
                    }
                }

                if(is_string($Function)){

                    $fn = explode("::",$Function);

                    $Class = $fn[0];
                    $Function = $fn[1];

                    if(class_exists($Class) === false ){
                        http_response_code(403);
                        throw new Exception('Class not available, existing or invalid.');
                    }

                    $Class = new $Class;
                    return $Class->$Function();
                }

                return call_user_func_array($Function, array_slice($matches, 1));

            }

        }

        http_response_code(404);
        throw new Exception('Route not found.');
    }

}