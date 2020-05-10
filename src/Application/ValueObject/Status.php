<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

use Webmozart\Assert\Assert;

class Status
{

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUSES = [
        self::STATUS_WAIT => self::STATUS_WAIT,
        self::STATUS_ACTIVE => self::STATUS_ACTIVE
    ];

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::STATUSES);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
