<?php

declare(strict_types=1);

namespace App\Application\DTO\Collection;

use App\Application\DTO\ImageDTO;
use App\Application\ValueObject\ImageType;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

/**
 * @method ImageDTO[] getIterator()
 */
class ImageDTOCollection extends ArrayCollection
{
    public function __construct(array $images = [])
    {
        Assert::allIsInstanceOf($images, ImageDTO::class);
        parent::__construct($images);
    }

    public function filterScreenshots(): ImageDTOCollection
    {
        return $this->filter(fn(ImageDTO $image): bool => $image->getType()->getValue() === ImageType::TYPE_SCREENSHOT);
    }

    public function filterPreviews(): ImageDTOCollection
    {
        return $this->filter(fn(ImageDTO $image): bool => $image->getType()->getValue() === ImageType::TYPE_PREVIEW);
    }

    public function filterPreviewsByWidth(int $width):  ImageDTOCollection
    {
        return $this->filter(fn(ImageDTO $image): bool =>
            $image->getType()->getValue() === ImageType::TYPE_PREVIEW
            && getimagesize($image->getUrl()->getValue())[0] >= $width
        );
    }

}
