<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(string $className, array $searchParams = [])
    {
        $searchStr = $searchParams ? implode(',', $searchParams) : '';
        parent::__construct(sprintf('Entity `%s` not found, search params: %s', $className, $searchStr));
    }
}
