<?php
namespace Cache;

use Zend\Mvc\MvcEvent;
//*** CACHE LAB: add the appropriate "use" statements
use Zend\Cache\StorageFactory;

class Module
{
    public function getServiceConfig()
    {
        return [
            'services' => [
				// override in /config/autoload/cache.local.php
                'cache-config' => [
                    'adapter' => [
						//*** CACHE LAB: complete the configuration for a filesystem cache adapter
                        'name'      => 'filesystem',
						//*** CACHE LAB: make sure this directory exists and the PHP user can read/write
                        'options'   => ['ttl' => 3600, 'cache_dir' => realpath(__DIR__ . '/../../../data/cache')],
                    ],
                    'plugins' => [
                        'exception_handler' => ['throw_exceptions' => FALSE],
                    ],
                ],
            ],
            'factories' => [
                'cache-adapter' => function ($container) {
					//*** CACHE LAB: what to return?
                    return StorageFactory::factory($container->get('cache-config'));
                },
            ],
        ];
    }
}
