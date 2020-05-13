<?php

declare(strict_types=1);

namespace App\Infrastructure\AutoMapper;

use App\Application\DTO\Collection\GameDTOCollection;
use App\Application\DTO\Collection\GenreDTOCollection;
use App\Application\DTO\Collection\ImageDTOCollection;
use App\Application\DTO\GameDTO;
use App\Application\DTO\GenreDTO;
use App\Application\DTO\ImageDTO;
use App\Domain\Collection\GameCollection;
use App\Domain\Collection\GenreCollection;
use App\Domain\Collection\ImageCollection;
use App\Domain\Entity\Game;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Image;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\MappingOperation\Operation;

class AutoMapperConfig implements AutoMapperConfiguratorInterface
{

    public function configure(AutoMapperConfigInterface $config): void
    {
        $config
              ->registerMapping(Game::class, GameDTO::class)
              ->forMember('images', Operation::mapArrayTo(ImageDTOCollection::class))
              ->forMember('genres', Operation::mapArrayTo(GenreDTOCollection::class))
              ->beConstructedUsing(fn(Game $game, AutoMapperInterface $mapper): GameDTO => new GameDTO(
                  $game->getId(),
                  $game->getSourceId(),
                  $game->getName(),
                  $game->getRating(),
                  $game->getReleaseDate(),
                  $game->getDescription(),
                  $game->getUrl(),
                  $game->getDistributor(),
                  $mapper->map($game->getImages(), ImageDTOCollection::class),
                  $mapper->map($game->getGenres(), GenreDTOCollection::class),
              ));

        $config
            ->registerMapping(Image::class, ImageDTO::class)
            ->beConstructedUsing(fn(Image $image): ImageDTO => new ImageDTO(
                $image->getId(),
                $image->getUrl(),
                $image->getType(),
            ));

        $config
            ->registerMapping(Genre::class, GenreDTO::class)
            ->beConstructedUsing(fn(Genre $genre): GenreDTO => new GenreDTO(
                $genre->getId(),
                $genre->getName()
            ));

        $config
            ->registerMapping(GameCollection::class, GameDTOCollection::class)
            ->forMember('elements', Operation::mapTo(GameDTO::class));
        $config
            ->registerMapping(ImageCollection::class, ImageDTOCollection::class)
            ->forMember('elements', Operation::mapTo(ImageDTO::class));
        $config
            ->registerMapping(GenreCollection::class, GenreDTOCollection::class)
            ->forMember('elements', Operation::mapTo(GenreDTO::class));

    }

}
