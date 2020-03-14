<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */

$router->get('/', function () {
    return redirect()->route('domains.new');
});

$router->get('/domains', [
    'as' => 'domains.index',
    'uses' => 'DomainsController@index'
]);

$router->get('/domains/new', [
    'as' => 'domains.new',
    'uses' => 'DomainsController@new'
]);

$router->post('/domains', [
    'as' => 'domains.create',
    'uses' => 'DomainsController@create'
]);

$router->get('/domains/{id}', [
    'as' => 'domains.show',
    'uses' => 'DomainsController@show'
]);
