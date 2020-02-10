<?php

namespace App\Tests;

use App\Http\HttpClientInterface;

abstract class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->bind(HttpClientInterface::class, function () {
            return new TestHttpClient();
        });

        return $app;
    }
}
