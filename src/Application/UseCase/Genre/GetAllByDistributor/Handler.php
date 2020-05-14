<?php

declare(strict_types=1);

namespace App\Application\UseCase\Genre\GetAllByDistributor;

use App\Application\DTO\Collection\GenreDTOCollection;
use App\Application\Repository\IGenreRepository;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{

    private IGenreRepository $genres;
    private AutoMapperInterface $mapper;

    public function __construct(IGenreRepository $genres, AutoMapperInterface $mapper)
    {
        $this->genres = $genres;
        $this->mapper = $mapper;
    }

    /**
     * @param Query $query
     * @throws UnregisteredMappingException
     */
    public function __invoke(Query $query): GenreDTOCollection
    {
        $genres = $this->genres->findByDistributorId($query->getDistributor()->getId()->toString());
        return $this->mapper->map($genres, GenreDTOCollection::class);
    }

}
