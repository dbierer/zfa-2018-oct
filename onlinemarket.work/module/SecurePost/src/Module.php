<?php
namespace SecurePost;

use Zend\Form\Element\Csrf;
use Interop\Container\ContainerInterface;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getServiceConfig()
    {
		return [
			'factories' => [
				//** DELEGATORS LAB: Create a new service which returns a "Zend\Form\Element\Csrf" element
			],
			'delegators' => [
				//** DELEGATORS LAB: Add a "delegators" key which points the form creation to the delegator
				//** DELEGATORS LAB: have the delegator apply to both the Market\Form\Postform and Registration\Form\RegForm
			],
		];
	}
}

