<?php
namespace Model\Table;

use DateTime;
use DateInterval;

use Model\Entity\Listing;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\EventManager;

class ListingsTable extends TableGateway
{
    const TABLE_NAME = 'listings';
    public function findByCategory($category)
    {
        return $this->select(['category' => $category]);
    }
    public function findById($id)
    {
        return $this->select(['listings_id' => $id])->current();
    }
    public function findLatest()
    {
        $select = (new Sql($this->getAdapter()))->select();
        $select->from(self::TABLE_NAME)
               ->order('listings_id desc')
               ->limit(1);
        return $this->selectWith($select)->current();
    }
    public function save(Listing $listing)
    {
        // NOTE: have a look at Model\Hydrator\ListingsHydrator
        //       this class calculates expires date, breaks out city and country, and unsets unwanted fields        
        $data = $this->getResultSetPrototype()->getHydrator()->extract($listing);
        return $this->insert($data);

    }
}
