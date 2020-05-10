<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Collection\GameCollection;
use App\Domain\Entity\Game;

interface IGameRepository
{
    public function findOneBy(array $criteria): ?Game;
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): GameCollection;
    public function add(Game $user): void;
}
