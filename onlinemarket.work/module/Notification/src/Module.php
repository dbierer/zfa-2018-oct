<?php
namespace Notification;

use Zend\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;

class Module
{
    public function getServiceConfig()
    {
        return [
            'services' => [
                // will be overridden in /config/autoload/global.php
                'notification-config' => [
                    'from' => 'admin@company.com',
                    // optional:
                    /*
                    'cc' => 'some@email.com',
                    'bcc' => 'some@email.com',
                    */
                    'subject' => 'Say What?',
                    'transport' => 'sendmail',      // sendmail | smtp | file
                ],
            ],
            'factories' => [
                Listener\Aggregate::class => function (ContainerInterface $container, $requestedName, ?array $options = NULL)
                {
                    $aggregate = new $requestedName();
                    $aggregate->setServiceContainer($container);
                    return $aggregate;
                },
            ],
        ];
    }
}
