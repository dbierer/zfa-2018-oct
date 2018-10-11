<?php
namespace Model\Table;

use Model\Entity\User;
use Application\Model\AbstractTable;

class UsersTable extends AbstractTable
{

    public static $tableName = 'users';
    public function findAll()
    {
        return $this->tableGateway->select();
    }
    public function findById($id)
    {
        return $this->tableGateway->select(['id' => $id])->current();
    }
    public function save(User $user)
    {
        $data = $this->tableGateway->getResultSetPrototype()->getHydrator()->extract($user);
        //*** PASSWORD LAB: modify this to use the Login\Security\Password class to hash the password
        $data['password'] = md5($data['password']);
        return $this->tableGateway->insert($data);
    }
}
