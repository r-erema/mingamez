<?php

declare(strict_types=1);

namespace App\Application\DTO\WriteNew;

use App\Application\DTO\Collection\GenreDTOCollection;
use App\Application\DTO\Collection\ImageDTOCollection;
use App\Application\ValueObject\Url;
use DateTimeImmutable;

class GameDTO
{
    private string $sourceId;
    private string $name;
    private int $rating;
    private DateTimeImmutable $releaseDate;
    private string $description;
    private Url $url;
    private GenreDTOCollection $genres;
    private ImageDTOCollection $images;

    public function __construct(
        string $sourceId,
        string $name,
        int $rating,
        DateTimeImmutable $releaseDate,
        string $description,
        Url $url,
        GenreDTOCollection $genres,
        ImageDTOCollection $images
    ) {
        $this->sourceId = $sourceId;
        $this->name = $name;
        $this->rating = $rating;
        $this->releaseDate = $releaseDate;
        $this->description = $description;
        $this->url = $url;
        $this->genres = $genres;
        $this->images = $images;
    }

    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReleaseDate(): DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getGenres(): GenreDTOCollection
    {
        return $this->genres;
    }

    public function getImages(): ImageDTOCollection
    {
        return $this->images;
    }


}
