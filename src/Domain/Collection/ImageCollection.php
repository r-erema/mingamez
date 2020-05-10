<?php

declare(strict_types=1);

namespace App\Domain\Collection;

use App\Application\ValueObject\ImageType;
use App\Domain\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class ImageCollection extends ArrayCollection
{

    public function __construct(array $elements = [])
    {
        Assert::allIsInstanceOf($elements, Image::class);
        parent::__construct($elements);
    }

    public function add($game): self
    {
        Assert::isInstanceOf($game, Image::class);
        parent::add($game);
        return $this;
    }

    public function filterPreviews(): ImageCollection
    {
        return $this->filter(fn(Image $image): bool => $image->getType()->getValue() === ImageType::TYPE_PREVIEW);
    }

    public function filterScreenshots(): ImageCollection
    {
        return $this->filter(fn(Image $image): bool => $image->getType()->getValue() === ImageType::TYPE_SCREENSHOT);
    }

}
