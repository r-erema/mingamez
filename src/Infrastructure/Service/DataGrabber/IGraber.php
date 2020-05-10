<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\DataGrabber;

use App\Application\DTO\Collection\GameWriteDTOCollection;

interface IGraber
{
    public function grabGames(): GameWriteDTOCollection;
}
