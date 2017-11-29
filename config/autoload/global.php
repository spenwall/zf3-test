<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
     'db' => [
         'adapters' => [
             'zftutorial' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'zftutorial',
                'hostname' => 'localhost',
             ],
             'testdb2' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'test2',
                'hostname' => 'localhost',
             ],
             'shared' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'wstar_shared',
                'hostname' => 'localhost',
             ]
         ],
         'driver' => 'Pdo_Mysql'
    ],
    
//    'service_manager' => [
//        'abstract_factories' => [
//            \Zend\Db\Adapter\AdapterAbstractServiceFactory::class,
//        ],
//    ],
];
