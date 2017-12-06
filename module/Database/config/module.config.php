<?php

namespace Database;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    //router section
    'router' => [
        'routes' => [
            'database' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/database',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\DatabaseController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
           Model\AddonProducts::class => Factory\ModelFactory::class, 
        ],
        'abstract_factories' => [
            Factory\LazyTableFactory::class,
        ]

    ],

    'controllers'  => [
        'factories' => [
            Controller\DatabaseController::class => Factory\DatabaseControllerFactory::class,
        ],
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];