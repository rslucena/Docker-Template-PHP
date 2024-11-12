<?php

declare(strict_types=1);

$http = new Swoole\Http\Server("0.0.0.0", 3000);

$http->on('start', function ($server) {
    echo "Server started at http://0.0.0.0:3000\n";
});

$http->on("request", function (Swoole\Http\Request $request, Swoole\Http\Response $response) {
    $response->end("OK");
});

$http->start();