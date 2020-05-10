<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use App\Application\ValueObject\ResetToken;
use App\Domain\Entity\Game;
use App\Domain\Entity\Network;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */

$metadata->setPrimaryTable(['name' => 'distributors']);

$metadata->mapField([
    'id' => true,
    'fieldName' => 'id',
    'type' => 'uuid_type'
]);

$metadata->mapField([
    'fieldName' => 'name',
    'type' => 'string',
]);

$metadata->mapOneToMany([
    'fieldName' => 'games',
    'mappedBy' => 'distributor',
    'targetEntity' => Game::class,
    'cascade' => ['persist'],
    'orphanRemoval' => true
]);
