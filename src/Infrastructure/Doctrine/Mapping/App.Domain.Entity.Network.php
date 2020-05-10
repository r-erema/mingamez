<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use App\Domain\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */

$metadata->setPrimaryTable(['name' => 'networks']);

$metadata->mapField([
    'id' => true,
    'fieldName' => 'id',
    'type' => 'uuid_type',
]);

$metadata->mapField([
    'fieldName' => 'name',
    'type' => 'network_type'
]);

$metadata->mapField([
    'fieldName' => 'identity',
    'type' => 'string'
]);

$metadata->mapManyToOne([
    'fieldName' => 'user',
    'inversedBy' => 'networks',
    'targetEntity' => User::class,
    'cascade' => ['persist'],
    'joinColumns' => [
        [
            'nullable' => false,
            'onDelete' => 'cascade'
        ]
    ]
]);
