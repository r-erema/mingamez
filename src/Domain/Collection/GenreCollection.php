<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Domain\Entity\Genre;
use App\Domain\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class GenreCollection extends ArrayCollection
{

    public function __construct(array $elements = [])
    {
        Assert::allIsInstanceOf($elements, Genre::class);
        parent::__construct($elements);
    }

    public function add($game): self
    {
        Assert::isInstanceOf($game, Genre::class);
        parent::add($game);
        return $this;
    }

}
