<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

class NetworkAlreadyAttached extends RuntimeException
{
    public function __construct()
    {
        parent::__construct();
    }
}
