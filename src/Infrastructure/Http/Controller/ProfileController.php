<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Repository\IUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    public const READ_ROUTE_NAME = 'profile.read';

    public function __construct(IUserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @Route("/profile", name=ProfileController::READ_ROUTE_NAME)
     * @return Response
     */
    public function show(): Response
    {
        $user = [];
        return $this->render('app/profile/read.html.twig', compact('user'));
    }
}
