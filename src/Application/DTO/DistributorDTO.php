<?php

declare(strict_types=1);

namespace App\Application\DTO;

use Ramsey\Uuid\UuidInterface;

class DistributorDTO
{

    private UuidInterface $id;
    private string $name;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

}
