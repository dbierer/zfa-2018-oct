<?php
namespace Application\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class AppEventAggregate implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $e, $priority = 100)
    {
        $shared = $e->getSharedManager();
        $this->listeners[] = $shared->attach('*', 'app-event-test', [$this, 'someListener'], $priority);
    }
    public function detach(EventManagerInterface $e, $priority = 100) {
        // do nothing
    }
    public function someListener(MvcEvent $e)
    {
        $whoTriggered = get_class($e->getTarget());
        $optMessage   = date('Y-m-d H:i:s')
					  . ':' . $whoTriggered
					  . ':' . ($e->getParam('message') ?? 'No Message');
        error_log($optMessage);
    }
}
