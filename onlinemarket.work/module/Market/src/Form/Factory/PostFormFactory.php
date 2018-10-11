<?php
namespace Market\Form\Factory;

use Model\Entity\Listing;
use Market\Form\ {PostForm,PostFilter};
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Hydrator\ObjectProperty;

class PostFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new PostForm();
        $form->setExpireDays($container->get('market-expire-days'));
        $form->setCategories($container->get('categories'));
        $form->setCaptchaOptions($container->get('market-captcha-options'));
        $form->buildForm();
        $form->setInputFilter($container->get(PostFilter::class));
        $form->setHydrator(new ObjectProperty());
        $form->bind(new Listing());
        return $form;
    }
}
