<?php
namespace Events\Traits;

use Zend\Form\Form;

trait RegFormTrait
{
    protected $regForm;
    public function setRegForm(Form $form)
    {
        $this->regForm = $form;
    }
}
