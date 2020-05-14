<?php

declare(strict_types=1);

namespace App\Application\UseCase\Genre\GetAllByDistributor;

use App\Application\DTO\DistributorDTO;

class Query
{

    private DistributorDTO $distributor;

    public function __construct(DistributorDTO $distributor)
    {
        $this->distributor = $distributor;
    }

    public function getDistributor(): DistributorDTO
    {
        return $this->distributor;
    }

}
