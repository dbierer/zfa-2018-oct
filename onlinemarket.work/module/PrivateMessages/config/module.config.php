<?php
namespace PrivateMessages;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'navigation' => [
        'default' => [
            'messages' => ['label' => 'Messages', 'route' => 'messages', 'resource' => 'menu-private-messages']
        ]
    ],
    'router' => [
        'routes' => [
            'messages' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/messages[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'keypairs' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/keypairs[/:action]',
                    'defaults' => [
                        'controller' => Controller\KeypairsController::class,
                        'action'     => 'diffie',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Form\Send::class => Form\Factory\SendFormFactory::class,
            Model\MessagesTable::class => Model\Factory\MessagesTableFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
    //*** ACL LAB
    'access-control-config' => [
        'resources' => [
			//*** ACL LAB: define a resource "messages" which points to 'PrivateMessages\Controller\IndexController',
			'private-messages-index' => Controller\IndexController::class,
            //*** NAVIGATION LAB: define a private message menu item as a resource
            'menu-private-messages' => 'menu-private-messages',
        ],
        'rights' => [
            'user' => [
				//*** ACL LAB: for the "messages" resource users are allowed all actions
				'private-messages-index' => ['allow' => NULL],
                //*** NAVIGATION LAB: users are allowed to see any messages menu resource item
				'menu-private-messages' => ['allow' => NULL],
            ],
        ],
    ],
];
