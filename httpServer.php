<?php

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server;
use React\Socket\Server as ReactServer;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$server = new Server(function (ServerRequestInterface $request) {
    echo 'Request to ' . $request->getUri() . PHP_EOL;
    return new Response(200, ['Content-Type' => 'text/plain'], 'Hello, world');
});
$socket = new ReactServer('127.0.0.1:8000', $loop);

$server->listen($socket);
$loop->run();