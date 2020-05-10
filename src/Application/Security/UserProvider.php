<?php

declare(strict_types=1);

namespace App\Application\Security;

use App\Application\Repository\IUserRepository;
use App\Application\ValueObject\Email;
use App\Domain\Entity\User;
use App\Infrastructure\Service\UsernameDeterminer;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function get_class;

class UserProvider implements UserProviderInterface
{
    private IUserRepository $users;

    public function __construct(IUserRepository $users)
    {
        $this->users = $users;
    }

    public function loadUserByUsername($username): UserInterface
    {
        $networkDeterminer = new UsernameDeterminer($username);
        $user = $this->loadUser($networkDeterminer->getUserIdentity(), $networkDeterminer->getNetworkName());
        return self::identityByUser($user, $networkDeterminer->getNetworkName());
    }

    public function refreshUser(UserInterface $identity): UserInterface
    {
        if (!$identity instanceof UserIdentity) {
            throw new UnsupportedUserException('Invalid user class ' . get_class($identity));
        }

        $networkDeterminer = new UsernameDeterminer($identity->getUsername());
        $user = $this->loadUser($networkDeterminer->getUserIdentity(), $networkDeterminer->getNetworkName());
        return self::identityByUser($user, $networkDeterminer->getNetworkName());
    }

    public function supportsClass($class): bool
    {
        return $class === UserIdentity::class;
    }

    private function loadUser(string $identity, string $networkName = null): User
    {
        if ($networkName !== null && $user = $this->users->findByNetworkIdentity($networkName, $identity)) {
            return $user;
        }

        if ($networkName === null && $user = $this->users->findByEmail(new Email($identity))) {
            return $user;
        }

        throw new UsernameNotFoundException('');
    }

    private static function identityByUser(User $user, string $networkName = null): UserIdentity
    {
        if ($networkName !== null && $network = $user->getNetworkByName($networkName)) {
            $userName = "{$networkName}:{$network->getIdentity()}";
        } else if ($email = $user->getEmail()) {
            $userName = $email->getValue();
        } else {
            throw new RuntimeException('Auth error');
        }

        return new UserIdentity(
            $user->getId()->toString(),
            $userName,
            $user->getRole()->getValue(),
            $user->getStatus()->getValue(),
            $user->getPasswordHash(),
        );
    }
}
