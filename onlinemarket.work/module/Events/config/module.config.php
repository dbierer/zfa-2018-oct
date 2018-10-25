<?php
namespace Events;

use PDO;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

return [
    //*** LISTENER AGGREGATE LAB: attach the listener
    // 'listeners' => [ ??? ],
    //*** ABSTRACT FACTORIES LAB: define Table Module classes using "ConfigAbstractFactory"
    ConfigAbstractFactory::class => [
        Model\EventTable::class => ['events-db-adapter',Entity\Event::class,'events-service-container'],
        Model\AttendeeTable::class => ['events-db-adapter',Entity\Attendee::class,'events-service-container'],
        Model\RegistrationTable::class => ['events-db-adapter',Entity\Registration::class,'events-service-container'],
    ],
    'router' => [
        'routes' => [
            'events' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/events',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                        'module'     => __NAMESPACE__,
                    ],
                ],
                'may_terminate' => TRUE,
                'child_routes' => [
                    'admin' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/admin[/][:eventId]',
                            'defaults' => [
                                'controller' => Controller\AdminController::class,
                                'action'     => 'index',
                            ],
                            'constraints' => [
                                'eventId' => '[0-9]+',
                            ],
                        ],
                    ],
                    'ajax' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/ajax',
                            'defaults' => [
                                'controller' => Controller\AjaxController::class,
                                'action'     => 'registration',
                            ],
                        ],
                        'may_terminate' => TRUE,
                        'child_routes' => [
                            'reg' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/reg/:eventId',
                                    'defaults' => [
                                        'controller' => Controller\AjaxController::class,
                                        'action'     => 'registration',
                                    ],
                                    'constraints' => [
                                        'eventId' => '[0-9]+',
                                    ],
                                ],
                            ],
                            'attendee' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/attendee/:regId',
                                    'defaults' => [
                                        'controller' => Controller\AjaxController::class,
                                        'action'     => 'attendee',
                                    ],
                                    'constraints' => [
                                        'regId' => '[0-9]+',
                                    ],
                                ],
                            ],
                            //*** FORMS AND FIELDSETS LAB: add a route to AJAX request for Attendee sub-form
                        ],
                    ],
                    'signup' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/signup[/][:eventId]',
                            'defaults' => [
                                'controller' => Controller\SignupController::class,
                                'action'     => 'index',
                            ],
                            'constraints' => [
                                'eventId' => '[0-9]+',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [__DIR__ . '/../view'],
    ],
    'service_manager' => [
		'services' => [
			'events-nav-config' => [
				['label' => 'Signup Form', 'class' => 'events-label', 'route' => 'events/signup', 'resource' => 'menu-events-signup-label' ], 
				['label' => 'Signup', 'class' => 'btn btn-block btn-success btn-large', 'route' => 'events/signup', 'resource' => 'menu-events-signup' ],
				['label' => 'Admin Area', 'class' => 'events-label', 'route' => 'events/admin', 'resource' => 'menu-events-admin-label' ], 
				['label' => 'Admin Area', 'class' => 'btn btn-block btn-success btn-large', 'route' => 'events/admin', 'resource' => 'menu-events-admin' ],
			],
		],
        'factories' => [
            // Listener\Aggregate::class => Listener\Factory\AggregateFactory::class,
            //*** DATABASE ENTITIES LAB: define entity classes as invokables
            Entity\Event::class => InvokableFactory::class,
            Entity\Attendee::class => InvokableFactory::class,
            Entity\Registration::class => InvokableFactory::class,
        ],
		//*** ABSTRACT FACTORIES LAB: define an abstract factory which sets the tableGateway property for all table module classes
		'abstract_factories' => [
			ConfigAbstractFactory::class
		],
		//*** NAVIGATION LAB: define navigation for events as a service container service
    ],
    'view_helpers' => [
		'factories' => [
			Helper\FormMultiText::class => InvokableFactory::class
		],
		'aliases' => [
			'formMultiText' => Helper\FormMultiText::class,
		],
    ],
    //*** NAVIGATION LAB: define default navigation
    'navigation' => [
		'default' => [
			['label' => 'Events', 'route' => 'events', 'resource' => 'menu-events'],
		],
	],
    //*** ACL LAB
    'access-control-config' => [
        'resources' => [
            //*** ACL LAB: define a resource 'events-index' which points to 'Events\Controller\IndexController'
            'events-index' => Controller\IndexController::class,
            //*** ACL LAB: define a resource 'events-admin' which points to 'Events\Controller\AdminController',
            'events-admin' => Controller\AdminController::class,
            //*** ACL LAB: define a resource 'events-sign' which points to 'Events\Controller\SignupController',
            'events-sign' => Controller\SignupController::class,
            //*** NAVIGATION LAB: assign menu items as resources
            'menu-events' => 'menu-events',
            'menu-events-admin' => 'menu-events-admin',
            'menu-events-signup' => 'menu-events-signup',
            'menu-events-admin-label' => 'menu-events-admin-label',
            'menu-events-signup-label' => 'menu-events-signup-label',
        ],
        'rights' => [
            'guest' => [
                //*** ACL LAB: for the 'events-index' resource, guests should be allowed any action
                'events-index' => ['allow' => NULL],
                //*** ACL LAB: for the 'events-sign' resource, guests should be allowed any action
                'events-sign' => ['allow' => NULL],
                //*** NAVIGATION LAB: guest can see the 'menu-events' and 'menu-events-signup' menu items
				'menu-events'        => ['allow' => NULL],
				'menu-events-signup' => ['allow' => NULL],
				'menu-events-signup-label' => ['allow' => NULL],
            ],
            'admin' => [
                //*** ACL LAB: for the 'events-admin' resource, admin should be allowed any action
                'events-admin' => ['allow' => NULL],
                //*** NAVIGATION LAB: admin can see the 'menu-admin' item
				'menu-events-admin' => ['allow' => NULL],
				'menu-events-admin-label' => ['allow' => NULL],
            ],
        ],
    ],
];
