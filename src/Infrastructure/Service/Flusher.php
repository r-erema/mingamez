<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Service\IFlusher;
use Doctrine\ORM\EntityManagerInterface;

class Flusher implements IFlusher
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
