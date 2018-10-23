<?php
namespace Logging;

use Zend\Log\Logger;
use Zend\EventManager\ {EventManagerInterface, AbstractListenerAggregate};

class Listener extends AbstractListenerAggregate
{

    const EVENT_SOMETHING = 'logging-log-something';
    protected $logger;
    public function __construct($logger)
    {
        $this->logger = $logger;
    }
    //*** LOGGER LAB: complete the "attach()"
    public function attach(EventManagerInterface $e, $priority = 100)
    {
        //*** attach a listener using the shared manager
        $shared = $e->getSharedManager();
        $shared->attach('*', self::EVENT_SOMETHING, [$this, 'logMessage']);
    }
	public function logMessage($e)
	{
		$level = $e->getParam('level', Logger::INFO);
		$message = $e->getParam('message', '');
		if ($message) $this->logger->log($level, strip_tags($message));
	}
}
