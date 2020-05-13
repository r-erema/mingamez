<?php

declare(strict_types=1);

namespace App\Application\DTO\WriteNew;

use App\Application\ValueObject\ImageType;
use App\Application\ValueObject\Url;

class ImageDTO
{

    private Url $url;
    private ImageType $type;

    public function __construct(Url $url, ImageType $type)
    {
        $this->url = $url;
        $this->type = $type;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getType(): ImageType
    {
        return $this->type;
    }

}
