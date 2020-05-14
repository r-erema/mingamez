<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Collection\DistributorCollection;
use App\Domain\Entity\Distributor;

interface IDistributorRepository
{
    public function findOneBy(array $criteria): ?Distributor;
    public function findAll(): DistributorCollection;
}
