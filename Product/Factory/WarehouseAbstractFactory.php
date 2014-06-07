<?php

namespace Product\Factory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Product\Warehouse;

/**
 *
 */
class WarehouseAbstractFactory implements AbstractFactoryInterface
{
    /**
     *
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return preg_match('/^[A-Z][a-zA-Z]+Warehouse$/', $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $service = new Warehouse();
        $service->setServiceLocator($serviceLocator)
                ->init();
        return $service;
    }
}
