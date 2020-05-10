<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\DataGrabber;

use GameWriteDTOCollection;

interface IGraber
{
    public function grabGames(): GameWriteDTOCollection;
}
