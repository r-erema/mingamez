<?php

declare(strict_types=1);

namespace App\Application\DTO\Collection;

use App\Application\DTO\DistributorDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

/**
 * @method  DistributorDTO[] getIterator()
 */
class DistributorDTOCollection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        Assert::allIsInstanceOf($elements, DistributorDTO::class);
        parent::__construct($elements);
    }

    public function getDistributorById(string $id): DistributorDTO
    {
        return $this
            ->filter(fn(DistributorDTO $distributorDTO) => $distributorDTO->getId()->toString() === $id)
            ->first();
    }

    public function getDistributorByName(string $name): DistributorDTO
    {
        return $this
            ->filter(fn(DistributorDTO $distributorDTO) => $distributorDTO->getName() === $name)
            ->first();
    }

    public function getIds(): array
    {
        return array_map(
            fn (DistributorDTO $distributorDTO): string => $distributorDTO->getId()->toString(),
            $this->toArray()
        );
    }

}
