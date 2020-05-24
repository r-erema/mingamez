<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator;

use App\Infrastructure\Http\Controller\IndexController;
use BabDev\PagerfantaBundle\RouteGenerator\RouteGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class GameRouteGenerator implements RouteGeneratorInterface
{

    private string $distributorId;
    private RouterInterface $router;

    public function __construct(string $distributorId, RouterInterface $router)
    {
        $this->distributorId = $distributorId;
        $this->router = $router;
    }

    public function __invoke(int $page): string
    {
        return $this->router->generate(IndexController::INDEX_ROUTE_NAME, [
            'distributor' => $this->distributorId,
            'page' => $page
        ]);
    }

}
