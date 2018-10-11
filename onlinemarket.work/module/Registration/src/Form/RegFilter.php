<?php
namespace Registration\Form;

use Zend\Filter;
use Zend\Validator;
use Zend\I18n\Validator\ {Alnum,Alpha};
use Zend\InputFilter\ {InputFilter, Input};

class RegFilter extends InputFilter
{
    use ConfigTrait;

    public function __construct($roles)
    {
        $this->setRoleConfig($roles);
        $this->addInputFilter();
    }

    public function addInputFilter()
    {
        $email = new Input('email');
        $email->getValidatorChain()
              ->attach(new Validator\EmailAddress());
        $email->getFilterChain()
              ->attach(new Filter\StringTrim())
              ->attach(new Filter\StripTags());
        $this->add($email);

        $password = new Input('password');
        $password->getValidatorChain()
              ->attach(new Validator\NotEmpty());
        $this->add($password);

        $username = new Input('username');
        $username->getValidatorChain()
              ->attach(new Validator\NotEmpty())
              ->attach(new Alnum());
        $username->getFilterChain()
              ->attach(new Filter\StringTrim())
              ->attach(new Filter\StripTags());
        $this->add($username);

        $question = new Input('securityQuestion');
        $question->getValidatorChain()
              ->attach(new Validator\NotEmpty());
        $question->getFilterChain()
              ->attach(new Filter\StringTrim())
              ->attach(new Filter\StripTags());
        $this->add($question);

        $answer = new Input('securityAnswer');
        $answer->getValidatorChain()
              ->attach(new Validator\NotEmpty());
        $answer->getFilterChain()
              ->attach(new Filter\StringTrim())
              ->attach(new Filter\StripTags());
        $this->add($answer);

        $role = new Input('role');
        $role->setRequired(TRUE);
        $role->getValidatorChain()
                ->attachByName('InArray', array('haystack' => array_keys($this->roleConfig)));
        $role->getFilterChain()
                ->attach(new Alpha());

    }
}
