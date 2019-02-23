<?php

namespace Tests\Randomovies\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PagesTest extends WebTestCase
{
    const PAGES = [
        '/',
        '/all',
        '/actors/all',
        '/search',
        '/login',
        '/register',
        '/password/forgotten',
    ];

    public function testPages()
    {
        $client = static::createClient();

        foreach (self::PAGES as $page) {
            $crawler = $client->request('GET', $page);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
                
        // $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());}        
    }
}
