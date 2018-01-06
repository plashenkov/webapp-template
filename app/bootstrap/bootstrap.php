<?php

use App\Lib\Router;
use App\Lib\Settings;
use Auryn\Injector;
use Whoops\Run as Whoops;

require __DIR__ . '/../vendor/autoload.php';

$settings = new Settings(require __DIR__ . '/settings.php');

$injector = new Injector;

require __DIR__ . '/injector.php';

/** @var Whoops $whoops */
$whoops = $injector->make(Whoops::class);
$whoops->register();

/** @var Router $router */
$router = $injector->make(Router::class);

require __DIR__ . '/routes.php';

$router->dispatch();
