<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

class UsernameDeterminer
{

    private ?string $networkName = null;
    private string $userIdentity;

    public function __construct(string $networkIdentity)
    {
        $exploded = explode(':', $networkIdentity);
        if (count($exploded) === 2) {
            [$this->networkName, $this->userIdentity] = $exploded;
        } else {
            $this->userIdentity = $networkIdentity;
        }
    }

    public function getNetworkName(): ?string
    {
        return $this->networkName;
    }

    public function getUserIdentity(): string
    {
        return $this->userIdentity;
    }

}
