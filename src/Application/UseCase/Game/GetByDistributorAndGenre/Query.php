<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\GetByDistributorAndGenre;

use App\Application\DTO\GenreDTO;
use App\Application\DTO\DistributorDTO;

class Query
{

    private string $distributorId;
    private string $genreId;
    private int $limit;

    public function __construct(string $distributorId, string $genreId, int $limit)
    {
        $this->distributorId = $distributorId;
        $this->genreId = $genreId;
        $this->limit = $limit;
    }
    public function getDistributorId(): string
    {
        return $this->distributorId;
    }
    public function getGenreId(): string
    {
        return $this->genreId;
    }
    public function getLimit(): int
    {
        return $this->limit;
    }

}
