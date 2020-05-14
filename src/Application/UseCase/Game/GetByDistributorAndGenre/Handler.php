<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\GetByDistributorAndGenre;

use App\Application\DTO\Collection\GameDTOCollection;
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
     * @param Query $query
     * @return GameDTOCollection
     * @throws UnregisteredMappingException
     */
    public function __invoke(Query $query): GameDTOCollection
    {
        $games = $this->games->findByDistributorAndGenreId($query->getDistributorId(), $query->getGenreId(), $query->getLimit());
        return $this->mapper->map($games, GameDTOCollection::class);
    }

}
