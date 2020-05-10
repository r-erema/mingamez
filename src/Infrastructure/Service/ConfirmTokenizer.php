<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use Ramsey\Uuid\Uuid;

class ConfirmTokenizer
{
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
