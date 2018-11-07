<?php
namespace Notification;

use Zend\Mvc\MvcEvent;
//*** EMAIL LAB: add the appropriate "use" statements
use Zend\Mail\Transport\ {SendMail, Smtp, File};
use Zend\Mail\Transport\ {SmtpOptions, FileOptions};
use Interop\Container\ContainerInterface;

class Module
{
    //*** EMAIL LAB: return the appropriate configuration which registers Notification\Listener\Aggregate as a listener
    public function getConfig()
    {
        return [
            'listeners' => [ Listener\Aggregate::class ]
        ];
    }
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
                    'transport' => [
                        'type' => 'sendmail',      // sendmail | smtp | file
                        'options' => [],
                    ],
                ],
            ],
            'factories' => [
                Listener\Aggregate::class => function (ContainerInterface $container, $requestedName, ?array $options = NULL)
                {
                    $aggregate = new $requestedName();
                    $aggregate->setServiceContainer($container);
                    return $aggregate;
                },
                //*** EMAIL LAB: define each of these transports
                'notification-transport-smtp' => function (ContainerInterface $container, $requestedName, ?array $options = NULL)
                {
                    $config = $container->get('notification-config');
                    $transport = new Smtp(new SmtpOptions($config['transport']['options']));
                    return $transport;
                },
                'notification-transport-file' => function (ContainerInterface $container, $requestedName, ?array $options = NULL)
                {
                    $config = $container->get('notification-config');
                    $transport = new File(new FileOptions($config['transport']['options']));
                    return $transport;
                },
                'notification-transport-sendmail' => function (ContainerInterface $container, $requestedName, ?array $options = NULL)
                {
                    $transport = new SendMail();
                    return $transport;
                },
            ],
        ];
    }
}
