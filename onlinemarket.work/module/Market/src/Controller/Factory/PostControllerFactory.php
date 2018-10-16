<?php
namespace Market\Controller\Factory;

use Market\Controller\PostController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
//*** SESSIONS LAB: add a "use" statement for session container

class PostControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new PostController();
		//*** INITIALIZERS LAB: the following line can be removed once the initializer has been created
        //$controller->setListingsTable($container->get('model-listings-table'));
        $controller->setCityCodesTable($container->get('model-city-codes-table'));
        $controller->setPostForm($container->get('Market\Form\PostForm'));
		//*** FILE UPLOAD LAB: inject file upload config into controller
		//*** SESSIONS LAB: inject a session container instance
        return $controller;
    }
}
