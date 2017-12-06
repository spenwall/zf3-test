<?php

namespace Database\Factory;

use Database\Controller\DatabaseController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\TableGateway\TableGateway;
use Database\Model\AddonProducts;

class DatabaseControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ListController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
//        $addonProducts = new AddonProducts($container->get('shared'));
        return new DatabaseController();
    }
};