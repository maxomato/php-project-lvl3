<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */

$router->get('/', [
    'as' => 'domains.form',
    'uses' => 'DomainController@form'
]);

$router->get('/domains/{id}', [
    'as' => 'domains.view',
    'uses' => 'DomainController@view'
]);

$router->get('/domains', [
    'as' => 'domains.list',
    'uses' => 'DomainController@list'
]);

$router->post('/domains', [
    'as' => 'domains.create',
    'uses' => 'DomainController@create'
]);
