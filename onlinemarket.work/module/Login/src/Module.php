<?php
namespace Login;

use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter;
use Login\Model\UsersTable;

//*** AUTHENTICATION LAB: add required "use" statements
use Zend\Authentication\Storage\Session;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

//*** PASSWORD LAB: add required "use" statements
use Login\Security\Password;
use Zend\Crypt\Password\Bcrypt;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'services' => [
				//*** PASSWORD LAB: modify this to use "verify()" from the Password class
                'login-auth-callback' => function ($hash, $password) {
                    return Password::verify($password, $hash);
                },
            ],
            'factories' => [
                'login-db-adapter' => function ($container) {
                    return new Adapter($container->get('model-primary-adapter-config'));
                },
				//*** AUTHENTICATION LAB: define an authentication adapter
                'login-auth-adapter' => function ($container) {
					//*** AUTHENTICATION LAB: return a CallbackCheckAdapter instance with these arguments: auth adapter, tablename, identity col, password col and callback
                    return new CallbackCheckAdapter(
                        $container->get('login-db-adapter'),
                        UsersTable::$tableName,
                        UsersTable::$identityCol,
                        UsersTable::$passwordCol,
                        $container->get('login-auth-callback')
                    );
                },
				//*** AUTHENTICATION LAB: define an authentication service
                'login-auth-service' => function ($container) {
					//*** AUTHENTICATION LAB: need storage and auth adapter as arguments
                    return new AuthenticationService(
                        // need storage and auth adapter as arguments
                        new Session(),
                        $container->get('login-auth-adapter'));
                },
            ],
        ];
    }
}
