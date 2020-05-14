<?php

declare(strict_types=1);

namespace App\Application\DTO\Collection;

use App\Application\DTO\GameDTO;
use App\Application\DTO\GenreDTO;
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

    public function fetchGenres(): GenreDTOCollection
    {
        $genres = array_map(fn(GameDTO $gameDTO) => $gameDTO->getGenres()->toArray(), $this->toArray());
        /** @var GenreDTO[] $genres */
        $genres = array_merge(...$genres);
        $uniqueGenres = [];
        foreach ($genres as $genre) {
            $uniqueGenres[$genre->getName()] = $genre;
        }
        return new GenreDTOCollection($uniqueGenres);
    }
}
