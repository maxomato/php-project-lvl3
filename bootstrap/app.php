<?php

use App\Http\HttpClient;
use App\Http\HttpClientInterface;
use App\Tests\TestHttpClient;

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

if (env('APP_DEBUG')) {
    $app->register(Barryvdh\Debugbar\LumenServiceProvider::class);
    $app->configure('debugbar');
}

$app->withFacades();

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

if ($app->environment('testing')) {
    $app->bind(HttpClientInterface::class, function () {
        return new TestHttpClient();
    });
} else {
    $app->bind(HttpClientInterface::class, function () {
        return new HttpClient();
    });
}



return $app;
