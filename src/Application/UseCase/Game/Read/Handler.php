<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

use App\Application\DTO\Collection\GameDTOCollection;
use App\Application\DTO\GameDTO;
use App\Application\Repository\IGameRepository;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{
    private IGameRepository $games;
    private AutoMapperInterface $mapper;

    public function __construct(IGameRepository $games, AutoMapperInterface $autoMapper) {
        $this->games = $games;
        $this->mapper = $autoMapper;
    }

    /**
     * @param Query $command
     * @return GameDTOCollection
     * @throws UnregisteredMappingException
     */
    public function __invoke(Query $command): GameDTOCollection
    {
        $games = $this->games->findBy([], ['rating' => 'desc'], 13);
        return $this->mapper->map($games, GameDTOCollection::class);
    }

}
