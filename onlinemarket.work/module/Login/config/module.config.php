<?php
namespace Login;

use PDO;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Form\Login::class => Form\Factory\LoginFormFactory::class,
            Model\UsersTable::class => Model\Factory\UsersTableFactory::class,
            //*** AUTHENTICATION LAB: define aggregate as invokable
            Listener\Aggregate::class => InvokableFactory::class,
        ],
    ],
    'listeners' => [
        //*** SECURITY::AUTHENTICATION LAB: add aggregate as listener
        Listener\Aggregate::class
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    //*** NAVIGATION LAB: define default navigation
    /*
    'navigation' => [
        'default' => [
            'login' => [],
            'logout' => []
        ],
    ],
    */
    //*** ACL LAB
    'access-control-config' => [
        'resources' => [
			//*** ACL LAB: define the login index controller as a resource "login"
            //*** NAVIGATION LAB: add these resources for menu options login and logout
        ],
        'rights' => [
            'guest' => [
				//*** ACL LAB: for the "login" resource, allow guests to use the "login" and "register" actions
                //*** NAVIGATION LAB: allow guests to see the "login" menu option but not "logout"
            ],
            'user' => [
				//*** ACL LAB: for the "login" resource, allow users to use the "logout"
                //*** NAVIGATION LAB: allow users to see the "logout" menu option but not "login"
            ],
        ],
    ],
];
