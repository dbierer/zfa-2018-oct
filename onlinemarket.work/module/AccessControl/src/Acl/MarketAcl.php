<?php
namespace AccessControl\Acl;

use Zend\Permissions\Acl\Acl;
class MarketAcl extends Acl
{
    const DEFAULT_ROLE = 'guest';
    public function __construct($config, $container)
    {
        //*** add roles w/ inheritance
        foreach ($config['roles'] as $role => $inherits) {
            if ($inherits !== NULL) {
                //*** add the role with inheritance
                $this->addRole($role, $inherits);
            } else {
                //*** add the role
                $this->addRole($role);
            }
        }
        //*** add resources
        $resources = $config['resources'];
        foreach ($resources as $key => $class) {
            //*** add resources
            $this->addResource($class);
        }
        // assign rights
        foreach ($config['rights'] as $role => $assignment) {
            foreach ($assignment as $key => $rights) {
				$assert = (isset($rights['assert'])) ? $container->get($rights['assert']) : NULL;
                if (array_key_exists('allow', $rights)) {
                    //*** assign allowed rights
                    if ($this->hasRole($role) && $this->hasResource($resources[$key]))
						$this->allow($role, $resources[$key], $rights['allow'], $assert);
                }
                if (array_key_exists('deny', $rights)) {
                    //*** assign denied rights
                    if ($this->hasRole($role) && $this->hasResource($resources[$key]))
						$this->deny($role, $resources[$key], $rights['deny'], $assert);
                }
            }
        }
        return $this;
    }
}
