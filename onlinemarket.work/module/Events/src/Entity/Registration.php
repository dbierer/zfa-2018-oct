<?php
namespace Events\Entity;

use Zend\Form\Annotation as ABC;

/**
 * @ABC\Name("registration")
 * @ABC\Hydrator("Zend\Hydrator\ObjectProperty")
 */
class Registration extends Base
{
	/**
	 * @ABC\Exclude()
     */
    public $event_id;
	/**
	 * @ABC\Attributes({"type":"text","placeholder":"First Name","class":"input-xlarge"})
     * @ABC\Options({"label":"First Name:"})
     * @ABC\Filter({"name":"StringTrim"})
     * @ABC\Filter({"name":"StripTags"})
     */
    public $first_name;
	/**
	 * @ABC\Attributes({"type":"text","placeholder":"Last Name","class":"input-xlarge"})
     * @ABC\Options({"label":"Last Name:"})
     * @ABC\Filter({"name":"StringTrim"})
     * @ABC\Filter({"name":"StripTags"})
     */
    public $last_name;
	/**
	 * @ABC\Exclude()
     */
    public $registration_time;
	/**
	 * @ABC\Exclude()
     */
    public $attendees = [];
}
