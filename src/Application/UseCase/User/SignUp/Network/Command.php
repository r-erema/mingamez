<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\SignUp\Network;

class Command
{
    private string $networkName;
    private string $identity;

    public function __construct(string $networkName, string $identity)
    {
        $this->networkName = $networkName;
        $this->identity = $identity;
    }

    public function getNetworkName(): string
    {
        return $this->networkName;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
