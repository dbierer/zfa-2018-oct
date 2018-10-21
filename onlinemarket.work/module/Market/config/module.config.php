<?php
namespace Market;

use Market\Plugin\Flash;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'market' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/market',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'post' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/post[/]',
                            'defaults' => [
                                'controller' => Controller\PostController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'lookup' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/lookup[/]',
                                    'defaults' => [
                                        'action'     => 'lookup',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'view' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/view',
                            'defaults' => [
                                'controller' => Controller\ViewController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'slash' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/',
                                ],
                            ],
                            'category' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/category[/:category]',
                                    'constraints' => [
                                        'category' => '[A-Za-z0-9]*',
                                    ],
                                    'defaults' => [
                                        'action'     => 'index',
                                    ],
                                ],
                            ],
                            'item' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/item[/:itemId]',
                                    'constraints' => [
                                        'itemId' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action'     => 'item',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'services' => [
            // defined in config/autoload/global.php
            /*
            'categories' => [],
            'market-expire-days' => [],
            'market-captcha-options' => [],
            */
            //*** NAVIGATION LAB: define categories navigation
            //*** FILE UPLOAD LAB: define config for file upload validators and filter
            'market-upload-config' => [
                'img_size'   => ['???'],
                'file_size'  => ['???'],
                'rename'     => ['???'],
                'img_url'    => '/images',
            ],
        ],
        'factories' => [
            Form\PostForm::class => Form\Factory\PostFormFactory::class,
            Form\PostFilter::class => Form\Factory\PostFilterFactory::class,
            //*** CACHE LAB: add missing factory for Market\Listener\CacheAggregate
            Listener\CacheAggregate::class => Listener\Factory\CacheAggregateFactory::class,
        ],
    ],
	//*** CACHE LAB: add entries to represent listeners defined as aggregates
    'listeners' => [ Listener\CacheAggregate::class ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\ViewController::class => Controller\Factory\ViewControllerFactory::class,
            Controller\PostController::class => Controller\Factory\PostControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
    'view_helpers' => [
        'factories' => [
            Helper\LeftLinks::class => InvokableFactory::class,
        ],
        'aliases' => [
            'leftLinks' => Helper\LeftLinks::class,
        ],
    ],
    //*** NAVIGATION LAB: define default navigation
    'navigation' => [
        'default' => [
            'market-home' => ['label' => 'Home', 'order' => -100, 'route' => 'market', 'resource' => 'menu-market-index'],
            'market-post' => ['label' => 'Post', 'route' => '???', 'resource' => '???'],
        ],
    ],
    //*** ACL LAB
    'access-control-config' => [
        'resources' => [
            'market-index' => 'Market\Controller\IndexController',
            //*** ACL LAB: define a resource "market-view" which points to 'Market\Controller\ViewController',
            //*** ACL LAB: define a resource "market-post" which points to 'Market\Controller\PostController',
            //*** NAVIGATION LAB: define a market menu item as resources
            'menu-market-index' => '???',
            'menu-market-post'  => '???',
        ],
        'rights' => [
            'guest' => [
                'market-index' => ['allow' => NULL],
                //*** ACL LAB: for the "market-view" resource guests are allowed all actions
                //*** NAVIGATION LAB: guests are allowed to see market index and market view menu items
            ],
            'user' => [
                //*** ACL LAB: for the "market-post" resource users are allowed all actions
                //*** NAVIGATION LAB: users are allowed to see the market post menu item
				'menu-market-post'  => ['allow' => NULL],
            ],
        ],
    ],
];

