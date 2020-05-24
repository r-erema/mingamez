<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

class Query
{

    private string $distributorId;
    private int $page;
    private int $count;

    public function __construct(string $distributorId, int $page, int $count)
    {
        $this->distributorId = $distributorId;
        $this->page = $page;
        $this->count = $count;
    }

    public function getDistributorId(): string
    {
        return $this->distributorId;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getCount(): int
    {
        return $this->count;
    }

}
