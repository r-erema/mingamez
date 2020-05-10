<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCase\ResetPassword;

use App\Application\ValueObject\ResetToken;
use App\Domain\Entity\User;
use App\Domain\Exception\ResettingAlreadyRequested;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Test\Builder\UserBuilder;

class RequestTest extends TestCase
{

    private User $user;

    public function setUp(): void
    {
        $this->user = (new UserBuilder())->viaEmail()->build();
        $this->user->confirmSignUp();
    }

    public function testSuccess(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $this->user->requestPasswordReset($token, $now);
        $this->assertNotNull($this->user->getResetToken());
    }

    public function testAlready(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));
        $this->user->requestPasswordReset($token, $now);
        $this->expectException(ResettingAlreadyRequested::class);
        $this->user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new DateTimeImmutable();

        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $this->user->requestPasswordReset($token1, $now);
        $this->assertEquals($token1, $this->user->getResetToken());

        $token2 = new ResetToken('token', $now->modify( '+3 day'));
        $this->user->requestPasswordReset($token2, $now->modify('+2 day'));
        $this->assertEquals($token2, $this->user->getResetToken());
    }


}
