<?php

use React\EventLoop\Factory;
use React\Stream\ReadableResourceStream;
use React\Stream\ThroughStream;
use React\Stream\WritableResourceStream;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();

$readable = new ReadableResourceStream(STDIN, $loop);
$writable = new WritableResourceStream(STDOUT, $loop);
$toUpper = new ThroughStream(function ($chunk) {
    return strtoupper($chunk);
});

$readable->on('data', function ($chunk) use ($writable) {
    $writable->write($chunk);
});

// or

$readable->pipe($toUpper)->pipe($writable);

$loop->run();