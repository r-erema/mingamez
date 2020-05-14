<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

class Query
{
    private array $criteria;
    private array $order;
    private int $limit;

    public function __construct(array $criteria, array $order, int $limit)
    {
        $this->criteria = $criteria;
        $this->order = $order;
        $this->limit = $limit;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }
    public function getOrder(): array
    {
        return $this->order;
    }
    public function getLimit(): int
    {
        return $this->limit;
    }

}
