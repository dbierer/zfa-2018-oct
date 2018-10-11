<?php
namespace Model\Table\Factory;

use Model\Entity\Listing;
use Model\Table\ListingsTable;
use Interop\Container\ContainerInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Hydrator\Reflection;

class ListingsTableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get('model-primary-adapter');
        $entity  = new Listing();
        $hydrator = new Reflection();
        $hydrator = $container->get('model-listings-hydrator');
        $resultSet = new HydratingResultSet($hydrator, $entity);
        return new ListingsTable(ListingsTable::TABLE_NAME, $adapter, NULL, $resultSet);
    }
}
