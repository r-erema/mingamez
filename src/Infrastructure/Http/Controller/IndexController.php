<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\Game\Read\Query;
use App\Domain\Collection\GameCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    use HandleTrait;

    public const INDEX_ROUTE_NAME = 'index';

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/", name=IndexController::INDEX_ROUTE_NAME)
     * @return Response
     */
    public function index(): Response
    {
        /** @var GameCollection $games */
        $games = $this->handle(new Query());

        $topGames = $games->slice(0, 4);
        $otherGames = $games->slice(4);
        return $this->render('app/portal/index.html.twig', compact('topGames', 'otherGames'));
    }

}
