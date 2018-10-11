<?php
namespace Events\Controller;

use Events\Entity\ {Registration, Attendee};
use Events\Traits\ {EventTableTrait, RegTableTrait, AttendeeTableTrait};
use Events\Model\ {EventTable, RegistrationTable, AttendeeTable};
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Filter;

class SignupController extends AbstractActionController
{
    protected $filter;
    use EventTableTrait;
    use RegTableTrait;
    use AttendeeTableTrait;

    public function indexAction()
    {
        $eventId = (int) $this->params('eventId', FALSE);
        if ($eventId) {
            return $this->eventSignup($eventId);
        }
        $events = $this->eventTable->findAll();
        return new ViewModel(array('events' => $events));
    }

    public function thanksAction()
    {
        return new ViewModel();
    }

    protected function eventSignup($eventId)
    {
        if (!$event = $this->eventTable->findById($eventId)) {
            return $this->redirect()->toRoute('events/signup');
        }
		//*** FORMS AND FIELDSETS LAB: send form instance to the view
		$vm = new ViewModel(array('event' => $event));
        $vm->setTemplate('events/signup/form.phtml');
        if ($this->request->isPost()) {
            $this->processForm($this->params()->fromPost(), $eventId);
            $vm->setTemplate('events/signup/thanks.phtml');
        }
        return $vm;
    }

	//*** DATABASE TABLE MODULE RELATIONSHIPS LAB: define this method such that for any given event, registrations and associated attendees are saved
    protected function processForm(array $formData, $eventId)
    {
        $formData = $this->sanitizeData($formData);
        $regId = $this->regTable->save(new Registration($formData['registration']));
        foreach ($formData['ticket'] as $name)
			$this->attendeeTable->save(new Attendee(['registration_id' => $regId, 'name_on_ticket' => $name]));
    }

    protected function sanitizeData(array $data)
    {
        $clean  = array();
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                foreach ($item as $subKey => $subItem) {
                    $clean[$key][$subKey] = $this->filter->filter($subItem);
                }
            } else {
                $clean[$key] = $this->filter->filter($item);
            }
        }
        return $clean;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

}
