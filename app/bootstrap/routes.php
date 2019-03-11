<?php

$router->addGroup('/api', function () use ($router) {
    $router->any('/some-method', \App\Controllers\API\SomeMethod::class);
});

$router->get('/{url:.*}', \App\Controllers\LoadApp::class);
