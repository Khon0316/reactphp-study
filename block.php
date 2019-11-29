<?php

use React\EventLoop\Factory;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();

$loop->addPeriodicTimer(1, function () {
    echo "Hello\n";
});

$loop->addTimer(1, function () {
    sleep(5);
});

$loop->run();