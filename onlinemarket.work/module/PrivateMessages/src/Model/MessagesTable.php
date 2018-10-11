<?php
namespace PrivateMessages\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Hydrator\ClassMethods;

class MessagesTable
{
    public static $tableName = 'messages';
    protected $hydrator;
    protected $tableGateway;
    public function __construct(Adapter $adapter)
    {
		$this->hydrator = new ClassMethods();
        $this->setTableGateway($adapter);
    }
    public function setTableGateway(Adapter $adapter)
    {
        $hydroResultSet = new HydratingResultSet($this->hydrator, new Message());
        $this->tableGateway = new TableGateway(static::$tableName, $adapter, NULL, $hydroResultSet);
    }
    public function findMessagesSent($email)
    {
        return $this->tableGateway->select(['from_email' => $email]);
    }
    public function findMessagesReceived($email)
    {
        return $this->tableGateway->select(['to_email' => $email]);
    }
    public function save(Message $message)
    {
        $message->setDateTime(date('Y-m-d H:i:s'));
        $data = $this->hydrator->extract($message);
        \Zend\Debug\Debug::dump($data, __METHOD__);
        return $this->tableGateway->insert($data);
    }
}
