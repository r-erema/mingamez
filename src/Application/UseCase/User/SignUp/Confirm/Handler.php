<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\SignUp\Confirm;

use App\Application\Repository\IFlusher;
use App\Application\Repository\IUserRepository;
use DomainException;
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
        if (null === ($user = $this->users->findByConfirmToken($command->getConfirmToken()))) {
            throw new DomainException('Incorrect confirm token');
        }
        $user->confirmSignUp();
        $this->flusher->flush();
    }

}
