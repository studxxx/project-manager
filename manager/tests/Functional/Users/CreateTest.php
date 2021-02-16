<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users;

use App\Tests\Functional\AuthFixture;
use App\Tests\Functional\DbWebTestCase;

class CreateTest extends DbWebTestCase
{
    public function testGuest(): void
    {
        $this->client->request('GET', '/users/create');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
        $this->client->setServerParameters(AuthFixture::userCredentials());
        $this->client->request('GET', '/users/create');

        self::assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testGet(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/users/create');

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Users', $crawler->filter('title')->text());
    }

    public function testCreate(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/users/create');

        $this->client->submitForm('Create', [
            'form[firstName]' => 'Tom',
            'form[lastName]' => 'Bent',
            'form[email]' => 'tom-bent@app.test',
        ]);

        self::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        self::assertSame(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Users', $crawler->filter('title')->text());
        self::assertStringContainsString('Tom Bent', $crawler->filter('body')->text());
        self::assertStringContainsString('tom-bent@app.test', $crawler->filter('body')->text());
    }

    public function testNotValid(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/users/create');

        $crawler = $this->client->submitForm('Create', [
            'form[firstName]' => '',
            'form[lastName]' => '',
            'form[email]' => 'not-email',
        ]);

        self::assertSame(200, $this->client->getResponse()->getStatusCode());

        self::assertStringContainsString('This value should not be blank.', $crawler
            ->filter('#form_firstName')->parents()->first()->filter('.form-error-message')->text());
        self::assertStringContainsString('This value should not be blank.', $crawler
            ->filter('#form_lastName')->parents()->first()->filter('.form-error-message')->text());
        self::assertStringContainsString('This value is not a valid email address.', $crawler
            ->filter('#form_email')->parents()->first()->filter('.form-error-message')->text());
    }

    public function testExists(): void
    {
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $this->client->request('GET', '/users/create');

        $crawler = $this->client->submitForm('Create', [
            'form[firstName]' => 'Tom',
            'form[lastName]' => 'Bent',
            'form[email]' => 'existing-user@app.test',
        ]);

        self::assertSame(200, $this->client->getResponse()->getStatusCode());

        self::assertStringContainsString('User with this email already exists.', $crawler
            ->filter('.alert.alert-danger')->text());
    }
}
