<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\ValueObject\Email;

interface IConfirmTokenSender
{
    public function send(Email $email, string $token): void;
}
