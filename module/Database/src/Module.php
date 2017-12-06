<?php

namespace Database;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    
    public function getConfig() 
    {
        return include __DIR__.'/../config/module.config.php';
    }
 
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AddonProducts::class => function($container) {
                    $dbAdapter = $container->get('shared');
                    return new Model\AddonProducts($dbAdapter);
                },
            ],
        ];
    }
}