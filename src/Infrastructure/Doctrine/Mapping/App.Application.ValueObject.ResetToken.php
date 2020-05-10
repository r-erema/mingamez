<?php /** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */

$metadata->isEmbeddedClass = true;

$metadata->mapField([
    'fieldName' => 'token',
    'type' => 'string',
    'length' => 100,
    'nullable' => true
]);

$metadata->mapField([
    'fieldName' => 'expires',
    'type' => 'datetime_immutable',
    'nullable' => true
]);
