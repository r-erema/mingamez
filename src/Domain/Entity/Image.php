<?php

namespace App\Domain\Entity;

use App\Application\ValueObject\ImageType;
use App\Application\ValueObject\Url;
use Ramsey\Uuid\UuidInterface;

class Image
{

    private UuidInterface $id;
    private Url $url;
    private ImageType $type;
    private Game $game;

    public function __construct(UuidInterface $id, Url $url, ImageType $type, Game $game)
    {
        $this->id = $id;
        $this->url = $url;
        $this->type = $type;
        $this->game = $game;
    }

    public function getType(): ImageType
    {
        return $this->type;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

}
