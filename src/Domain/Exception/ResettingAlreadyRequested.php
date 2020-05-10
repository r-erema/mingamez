<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

class ResettingAlreadyRequested extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Reset request already sent');
    }
}
