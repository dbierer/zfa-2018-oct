<?php
namespace Market;

use Market\Event\LogEvent;
use Zend\Mvc\MvcEvent;
//*** NAVIGATION LAB: add "use" statement for the ConstructedNavigationFactory

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    //*** SHARED EVENT MANAGER LAB: add a listener to the "log" event which records the title of the item posted
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, [$this, 'injectCategories']);
    }
    public function injectCategories(MvcEvent $e)
    {
        $viewModel = $e->getViewModel();
        $serviceManager = $e->getApplication()->getServiceManager();
        $viewModel->setVariable('categories', $serviceManager->get('categories'));
    }
	//*** NAVIGATION LAB: define navigation for categories
    public function getServiceConfig()
    {
        return [];
    }
    //*** INITIALZERS LAB: define an initializer which will inject a ListingsTable instance into controllers
    public function getControllerConfig()
    {
        return [];
    }
}
