<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\ResetPassword\Reset;

use App\Application\Service\IFlusher;
use App\Application\Repository\IUserRepository;
use App\Domain\Exception\BadResetToken;
use App\Infrastructure\Service\PasswordHasher;
use DateTimeImmutable;
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
        if (null === ($user = $this->users->findByResetToken($command->getResetToken()))) {
            throw new BadResetToken();
        }

        $user->passwordReset(new DateTimeImmutable(), PasswordHasher::hash($command->getResetToken()));

        $this->flusher->flush();
    }

}
