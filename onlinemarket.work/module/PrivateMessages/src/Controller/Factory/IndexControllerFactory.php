<?php
namespace PrivateMessages\Controller\Factory;

use Exception;
use Model\Entity\User;
use PrivateMessages\Controller\IndexController;
use PrivateMessages\Form\Send as SendForm;
use PrivateMessages\Model\MessagesTable;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = NULL)
    {
        $controller = new IndexController();
        $controller->setTable($container->get(MessagesTable::class));
        $controller->setSendForm($container->get(SendForm::class));
        //*** ACL LAB: modify this so that the controller only gets an authenticated user
        $user = new User();
		if ($container->has('login-auth-service')) {
			$authService = $container->get('login-auth-service');
			$user = $authService->getIdentity() ?? $user;
		}
        $controller->setUser($user);
        return $controller;
    }
}
