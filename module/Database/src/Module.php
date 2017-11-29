<?php

namespace Database;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    
    public function getConfig() 
    {
        return include __DIR__.'/../config/module.config.php';
    }
 
    // public function getControllerConfig()
    // {
    //     return [
    //         'factories' => [
    //             Controller\DatabaseController::class => function($container) {
    //                 return new Controller\DatabaseController();
    //             },
    //         ],
    //     ];
    // }
}