<?php

declare(strict_types=1);

namespace App\Application\DTO;

use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class GenreDTO
{

    private UuidInterface $id;
    private string $name;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

}
