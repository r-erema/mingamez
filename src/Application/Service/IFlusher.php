<?php

declare(strict_types=1);

namespace App\Application\Service;

interface IFlusher
{
    public function flush(): void;
}
