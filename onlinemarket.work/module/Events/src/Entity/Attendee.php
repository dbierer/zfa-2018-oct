<?php
namespace Events\Entity;

use Zend\Form\Annotation as ABC;

/**
 * @ABC\Name("attendee")
 * @ABC\Hydrator("Zend\Hydrator\ObjectProperty")
 */
class Attendee extends Base
{
	/**
	 * @ABC\Exclude()
     */
    public $registration_id;
	/**
	 * @ABC\Name("name_on_ticket[]")
	 * @ABC\Attributes({"type":"text","placeholder":"Name on Ticket","class":"input-xlarge"})
     * @ABC\Options({"label":"Name:"})
     * @ABC\Filter({"name":"StringTrim"})
     * @ABC\Filter({"name":"StripTags"})
     */
    public $name_on_ticket;
}
