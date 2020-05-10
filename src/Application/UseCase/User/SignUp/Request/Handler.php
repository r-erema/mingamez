<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\SignUp\Request;

use App\Application\Repository\IFlusher;
use App\Application\Repository\IUserRepository;
use App\Application\ValueObject\Email;
use App\Domain\Entity\User;
use App\Infrastructure\Service\IConfirmTokenSender;
use App\Infrastructure\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;
use other\ProjectManager\src\Model\User\Service\ConfirmTokenizer;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{

    private IUserRepository $users;
    private IFlusher $flusher;
    private IConfirmTokenSender $tokenSender;

    public function __construct(IUserRepository $userRepository, IFlusher $flusher, IConfirmTokenSender $tokenSender)
    {
        $this->users = $userRepository;
        $this->flusher = $flusher;
        $this->tokenSender = $tokenSender;
    }

    public function __invoke(Command $command): void
    {
        $email = new Email($command->getEmail());

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('User already exists');
        }

        $user = User::signUpByEmail(
            Uuid::uuid4(),
            new DateTimeImmutable(),
            new Email($command->getEmail()),
            PasswordHasher::hash($command->getPassword()),
            $token = ConfirmTokenizer::generate()
        );

        $this->users->add($user);
        $this->tokenSender->send($email, $token);
        $this->flusher->flush();
    }
}
