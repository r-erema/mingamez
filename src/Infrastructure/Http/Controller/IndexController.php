<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\DTO\Collection\DistributorDTOCollection;
use App\Application\DTO\DistributorDTO;
use App\Application\UseCase\Distributor\GetAll\Query as GetAllDistributors;
use App\Application\UseCase\Game\GetByDistributorAndGenre\Query as GetByGenre;
use App\Application\UseCase\Game\Read\Query as GetGames;
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

    public function __construct(MessageBusInterface $messageBus, string $defaultDistributor)
    {
        $this->messageBus = $messageBus;
        $this->defaultDistributor = $defaultDistributor;
    }

    /**
     * @Route(
     *     "/{distributor}",
     *     defaults={"distributor": null},
     *     name=IndexController::INDEX_ROUTE_NAME
     * )
     * @param string $distributor
     * @return Response
     */
    public function index(?string $distributor): Response
    {
        $distributors = $this->handle(new GetAllDistributors());
        $currentDistributor = $this->resolveDistributor($distributors, $distributor);
        $games = $this->handle(new GetGames(['distributor' => $currentDistributor->getId()], ['rating' => 'desc'], 13));
        $genres = $this->handle(new GetGenres($currentDistributor));
        $topGames = $games->slice(0, 4);
        $otherGames = $games->slice(4);
        return $this->render('app/portal/index.html.twig', compact(
            'currentDistributor',
            'distributors',
            'genres',
            'topGames',
            'otherGames')
        );
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
        if ($distributorId !== null) {
            $distributorIds = array_map(
                fn(DistributorDTO $distributorDTO): string => $distributorDTO->getId()->toString(),
                $distributors->toArray()
            );
            if (in_array($distributorId, $distributorIds, true)) {
                return $distributors->getDistributorById($distributorId);
            }
        }
        return $distributors->getDistributorByName($this->defaultDistributor);
    }
}
