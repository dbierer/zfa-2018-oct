<?php
namespace Events\Controller;

use Events\Module;
use Events\Entity\ {Registration, Attendee};
use Events\Traits\ {EventTableTrait, RegTableTrait, AttendeeTableTrait, RegFormTrait};
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
    use RegFormTrait;

    public function indexAction()
    {
        $eventId = (int) $this->params('eventId', FALSE);
        if ($eventId) {
			$this->setBindings($eventId);
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
		$vm = new ViewModel(array('event' => $event, 'regForm' => $this->regForm));
        $vm->setTemplate('events/signup/reg-form.phtml');
        if ($this->request->isPost()) {
            $vm->setTemplate('events/signup/thanks.phtml');
            if ($this->processForm($this->params()->fromPost(), $eventId)) {
				$message = 'Successfully added registration';
			} else {
				$message = 'Sorry! Unable to add registration';
			}
			$vm->setVariable('message', $message);
        }
        return $vm;
    }

	//*** DATABASE TABLE MODULE RELATIONSHIPS LAB: define this method such that for any given event, registrations and associated attendees are saved
    protected function processForm(array $formData, $eventId)
    {
		$result = FALSE;
		$this->setBindings($eventId);
        $this->regForm->setData($formData);
        if ($this->regForm->isValid()) {
			$registration = $this->regForm->getData();
			$result = $regId = $this->regTable->save($registration);
			foreach ($formData['name_on_ticket'] as $name) {
				if ($name) $this->attendeeTable->save(new Attendee(['registration_id' => $regId, 'name_on_ticket' => $name]));
			}
		}
		return $result;
    }

	protected function setBindings($eventId)
	{
		$this->regForm->bind(new Registration(['event_id' => $eventId]));
		for ($x = 0; $x < \Events\Module::MAX_NAMES_PER_TICKET; $x++) {
			$sub = $this->regForm->get('attendee_' . $x);
			$sub->bind(new Attendee());
		}
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
