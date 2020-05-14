<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig;

use App\Application\DTO\GameDTO;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MingamezExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('rating', [$this, 'computeRating'], ['is_safe' => ['html']]),
        ];
    }

    public function computeRating(float $rating): string
    {
        if ($rating > 90000) {
            return str_repeat('&#9733; ', 5);
        }

        if ($rating < 90000 && $rating > 50000) {
            return str_repeat('&#9733; ', 4);
        }

        return str_repeat('&#9733; ', 3);
    }

}
