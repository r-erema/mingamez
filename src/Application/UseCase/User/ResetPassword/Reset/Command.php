<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\ResetPassword\Reset;

class Command
{
    private string $resetToken;
    private string $password;

    public function __construct(string $resetToken, string $password)
    {
        $this->resetToken = $resetToken;
        $this->password = $password;
    }
    public function getResetToken(): string
    {
        return $this->resetToken;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

}
