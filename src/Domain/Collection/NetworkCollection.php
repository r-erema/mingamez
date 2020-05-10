<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Network;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class NetworkCollection extends ArrayCollection
{

    public function __construct(array $elements = [])
    {
        Assert::allIsInstanceOf($elements, Network::class);
        parent::__construct($elements);
    }

    public function add($game): self
    {
        Assert::isInstanceOf($game, Network::class);
        parent::add($game);
        return $this;
    }

    public function containsWithName(string $networkName): bool
    {
        $found = $this->filter(fn(Network $network): bool => $network->getName() === $networkName);
        return count($found) > 0;
    }

}
