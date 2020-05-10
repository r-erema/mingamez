<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Application\ValueObject\Url;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UrlType extends StringType
{

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Url ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Url($value) : null;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}
