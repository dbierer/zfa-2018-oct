<?php
namespace RestApi\Controller\Factory;

use RestApi\Service\ApiService;
use RestApi\Controller\ApiController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ApiControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = NULL)
    {
        return new ApiController($container->get(ApiService::class));
    }
}
