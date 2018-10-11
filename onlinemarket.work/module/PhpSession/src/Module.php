<?php
namespace PhpSession;

use Zend\Mvc\MvcEvent;
use Zend\Session\ {SessionManager, SessionConfig, Container};
use Zend\Session\Storage\SessionArrayStorage;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $em = $e->getApplication()->getEventManager();
        //*** SESSION LAB: attach a listener which starts the session using the constructed session manager
    }
    public function startSession(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $sm->get(SessionManager::class)->start();
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
				//*** SESSION LAB: define the logic to build a session manager instance
                SessionManager::class => function ()
                {
                    $sessionManager = new SessionManager();
					//*** SESSION LAB: set storage to SessionArrayStorage
					//*** SESSION LAB: set this session manager as a default for all session containers
                    return $sessionManager;
                },
            ],
        ];
    }
}
