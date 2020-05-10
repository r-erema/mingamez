<?php

declare(strict_types=1);

namespace Test\Functional;

use App\Infrastructure\Fixture\UserFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
/*
class HomeTest extends WebTestCase
{

    public function testGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('/signin', $client->getResponse()->headers->get('Location'));
    }

    public function testSuccess(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => UserFixture::SIMPLE_USER_ACTIVE_EMAIL,
            'PHP_AUTH_PW' => UserFixture::PASSWORD,
        ]);
        $crawler = $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString(UserFixture::SIMPLE_USER_ACTIVE_EMAIL, $crawler->text());
    }
}*/
