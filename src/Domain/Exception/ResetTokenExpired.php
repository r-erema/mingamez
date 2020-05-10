<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

class ResetTokenExpired extends RuntimeException
{
    public function __construct()
    {
        parent::__construct();
    }
}
