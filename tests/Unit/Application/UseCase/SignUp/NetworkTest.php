<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCase\SignUp;

use App\Domain\Entity\Network;
use App\Domain\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class NetworkTest extends TestCase
{

    public function testSuccess(): void
    {
        $user = User::signUpByNetwork(
            Uuid::uuid4(),
            $date = new DateTimeImmutable(),
            Network::NETWORK_GOOGLE,
            '0000011'
        );

        $this->assertTrue($user->isActive());
        $this->assertCount(1, $networks = $user->getNetworksArray());
        $this->assertInstanceOf(Network::class, $first = reset($networks));
        $this->assertTrue($user->getRole()->isUser());
    }

}
