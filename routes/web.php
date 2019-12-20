<?php

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Router;

/**
 * @var Router $router
 */

$router->get('/', function () use ($router) {
    $version = $router->app->version();
    Log::debug($version);

    return view('index', ['version' => $version]);
});
