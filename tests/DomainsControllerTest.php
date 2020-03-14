<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DomainsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testNew()
    {
        $this->get(route('domains.new'))->assertResponseOk();
    }

    public function testCreate()
    {
        $url = 'http://google.com';
        $params = [
          'url' => $url
        ];

        $responsePath = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'response.html';
        $body = file_get_contents($responsePath);
        $this->mockHttpRequest([
           'statusCode' => 200,
           'headers' => [],
           'body' => $body
        ]);

        $this->post(route('domains.create'), $params);

        $this->seeInDatabase('domains', [
            'name' => $url,
            'h1' => 'Header',
            'description' => 'description',
            'keywords' => 'keywords'
        ]);
    }

    public function testIndex()
    {
        $urls = [
            'http://google.com',
            'http://yandex.ru'
        ];
        foreach ($urls as $index => $url) {
            DB::table('domains')->insert([
                'name' => $url,
            ]);
        }

        $this->get(route('domains.index'))->assertResponseOk();
    }

    /**
     * @param array $options
     */
    private function mockHttpRequest(array $options)
    {
        $this->app->bind(ClientInterface::class, function () use ($options) {
            $response = new Response(
                $options['statusCode'],
                $options['headers'],
                $options['body']
            );
            $mock = new MockHandler([$response]);
            $handlerStack = HandlerStack::create($mock);

            return new Client(['handler' => $handlerStack]);
        });
    }
}
