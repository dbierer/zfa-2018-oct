<?php
namespace Registration\Form;

trait ConfigTrait
{
    protected $roleConfig;
    public function setRoleConfig(array $config)
    {
        $this->roleConfig = array_combine($config,$config);
    }
}
