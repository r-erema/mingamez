<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Application\ValueObject\Email;
use App\Application\ValueObject\ResetToken;
use App\Application\ValueObject\Role;
use App\Application\ValueObject\Status;
use App\Domain\Collection\NetworkCollection;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use App\Domain\Exception\NetworkAlreadyAttached;
use App\Domain\Exception\ResettingAlreadyRequested;
use App\Domain\Exception\ResettingNotRequested;
use App\Domain\Exception\ResetTokenExpired;
use App\Domain\Exception\RoleIsAlreadySame;
use App\Domain\Exception\UserAlreadySignedUp;
use App\Domain\Exception\UserIsNotActive;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User
{

    private UuidInterface $id;
    private DateTimeImmutable $date;
    private ?Email $email = null;
    private ?string $passwordHash = null;
    private ?string $confirmToken = null;
    private ?ResetToken $resetToken = null;
    private Status $status;
    private Role $role;
    private Collection $networks;

    private function __construct(UuidInterface $id, DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->date = $date;
        $this->role = Role::user();
        $this->networks = new NetworkCollection();
    }

    public static function signUpByEmail(UuidInterface $id, DateTimeImmutable $date, Email $email, string $passwordHash, string $token): User
    {
        $user = new self($id, $date);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmToken = $token;
        $user->status = new Status(Status::STATUS_WAIT);
        return $user;
    }

    public static function signUpByNetwork(UuidInterface $id, DateTimeImmutable $date, string $networkName, string $networkIdentity): User
    {
        $user = new self($id, $date);
        $user->attachNetwork(new Network(Uuid::uuid4(), $networkName, $networkIdentity, $user));
        $user->status = new Status(Status::STATUS_ACTIVE);
        return $user;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function isWait(): bool
    {
        return $this->status->getValue() === Status::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status->getValue() === Status::STATUS_ACTIVE;
    }

    public function confirmSignUp(): void {
        if ($this->status->getValue() === Status::STATUS_ACTIVE) {
            throw new UserAlreadySignedUp();
        }

        $this->status = new Status(Status::STATUS_ACTIVE);
        $this->confirmToken = null;
    }

    public function attachNetwork(Network $network): void
    {
        if ($this->networks->containsWithName($network->getName())) {
            throw new NetworkAlreadyAttached();
        }
        $this->networks->add($network);
    }

    public function getNetworksArray(): array
    {
        return $this->networks->toArray();
    }

    public function getNetworkByName(string $networkName): ?Network
    {
        return $this->networks
            ->filter(fn(Network $network): bool => $network->getName() === $networkName)
            ->first();
    }

    public function requestPasswordReset(ResetToken $resetToken, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new UserIsNotActive();
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new ResettingAlreadyRequested();
        }
        $this->resetToken = $resetToken;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new ResettingNotRequested();
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new ResetTokenExpired();
        }
        $this->passwordHash = $hash;
        $this->resetToken = null;
    }

    public function setRole(Role $role): void
    {
        if ($role->getValue() === $this->role->getValue()) {
            throw new RoleIsAlreadySame();
        }
        $this->role = $role;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

}
