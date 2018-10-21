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

	protected $cacheKey = '';
	protected $routeMatch;
	protected $cacheAdapter;
	
    public function attach(EventManagerInterface $e, $priority = 100)
    {
        //*** attach a series of listeners using the shared manager
        $shared = $e->getSharedManager();
        //*** attach a listener to store category into the RouteMatch object
        $this->listeners[] = $shared->attach('*', MvcEvent::EVENT_DISPATCH, [$this, 'storeRouteMatch'], $priority + 100);
        //*** attach a listener to get the page view from cache
        $this->listeners[] = $shared->attach('*', MvcEvent::EVENT_DISPATCH, [$this, 'getPageViewFromCache'], $priority);
        //*** attach a listener which listens at the very end of the cycle and check to see if the "mustCache" param has been set
        $this->listeners[] = $shared->attach('*', MvcEvent::EVENT_FINISH, [$this, 'storePageViewToCache'], $priority);
        //*** attach a listener to clear cache if EVENT_CLEAR_CACHE is triggered
        $this->listeners[] = $shared->attach('*', self::EVENT_CLEAR_CACHE, [$this, 'clearCache'], $priority);
    }
    public function storeRouteMatch(MvcEvent $e)
    {
		$this->routeMatch = $e->getRouteMatch();
		$this->cacheKey   = $this->generateCacheKey();
	}
    public function clearCache($e)
    {
		$this->cacheAdapter->flush();
		error_log(date('Y-m-d H:i:s') . ': Cleared Cache');
    }
    //*** configure this to check to see if the "ViewController" has been chosen
    //*** if so, check to see if the response object has been cached and return it
    //*** otherwise set a param "mustCache" to indicate this page view should be cached
    public function getPageViewFromCache(MvcEvent $e)
    {
		//*** get the route match from the event
        $routeMatch = $e->getRouteMatch();
		//*** get the controller from the route match
        $controller = $routeMatch->getParam('controller');
        if ($controller == 'Market\Controller\ViewController') {
            // matched route == market/view/category | market/view/item
            $cacheKey   = $this->generateCacheKey();
            if ($this->cacheAdapter->hasItem($cacheKey)) {
                return $this->cacheAdapter->getItem($cacheKey);
            } else {
                $routeMatch->setParam('re-cache', $cacheKey);
            }
        }
    }
    public function storePageViewToCache(MvcEvent $e)
    {
        //*** complete the logic for this method
        if ($this->routeMatch->getParam('re-cache')) {
			$cacheKey = $this->generateCacheKey();
            $this->cacheAdapter->setItem($cacheKey, $e->getResponse());
            error_log(date('Y-m-d H:i:s') . ':Cached: ' . $cacheKey);
        }
    }
    protected function generateCacheKey()
    {
		$cacheKey   = str_replace('/', '_', $this->routeMatch->getMatchedRouteName()) . '_';
		if ($itemId = $this->routeMatch->getParam('itemId')) {
			$cacheKey .= $itemId;
		} elseif ($category = $this->routeMatch->getParam('category')) {
			$cacheKey .= $category;
		}
		return $cacheKey;
	}
	public function setCacheAdapter($cacheAdapter)
	{
		$this->cacheAdapter = $cacheAdapter;
	}

}
