<?php

declare(strict_types=1);

namespace App\Application\DTO\WriteNew\Collection;

use App\Application\DTO\WriteNew\ImageDTO;
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
}
