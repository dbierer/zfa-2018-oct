<?php
//*** CACHE LAB
namespace Market\Listener;

use Market\Controller\MarketController;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\ {AbstractListenerAggregate,EventManagerInterface};
use Application\Traits\ServiceContainerTrait;

class CacheAggregate extends AbstractListenerAggregate
{

    const EVENT_CLEAR_CACHE = 'market-event-clear-cache';

    use ServiceContainerTrait;

    public function attach(EventManagerInterface $e, $priority = 100)
    {
        //*** attach a series of listeners using the shared manager
        //*** attach a listener to get the page view from cache
        //*** attach a listener which listens at the very end of the cycle and check to see if the "mustCache" param has been set
        //*** attach a listener to clear cache if EVENT_CLEAR_CACHE is triggered
    }
    public function clearCache($e)
    {
        //*** complete the logic for this method
    }
    //*** configure this to check to see if the "ViewController" has been chosen
    //*** if so, check to see if the response object has been cached and return it
    //*** otherwise set a param "mustCache" to indicate this page view should be cached
    public function getPageViewFromCache(MvcEvent $e)
    {
		//*** get the route match from the event
		//*** get the controller from the route match
        if ($controller == 'Market\Controller\ViewController') {
            // matched route == market/view/category | market/view/item
        }
    }
    public function storePageViewToCache(MvcEvent $e)
    {
		//*** get the route match from the event
        //*** complete the logic for this method
        if ($routeMatch && $cacheKey = $routeMatch->getParam('mustCache')) {
            error_log('Cached: ' . $cacheKey);
        }
    }
}
