<?php
namespace Events\Controller;

use AccessControl\Traits\AclTrait;
use Login\Traits\AuthServiceTrait;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
	use AclTrait;
	use AuthServiceTrait;
	
    public function indexAction()
    {
        return new ViewModel(['acl' => $this->acl, 'authService' => $this->authService]);
    }
}
