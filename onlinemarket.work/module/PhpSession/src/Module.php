<?php
namespace PhpSession;

use Zend\Mvc\MvcEvent;
//*** SESSION LAB: add the appropriate "use" statements
use Zend\Session\ {SessionManager, SessionConfig, Container};
use Zend\Session\Storage\SessionArrayStorage;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function onBootstrap(MvcEvent $e)
    {
        $em = $e->getApplication()->getEventManager();
        //*** SESSION LAB: attach a listener which starts the session using the constructed session manager
        $em->attach(MvcEvent::EVENT_DISPATCH, [$this, 'startSession'], 9999);
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
                SessionManager::class => function($container) {
					$storage = new SessionArrayStorage();
					$storage->init();
					$config  = new SessionConfig();
					//*** SESSION LAB: set storage to SessionStorage
                    $sessionManager = new SessionManager($config, $storage);
					//*** SESSION LAB: set this session manager as a default for all session containers
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
                Container::class => function($container) {
					return new Container(__NAMESPACE__);
				},
            ],
        ];
    }
}
