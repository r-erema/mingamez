<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use App\Application\ValueObject\ResetToken;
use App\Domain\Entity\Network;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */

$metadata->setPrimaryTable(['name' => 'users']);

$metadata->mapField([
    'id' => true,
    'fieldName' => 'id',
    'type' => 'uuid_type'
]);

$metadata->mapField([
    'fieldName' => 'date',
    'type' => 'datetime_immutable'
]);

$metadata->mapField([
    'fieldName' => 'email',
    'type' => 'email_type',
    'length' => 254,
    'unique' => true,
    'nullable' => true
]);

$metadata->mapField([
    'fieldName' => 'passwordHash',
    'type' => 'string',
    'length' => 100,
    'nullable' => true
]);

$metadata->mapField([
    'fieldName' => 'confirmToken',
    'type' => 'string',
    'length' => 100,
    'nullable' => true
]);

$metadata->mapEmbedded([
    'fieldName' => 'resetToken',
    'class' => ResetToken::class,
    'columnPrefix' => 'reset_token_',
    'nullable' => true
]);

$metadata->mapField([
    'fieldName' => 'status',
    'type' => 'status_type'
]);

$metadata->mapField([
    'fieldName' => 'role',
    'type' => 'role_type'
]);

$metadata->mapOneToMany([
    'fieldName' => 'networks',
    'targetEntity' => Network::class,
    'mappedBy' => 'user',
    'cascade' => ['persist'],
    'orphanRemoval' => true
]);
