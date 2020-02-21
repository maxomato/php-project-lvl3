<?php

namespace App\Tests;

use App\Interfaces\HttpClientInterface;

class TestHttpClient implements HttpClientInterface
{
    public function send(string $url)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'response.html';
        $body = file_get_contents($path);

        return [
                'state' => self::STATE_COMPLETED,
                'status' => 200,
                'body' => $body,
                'content_length' => strlen($body)
        ];
    }
}
