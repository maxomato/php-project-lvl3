<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */

$router->get('/', function () {
    return view('index');
});

$router->get('/domains/{id}', [
    'as' => 'domain-view', 'uses' => 'Controller@domains'
]);

$router->post('/domains', 'Controller@domains');
