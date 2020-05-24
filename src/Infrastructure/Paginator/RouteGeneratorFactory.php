<?php

declare(strict_types=1);

namespace App\Infrastructure\Paginator;

use BabDev\PagerfantaBundle\RouteGenerator\RouteGeneratorFactoryInterface;
use BabDev\PagerfantaBundle\RouteGenerator\RouteGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class RouteGeneratorFactory implements RouteGeneratorFactoryInterface
{

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function create(array $options = []): RouteGeneratorInterface
    {
        return new GameRouteGenerator($options['distributorId'], $this->router);
    }
}
