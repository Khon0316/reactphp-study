<?php

use App\ConnectionsPool;
use React\EventLoop\Factory;
use React\Socket\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';


$loop = Factory::create();
$server = new \React\Socket\Server('127.0.0.1:8000', $loop);
$pool = new ConnectionsPool();

$server->on('connection', function (ConnectionInterface $connection) use ($pool) {
    $pool->add($connection);
});

$loop->run();