<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

use Webmozart\Assert\Assert;

class Role
{

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLES = [
        self::ROLE_USER => self::ROLE_USER,
        self::ROLE_ADMIN => self::ROLE_ADMIN
    ];

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::ROLES);
        $this->value = $value;
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public function isUser(): bool
    {
        return $this->value === self::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->value === self::ROLE_ADMIN;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
