<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\ChangeRole;

use App\Application\Service\IFlusher;
use App\Application\Repository\IUserRepository;
use App\Application\ValueObject\Role;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{

    private IUserRepository $users;
    private IFlusher $flusher;

    public function __construct(IUserRepository $users, IFlusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function __invoke(Command $command): void
    {
        $user = $this->users->get($command->getUserId());
        $user->setRole(new Role($command->getRole()));
        $this->flusher->flush();
    }

}
