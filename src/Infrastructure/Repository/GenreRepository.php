<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\IGenreRepository;
use App\Domain\Collection\GenreCollection;
use App\Domain\Entity\Distributor;
use App\Domain\Entity\Game;
use App\Domain\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class GenreRepository implements IGenreRepository
{

    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Genre::class);
    }

    public function findOneBy(array $criteria): ?Genre
    {
        /** @var Genre $genre */
        $genre = $this->repository->findOneBy($criteria);
        return $genre;
    }

    public function findAll(): GenreCollection
    {
        $genres = $this->repository->findAll();
        return new GenreCollection($genres);
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): GenreCollection
    {
        $genres = $this->repository->findBy($criteria, $orderBy, $limit, $offset);
        return new GenreCollection($genres);
    }
    public function findByDistributorId(string $distributorId): GenreCollection
    {
        $result = $this->em->createQueryBuilder()
            ->select('genre')
            ->from(Genre::class, 'genre')
            ->join('genre.games', 'g')
            ->join('g.distributor', 'd')
            ->where('d.id = :id')
            ->setParameter('id', $distributorId)
            ->getQuery()
            ->getResult();
        return new GenreCollection($result);
    }

    public function add(Genre $genre): void
    {
        $this->em->persist($genre);
    }

}
