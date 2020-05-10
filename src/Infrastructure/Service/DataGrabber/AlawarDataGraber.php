<?php

namespace App\Infrastructure\Service\DataGrabber;

use App\Application\DTO\GameWriteDTO;
use App\Application\DTO\GenreDTO;
use App\Application\DTO\ImageDTO;
use App\Application\ValueObject\ImageType;
use App\Application\ValueObject\Url;
use DateTimeImmutable;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use GameWriteDTOCollection;
use GenreDTOCollection;
use ImageDTOCollection;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AlawarDataGraber implements IGraber
{

    private const XML_URL = 'http://export.alawar.ru/games_agsn_xml.php?pid=40516&lang=ru';

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return GameWriteDTOCollection
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function grabGames(): GameWriteDTOCollection
    {
        $response = $this->httpClient->request('GET', self::XML_URL);
        $dom = new DOMDocument();
        $dom->loadXML($response->getContent());
        $xPath = new DOMXPath($dom);
        $genres = self::retrieveGenres($xPath);
        $ratings = self::retrieveRatings($xPath);
        $games = self::retrieveGames($xPath);

        $gameDTOs = [];
        foreach ($games as $game) {

            $found = array_filter($ratings, fn(array $rating): bool => $rating['native_game_id'] === $game['native_game_id']);
            $rating = array_shift($found);

            $gameGenres = array_filter($genres, fn(array $genre): bool => in_array($game['native_game_id'], $genre['native_game_id'], true));
            $genresDTOs = [];
            foreach ($gameGenres as $genre) {
                $genresDTOs[] = new GenreDTO($genre['name']);
            }

            $imagesDTOs = [];
            foreach ($game['images'] as $image) {
                if (($url = filter_var($image['url'], FILTER_VALIDATE_URL)) === false) {
                    continue;
                }
                $imagesDTOs[] = new ImageDTO(new Url($url), new ImageType($image['type']));
            }

            $gameDTOs[] = new GameWriteDTO(
                $game['native_game_id'],
                $game['name'],
                (int) $rating['rating_value'],
                $game['release_date'],
                $game['description'],
                $game['url'],
                new GenreDTOCollection($genresDTOs),
                new ImageDTOCollection($imagesDTOs)
            );
        }

        return new GameWriteDTOCollection(...$gameDTOs);
    }

    private static function retrieveGenres(DOMXPath $xPath): array {
        $genresDOMList = $xPath->query('//Catalog[@Code=\'casualpcgames\']//Dictionary[@Code=\'Genre\']/*');
        $genresBucket = [];
        foreach ($genresDOMList as $genreDOMElement) { /** @var DOMElement $genreDOMElement */
            $name = $genreDOMElement->getElementsByTagName('Name')->item(0)->nodeValue;
            $gamesDOMNodes = $genreDOMElement->getElementsByTagName('Elements')->item(0)->childNodes;
            $gameIds = [];
            foreach ($gamesDOMNodes as $gameIdDOMElement) {
                $gameIds[] = $gameIdDOMElement->getAttribute('ID');
            }
            $genresBucket[] = [
                'name' => $name,
                'native_game_id' => $gameIds,
            ];
        }
        return $genresBucket;
    }

    private static function retrieveRatings(DOMXPath $xPath): array {
        $ratingsDOMList = $xPath->query('//Catalog[@Code=\'casualpcgames\']//Dictionary[@Code=\'Rating\']/*/Elements/*');
        $ratingBucket = [];
        foreach ($ratingsDOMList as $ratingDOMElement) {
            $ratingBucket[] = [
                'native_game_id' => $ratingDOMElement->getAttribute('ID'),
                'rating_value' => $ratingDOMElement->getAttribute('Value'),
            ];
        }
        return $ratingBucket;
    }

    /**
     * @param DOMXPath $xPath
     * @return array
     * @throws Exception
     */
    private static function retrieveGames(DOMXPath $xPath): array {
        $gamesDOMList = $xPath->query('//ALAWAR_EXPORT/Languages/Language[@Code=\'ru\']/*/Catalog[@Code=\'casualpcgames\']/Items/Item');
        $gamesBucket = [];
        foreach ($gamesDOMList as $gameDOMElement) {
            $game['native_game_id'] = $gameDOMElement->getAttribute('ID');
            $game['name'] = $gameDOMElement->getElementsByTagName('Name')->item(0)->textContent;
            $propertiesDOMNodes = $gameDOMElement->getElementsByTagName('Properties')->item(0)->childNodes;
            foreach ($propertiesDOMNodes as $propertyDOMElement) {/** @var DOMElement $propertyDOMElement */
                switch ($propertyDOMElement->getAttribute('Code')) {
                    case 'ReleaseDate' : {
                        $game['release_date'] = new DateTimeImmutable($propertyDOMElement->textContent); break;
                    }
                    case 'Description450' : {
                        $game['description'] = $propertyDOMElement->textContent; break;
                    }
                }
            }

            $imagesDOMList = $gameDOMElement->getElementsByTagName('Images')->item(0);
            $images = [];
            if ($imagesDOMList) {
                foreach ($imagesDOMList->childNodes as $imageDOMElement) {
                    if ($link = $imageDOMElement->textContent) {
                        $images[] = [
                            'url' => $link,
                            'type' => ImageType::TYPE_PREVIEW
                        ];
                    }
                }
            }

            $screenshotsDOMList = $gameDOMElement->getElementsByTagName('Screenshots')->item(0);
            if ($screenshotsDOMList) {
                foreach ($screenshotsDOMList->childNodes as $screenshotDOMElement) {
                    if ($link = $screenshotDOMElement->textContent) {
                        $images[] = [
                            'url' => $link,
                            'type' => ImageType::TYPE_SCREENSHOT
                        ];
                    }
                }
            }
            $filesDOMList = $gameDOMElement->getElementsByTagName('Files')->item(0);
            if ($filesDOMList) {
                foreach ($filesDOMList->childNodes as $fileDOMElement) {
                    if ($link = $fileDOMElement->textContent) {
                        $game['url'] = new Url($fileDOMElement->textContent);
                    }
                }
            }

            $game['images'] = $images;
            $gamesBucket[] = $game;
        }
        return $gamesBucket;
    }

}
