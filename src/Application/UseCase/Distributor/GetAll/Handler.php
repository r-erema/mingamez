<?php

declare(strict_types=1);

namespace App\Application\UseCase\Distributor\GetAll;

use App\Application\DTO\Collection\DistributorDTOCollection;
use App\Application\Repository\IDistributorRepository;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{

    private IDistributorRepository $distributors;
    private AutoMapperInterface $mapper;

    public function __construct(IDistributorRepository $distributors, AutoMapperInterface $mapper)
    {
        $this->distributors = $distributors;
        $this->mapper = $mapper;
    }

    /**
     * @param Query $query
     * @return DistributorDTOCollection
     * @throws UnregisteredMappingException
     */
    public function __invoke(Query $query): DistributorDTOCollection
    {
        $distributors = $this->distributors->findAll();
        return $this->mapper->map($distributors, DistributorDTOCollection::class);
    }

}
