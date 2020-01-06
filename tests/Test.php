<?php

namespace App\Tests;

use Laravel\Lumen\Testing\DatabaseTransactions;

class Test extends TestCase
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
        $this->call('POST', route('domains.store'), $params);

        $response = $this->call('GET', route('domains.show', ['id' => 1]));
        $this->assertEquals(200, $response->status());

        $this->seeInDatabase('domains', ['name' => $url]);
    }
}
