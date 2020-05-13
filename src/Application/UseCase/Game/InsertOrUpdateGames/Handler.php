<?php

declare(strict_types=1);

namespace App\Application\UseCase\Game\InsertOrUpdateGames;

use App\Application\DTO\Collection\GameDTOCollection;
use App\Application\DTO\GameDTO;
use App\Application\DTO\GenreDTO;
use App\Application\Repository\IDistributorRepository;
use App\Application\Repository\IGameRepository;
use App\Application\Repository\IGenreRepository;
use App\Application\Service\IFlusher;
use App\Domain\Collection\ImageCollection;
use App\Domain\Entity\Distributor;
use App\Domain\Entity\Game;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Image;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{
    private IGameRepository $games;
    private IDistributorRepository $distributors;
    private IGenreRepository $genres;
    private IFlusher $flusher;

    public function __construct(
        IGameRepository $games,
        IDistributorRepository $distributors,
        IGenreRepository $genres,
        IFlusher $flusher
    ) {
        $this->games = $games;
        $this->distributors = $distributors;
        $this->genres = $genres;
        $this->flusher = $flusher;
    }


    public function __invoke(Command $command)
    {
        $distributor = $this->distributors->findOneBy(['name' => $command->getDistributor()->getName()]);
        if ($distributor === null) {
            $distributor = new Distributor(Uuid::uuid4(), $command->getDistributor()->getName());
        }

        $this->syncGenres($command->getGames());

        foreach ($command->getGames() as $gameDTO) {
            $game = $this->getUpdatedGame($gameDTO, $distributor);
            $this->games->add($game);
        }
        $this->flusher->flush();
    }

    private function getUpdatedGame(GameDTO $gameDTO, Distributor $distributor): Game {
        $game = $this->games->findOneBy([
            'sourceId' => $gameDTO->getSourceId(),
            'distributor' => $distributor
        ]);
        if ($game === null) {
            $game = new Game(
                Uuid::uuid4(),
                $gameDTO->getSourceId(),
                $gameDTO->getName(),
                $gameDTO->getRating(),
                $gameDTO->getReleaseDate(),
                $gameDTO->getDescription(),
                $gameDTO->getUrl(),
                $distributor
            );
        } else {
            $game->setName($gameDTO->getName())
                 ->setRating($gameDTO->getRating())
                 ->setReleaseDate($gameDTO->getReleaseDate())
                 ->setDescription($gameDTO->getDescription())
                 ->setUrl($gameDTO->getUrl());
        }

        $images = new ImageCollection();
        foreach ($gameDTO->getImages() as $imageDTO) {
            $images->add(new Image(Uuid::uuid4(), $imageDTO->getUrl(), $imageDTO->getType(), $game));
        }
        $game->setImages($images);

        $allGenres = $this->genres->findAll();
        $gameGenresNames = array_map(
            fn(GenreDTO $genreDTO): string => $genreDTO->getName(),
            $gameDTO->getGenres()->toArray()
        );
        $genres = $allGenres->filter(fn(Genre $genre) => in_array($genre->getName(), $gameGenresNames, true));
        $game->setGenres($genres);

        return $game;
    }

    public function syncGenres(GameDTOCollection $games): void
    {
        $existedGenreNames = array_map(fn(Genre $genre): string => $genre->getName(), $this->genres->findAll()->toArray());
        $genreNamesToPersist = [];
        foreach ($games as $gameDTO) {
            $newGenreDTOs = $gameDTO
                ->getGenres()
                ->filter(fn(GenreDTO $genreDTO): bool => !in_array($genreDTO->getName(), $existedGenreNames, true));
            $genreNamesToPersist[] = array_map(fn(GenreDTO $genreDTO): string => $genreDTO->getName(), $newGenreDTOs->toArray());
        }
        $genreNamesToPersist = array_unique(array_merge(...$genreNamesToPersist));
        foreach ($genreNamesToPersist as $genreName) {
            $genre = new Genre(Uuid::uuid4(), $genreName);
            $this->genres->add($genre);
        }
        $this->flusher->flush();
    }

}
