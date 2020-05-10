<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCase\SignUp;

use App\Domain\Exception\UserAlreadySignedUp;
use PHPUnit\Framework\TestCase;
use Test\Builder\UserBuilder;

class ConfirmTest extends TestCase
{

    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();

        $this->assertFalse($user->isWait());
        $this->assertTrue($user->isActive());
        $this->assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignUp();
        $this->expectException(UserAlreadySignedUp::class);
        $user->confirmSignUp();
    }

}
