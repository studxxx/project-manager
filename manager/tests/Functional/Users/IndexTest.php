<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users;

use App\Tests\Functional\AuthFixture;
use App\Tests\Functional\DbWebTestCase;

class IndexTest extends DbWebTestCase
{
    public function testGuest(): void
    {
        $this->client->request('GET', '/users');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
        $this->client->setServerParameters(AuthFixture::userCredentials());
        $this->client->request('GET', '/users');

        self::assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdmin(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/users');

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Users', $crawler->filter('title')->text());
    }
}
