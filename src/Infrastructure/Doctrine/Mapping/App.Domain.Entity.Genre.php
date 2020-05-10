<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use App\Application\ValueObject\ResetToken;
use App\Domain\Entity\Game;
use App\Domain\Entity\Network;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */
$metadata->setPrimaryTable(['name' => 'genres']);

$metadata->mapField([
    'id' => true,
    'fieldName' => 'id',
    'type' => 'uuid_type'
]);

$metadata->mapField([
    'fieldName' => 'name',
    'type' => 'string',
    'unique' => true
]);

$metadata->mapManyToMany([
    'fieldName' => 'games',
    'targetEntity' => Game::class,
    'mappedBy' => 'genres',
    'cascade' => ['persist'],
    'orphanRemoval' => true
]);
