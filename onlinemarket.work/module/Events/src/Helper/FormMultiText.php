<?php
namespace Events\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormText;

class FormMultiText extends FormText
{
    /**
     * Render a form <input> element from the provided $element
     *
     * @param  ElementInterface $element
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }
        $attributes          = $element->getAttributes();
        $attributes['name']  = $name;
        $type                = $this->getType($element);
        $attributes['type']  = $type;
        $attributes['value'] = $element->getValue();
        if ('password' == $type) {
            $attributes['value'] = '';
        }
        // remove "name" attributes
        unset($attributes['name']);
        $attributeString = 'name="' . $name . '" ' . $this->createAttributesString($attributes);
        return sprintf('<input %s%s', $attributeString, $this->getInlineClosingBracket());
    }
}
