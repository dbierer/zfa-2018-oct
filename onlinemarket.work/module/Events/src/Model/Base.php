<?php
namespace Events\Model;

use Events\Entity\EventEntityInterface;
use Zend\EventManager\EventManager;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Hydrator\ObjectProperty;
use Psr\Container\ContainerInterface;
//*** DELEGATING HYDRATOR LAB: add the correct "use" statements

class Base implements TableGatewayInterface
{
    public static $tableName;
    protected $tableGateway;
    protected $container;
    //*** DELEGATING HYDRATOR LAB: have the base class accept a DelegatingHydrator instance added as the last argument
    public function __construct(Adapter $adapter,
                                EventEntityInterface $entity,
                                ContainerInterface $container)
    {
        //*** DELEGATING HYDRATOR LAB: use the hydrator injected
        $resultSet = new HydratingResultSet(new ObjectProperty(), $entity);
        // sets up TableGateway to produce instances of get_class($entity) when queried
        $this->tableGateway = new TableGateway(static::$tableName, $adapter, NULL, $resultSet);
        $this->container = $container;
    }
}
