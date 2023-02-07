<?php

namespace App\Middlewares;

class ResponseMiddleware
{

    private int $Code = 201;

    private array $Headers = [];

    private string $Body = "";

    protected string $ContentType = "application/json";

    /**
     * Initializes the variables needed
     * to respond to the request
     *
     * @param string $name
     *
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    /**
     * Create the response object
     *
     * @return void
     */
    public function end():void{

        foreach ($this->Headers ?? [] as $key => $definition) {
            header("$key: $definition");
        }

        header("Content-Type:$this->ContentType");

        http_response_code( $this->Code );

        die($this->Body);

    }

}