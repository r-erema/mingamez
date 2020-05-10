<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Entity\Network;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class NetworkType extends AbstractEnumType
{
    protected static $choices = Network::NETWORKS;
}
