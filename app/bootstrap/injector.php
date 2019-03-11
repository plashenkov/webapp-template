<?php

use App\Lib\DB\DB;
use App\Lib\ErrorHandler\HybridErrorHandler;
use App\Lib\ResultEmitter\HybridResultEmitter;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\View\TwigView;
use App\Lib\View\View;
use Auryn\Injector;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

$injector->share($injector);
$injector->share($config);
$injector->share(Request::class);

$injector->share(Router::class);
$injector->define(Router::class, [
    ':resultEmitter' => new HybridResultEmitter($config->get('debug'))
]);

$injector->share(Whoops::class);
$injector->prepare(Whoops::class, function (Whoops $whoops, Injector $injector) use ($config) {
    $whoops->pushHandler(new PrettyPageHandler);
    $whoops->pushHandler($injector->make(HybridErrorHandler::class, [
        ':isDebug' => $config->get('debug')
    ]));
});

$injector->share(Logger::class);
$injector->define(Logger::class, [
    ':name' => 'default',
    ':handlers' => [new StreamHandler($config->get('logFile'))]
]);

$injector->alias(View::class, TwigView::class);

$injector->share(TwigView::class);

$injector->share(Twig_Environment::class);
$injector->define(Twig_Environment::class, [
    ':loader' => new Twig_Loader_Filesystem($config->get('templates.dir')),
    ':options' => [
        'cache' => $config->get('templates.cache'),
    ],
]);

$injector->share(DB::class);
$injector->define(DB::class, [
    ':dsn' => $config->get('database.dsn'),
    ':username' => $config->get('database.user'),
    ':passwd' => $config->get('database.password')
]);
