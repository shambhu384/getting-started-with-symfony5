<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{
    public function testShowPost()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/customer/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"firstName": "Scott","lastName": "Dev","email": "scott@dev.org","gender": "M"}'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $customer = json_decode($client->getResponse()->getContent());

        sleep(3);

        $client->request('GET', sprintf('/customer/%s', $customer->id));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
