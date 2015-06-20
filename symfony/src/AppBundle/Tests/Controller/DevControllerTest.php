<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DevControllerTest extends WebTestCase
{
    public function testGetidolresources()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'idolresources');
    }

}
