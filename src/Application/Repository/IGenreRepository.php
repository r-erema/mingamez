<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Collection\GenreCollection;
use App\Domain\Entity\Genre;

interface IGenreRepository
{
    public function findOneBy(array $criteria): ?Genre;
    public function findAll(): GenreCollection;
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): GenreCollection;
    public function findByDistributorId(string $distributorId): GenreCollection;
    public function add(Genre $genre): void;
}
