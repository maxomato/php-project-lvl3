<?php

use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */

$router->get('/', function () {
    return redirect()->route('domain.new');
});

$router->get('/domain', [
    'as' => 'domain.index',
    'uses' => 'DomainController@index'
]);

$router->get('/domain/new', [
    'as' => 'domain.new',
    'uses' => 'DomainController@new'
]);

$router->post('/domain', [
    'as' => 'domain.create',
    'uses' => 'DomainController@create'
]);

$router->get('/domain/{id}', [
    'as' => 'domain.show',
    'uses' => 'DomainController@show'
]);
