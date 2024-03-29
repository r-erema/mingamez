<?php

namespace App\Infrastructure\Service\DataGrabber;

use App\Application\DTO\WriteNew\Collection\GameDTOCollection;
use App\Application\DTO\WriteNew\Collection\GenreDTOCollection;
use App\Application\DTO\WriteNew\Collection\ImageDTOCollection;
use App\Application\DTO\WriteNew\GameDTO;
use App\Application\DTO\WriteNew\GenreDTO;
use App\Application\DTO\WriteNew\ImageDTO;
use App\Application\ValueObject\ImageType;
use App\Application\ValueObject\Url;
use DateTimeImmutable;
use Exception;
use JsonException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SteamPayDataGraber implements IGraber
{

    private const PRODUCTS_API_URL = 'https://steampay.com/api/products';
    private const PRODUCT_API_URL_TPL = 'https://steampay.com/api/product/%d';
    private const TOP_PRODUCTS_API_TOP = 'https://steampay.com/api/top';
    private const GAME_URL_POSTFIX = '?agent=mingamez';

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return GameDTOCollection
     * @throws JsonException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    public function grabGames(): GameDTOCollection
    {
        $response = $this->httpClient->request('GET', self::PRODUCTS_API_URL);
        $games = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $response = $this->httpClient->request('GET', self::TOP_PRODUCTS_API_TOP);
        $top50Games = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)['products'];
        $currentRating = 100000;
        foreach ($top50Games as &$game) {
            $game['rating'] = $currentRating;
            $currentRating--;
        }
        unset($game);

        $getRating = static function(int $id) use ($top50Games): int {
            $found = array_filter($top50Games, fn(array $game): bool => $id === $game['id']);
            if ($found) {
                $game = array_shift($found);
                return $game['rating'];
            }
            return 0;
        };

        $result = new GameDTOCollection();
        foreach ($games['products'] as $game) {
            usleep(500);
            $response = $this->httpClient->request('GET', sprintf(self::PRODUCT_API_URL_TPL, $game['id']));
            $game = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $game = $game['product'];

            $genres = new GenreDTOCollection(array_map(fn(string $name): GenreDTO => new GenreDTO($name), $game['genres']));
            $game['image'] = str_replace(' ', '-', $game['image']);
            if (false === filter_var($game['image'], FILTER_VALIDATE_URL)) {
                continue;
            }
            $game['image'] = str_replace('-﻿', '', $game['image']);
            $images = new ImageDTOCollection([new ImageDTO(new Url($game['image']), new ImageType(ImageType::TYPE_PREVIEW))]);
            foreach ($game['screenshots'] as $screenshot) {
                $screenshot['url'] = str_replace(' ', '-', $screenshot['url']);
                $images->add(new ImageDTO(new Url($screenshot['url']), new ImageType(ImageType::TYPE_SCREENSHOT)));
            }

            $gameDTO = new GameDTO(
                (string) $game['id'],
                $game['title'],
                $getRating($game['id']),
                new DateTimeImmutable(self::sanitizeDate($game['release_date'])),
                '',
                new Url(sprintf('%s%s', $game['url'], self::GAME_URL_POSTFIX)),
                $genres,
                $images
            );
            $result->add($gameDTO);
        }

        return $result;
    }

    private static function sanitizeDate(?string $date): string
    {
        if ($date === null) {
            return date('d.m.Y');
        }

        $exploded = explode('.', $date);
        if (count($exploded) === 2) {
            return '01.' . implode('.', $exploded);
        }
        return $date;
    }

}
