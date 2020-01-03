<?php

namespace App\Tests;

use Laravel\Lumen\Testing\DatabaseTransactions;

class Test extends TestCase
{
    use DatabaseTransactions;

    public function testMainPage()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }

    public function testAddDomain()
    {
        $url = 'http://google.com';
        $params = [
          'name' => $url
        ];
        $this->call('POST', '/domains', $params);

        $response = $this->call('GET', '/domains/1');
        $this->assertEquals(200, $response->status());

        $this->seeInDatabase('domains', ['name' => $url]);
    }
}
