<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\ValueObject\ResetToken;
use DateInterval;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class ResetTokenizer
{
    private DateInterval $interval;

    public function __construct(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function generate(): ResetToken
    {
        return new ResetToken(
            Uuid::uuid4()->toString(),
            (new DateTimeImmutable())->add($this->interval)
        );
    }
}
