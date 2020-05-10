<?php

declare(strict_types=1);

namespace App\Application\Repository;

interface IFlusher
{
    public function flush(): void;
}
