<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Distributor;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class DistributorCollection extends ArrayCollection
{
    public function __construct(array $games = [])
    {
        Assert::allIsInstanceOf($games, Distributor::class);
        parent::__construct($games);
    }
}
