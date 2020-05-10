<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCase\SignUp;

use App\Application\ValueObject\Email;
use App\Domain\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{

    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $id = Uuid::uuid4(),
            $date = new DateTimeImmutable(),
            $email = new Email('test@test.test'),
            $hash = 'hash',
            $token = 'token'
        );

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($date, $user->getDate());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($hash, $user->getPasswordHash());
        $this->assertEquals($token, $user->getConfirmToken());
        $this->assertTrue($user->getRole()->isUser());
    }
}
