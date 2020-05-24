<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator;

use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class GamePaginatorBuilder
{

    private AutoMapperInterface $mapper;

    public function __construct(AutoMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function build(QueryBuilder $qb, int $count, int $page): Pagerfanta
    {
        $adapter = new GameDTODoctrineAdapterDecorator(new DoctrineORMAdapter($qb), $this->mapper);
        return (new Pagerfanta($adapter))
            ->setMaxPerPage($count)
            ->setCurrentPage($page);
    }
}
