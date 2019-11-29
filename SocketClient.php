<?php

use React\EventLoop\Factory;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use React\Stream\ReadableResourceStream;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$input = new ReadableResourceStream(STDIN, $loop);

$connector = new Connector($loop);
$connector->connect('127.0.0.1:8000')
    ->then(function (ConnectionInterface $connection) use ($input) {
        $input->on('data', function ($data) use ($connection) {
            $connection->write($data);
        });

        $connection->on('data', function ($data) {
            echo $data;
        });
    }, function (Exception $exception) {
        echo $exception->getMessage() . PHP_EOL;
    });

$loop->run();