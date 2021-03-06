<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Application\ValueObject\ImageType;
use App\Application\ValueObject\Url;
use Ramsey\Uuid\UuidInterface;

class ImageDTO
{
    private UuidInterface $id;
    private Url $url;
    private ImageType $type;

    public function __construct(UuidInterface $id, Url $url, ImageType $type)
    {
        $this->id = $id;
        $this->url = $url;
        $this->type = $type;
    }
    public function getId(): UuidInterface
    {
        return $this->id;
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
