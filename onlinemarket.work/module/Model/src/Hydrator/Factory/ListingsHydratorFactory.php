<?php
namespace Model\Hydrator\Factory;

use Model\Hydrator\ListingsHydrator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ListingsHydratorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ListingsHydrator();
    }
}
