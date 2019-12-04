<?php

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server;
use React\Socket\Server as ReactServer;

require __DIR__ . '/vendor/autoload.php';
$posts = require 'posts.php';

$loop = Factory::create();
$server = new Server(function (ServerRequestInterface $request) use ($posts) {
    // print_r($request->getQueryParams());
    $params = $request->getQueryParams();
    $tag = $params['tag'] ?? null;

    $filteredPosts = array_filter($posts, function (array $post) use ($tag) {
        if ($tag) {
            return in_array($tag, $post['tags']);
        }

        return true;
    });

    $page = $params['page'] ?? 1;
    $filteredPosts = array_chunk($filteredPosts, 3);
    $filteredPosts = $filteredPosts[$page - 1] ?? [];

    echo 'Request to ' . $request->getUri() . PHP_EOL;
    // return new Response(200, ['Content-Type' => 'text/plain'], 'Hello, world');
    return new Response(200, ['Content-Type' => 'application/json'], json_encode($filteredPosts));
});
$socket = new ReactServer('127.0.0.1:8000', $loop);

$server->listen($socket);
$loop->run();