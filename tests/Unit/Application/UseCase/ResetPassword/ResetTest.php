<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCase\ResetPassword;

use App\Application\ValueObject\ResetToken;
use App\Domain\Entity\User;
use App\Domain\Exception\ResettingNotRequested;
use App\Domain\Exception\ResetTokenExpired;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Test\Builder\UserBuilder;

class ResetTest extends TestCase
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
        $this->user->passwordReset($now, $hash = 'hash');
        $this->assertNull($this->user->getResetToken());
        $this->assertEquals($hash, $this->user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now);
        $this->user->requestPasswordReset($token, $now);
        $this->expectException(ResetTokenExpired::class);
        $this->user->passwordReset($now->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $now = new DateTimeImmutable();
        $this->expectException(ResettingNotRequested::class);
        $this->user->passwordReset($now, 'hash');
    }

}
