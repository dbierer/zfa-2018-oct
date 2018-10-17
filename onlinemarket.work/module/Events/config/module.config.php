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
    'service_manager' => [
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
    //*** NAVIGATION LAB: define default navigation
    // 'navigation' => [ ??? ],
    //*** ACL LAB
    /*
    'access-control-config' => [
        'resources' => [
            //*** ACL LAB: define a resource 'events-index' which points to 'Events\Controller\IndexController'
            //*** ACL LAB: define a resource 'events-admin' which points to 'Events\Controller\AdminController',
            //*** ACL LAB: define a resource 'events-sign' which points to 'Events\Controller\SignupController',
            //*** NAVIGATION LAB: assign menu items as resources
        ],
        'rights' => [
            'guest' => [
                //*** ACL LAB: for the 'events-index' resource, guests should be allowed any action
                //*** ACL LAB: for the 'events-sign' resource, guests should be allowed any action
                //*** NAVIGATION LAB: guest can see the 'menu-events' and 'menu-events-signup' menu items
            ],
            'admin' => [
                //*** ACL LAB: for the 'events-admin' resource, admin should be allowed any action
                //*** NAVIGATION LAB: admin can see the 'menu-admin' item
            ],
        ],
    ],
    */
];
