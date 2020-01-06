<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */

$router->get('/', [
    'as' => 'domains.create',
    'uses' => 'DomainController@create'
]);

$router->get('/domains/{id}', [
    'as' => 'domains.show',
    'uses' => 'DomainController@show'
]);

$router->post('/domains', [
    'as' => 'domains.store',
    'uses' => 'DomainController@store'
]);
