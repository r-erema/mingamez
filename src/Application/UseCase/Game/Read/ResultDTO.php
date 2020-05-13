<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

use App\Domain\Collection\GameCollection;
class ResultDTO
{

    private GameCollection $topGames;
    private GameCollection $games;

    public function __construct(GameCollection $topGames, GameCollection $games)
    {
        $this->topGames = $topGames;
        $this->games = $games;
    }


}
