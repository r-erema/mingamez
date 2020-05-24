<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

use App\Application\DTO\Collection\GameDTOCollection;
use Pagerfanta\Pagerfanta;

class Result
{

    private GameDTOCollection $topGames;
    private Pagerfanta $paginator;

    public function __construct(GameDTOCollection $topGames, Pagerfanta $pager)
    {
        $this->topGames = $topGames;
        $this->paginator = $pager;
    }
    public function getTopGames(): GameDTOCollection
    {
        return $this->topGames;
    }
    public function getPaginator(): Pagerfanta
    {
        return $this->paginator;
    }

}
