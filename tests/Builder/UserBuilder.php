<?php

declare(strict_types=1);

namespace Test\Builder;

use App\Application\ValueObject\Email;
use App\Application\ValueObject\Role;
use App\Domain\Entity\User;
use BadMethodCallException;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserBuilder
{
    private UuidInterface $id;
    private DateTimeImmutable $date;

    private Email $email;
    private string $hash;
    private string $token;
    private bool $confirmed = false;

    private ?Role $role = null;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->date = new DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('mail@app.test');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';
        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function withId(UuidInterface $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withRole(Role $role): self
    {
        $clone = clone $this;
        $clone->role = $role;
        return $clone;
    }

    public function build(): User
    {
        $user = null;

        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }
        }

        if (!$user) {
            throw new BadMethodCallException('Specify via method.');
        }

        if ($this->role) {
            $user->setRole($this->role);
        }

        return $user;
    }
}
