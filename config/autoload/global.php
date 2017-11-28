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
             'testdb' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'test',
                'hostname' => 'localhost',
             ],
             'testdb2' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'test2',
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
