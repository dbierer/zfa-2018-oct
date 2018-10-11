<?php
namespace Events\Controller;

use Events\Traits\EventTableTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{

    use EventTableTrait;

    public function indexAction()
    {
        $eventId = $this->params()->fromRoute('eventId', FALSE);
        if ($eventId) {
			//*** DATABASE TABLE MODULE RELATIONSHIPS LAB: use any of the approaches covered in the slides to provide a list of registrations and associated attendees for a given event
        } else {
            $events = $this->eventTable->findAll();
            $viewModel = new ViewModel(['events' => $events]);
        }
        return $viewModel;
    }

}
