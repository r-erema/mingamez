<?php

declare(strict_types=1);

namespace App\Application\ValueObject;

use Webmozart\Assert\Assert;

class ImageType
{

    public const TYPE_PREVIEW = 'preview';
    public const TYPE_SCREENSHOT = 'screenshot';
    public const TYPES = [
        self::TYPE_PREVIEW => self::TYPE_PREVIEW,
        self::TYPE_SCREENSHOT => self::TYPE_SCREENSHOT,
    ];

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::TYPES);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

}
