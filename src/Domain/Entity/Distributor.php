<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Collection\GameCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Distributor
{

    private UuidInterface $id;
    private string $name;
    private Collection $games;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->games = new GameCollection();
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
