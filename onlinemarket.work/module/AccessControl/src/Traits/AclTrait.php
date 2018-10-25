<?php
namespace AccessControl\Traits;

use Zend\Permissions\Acl\Acl;

trait AclTrait
{
	protected $acl;
	public function setAcl(Acl $acl)
	{
		$this->acl = $acl;
	}
}
		
