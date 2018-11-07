<?php
//*** EMAIL LAB: review the code
namespace Notification\Listener;

use Application\Traits\ServiceContainerTrait;
use Notification\Event\NotificationEvent;

use Exception;
use Zend\Mail\Message;
use Zend\Mime\ {Mime, Message as MimeMessage, Part as MimePart};
use Zend\Mail\Transport\ {Smtp,SmtpOptions,File,FileOptions,SendMail};
use Zend\EventManager\ {EventInterface, EventManagerInterface,AbstractListenerAggregate};

class Aggregate extends AbstractListenerAggregate
{

    const DEFAULT_MESSAGE    = 'Online Market Item Successfully Posted';  
    const ERROR_SENDING      = 'ERROR: unable to send message to ';
    const ERROR_NO_RECIPIENT = 'ERROR: must specify a recipient for the email';
    const ERROR_NO_TRANSPORT = 'ERROR: transport type invalid or not specified';
    const ERROR_TRANSPORT_SERVICE = 'ERROR: unable to create transport service';
    
    use ServiceContainerTrait;

    public function attach(EventManagerInterface $e, $priority = 100)
    {
		//*** EMAIL LAB: attach listener using the shared manager
        $shared = $e->getSharedManager();
        $this->listeners[] = $shared->attach('*', NotificationEvent::EVENT_NOTIFICATION, [$this, 'sendEmail'], $priority);
    }
    public function sendEmail(EventInterface $e)
    {
        try {
            $config = $e->getParams();
            // throw exception if "to" is not set
            if (!isset($config['to'])) 
                throw new Exception(self::ERROR_NO_RECIPIENT);
            // get config from event $e
            if (!isset($config['transport']['type'])) 
                // throw exception if transport type not set
                throw new Exception(self::ERROR_NO_TRANSPORT);
            // create HTML body
            $html = new MimePart($config['message'], self::DEFAULT_MESSAGE);
            $html->type = Mime::TYPE_HTML;  // i.e. 'text/html'
            $html->charset = 'utf-8';
            $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
            $body = new MimeMessage();
            $body->setParts([$html]);
            // set up mail message
            $message  = new Message();
            $message->setEncoding('UTF-8');
            $message->setSubject($config['subject']);
            $message->setBody($body);
            $message->addTo($config['to']);
            $message->addFrom($config['from']);
            // "cc" and "bcc" are optional
            if (isset($config['cc']))
                $message->addCc($config['cc']);
            if (isset($config['bcc']))
                $message->addBcc($config['bcc']);
            // get transport
            $serviceKey = 'notification-transport-' . $config['transport']['type'];
            $transport = $this->serviceContainer->get($serviceKey);
            
            // send
            $transport->send($message);
            NotificationEvent::$success = TRUE;
        } catch (Exception $e) {
            error_log(date('Y-m-d H:i:s') . ':' . __METHOD__ . ':' . self::ERROR_SENDING . $to . ':' . $e->getMessage());
            NotificationEvent::$success = FALSE;
        }
    }
}
