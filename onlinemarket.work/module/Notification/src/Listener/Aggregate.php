<?php
//*** EMAIL LAB: review the code
namespace Notification\Listener;

use Application\Traits\ServiceContainerTrait;
use Notification\Event\NotificationEvent;

use Zend\Mail\Message;
use Zend\Mime\ {Mime, Message as MimeMessage, Part as MimePart};
use Zend\Mail\Transport\ {Smtp,SmtpOptions,File,FileOptions,SendMail};
use Zend\EventManager\ {EventInterface, EventManagerInterface,AbstractListenerAggregate};

class Aggregate extends AbstractListenerAggregate
{

    const DEFAULT_MESSAGE = 'Online Market Item Successfully Posted';    
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
            // get config from event $e
            $config = $e->getParam('notify-config');
            // set up ViewModel and template for rendering
            $viewModel = new ViewModel();
            // throw exception if "to" is not set
            if (!$to = $e->getParam('to')) {
                throw new Exception(self::ERROR_NO_RECIPIENT);
            }
            // create HTML body
            $html = new MimePart($e->getParam('message', self::DEFAULT_MESSAGE));
            $html->type = Mime::TYPE_HTML;  // i.e. 'text/html'
            $html->charset = 'utf-8';
            $html->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
            $body = new MimeMessage();
            $body->setParts([$html]);
            // set up mail message
            $message  = new Message();
            $message->setEncoding('UTF-8');
            $message->addTo($to);
            $message->addFrom($config['from']);
            // "cc" and "bcc" are optional
            if (isset($config['cc']))
                $message->addCc($config['cc']);
            if (isset($config['bcc']))
                $message->addBcc($config['bcc']);
            $message->setSubject($config['subject']);
            $message->setBody($body);
            // get transport
            switch ($config['transport']) {
                case 'smtp' :
                    $transport = $this->serviceManager->get('email-notification-transport-smtp');
                    break;
                case 'file' :
                    $transport = $this->serviceManager->get('email-notification-transport-file');
                    break;
                default :
                    $transport = $this->serviceManager->get('email-notification-transport-sendmail');
            }
            // send
            NotifyEvent::$success = TRUE;
            $transport->send($message);
        } catch (Exception $e) {
            error_log(__METHOD__ . ':' . __LINE__ . ':' . self::ERROR_SENDING . $to . ':' . $e->getMessage());
            NotificationEvent::$success = FALSE;
        }
    }
}
