<?php

namespace Product;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 */
class WarehouseInitializer implements InitializerInterface
{

    /**
     *
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof \Product\Warehouse) {
            $instance->setServiceLocator($serviceLocator)
                     ->init();
        }
    }
}
