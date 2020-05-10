<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use App\Domain\Entity\Distributor;
use App\Domain\Entity\Genre;
use App\Domain\Entity\Image;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */

$metadata->setPrimaryTable(['name' => 'games']);

$metadata->mapField([
    'id' => true,
    'fieldName' => 'id',
    'type' => 'uuid_type'
]);

$metadata->mapField([
    'fieldName' => 'sourceId',
    'type' => 'string',
    'length' => 50,
]);

$metadata->mapField([
    'fieldName' => 'name',
    'type' => 'string',
]);

$metadata->mapField([
    'fieldName' => 'rating',
    'type' => 'integer'
]);

$metadata->mapField([
    'fieldName' => 'releaseDate',
    'type' => 'datetime_immutable'
]);

$metadata->mapField([
    'fieldName' => 'description',
    'type' => 'string',
    'length' => 1000
]);

$metadata->mapField([
    'fieldName' => 'url',
    'type' => 'url_type',
]);

$metadata->mapManyToOne([
    'fieldName' => 'distributor',
    'inversedBy' => 'games',
    'targetEntity' => Distributor::class,
    'cascade' => ['persist'],
    'joinColumns' => [
        [
            'nullable' => false,
            'onDelete' => 'cascade'
        ]
    ]
]);

$metadata->mapOneToMany([
    'fieldName' => 'images',
    'targetEntity' => Image::class,
    'mappedBy' => 'game',
    'cascade' => ['persist'],
    'orphanRemoval' => true
]);

$metadata->mapManyToMany([
    'fieldName' => 'genres',
    'targetEntity' => Genre::class,
    'inversedBy' => 'games',
    'cascade' => ['persist'],
    'joinTable' => [
        'name' => 'games_genres'
    ]
]);
