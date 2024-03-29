<?php

declare(strict_types=1);

namespace App\Application\DTO\WriteNew\Collection;

use App\Application\DTO\WriteNew\GameDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

/**
 * @method  GameDTO[] getIterator()
 */
class GameDTOCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assert::allIsInstanceOf($elements, GameDTO::class);
        parent::__construct($elements);
    }
}
