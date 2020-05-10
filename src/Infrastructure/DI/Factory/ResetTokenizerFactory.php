<?php

declare(strict_types=1);

namespace App\Infrastructure\DI\Factory;

use App\Infrastructure\Service\ResetTokenizer;
use DateInterval;
use Exception;

class ResetTokenizerFactory
{
    /**
     * @param string $interval
     * @return ResetTokenizer
     * @throws Exception
     */
    public function create(string $interval): ResetTokenizer
    {
        return new ResetTokenizer(new DateInterval($interval));
    }
}
