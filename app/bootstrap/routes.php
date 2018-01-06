<?php

$router->addGroup('/api', function () use ($router) {
    $router->any('/some-method', 'ApiController::someMethod');
});

$router->get('/{url:.*}', 'AppController::loadApp');
