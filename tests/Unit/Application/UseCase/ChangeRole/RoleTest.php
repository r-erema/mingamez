<?php

declare(strict_types=1);

namespace Test\Unit\Application\UseCase\ChangeRole;

use App\Application\ValueObject\Role;
use App\Domain\Exception\RoleIsAlreadySame;
use PHPUnit\Framework\TestCase;
use Test\Builder\UserBuilder;

class RoleTest extends TestCase
{

    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();
        $user->setRole(Role::admin());
        $this->assertFalse($user->getRole()->isUser());
        $this->assertTrue($user->getRole()->isAdmin());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();
        $this->expectException(RoleIsAlreadySame::class);
        $user->setRole(Role::user());
    }

}
