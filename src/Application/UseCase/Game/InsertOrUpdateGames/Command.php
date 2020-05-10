<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\InsertOrUpdateGames;

use App\Application\DTO\Collection\GameWriteDTOCollection;
use App\Application\DTO\DistributorDTO;

class Command
{

    private DistributorDTO $distributor;
    private GameWriteDTOCollection $games;

    public function __construct(DistributorDTO $distributor, GameWriteDTOCollection $games)
    {
        $this->distributor = $distributor;
        $this->games = $games;
    }

    public function getDistributor(): DistributorDTO
    {
        return $this->distributor;
    }

    public function getGames(): GameWriteDTOCollection
    {
        return $this->games;
    }

}
