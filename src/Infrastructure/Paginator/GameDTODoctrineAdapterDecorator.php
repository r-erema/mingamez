<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator;

use App\Application\DTO\Collection\GameDTOCollection;
use App\Domain\Collection\GameCollection;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class GameDTODoctrineAdapterDecorator implements AdapterInterface
{

    private DoctrineORMAdapter $adapter;
    private AutoMapperInterface $mapper;

    public function __construct(DoctrineORMAdapter $adapter, AutoMapperInterface $mapper)
    {
        $this->adapter = $adapter;
        $this->mapper = $mapper;
    }

    public function getNbResults(): int
    {
        return $this->adapter->getNbResults();
    }

    /**
     * @param int $offset
     * @param int $length
     * @return GameDTOCollection
     * @throws UnregisteredMappingException
     */
    public function getSlice($offset, $length): GameDTOCollection
    {
        return $this->mapper->map(
            new GameCollection(iterator_to_array($this->adapter->getSlice($offset, $length))),
            GameDTOCollection::class
        );
    }

}
