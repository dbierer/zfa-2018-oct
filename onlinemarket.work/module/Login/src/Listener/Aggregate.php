<?php
namespace Login\Listener;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\ {AbstractListenerAggregate,EventManagerInterface};

class Aggregate extends AbstractListenerAggregate
{

    //*** AUTHENTICATION LAB: attach "injectAuthService" as a listener to the MVC dispatch event using a wildcard identifier
    public function attach(EventManagerInterface $e, $priority = 100)
    {
        $shared = $e->getSharedManager();
        $this->listeners[] = $shared->attach('*', MvcEvent::EVENT_DISPATCH, [$this, 'injectAuthService']);
    }
    public function injectAuthService(MvcEvent $e)
    {
        $layout = $e->getViewModel();
        //*** AUTHENTICATION LAB: use service container to retrieve the auth service
        $sm = $e->getApplication()->getServiceManager();
        $authService = $sm->get('login-auth-service');
        //*** AUTHENTICATION LAB: inject auth service into layout
        $layout->setVariable('authService', $authService);
    }
}
