<?php

namespace Product\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Product\Category;

/**
 *
 */
class VideoCategoryFactory implements FactoryInterface
{

    /**
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Category(array(
            'code'        => 'CTVUK',
            'name'        => 'Movies',
            'description' => 'Movies, TV'
        ));
    }
}
