<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Application\ValueObject\Status;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use Webmozart\Assert\Assert;

class StatusType extends AbstractEnumType
{
    protected static $choices = Status::STATUSES;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        Assert::isInstanceOf($value, Status::class);
        return parent::convertToDatabaseValue($value->getValue(), $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Status($value) : null;
    }
}
