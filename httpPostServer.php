<?php

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server;
use React\Socket\Server as ReactServer;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$posts = [];

$server = new Server(function (ServerRequestInterface $request) use (&$posts) {
    $path = $request->getUri()->getPath();

    if ($path === '/store') {
        $posts[] = json_decode((string) $request->getBody(), true);

        return new Response(201);
    }

    return new Response(200, ['Content-Type' => 'application/json'], json_encode($posts));
});

$socket = new ReactServer('127.0.0.1:8000', $loop);

$server->listen($socket);
$loop->run();