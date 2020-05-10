<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::email($value);
        $this->value = (string) mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
