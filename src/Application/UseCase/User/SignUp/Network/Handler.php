<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\SignUp\Network;

use App\Application\Repository\IFlusher;
use App\Application\Repository\IUserRepository;
use App\Domain\Entity\User;
use App\Domain\Exception\UserAlreadyExists;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
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
        if ($this->users->hasByNetworkIdentity($command->getNetworkName(), $command->getIdentity())) {
            throw new UserAlreadyExists();
        }

        $user = User::signUpByNetwork(
            Uuid::uuid4(),
            new DateTimeImmutable(),
            $command->getNetworkName(),
            $command->getIdentity()
        );

        $this->users->add($user);
        $this->flusher->flush();
    }

}
