<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

class Network
{

    public const NETWORK_GOOGLE = 'google';
    public const NETWORK_FACEBOOK = 'facebook';
    public const NETWORKS = [
        self::NETWORK_GOOGLE => self::NETWORK_GOOGLE,
        self::NETWORK_FACEBOOK => self::NETWORK_FACEBOOK
    ];

    private UuidInterface $id;
    private string $name;
    private string $identity;
    private User $user;

    public function __construct(UuidInterface $id, string $name, string $identity, User $user)
    {
        Assert::oneOf($name, self::NETWORKS);
        $this->id = $id;
        $this->name = $name;
        $this->identity = $identity;
        $this->user = $user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

}
