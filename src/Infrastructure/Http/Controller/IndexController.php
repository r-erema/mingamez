<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\DTO\Collection\DistributorDTOCollection;
use App\Application\DTO\DistributorDTO;
use App\Application\UseCase\Distributor\GetAll\Query as GetAllDistributors;
use App\Application\UseCase\Game\GetByDistributorAndGenre\Query as GetByGenre;
use App\Application\UseCase\Game\Read\Query as GetGames;
use App\Application\UseCase\Game\Read\Result;
use App\Application\UseCase\Genre\GetAllByDistributor\Query as GetGenres;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    use HandleTrait;

    public const INDEX_ROUTE_NAME = 'index';
    public const GENRE_ROUTE_NAME = 'genre';

    private string $defaultDistributor;
    private int $maxGamesPerPage;

    public function __construct(MessageBusInterface $messageBus, string $defaultDistributor, int $maxGamesPerPage)
    {
        $this->messageBus = $messageBus;
        $this->defaultDistributor = $defaultDistributor;
        $this->maxGamesPerPage = $maxGamesPerPage;
    }

    /**
     * @Route(
     *     "/{distributor}/{page}",
     *     defaults={"distributor": null, "page": 1},
     *     name=IndexController::INDEX_ROUTE_NAME
     * )
     * @param string $distributor
     * @param int $page
     * @return Response
     */
    public function index(?string $distributor, int $page): Response
    {
        $distributors = $this->handle(new GetAllDistributors());
        $currentDistributor = $this->resolveDistributor($distributors, $distributor);
        /** @var Result $result */
        $result = $this->handle(new GetGames($currentDistributor->getId()->toString(), $page, $this->maxGamesPerPage));
        $genres = $this->handle(new GetGenres($currentDistributor));
        $topGames = $result->getTopGames();
        $otherGames = $result->getPaginator()->getCurrentPageResults();
        $pager = $result->getPaginator();
        return $this->render('app/portal/index.html.twig', compact(
            'currentDistributor',
            'distributors',
            'genres',
            'topGames',
            'otherGames',
            'pager'
        ));
    }

    /**
     * @Route(
     *     "/{distributor}/genre/{genreId}",
     *     defaults={"distributor": null},
     *     name=IndexController::GENRE_ROUTE_NAME
     * )
     * @param string $genreId
     * @param string $distributor
     * @return Response
     */
    public function genres(string $genreId, ?string $distributor): Response
    {
        $distributors = $this->handle(new GetAllDistributors());
        $currentDistributor = $this->resolveDistributor($distributors, $distributor);
        $games = $this->handle(new GetByGenre($currentDistributor->getId()->toString(), $genreId, 13));
        $genres = $this->handle(new GetGenres($currentDistributor));
        $topGames = $games->slice(0, 4);
        $otherGames = $games->slice(4);
        //todo: Do pagination
        return $this->render('app/portal/index.html.twig', compact(
            'currentDistributor',
            'distributors',
            'genres',
            'topGames',
            'otherGames')
        );
    }

    private function resolveDistributor(DistributorDTOCollection $distributors, ?string $distributorId): DistributorDTO
    {
        if (($distributorId !== null) && in_array($distributorId, $distributors->getIds(), true)) {
            return $distributors->getDistributorById($distributorId);
        }
        return $distributors->getDistributorByName($this->defaultDistributor);
    }
}
