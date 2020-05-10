<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Application\ValueObject\ImageType as Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use Webmozart\Assert\Assert;

class ImageType extends AbstractEnumType
{
    protected static $choices = Type::TYPES;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        Assert::isInstanceOf($value, Type::class);
        return parent::convertToDatabaseValue($value->getValue(), $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Type($value) : null;
    }
}
