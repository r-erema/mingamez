<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\IDistributorRepository;
use App\Domain\Collection\DistributorCollection;
use App\Domain\Entity\Distributor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DistributorRepository implements IDistributorRepository
{

    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Distributor::class);
    }

    public function findOneBy(array $criteria): ?Distributor
    {
        /** @var Distributor $distributor */
        $distributor = $this->repository->findOneBy($criteria);
        return $distributor;
    }

    public function findAll(): DistributorCollection
    {
        return new DistributorCollection($this->repository->findAll());
    }


}
