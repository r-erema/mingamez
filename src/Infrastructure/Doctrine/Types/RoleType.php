<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Application\ValueObject\Role;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use Webmozart\Assert\Assert;

class RoleType extends AbstractEnumType
{
    protected static $choices = Role::ROLES;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        Assert::isInstanceOf($value, Role::class);
        return parent::convertToDatabaseValue($value->getValue(), $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Role($value) : null;
    }
}
