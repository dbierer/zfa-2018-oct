<?php
namespace Events;

use Events\Entity\ {Event, Registration, Attendee};
use Zend\Mvc\MvcEvent;
use Zend\EventManager\ {EventManager, SharedEventManager};
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Db\Adapter\Adapter;
use Zend\Filter;
use Zend\Form\Annotation\AnnotationBuilder;
//*** DELEGATING HYDRATOR LAB: add the correct "use" statements
//*** NAVIGATION LAB: add "use" statement for the ConstructedNavigationFactory

class Module
{
	const MAX_NAMES_PER_TICKET = 6;
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => InvokableFactory::class,
                Controller\AdminController::class  => function ($container, $requestedName) {
                    $controller = new $requestedName();
                    $controller->setEventTable($container->get(Model\EventTable::class));
                    $controller->setRegTable($container->get(Model\RegistrationTable::class));
                    return $controller;
                },
                Controller\AjaxController::class  => function ($container, $requestedName) {
                    $controller = new $requestedName();
                    $controller->setRegTable($container->get(Model\RegistrationTable::class));
                    $controller->setAttendeeTable($container->get(Model\AttendeeTable::class));
                    return $controller;
                },
                Controller\SignupController::class => function ($container, $requestedName) {
                    $controller = new $requestedName();
                    $controller->setEventTable($container->get(Model\EventTable::class));
                    $controller->setRegTable($container->get(Model\RegistrationTable::class));
                    $controller->setAttendeeTable($container->get(Model\AttendeeTable::class));
                    $controller->setFilter($container->get('events-reg-data-filter'));
                    $controller->setRegForm($container->get('events-reg-form'));
                    return $controller;
                },
            ],
        ];
    }
    public function getServiceConfig()
    {
        return [
            'aliases' => [
                'events-db-adapter' => 'model-primary-adapter',
            ],
            'factories' => [
                'events-reg-data-filter' => function ($container) {
                    $filter = new Filter\FilterChain();
                    $filter->attach(new Filter\StringTrim())
                           ->attach(new Filter\StripTags());
                    return $filter;
                },
                'events-reg-form' => function ($container) {
					$form = (new AnnotationBuilder())->createForm($container->get(Entity\Registration::class));
					$fieldset = $container->get('events-attendee-form');
					for ($x = 0; $x < self::MAX_NAMES_PER_TICKET; $x++) $form->add(clone $fieldset, ['name' => 'attendee_' . $x]);
					return $form;
				},
                'events-attendee-form' => function ($container) {
					return (new AnnotationBuilder())->createForm($container->get(Entity\Attendee::class));
				},
                'events-service-container' => function ($container) {
                    return $container;
                },
                //*** DELEGATING HYDRATOR LAB: define a service which returns an instance of Zend\Hydrator\DelegatingHydrator
                'events-delegating-hydrator' => function ($container) {
                    //*** DELEGATING HYDRATOR LAB: assign a "ObjectProperty" hydrator to the "Registration" entity and "ClassMethods" to the others
                },
                //*** ABSTRACT FACTORIES LAB: define a single abstract factory to build these table classes
                /*
                Model\EventTable::class => function ($container, $requestedName) {
                    return new $requestedName($container->get('events-db-adapter'),
                                              $container->get(Event::class),
                                              $container);
                },
                Model\RegistrationTable::class => function ($container, $requestedName) {
                    return new $requestedName($container->get('events-db-adapter'),
                                              $container->get(Registration::class),
                                              $container);
                },
                Model\AttendeeTable::class => function ($container, $requestedName) {
                    return new $requestedName($container->get('events-db-adapter'),
                                              $container->get(Attendee::class),
                                              $container);
                },
                */
            ],
        ];
    }
}

