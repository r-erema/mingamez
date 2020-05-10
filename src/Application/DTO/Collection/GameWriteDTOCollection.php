<?php

declare(strict_types=1);

use App\Application\DTO\GameWriteDTO;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method  GameWriteDTO[] getIterator()
 */
class GameWriteDTOCollection extends ArrayCollection
{
    public function __construct(GameWriteDTO ...$elements)
    {
        parent::__construct($elements);
    }
}