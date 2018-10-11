<?php
namespace Events\Model;

use Zend\Db\Adapter\Adapter;
use Events\Entity\EventEntityInterface;
use Psr\Container\ContainerInterface;

interface TableGatewayInterface
{
    public function __construct(Adapter $adapter,
                                EventEntityInterface $entity,
                                ContainerInterface $container);
}
