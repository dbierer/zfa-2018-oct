<?php
namespace Logging;

use Zend\Mvc\ {MvcEvent};

//*** LOGGER LAB: add the required "use" statements
use Zend\Log\ {???};

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    //*** LOGGER LAB: attach a listener after modules are loaded which sets up the log service as the default exception hander
    public function onBootstrap(MvcEvent $e)
    {
        $em = $e->getApplication()->getEventManager();
    }

    public function setLogService(MvcEvent $e)
    {
		//*** LOGGER LAB: register the logger as the default error handler
		//*** LOGGER LAB: register the logger as the default exception handler
        $logger = $e->getApplication()->getServiceManager()->get('logging-logger');
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'logging-logger' => function ($container) {
                    //*** LOGGER LAB: define a logger which logs everything to the Firefox Console
                    //*** LOGGER LAB: define a logger which logs only critical and above to the PHP error log
                    //*** LOGGER LAB: attach two writers to the same logger
                    $logger = new Logger();
                    return $logger;
                },
                //*** DATABASE EVENTS LAB: inject database adapter platform into constructor for "logging-listener"
                Listener::class => function ($container) {
                    $adapter = $container->get('model-primary-adapter');
                    return new Listener($container->get('logging-logger'), $adapter->getPlatform());
                },
            ],
        ];
    }

}
