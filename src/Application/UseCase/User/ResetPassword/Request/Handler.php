<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\ResetPassword\Request;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Repository\IFlusher;
use App\Application\Repository\IUserRepository;
use App\Application\ValueObject\Email;
use App\Infrastructure\Service\IResetTokenSender;
use App\Infrastructure\Service\ResetTokenizer;
use DateTimeImmutable;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{
    private IUserRepository $users;
    private IFlusher $flusher;
    private ResetTokenizer $tokenizer;
    private IResetTokenSender $tokenSender;

    public function __construct(
        IUserRepository $users,
        IFlusher $flusher,
        ResetTokenizer $tokenizer,
        IResetTokenSender $tokenSender
    )
    {
        $this->users = $users;
        $this->flusher = $flusher;
        $this->tokenizer = $tokenizer;
        $this->tokenSender = $tokenSender;
    }

    /**
     * @param Command $command
     * @throws EntityNotFoundException
     */
    public function __invoke(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->getEmail()));

        $resetToken = $this->tokenizer->generate();
        $user->requestPasswordReset($resetToken, new DateTimeImmutable());

        $this->flusher->flush();
        $this->tokenSender->send($user->getEmail(), $resetToken->getToken());
    }

}
