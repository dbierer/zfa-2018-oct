<?php
namespace SecurePost;

use Zend\Form\Element\Csrf;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

class AddsCsrf implements DelegatorFactoryInterface
{
	public function __invoke(ContainerInterface $container,
							  $name,
							  callable $callback,
							  array $options = null)
	{
		//** DELEGATORS LAB: run the callback to create the form
		//** DELEGATORS LAB: add the secure form CSRF element to the form
	}
}

