<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use App\Domain\Entity\Game;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */

$metadata->setPrimaryTable(['name' => 'images']);

$metadata->mapField([
    'id' => true,
    'fieldName' => 'id',
    'type' => 'uuid_type',
]);

$metadata->mapField([
    'fieldName' => 'url',
    'type' => 'url_type'
]);

$metadata->mapField([
    'fieldName' => 'type',
    'type' => 'image_type'
]);

$metadata->mapManyToOne([
    'fieldName' => 'game',
    'inversedBy' => 'images',
    'targetEntity' => Game::class,
    'cascade' => ['persist'],
    'joinColumns' => [
        [
            'nullable' => false,
            'onDelete' => 'cascade'
        ]
    ]
]);
