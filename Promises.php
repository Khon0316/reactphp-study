<?php

use React\Promise\Deferred;

require __DIR__ . '/vendor/autoload.php';

function http($url, $method)
{
    $response = null;
    // $response = 'data';
    $deferred = new Deferred();

    if ($response) {
        $deferred->resolve($response);
    } else {
        $deferred->reject(new Exception('No response'));
    }

    return $deferred->promise();
}


http('http://google.co.kr', 'GET')
    ->then(function ($response) {
        return strtoupper($response);
    })
    ->then(function ($response) {
        echo $response . PHP_EOL;
    })
    ->otherwise(function (Exception $exception) {
        echo $exception->getMessage() . PHP_EOL;
    });
