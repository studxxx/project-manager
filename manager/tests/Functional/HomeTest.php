<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class HomeTest extends DbWebTestCase
{
    public function testGuest(): void
    {
        $this->client->request('GET', '/');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
        $this->client->setServerParameters(AuthFixture::userCredentials());
        $crawler = $this->client->request('GET', '/');

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Home', $crawler->filter('title')->text());
    }

    public function testAdmin(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/');

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Home', $crawler->filter('title')->text());
    }
}
