<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\ValueObject\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetTokenSender implements IResetTokenSender
{

    private string $from;
    private MailerInterface $mailer;
    private TranslatorInterface $translator;

    public function __construct(string $from, MailerInterface $mailer, TranslatorInterface $translator)
    {
        $this->from = $from;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    /**
     * @param Email $email
     * @param string $token
     * @throws TransportExceptionInterface
     */
    public function send(Email $email, string $token): void
    {
        $message = (new TemplatedEmail())
            ->from($this->from)
            ->to($email->getValue())
            ->subject($this->translator->trans('Reset token', [], 'common'))
            ->htmlTemplate('email/auth/reset.html.twig')
            ->context(['token' => $token]);
        $this->mailer->send($message);
    }
}
