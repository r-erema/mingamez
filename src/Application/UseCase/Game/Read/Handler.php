<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

use App\Application\Repository\IGameRepository;
use App\Domain\Collection\GameCollection;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{
    private IGameRepository $games;

    public function __construct(IGameRepository $games) {
        $this->games = $games;
    }

    public function __invoke(Query $command): GameCollection
    {
        return $this->games->findBy(['distributor' => 'a4a351ee-436d-4d08-be58-83178dbbfb1d'], ['rating' => 'desc'], 13);
    }

}
