<?php

declare(strict_types=1);

namespace App\Application\DTO\WriteNew\Collection;

use App\Application\DTO\WriteNew\GenreDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

/**
 * @method GenreDTO[] getIterator()
 */
class GenreDTOCollection extends ArrayCollection
{
    public function __construct(array $genres = [])
    {
        Assert::allIsInstanceOf($genres, GenreDTO::class);
        parent::__construct($genres);
    }
}
