<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users;

use App\Model\User\Entity\User\Id;
use App\Tests\Functional\AuthFixture;
use App\Tests\Functional\DbWebTestCase;

class ShowTest extends DbWebTestCase
{
    public function testGuest(): void
    {
        $this->client->request('GET', '/users/' . UsersFixture::EXISTING_ID);

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
        $this->client->setServerParameters(AuthFixture::userCredentials());
        $this->client->request('GET', '/users/' . UsersFixture::EXISTING_ID);

        self::assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testGet(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/users/' . UsersFixture::EXISTING_ID);

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Users', $crawler->filter('title')->text());
        self::assertStringContainsString('Show User', $crawler->filter('table')->text());
    }

    public function testNotFound(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/users/' . Id::next()->getValue());

        self::assertSame(404, $this->client->getResponse()->getStatusCode());
    }
}
