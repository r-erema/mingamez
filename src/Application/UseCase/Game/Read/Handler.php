<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\Read;

use App\Application\DTO\Collection\GameDTOCollection;
use App\Application\Repository\IGameRepository;
use App\Infrastructure\Paginator\GamePaginatorBuilder;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\Criteria;
use RuntimeException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{
    private IGameRepository $games;
    private AutoMapperInterface $mapper;
    private GamePaginatorBuilder $paginatorBuilder;

    public function __construct(
        IGameRepository $games,
        AutoMapperInterface $mapper,
        GamePaginatorBuilder $paginatorBuilder
    ) {
        $this->games = $games;
        $this->mapper = $mapper;
        $this->paginatorBuilder = $paginatorBuilder;
    }


    /**
     * @param Query $query
     * @return Result
     * @throws UnregisteredMappingException
     */
    public function __invoke(Query $query): Result
    {
        $topGames = $this->games->findBy(['distributor' => $query->getDistributorId()], ['rating' => 'desc'], 4);
        $expr = Criteria::expr();
        if ($expr === null) {
            throw new RuntimeException('Couldn\'t create criteria');
        }
        $criteria = Criteria::create()
            ->where($expr->eq('distributor', $query->getDistributorId()))
            ->andWhere($expr->notIn('id', $topGames->getIds()))
            ->orderBy(['rating' => 'desc']);
        $otherGamesQB = $this->games->createQueryBuilder($criteria);

        $paginator = $this->paginatorBuilder->build($otherGamesQB, $query->getCount(), $query->getPage());

        return new Result(
            $this->mapper->map($topGames, GameDTOCollection::class),
            $paginator
        );
    }

}
