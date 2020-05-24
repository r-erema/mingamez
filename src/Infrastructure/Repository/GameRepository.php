<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\IGameRepository;
use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class GameRepository implements IGameRepository
{

    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Game::class);
    }

    public function add(Game $user): void
    {
        $this->em->persist($user);
    }

    public function findOneBy(array $criteria): ?Game
    {
        /** @var Game $game */
        $game = $this->repository->findOneBy($criteria);
        return $game;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): GameCollection
    {
        $games = $this->repository->findBy($criteria, $orderBy, $limit, $offset);
        return new GameCollection($games);
    }

    /**
     * @param Criteria $criteria
     * @return QueryBuilder
     * @throws QueryException
     */
    public function createQueryBuilder(Criteria $criteria): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select('g')
            ->from(Game::class, 'g')
            ->addCriteria($criteria);
    }

    public function findByDistributorAndGenreId(string $distributorId, string $genreId, int $limit): GameCollection
    {
        $result = $this->em->createQueryBuilder()
            ->select('g')
            ->from(Game::class, 'g')
            ->join('g.genres', 'genres')
            ->where('genres.id = :genreId')
            ->andWhere('g.distributor = :distributorId')
            ->setMaxResults($limit)
            ->setParameters(['genreId' => $genreId, 'distributorId' => $distributorId])
            ->getQuery()
            ->getResult();
        return new GameCollection($result);
    }

}
