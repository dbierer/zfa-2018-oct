<?php
namespace Market\Controller;

use Zend\Session\Container;

trait SessionTrait
{
    protected $sessionContainer;
    public function setSessionContainer(Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }
}
