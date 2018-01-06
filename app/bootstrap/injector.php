<?php

use App\Core\ErrorHandler;
use App\Core\ResultEmitter;
use App\Lib\Router;
use App\Lib\Request;
use Auryn\Injector;
use League\Plates\Engine as Plates;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

$injector->share($injector);
$injector->share($config);
$injector->share(Request::class);

$injector->share(Router::class);
$injector->define(Router::class, [
    ':controllersNamespace' => 'App\Controllers',
    ':resultEmitter' => new ResultEmitter($config->get('debug'))
]);

$injector->share(Whoops::class);
$injector->prepare(Whoops::class, function (Whoops $whoops, Injector $injector) use ($config) {
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->pushHandler($injector->make(ErrorHandler::class, [
        ':isDebug' => $config->get('debug')
    ]));
});

$injector->share(Logger::class);
$injector->define(Logger::class, [
    ':name' => 'default',
    ':handlers' => [new StreamHandler($config->get('logFile'))]
]);

$injector->share(Plates::class);
$injector->define(Plates::class, [
    ':directory' => $config->get('views.directory'),
    ':fileExtension' => $config->get('views.fileExtension')
]);
