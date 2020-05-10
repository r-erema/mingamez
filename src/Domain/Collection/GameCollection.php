<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Game;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class GameCollection extends ArrayCollection
{

    public function __construct(array $games = [])
    {
        Assert::allIsInstanceOf($games, Game::class);
        parent::__construct($games);
    }

    public function add($game): self
    {
        Assert::isInstanceOf($game, Game::class);
        parent::add($game);
        return $this;
    }

}
