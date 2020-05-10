<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Repository\IGameRepository;
use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

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


}
