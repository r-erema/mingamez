<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;

interface IGameRepository
{
    public function findOneBy(array $criteria): ?Game;
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): GameCollection;
    public function createQueryBuilder(Criteria $criteria): QueryBuilder;
    public function findByDistributorAndGenreId(string $distributorId, string $genreId, int $limit): GameCollection;
    public function add(Game $user): void;
}
