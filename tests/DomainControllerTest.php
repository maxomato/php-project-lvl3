<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DomainControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testMainPage()
    {
        $response = $this->call('GET', route('domains.create'));
        $this->assertEquals(200, $response->status());
    }

    public function testAddDomain()
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

        $this->call('POST', route('domains.store'), $params);

        $this->seeInDatabase('domains', [
            'name' => $url,
            'h1' => 'Header',
            'description' => 'description',
            'keywords' => 'keywords'
        ]);
    }

    public function testListDomains()
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

        $response = $this->call('GET', route('domains.index'));
        $this->assertEquals(200, $response->status());
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
