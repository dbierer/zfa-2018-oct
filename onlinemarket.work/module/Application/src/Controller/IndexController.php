<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Exception;
use Notification\Event\NotificationEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $em = $this->getEventManager();
        $em->addIdentifiers(['IDENTIFIER_DB']);
        $em->trigger('EVENT_DB_MOD', $this,
            ['message' => 'Whatever!!!']);
        return new ViewModel();
    }
    public function exceptionAction()
    {
        //*** LOGGER LAB: an exception will be thrown because there is no corresponding view template
        return new ViewModel();
    }
    public function triggerAction()
    {
        $this->getEventManager()->trigger('app-event-test', $this, ['message' => 'Surprise!']);
    }
    public function emailAction()
    {
        $sm = $this->getEvent()->getApplication()->getServiceManager();
        $notificationConfig = $sm->get('notification-config');
        $notificationConfig['to'] = 'test@zend.com';
        $notificationConfig['message'] = sprintf('TEST was successfully posted on %s', date('Y-m-d H:i:s'));
        $em = $this->getEventManager();
        $em->trigger( NotificationEvent::EVENT_NOTIFICATION, 
                      $this, 
                      $notificationConfig );
        return new ViewModel(['success' => NotificationEvent::$success, 'emailDir' => $notificationConfig['transport']['options']['path']]);
    }
}
