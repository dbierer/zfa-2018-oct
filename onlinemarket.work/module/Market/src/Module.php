<?php
namespace Market;

use Model\Table\ListingsTable;
use Market\Event\LogEvent;
use Zend\Mvc\MvcEvent;
//*** NAVIGATION LAB: add "use" statement for the ConstructedNavigationFactory
use Zend\Navigation\Service\ConstructedNavigationFactory;

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
        return [
			'factories' => [
				'market-categories-nav-config' => function ($container) {
					$categories = $container->get('categories');
					$config = [];
					foreach ($categories as $item) {
						// example: ['label' => 'barter', 'route' => 'market/view/category', 'params' => ['category' =>  'barter']],
						$config[$item] = ['label' => ucfirst($item), 'route' => 'market/view/category', 'params' => ['category' => $item]];
					}
					return $config;
				},
				'market-categories-navigation' => function ($container) {
                    $factory = new ConstructedNavigationFactory($container->get('market-categories-nav-config'));
                    return $factory->createService($container);
				},
			],
        ];
    }
    //*** INITIALZERS LAB: define an initializer which will inject a ListingsTable instance into controllers
    public function getControllerConfig()
    {
        return [
			'initializers' => [
				'market-set-listings-table' => function ($container, $instance) {
					if ($instance instanceof Controller\ListingsTableAwareInterface) {
						$instance->setListingsTable($container->get('model-listings-table'));
					}
				},
			],
        ];
    }
}
