<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Application\ValueObject\Url;
use App\Domain\Collection\GenreCollection;
use App\Domain\Collection\ImageCollection;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class Game
{

    private UuidInterface $id;
    private string $sourceId;
    private string $name;
    private int $rating;
    private DateTimeImmutable $releaseDate;
    private string $description;
    private Url $url;
    private Distributor $distributor;
    private Collection $images;
    private Collection $genres;

    public function __construct(
        UuidInterface $id,
        string $sourceId,
        string $name,
        int $rating,
        DateTimeImmutable $releaseDate,
        string $description,
        Url $url,
        Distributor $distributor
    ) {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->name = $name;
        $this->rating = $rating;
        $this->releaseDate = $releaseDate;
        $this->description = $description;
        $this->url = $url;
        $this->distributor = $distributor;
        $this->genres = new GenreCollection();
        $this->images = new ImageCollection();
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
    public function getImages(): ImageCollection
    {
        if (!$this->images instanceof ImageCollection) {
            $this->images = new ImageCollection($this->images->toArray());
        }
        return $this->images;
    }
    public function getGenres(): GenreCollection
    {
        if (!$this->genres instanceof GenreCollection) {
            $this->genres = new GenreCollection($this->genres->toArray());
        }
        return $this->genres;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function setRating(int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }
    public function setReleaseDate(DateTimeImmutable $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function setUrl(Url $url): self
    {
        $this->url = $url;
        return $this;
    }
    public function setImages(ImageCollection $images): self
    {
        $this->images = $images;
        return $this;
    }
    public function setGenres(GenreCollection $genres): self
    {
        $this->genres = $genres;
        return $this;
    }

}
