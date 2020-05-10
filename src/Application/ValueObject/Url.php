<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

use Webmozart\Assert\Assert;

class Url
{

    private string $value;

    public function __construct(string $value)
    {
        $value = (string) mb_strtolower($value);
        Assert::notFalse(filter_var($value, FILTER_VALIDATE_URL), "Invalid url: {$value}");
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
