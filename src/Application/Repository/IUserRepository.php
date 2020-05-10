<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\Exception\EntityNotFoundException;
use App\Application\ValueObject\Email;
use App\Domain\Entity\User;

interface IUserRepository
{

    /**
     * @param string $id
     * @return User
     */
    public function get(string $id): User;

    /**
     * @param Email $email
     * @return User
     * @throws EntityNotFoundException
     */
    public function getByEmail(Email $email): User;

    public function hasByEmail(Email $email): bool;
    public function findByEmail(Email $email): ?User;
    public function hasByNetworkIdentity(string $networkName, string $identity): bool;
    public function findByNetworkIdentity(string $networkName, string $identity): ?User;
    public function findByConfirmToken(string $token): ?User;
    public function findByResetToken(string $token): ?User;
    public function add(User $user): void;
}
