<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\InsertOrUpdateGames;

use App\Application\DTO\Collection\GameDTOCollection;
use App\Application\DTO\DistributorDTO;

class Command
{

    private DistributorDTO $distributor;
    private GameDTOCollection $games;

    public function __construct(DistributorDTO $distributor, GameDTOCollection $games)
    {
        $this->distributor = $distributor;
        $this->games = $games;
    }

    public function getDistributor(): DistributorDTO
    {
        return $this->distributor;
    }

    public function getGames(): GameDTOCollection
    {
        return $this->games;
    }

}
