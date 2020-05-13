<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Application\DTO\Collection\GenreDTOCollection;
use App\Application\DTO\Collection\ImageDTOCollection;
use App\Application\ValueObject\Url;
use App\Domain\Entity\Distributor;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class GameDTO
{

    private UuidInterface $id;
    private string $sourceId;
    private string $name;
    private int $rating;
    private DateTimeImmutable $releaseDate;
    private string $description;
    private Url $url;
    private Distributor $distributor;
    private ImageDTOCollection $images;
    private GenreDTOCollection $genres;

    public function __construct(
        UuidInterface $id,
        string $sourceId,
        string $name,
        int $rating,
        DateTimeImmutable $releaseDate,
        string $description,
        Url $url,
        Distributor $distributor,
        ImageDTOCollection $images,
        GenreDTOCollection $genres
    ) {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->name = $name;
        $this->rating = $rating;
        $this->releaseDate = $releaseDate;
        $this->description = $description;
        $this->url = $url;
        $this->distributor = $distributor;
        $this->images = $images;
        $this->genres = $genres;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
    public function getSourceId(): string
    {
        return $this->sourceId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getRating(): int
    {
        return $this->rating;
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
    public function getDistributor(): Distributor
    {
        return $this->distributor;
    }
    public function getImages(): ImageDTOCollection
    {
        return $this->images;
    }
    public function getGenres(): GenreDTOCollection
    {
        return $this->genres;
    }

}
