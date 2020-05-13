<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\DataGrabber;

use App\Application\DTO\Collection\GameDTOCollection;

interface IGraber
{
    public function grabGames(): GameDTOCollection;
}
