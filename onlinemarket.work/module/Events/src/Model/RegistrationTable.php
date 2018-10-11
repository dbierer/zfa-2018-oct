<?php
namespace Events\Model;

use Events\Listener\Event as RegEvent;
use Events\Entity\Attendee;
use Events\Entity\Registration;
use Zend\Db\Sql\ {Sql,Where};

// Table Structure:
/*
CREATE TABLE `registration` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `registration_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8
 */

class RegistrationTable extends Base
{
    public static $tableName = 'registration';
    public function findAllForEvent($eventId)
    {
        return $this->findUsingTwoQueries($eventId);
    }
    public function findRegByEventId($eventId)
    {
        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        $select->from(['r' => self::$tableName])->where(['r.event_id' => $eventId])->order('r.registration_time DESC');
        return $this->tableGateway->selectWith($select);
    }
    protected function findUsingTwoQueries($eventId)
    {
        $final = [];
        $redIds = [];
        $registrations = $this->findRegByEventId($eventId);
        foreach ($registrations as $reg) {
            // the iteration $registrations is "forward-only" which means we need to store it into an array
            $final[$reg->id] = $reg;
        }
        // use Zend\Db\Sql\Sql to pull attendees for list of registrations in registration_id order 
        $attendeeTable = $this->container->get(AttendeeTable::class);
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $where = (new Where())->in('registration_id', array_keys($final));
        $select = $sql->select();
        $select->from(AttendeeTable::$tableName)
               ->order('registration_id ASC')
               ->where($where);
        $attendees = $attendeeTable->tableGateway->selectWith($select);
        // match registrations against attendees
        foreach ($attendees as $attendee) {
			$final[$attendee->registration_id]->attendees[] = $attendee;
		}
        return $final;
    }
    public function save(Registration $reg)
    {
        $hydrator = $this->tableGateway->getResultSetPrototype()->getHydrator();
        $data = $hydrator->extract($reg);
        // need to get rid of this property as it's not a column in the "registration" table
        unset($data['attendees']);
        $this->tableGateway->insert($data);
        return $this->tableGateway->getLastInsertValue();
    }
}
